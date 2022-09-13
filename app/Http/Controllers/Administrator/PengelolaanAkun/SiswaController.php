<?php

namespace App\Http\Controllers\Administrator\PengelolaanAkun;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;

use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class SiswaController extends BaseController
{
    public function viewSiswa(Request $request, $id_kelas = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, NULL, true);

        return view('administrator/pengelolaan-akun/siswa/view-siswa', compact('auth_data', 'data_kelas', 'id_kelas'));
    }

    public function actionViewSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'pengelolaan-akun/siswa/view-detail/' . $input->id_kelas
            ];
        }
    }
    public function viewDetailSiswa(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('administrator/pengelolaan-akun/siswa/view-siswa', compact('auth_data', 'data_kelas', 'id_kelas'));
    }

    public function datatablesSiswa(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pengguna = Pengguna::select('pengguna.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.username', 'pengguna.nm_pengguna', 'kelas.nm_kelas')
            ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('siswa.id_kelas', '=', $id_kelas)
            ->orderBy('siswa.nis_siswa', 'asc')
            ->get();

        return Datatables::of($pengguna)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_pengguna' => $item->id_pengguna
                );
                return $data;
            })
            ->make(true);
    }
}
