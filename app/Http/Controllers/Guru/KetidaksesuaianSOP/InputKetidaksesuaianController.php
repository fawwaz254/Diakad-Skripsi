<?php

namespace App\Http\Controllers\Guru\KetidaksesuaianSOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPelanggaran;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KetidaksesuaianSOP;
use App\Models\Pengguna;
use Auth;
use DB;
use Session;
use Validator;

class InputKetidaksesuaianController extends Controller
{
    public function viewInputKetidaksesuaianSOP(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/ketidaksesuaian-sop/input-ketidaksesuaian-sop/view-input-ketidaksesuaian-sop', compact('auth_data'));
    }

    public function addInputKetidaksesuaianSOP(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pengguna = Pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')->whereHas('status_pengguna', function ($query) {
            $query->where('nm_status_pengguna', '=', 'AKTIF');
        })->get();
        return view('guru/ketidaksesuaian-sop/input-ketidaksesuaian-sop/add-input-ketidaksesuaian-sop', compact('auth_data', 'pengguna'));
    }

    public function editInputKetidaksesuaianSOP($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        $ketidaksesuaian_sop = KetidaksesuaianSOP::where('id_ketidaksesuaian_sop', $id)->with('pengguna')->first();

        return view('guru/ketidaksesuaian-sop/input-ketidaksesuaian-sop/edit-input-ketidaksesuaian-sop', compact('auth_data',  'ketidaksesuaian_sop'));
    }

    // public function ajaxGetPengguna(Request $request)
    // {
    //     # code...
    //     $input = (object) $request->input();
    //     // $auth_data = $input->auth_data;

    //     if ($input->unitKerja == 'guru') {
    //         $pengguna = Pengguna::where('status_join_table', 2)->whereHas('status_pengguna', function ($query) {
    //             $query->where('nm_status_pengguna', '=', 'AKTIF');
    //         })->get();
    //     } elseif ($input->unitKerja == 'tendik') {
    //         $pengguna = Pengguna::where('status_join_table', 1)->whereHas('status_pengguna', function ($query) {
    //             $query->where('nm_status_pengguna', '=', 'AKTIF');
    //         })->get();
    //     } else {
    //         $pengguna = Pengguna::get();
    //     }

    //     return $pengguna;
    // }

    public function datatablesInputKetidaksesuaianSOP(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KetidaksesuaianSOP::where('id_pengguna_input', $auth_data->pengguna->id_pengguna)->with('pengguna');

        return Datatables::of($list_data)
            ->addColumn('tgl_pelanggaran', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_pelanggaran));
            })
            ->addColumn('action', function ($item) {
                if ($item->path_file) {
                    $file =  Storage::disk('spaces')->url($item->path_file);
                    $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);

                    $note = 'image';
                } else {
                    $file = null;
                    $note = null;
                }

                $data = array(
                    'id'        => $item->id_ketidaksesuaian_sop,
                    'file'      => $file,
                    'note'      => $item->mapel,
                );
                return $data;
            })
            ->make(true);
    }

    public function actionInputKetidaksesuaianSOP(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_pengguna'               => 'required',
            'catatan'                   => 'required',
            'tgl'                       => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $data                               = new KetidaksesuaianSOP();
                $data->id_ketidaksesuaian_sop       = $id;
                $data->id_pengguna                  = $input->id_pengguna;
                $data->id_pengguna_input            = $input->auth_data->pengguna->id_pengguna;
                $data->catatan_pelanggaran          = $input->catatan;
                $data->tgl_pelanggaran              = date_format(date_create($input->tgl), "Y-m-d H:i:s");
                $data->created_by                   = $input->auth_data->pengguna->id_pengguna;

                if ($request->hasFile('file')) {

                    $validator = Validator::make($request->all(), [
                        'file' => 'mimes:jpeg,jpg,png,pdf|required|max:5120'
                    ]);

                    if ($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    } else {
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/ketidaksesuaiansop/' . $id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;
                    }
                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ketidaksesuaian-sop/input-ketidaksesuaian-sop',
                    'message' => 'Save Data Ketidaksesuaian SOP Successfully'
                ];
            } elseif ($mode == 'edit') {

                $data                               = KetidaksesuaianSOP::find($id);
                $data->catatan_pelanggaran          = $input->catatan;
                $data->tgl_pelanggaran              = date_format(date_create($input->tgl), "Y-m-d H:i:s");
                $data->updated_by                   = $input->auth_data->pengguna->id_pengguna;

                if ($request->hasFile('file')) {

                    $validator = Validator::make($request->all(), [
                        'file' => 'mimes:jpeg,jpg,png,pdf|required|max:5120'
                    ]);

                    if ($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    } else {
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/ketidaksesuaiansop/' . $id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;
                    }
                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ketidaksesuaian-sop/input-ketidaksesuaian-sop',
                    'message' => 'Save Data Ketidaksesuaian SOP Successfully'
                ];
            } elseif ($mode == 'delete') {

                // make object to find id
                $pelanggaranSiswa               = KetidaksesuaianSOP::find($id);
                $pelanggaranSiswa->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->save();

                $pelanggaranSiswa->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Ketidaksesuaian SOP Successfully'
                ];
            }
        }
    }

    public function previewFile($id, Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_kerja_harian = KetidaksesuaianSOP::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);

        return view('guru/ketidaksesuaian-sop/input-ketidaksesuaian-sop/preview-file-ketidaksesuaian-sop', compact('auth_data', 'laporan_kerja_harian', 'link', 'ext'));
    }
    public function downloadFile(Request $request, $id = null)
    {
        $input = (object) $request->input();
        $laporan_kerja_harian = KetidaksesuaianSOP::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);

        return Storage::disk('spaces')->download($laporan_kerja_harian->path_file, $laporan_kerja_harian->nm_file . "." . $ext);
    }
}
