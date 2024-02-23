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
        $list_data = CalonSiswaBaru::select('calon_siswa_baru.id_c_siswa', 'calon_siswa_baru.kode_voucher', 'calon_siswa_baru.nm_c_siswa', 'calon_siswa_baru.nomor_hp', 'calon_siswa_sekolah.nm_sekolah_asal')
            ->join('calon_siswa_sekolah', function ($q) {
                $q->on('calon_siswa_sekolah.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                    ->whereNull('calon_siswa_sekolah.deleted_at');
            })
            ->whereIn('calon_siswa_baru.status_verifikasi', [1, 2])
            ->whereNull('calon_siswa_baru.nomor_ujian')
            ->whereNotNull('calon_siswa_baru.file_hasil_placement')
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
                    // 'file'      => $file,
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

        $siswa = CalonSiswaBaru::where('id_penerimaan', '=', $id_penerimaan)->orderBy('calon_siswa_baru.kode_voucher', 'asc')->get();
        return response()->json($siswa);
    }

    public function uploadHasilPlacement(Request $request)
    {
        $input = (object) $request->input();

        DB::beginTransaction();

        try {

            $data = CalonSiswaBaru::where('id_c_siswa', '=', $request->id_c_siswa)->first();

            $validator = Validator::make($request->all(), [
                'file_hasil_placement'       => 'file:jpeg,jpg,png,pdf|required|max:5120',
            ]);

            if ($validator->fails()) {
                return [
                    'status' => 300, // FAILED
                    'message' => $validator->errors()->first()
                ];
            }

            $upload = $request->file('file_hasil_placement');
            $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);

            if ($request->hasFile('file_hasil_placement')) {

                $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                // $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/tendik/' . $data->id_c_siswa, request()->file_hasil_placement, 'public');

                $calon_siswa                        = CalonSiswaBaru::find($request->id_c_siswa);
                // $calon_siswa->path_file             = $file;
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
    }


    // public function actionViewPlacement(Request $request)
    // {
    //     $input      = (object) $request->input();
    //     $auth_data  = $input->auth_data;

    //     $validator  = Validator::make($request->all(), [
    //         'id_penerimaan' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return [
    //             'status'    => 300, // FAILED
    //             'message'   => $validator->errors()->first()
    //         ];
    //     } else {
    //         return [
    //             'status'    => 204, // SUCCESS AND LOAD CONTENT
    //             'path'      => 'report/input-hasil-placement/' . $input->id_penerimaan
    //         ];
    //     }
    // }

    // public function showPlacement($id, Request $request)
    // {
    //     $input      = (object) $request->input();
    //     $auth_data  = $input->auth_data;

    //     /** get all data penerimaan */
    //     $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

    //     /** groupping by year and semester */
    //     $grup_penerimaan_tahun = $data_penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
    //         return $item->groupBy('nm_semester_penerimaan');
    //     });

    //     /** get penerimaan by id */
    //     $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

    //     /** data (id_penerimaan) tidak ditemukan */
    //     if (!$penerimaan) {
    //         abort(404);
    //     }

    //     $mode = 'show';

    //     return view('ppdb/hasil-placement/view-hasil-placement', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun', 'mode', 'id'));
    // }

    // public function datatablesHasilPlacement($id, Request $request)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $list_data = LibPenerimaan::fetchDataCalonSiswaPenetapan($auth_data, $id);

    //     return Datatables::of($list_data)
    //         ->addColumn('action', function ($item) {
    //             $data = array(
    //                 'id' => $item->id_c_siswa,
    //             );
    //             return $data;
    //         })
    //         ->make(true);
    // }

    // public function uploadHasilPlacement($id, Request $request)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

    //     return view('ppdb/hasil-placement/upload-hasil-placement', compact('auth_data', 'penerimaan'));
    // }

    // public function actionUploadHasilPlacement(Request $request)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $validator = Validator::make($request->all(), [
    //         'file' => 'file|required|max:5120|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,bmp,png'
    //     ]);

    //     if ($validator->fails()) {
    //         return [
    //             'status' => 300, // FAILED
    //             'message' => $validator->errors()->first()
    //         ];
    //     } else {

    //         $penerimaan = LibPenerimaan::fetchDataCalonSiswaPenetapan($auth_data, $id);

    //         $id = $penerimaan->id_c_siswa;

    //         $file = Storage::disk('spaces')->putFile($id, request()->file, 'public');

    //         $data = CalonSiswaBaru::find($id);
    //         $data->file_hasil_placement = $file;

    //         dd($data);
    //         $data->save();

    //         return [
    //             'status' => 202,
    //             'message' => 'Data Berhasil Diupload'
    //         ];
    //     }
    // }



}
