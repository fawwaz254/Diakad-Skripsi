<?php

namespace App\Http\Controllers\PPDB\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;

use Yajra\Datatables\Datatables;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;
use App\Models\CalonSiswaBaru;
use DB;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;

class HasilPlacementController extends Controller
{
    public function viewHasilPlacement(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        return view('ppdb/hasil-placement/view-hasil-placement', compact('auth_data'));
    }

    public function datatablesHasilPlacement(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $list_data = LibPenerimaan::fetchDataCalonSiswaPenetapan($auth_data, $id = null);
        $list_data = CalonSiswaBaru::select('calon_siswa_baru.id_c_siswa', 'calon_siswa_baru.kode_voucher', 'calon_siswa_baru.nm_c_siswa', 'calon_siswa_baru.nomor_hp', 'calon_siswa_sekolah.nm_sekolah_asal', 'calon_siswa_baru.path_file')
            ->join('calon_siswa_sekolah', function ($q) {
                $q->on('calon_siswa_sekolah.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                    ->whereNull('calon_siswa_sekolah.deleted_at');
            })
            ->whereIn('calon_siswa_baru.status_verifikasi', [1, 2])
            ->whereNull('calon_siswa_baru.nomor_ujian')
            ->whereNotNull('calon_siswa_baru.file_hasil_placement')
            ->whereNotNull('calon_siswa_baru.path_file')
            ->orderBy('calon_siswa_baru.kode_voucher', 'asc');

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                if ($item->path_file) {
                    $file =  Storage::disk('spaces')->url($item->path_file);
                    $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);
                    if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx') {
                        $note = 'file';
                    } else {
                        $note = 'image';
                    }
                } else {
                    $file = null;
                    $note = null;
                }

                $data = array(
                    'id'        => $item->id_c_siswa,
                    'file'      => $file,
                );
                return $data;
            })
            ->make(true);
    }

    public function viewUploadPlacement(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        $mode = 'view';

        return view('ppdb/hasil-placement/upload-hasil-placement', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun', 'mode'));
    }

    public function getSiswa($id_penerimaan)
    {

        $siswa = CalonSiswaBaru::where('id_penerimaan', '=', $id_penerimaan)
            ->whereNotNull('calon_siswa_baru.kode_voucher')
            ->orderBy('calon_siswa_baru.kode_voucher', 'asc')->get();
        return response()->json($siswa);
    }

    public function uploadHasilPlacement(Request $request, $mode = null, $id_c_siswa = null)
    {
        $input = (object) $request->input();

        $data = CalonSiswaBaru::where('id_c_siswa', '=', $request->id_c_siswa)->first();

        $validator = Validator::make($request->all(), [
            'file_hasil_placement'       => 'file:jpeg,jpg,png,pdf|required|max:5120',
        ]);

        if ($validator->fails() && $mode != "delete") {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        if ($mode == "add") {
            DB::beginTransaction();

            try {

                $upload = $request->file('file_hasil_placement');
                $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);

                if ($request->hasFile('file_hasil_placement')) {

                    $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                    $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/ppdb/' . $data->id_c_siswa, request()->file_hasil_placement, 'public');

                    $calon_siswa                        = CalonSiswaBaru::find($request->id_c_siswa);
                    $calon_siswa->path_file             = $file;
                    $calon_siswa->file_hasil_placement  = $filename;
                    $calon_siswa->save();
                }

                DB::commit();
                //  Successfully
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'report/input-hasil-placement',
                    'message' => 'Save Data Dokumen Successfully'
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // failed
                return [
                    'status' => 203, // GAGAL
                    'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine()
                ];
            }
        } elseif ($mode = "delete") {
            $hasil_placement = CalonSiswaBaru::find($id_c_siswa);

            // delete DO Spaces
            $files = Storage::disk('spaces')->delete($hasil_placement->path_file);

            $hasil_placement->file_hasil_placement = null;
            $hasil_placement->path_file = null;
            $hasil_placement->updated_by = $input->auth_data->pengguna->id_pengguna;

            $hasil_placement->save();

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Hasil Placement Successfully'
            ];
        }
    }

    public function previewHasilPlacement(Request $request, $id_c_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_hasil_placement = CalonSiswaBaru::findOrFail($id_c_siswa);
        $ext = pathinfo($data_hasil_placement->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($data_hasil_placement->path_file);

        return view('ppdb/hasil-placement/preview-hasil-placement', compact('auth_data', 'data_hasil_placement', 'link', 'ext', 'id_c_siswa'));
    }
}
