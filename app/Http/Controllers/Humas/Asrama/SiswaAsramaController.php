<?php

namespace App\Http\Controllers\Humas\Asrama;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaAsrama;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class SiswaAsramaController extends Controller
{
    public function  viewSiswaAsrama(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/asrama/data-siswa/view-data-siswa', compact('auth_data'));
    }

    public function datatablesSiswaAsrama(Request $request, $jenis)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($jenis == 'asrama') {
            $list_siswa = Siswa::whereHas('siswaAsrama')->with('siswaAsrama', 'kelas', 'pengguna');
        } else {
            $list_siswa = Siswa::doesntHave('siswaAsrama')->with('siswaAsrama', 'kelas', 'pengguna');
        }

        return Datatables::of($list_siswa)
            ->addColumn('action', function ($item) {

                $data = array(
                    'id_siswa' => $item->id_siswa,

                );
                return $data;
            })
            ->make(true);
    }
    public function actionAddSiswaAsrama(Request $request)
    {
        $input = (object) $request->input();
        // dd($input);
        if ($siswa_asrama = SiswaAsrama::where('id_siswa', $input->id_siswa)->first()) { } else {
            $now = Carbon::now();
            $siswa_asrama = new SiswaAsrama;
            $siswa_asrama->id_siswa_asrama = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $siswa_asrama->id_siswa = $input->id_siswa;
            $siswa_asrama->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                // 'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah paket Soal'
            ];
        }
    }

    public function actionDeleteSiswaAsrama(Request $request)
    {
        $input = (object) $request->input();
        if ($siswa_asrama = SiswaAsrama::where('id_siswa', $input->id_siswa)->first()) {
            $siswa_asrama->deleted_by = $input->auth_data->pengguna->id_pengguna;
            $siswa_asrama->save();
            $siswa_asrama->delete();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                // 'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menghapus paket Soal'
            ];
        }
    }
}
