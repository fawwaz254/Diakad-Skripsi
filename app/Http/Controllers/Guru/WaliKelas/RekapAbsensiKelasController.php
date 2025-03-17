<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Guru;
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

class RekapAbsensiKelasController extends BaseController
{
    public function viewRekapAbsensiKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $data_kbm = LibGuru::fetchDataJadwalKBMByKelas($auth_data, $semester_aktif->id_semester, $wali_kelas->id_kelas);

        $grup_kbm_perhari = $data_kbm->sortBy('jadwal_hari.kode_jadwal_hari')->groupBy('jadwal_hari.nm_jadwal_hari');

        return view('guru/wali-kelas/rekap-absensi-kelas/view-rekap-absensi-kelas', compact('auth_data', 'semester_aktif', 'grup_kbm_perhari', 'wali_kelas'));
    }

    // ==== ACTION REKAP ABSEN KELAS ====
    public function actionViewRekapAbsensiKelas(Request $request)
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
                'path' => 'wali-kelas/rekap-absensi-kelas/rekap-absensi-kelas-siswa/' . $input->id_jadwal_kelas_mp
            ];
        }
    }

    public function viewRekapAbsensiKelasSiswa(Request $request, $id_jadwal_kelas_mp)
    {
        set_time_limit(-1);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = JadwalKelasMp::with(['kelas_mp', 'kelas_mp.mata_pelajaran', 'kelas_mp.kelas', 'ruangan', 'jadwal_hari'])
            ->where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)
            ->first();

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $id_jadwal_kelas_mp);

        $data_presensi = PresensiMp::with('presensi_mp_siswa')->where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->get();
        return view('guru/wali-kelas/rekap-absensi-kelas/view-rekap-absensi-kelas-siswa', compact('auth_data', 'semester_aktif', 'data_kelas', 'data_siswa', 'data_presensi'));
    }
}
