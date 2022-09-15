<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use Yajra\Datatables\Datatables;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Models\Guru;
use App\Models\Pengguna;

class RekapNomorHpController extends Controller
{
    public function viewRekapNomorHp(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        return view('guru/wali-kelas/rekap-nomor-hp-siswa/view-rekap-nomor-hp-siswa', compact('auth_data', 'wali_kelas'));
    }

    public function datatablesRekapNomorHp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $list_data = Pengguna::with('siswa','siswa.wali_murid')
        ->whereHas('siswa', function ($query) use($wali_kelas) {
            $query->where('id_kelas', '=', $wali_kelas->id_kelas);
        })->get();

        // dd($pengguna);

        // $list_data = Pengguna::where('')

        return Datatables::of($list_data)
                ->make(true);
    }
}
