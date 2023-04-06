<?php

namespace App\Http\Controllers\Guru\Presensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\JadwalKelasMp;
use App\Models\PresensiMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class RekapAbsenController extends BaseController
{
    public function viewRekapAbsen(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        $grup_kbm_perhari = $data_kbm->groupBy('nm_jadwal_hari');

        return view('guru/presensi/rekap-absen/view-rekap-absen', compact('auth_data', 'semester_aktif', 'grup_kbm_perhari'));
    }

    // ==== ACTION REKAP ABSEN KBM ====
    public function actionViewKBMRekapAbsen(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/rekap-absen/view-kbm/' . $input->id_jadwal_kelas_mp
            ];
        }
    }

    public function viewKBMRekapAbsen(Request $request, $id_jadwal_kelas_mp)
    {
        # code...
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $id_jadwal_kelas_mp, null, 'all');

        $data_presensi = PresensiMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->orderBy('pertemuan_ke', 'asc')->get();


        return view('guru/presensi/rekap-absen/view-kbm-rekap-absen', compact('auth_data', 'semester_aktif', 'data_kelas', 'data_siswa', 'data_presensi', 'id_jadwal_kelas_mp'));
    }

    public function printKBMRekapAbsen(Request $request, $id_jadwal_kelas_mp)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $id_jadwal_kelas_mp, null, 'all');

        $data_presensi = PresensiMp::with('presensi_mp_siswa')->where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->orderBy('pertemuan_ke', 'asc')->get();

        return view('guru/presensi/rekap-absen/print-kbm-rekap-absen', compact('auth_data', 'semester_aktif', 'data_kelas', 'data_siswa', 'data_presensi'));
    }
}
