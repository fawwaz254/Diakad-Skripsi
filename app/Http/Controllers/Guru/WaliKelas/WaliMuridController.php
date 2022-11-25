<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\WaliMurid;

class WaliMuridController extends Controller
{
    public function viewWaliMurid(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru.wali-kelas.wali-murid.view-wali-murid', compact('auth_data'));
    }

    public function datatablesWaliMurid(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)->whereHas('wali_murid')->with('wali_murid.pengguna', 'pengguna', 'kelas')->get();

        // dd($list_siswa);
        return Datatables::of($list_siswa)->addColumn('action', function ($item) {
            $data = array(
                'id' => $item->wali_murid->pengguna->id_pengguna
            );
            return $data;
        })->make(true);
    }
}
