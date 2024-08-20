<?php

namespace App\Http\Controllers\Guru\Jadwal;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibKelas;
use App\Models\Semester as Semester;
use App\Models\KelasMp as KelasMp;


use Auth;
use DB;
use Session;
use Validator;

class JadwalKBMController extends BaseController
{

    public function viewJadwalKBM(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/jadwal/jadwal-kbm/view-jadwal-kbm', compact('auth_data', 'semester_aktif', 'data_semester'));

    }
    public function datatablesJadwalKBM(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->kd_mata_pelajaran . " - " . $item->nm_mata_pelajaran;
            })
            ->addColumn('jadwal_jam', function ($item) {
                return $item->jam_mulai . ":" . $item->menit_mulai . " - " . $item->jam_selesai . ":" . $item->menit_selesai;
            })
            ->addColumn('status_pjmp', function ($item) {
                if ($item->pjmp_pengampu_mp == 1) {
                    return "PJMP";
                } else {
                    return "Anggota";
                }
            })
            ->make(true);
    }

    public function datatablesKBMTanpaJadwal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $list_data = KelasMp::select('kelas_mp.id_kelas_mp', 'kelas_mp.id_semester', 'kelas_mp.nm_kelas_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_kelas_mp.id_jadwal_kelas_mp', 'pengampu_mp.pjmp_pengampu_mp')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->where('pengampu_mp.id_guru', '=', $guru->id_guru)
            ->where('kelas_mp.id_semester', '=', $semester_aktif->id_semester)
            ->where('id_jadwal_jam', '0')
            ->get();
        // return response()->json($list_data);
        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->kd_mata_pelajaran . " - " . $item->nm_mata_pelajaran;
            })
            ->addColumn('status_pjmp', function ($item) {
                if ($item->pjmp_pengampu_mp == 1) {
                    return "PJMP";
                } else {
                    return "Anggota";
                }
            })
            ->make(true);
    }

}
