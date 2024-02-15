<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Jurusan as Jurusan;
use App\Models\Jalur as Jalur;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\Kelas as Kelas;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class UpdateFotoController extends BaseController
{
    public function viewUpdateFoto(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $jurusan = Jurusan::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $jalur = Jalur::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $status_pengguna = StatusPengguna::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->where('status_join_table', '=', 3)
            ->get();
        $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')
            ->distinct()
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('thn_masuk_siswa', 'ASC')->get();

        return view('pendidikan/siswa/update-foto/view-update-foto', compact('auth_data', 'jurusan', 'jalur', 'status_pengguna', 'thn_masuk_siswa'));
    }

    public function viewBatchUpdateFoto(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('pendidikan/siswa/update-foto/view-batch-upload-foto', compact('auth_data'));
    }

    public function actionViewUpdateFoto(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'siswa/update-foto/view-detail-update-foto/' . $input->id_jurusan . '/' . $input->id_kelas . '/' . $input->thn_masuk_siswa . '/' . $input->id_jalur . '/' . $input->id_status_pengguna
            ];
        }
    }

    public function viewDetailUpdateFoto(Request $request, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);


        $jurusan = Jurusan::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $kelas = Kelas::where('id_jurusan', '=', $id_jurusan)->where('is_aktif', 1)->get();
        $jalur = Jalur::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $status_pengguna = StatusPengguna::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->where('status_join_table', '=', 3)
            ->get();
        $thn_masuk_siswa_list = Siswa::select('thn_masuk_siswa')
            ->distinct()
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('thn_masuk_siswa', 'ASC')->get();

        return view('pendidikan/siswa/update-foto/view-detail-update-foto', compact('auth_data', 'id_jurusan', 'id_kelas', 'thn_masuk_siswa', 'id_jalur', 'id_status_pengguna', 'jurusan', 'kelas', 'jalur', 'status_pengguna', 'thn_masuk_siswa_list', 'siswa'));
    }

    public function datatablesUpdateFoto(Request $request, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);

        return Datatables::of($siswa)
            ->editColumn('path_foto_pengguna', function ($item) {
                if (!empty($item->path_foto_pengguna)) {
                    return Storage::disk('spaces')->url($item->path_foto_pengguna);
                } else {
                    return asset('media/blank-user.png');
                }
            })
            ->addColumn('thn_masuk_siswa', function ($item) {
                return $item->thn_masuk_siswa;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pengguna
                );
                return $data;
            })
            ->make(true);
    }

    public function viewUpload(Request $request, $id_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('pendidikan/siswa/update-foto/view-upload-foto', compact('auth_data', 'id_pengguna'));
    }

    public function actionUpdateFoto(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), []);

        if ($validator->fails() && $mode != 'delete' && $mode != 'upload' && $mode != 'delete-file') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'upload') {
                $validator = Validator::make($request->all(), [
                    'file' => 'file|required|max:2048|mimes:jpg,jpeg,bmp,png'
                ]);


                $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;

                $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/siswa/' . $id, request()->file, 'public');

                //save file name to database
                $siswa                          = Pengguna::find($id);
                $siswa->path_foto_pengguna      = $file;
                $siswa->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'message' => 'Succes Upload foto siswa', // SUCCESS AND LOAD CONTENT
                    'path' => 'kesiswaan#siswa/update-foto/upload/' . $id
                ];
            }
        }
    }

    public function actionBatchUploadFoto(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'file' => 'file|required|max:2048|mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        $upload_image = $request->file('file');
        $filename = pathinfo($upload_image->getClientOriginalName(), PATHINFO_FILENAME);

        if ($siswa = Siswa::where('nis_siswa', $filename)->first()) {
            $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;

            $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/siswa/' . $siswa->id_pengguna, $upload_image, 'public');

            //save file name to database
            $siswa                          = Pengguna::find($siswa->id_pengguna);
            $siswa->path_foto_pengguna      = $file;
            $siswa->updated_by              = $input->auth_data->pengguna->id_pengguna;
            $siswa->save();

            return Response::json('success', 200);
        } else {
            return Response::json('error ' . $filename, 400);
        }
    }
}
