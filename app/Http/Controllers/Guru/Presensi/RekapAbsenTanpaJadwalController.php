<?php

namespace App\Http\Controllers\Guru\Presensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\JadwalKelasMp;
use App\Models\PresensiMp;
use App\Models\Kelas;
use App\Models\KelasMp;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\PengampuMp;
use App\Models\Siswa;



use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class RekapAbsenTanpaJadwalController extends BaseController
{
    public function viewRekapAbsenTanpaJadwal(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        // $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        // $grup_kbm_perhari = $data_kbm->groupBy('nm_jadwal_hari');

        $guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $data = KelasMp::select('kelas_mp.id_kelas_mp', 'kelas_mp.id_semester', 'kelas_mp.id_kelas', 'kelas_mp.id_mata_pelajaran', 'kelas_mp.id_kelas_mp_grup', 'kelas_mp.status_entry', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'mata_pelajaran.id_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.id_kelas', 'kelas.nm_kelas')
                ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                ->where('pengampu_mp.id_guru', '=', $guru->id_guru)
                ->where('kelas_mp.id_semester', '=', $semester_aktif->id_semester)
                ->where('kelas_mp.status_entry', '=', 2)
                ->get();

        return view('guru/presensi/rekap-absen-tanpa-jadwal/view-rekap-absen-tanpa-jadwal', compact('auth_data', 'semester_aktif', 'data'));
    }

    // ==== ACTION REKAP ABSEN KBM ====
    public function actionViewKBMRekapAbsenTanpaJadwal(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/rekap-absen-tanpa-jadwal/view-kbm/'.$input->id_kelas_mp
            ];
        }
    }

    public function viewKBMRekapAbsenTanpaJadwal(Request $request, $id_kelas_mp)
    {
        # code...
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;
        $guru           = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $id_guru        = $guru->id_guru;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'kelas_mp.id_kelas_mp', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.aktif_status_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                        ->join('pengguna', function ($join) {
                            $join->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                            ->whereNull('pengguna.deleted_at');
                        })
                        ->join('status_pengguna', function($join) {
                            $join->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                            ->whereNull('status_pengguna.deleted_at');
                        })->join('pengambilan_mp', function ($join) {
                            $join->on('pengambilan_mp.id_siswa', '=', 'siswa.id_siswa')
                            ->whereNull('pengambilan_mp.deleted_at');
                        })
                        ->join('kelas_mp', function ($join) {
                            $join->on('kelas_mp.id_kelas_mp', '=', 'pengambilan_mp.id_kelas_mp')
                            ->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('kelas', function ($join) {
                            $join->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                            ->whereNull('kelas.deleted_at');
                        })
                        ->where('kelas_mp.id_kelas_mp', $id_kelas_mp)
                        ->where('kelas_mp.id_semester', '=', $semester_aktif->id_semester)->get();

        $data_kelas = Guru::select('guru.id_guru', 'guru.id_pengguna', 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'pengampu_mp.pjmp_pengampu_mp', 'presensi_mp.pertemuan_ke', 'presensi_mp.uraian_materi', 'presensi_mp.waktu_mulai', 'presensi_mp.waktu_selesai')
                        ->join('pengampu_mp', function ($q) {
                            $q->on('pengampu_mp.id_guru', '=', 'guru.id_guru')
                                ->whereNull('pengampu_mp.deleted_at');
                        })
                        ->join('kelas_mp', function ($q) {
                            $q->on('kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                                ->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('mata_pelajaran', function ($q) {
                            $q->on('mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                                ->whereNull('mata_pelajaran.deleted_at');
                        })
                        ->join('kelas', function ($q) {
                            $q->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                                ->whereNull('kelas.deleted_at');
                        })
                        ->join('presensi_mp', function($q) {
                            $q->on('presensi_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                ->whereNull('presensi_mp.deleted_at');
                        })                    
                        ->where('pengampu_mp.id_guru', '=', $id_guru)
                        ->where('kelas_mp.id_kelas_mp', '=', $id_kelas_mp)
                        ->orderBy('presensi_mp.pertemuan_ke', 'desc')
                        ->first();

        // $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        // $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $id_jadwal_kelas_mp, null, 'all');

        $data_presensi = PresensiMp::with('presensi_mp_siswa')->where('id_kelas_mp', $id_kelas_mp)->orderBy('pertemuan_ke', 'asc')->get();

        return view('guru/presensi/rekap-absen-tanpa-jadwal/view-kbm-rekap-absen-tanpa-jadwal', compact('auth_data', 'semester_aktif', 'data_kelas', 'data_siswa', 'data_presensi', 'id_jadwal_kelas_mp'));
    }

    public function printKBMRekapAbsenTanpaJadwal(Request $request, $id_kelas_mp)
    {
        # code...
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $guru           = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $id_guru        = $guru->id_guru;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        
        $data_siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'kelas_mp.id_kelas_mp', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.aktif_status_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                        ->join('pengguna', function ($join) {
                            $join->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                            ->whereNull('pengguna.deleted_at');
                        })
                        ->join('status_pengguna', function($join) {
                            $join->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                            ->whereNull('status_pengguna.deleted_at');
                        })->join('pengambilan_mp', function ($join) {
                            $join->on('pengambilan_mp.id_siswa', '=', 'siswa.id_siswa')
                            ->whereNull('pengambilan_mp.deleted_at');
                        })
                        ->join('kelas_mp', function ($join) {
                            $join->on('kelas_mp.id_kelas_mp', '=', 'pengambilan_mp.id_kelas_mp')
                            ->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('kelas', function ($join) {
                            $join->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                            ->whereNull('kelas.deleted_at');
                        })
                        ->where('kelas_mp.id_kelas_mp', $id_kelas_mp)
                        ->where('kelas_mp.id_semester', '=', $semester_aktif->id_semester)->get();

        $data_kelas = Guru::select('guru.id_guru', 'guru.id_pengguna', 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'pengampu_mp.pjmp_pengampu_mp', 'presensi_mp.pertemuan_ke', 'presensi_mp.uraian_materi', 'presensi_mp.waktu_mulai', 'presensi_mp.waktu_selesai')
                        ->join('pengampu_mp', function ($q) {
                            $q->on('pengampu_mp.id_guru', '=', 'guru.id_guru')
                                ->whereNull('pengampu_mp.deleted_at');
                        })
                        ->join('kelas_mp', function ($q) {
                            $q->on('kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                                ->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('mata_pelajaran', function ($q) {
                            $q->on('mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                                ->whereNull('mata_pelajaran.deleted_at');
                        })
                        ->join('kelas', function ($q) {
                            $q->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                                ->whereNull('kelas.deleted_at');
                        })
                        ->join('presensi_mp', function($q) {
                            $q->on('presensi_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                ->whereNull('presensi_mp.deleted_at');
                        })                    
                        ->where('pengampu_mp.id_guru', '=', $id_guru)
                        ->where('kelas_mp.id_kelas_mp', '=', $id_kelas_mp)
                        ->orderBy('presensi_mp.pertemuan_ke', 'desc')
                        ->first();

        $data_presensi = PresensiMp::with('presensi_mp_siswa')->where('id_kelas_mp', $id_kelas_mp)->orderBy('pertemuan_ke', 'asc')->get();

        // $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        // $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $id_jadwal_kelas_mp, null, 'all');

        // $data_presensi = PresensiMp::with('presensi_mp_siswa')->where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->orderBy('pertemuan_ke', 'asc')->get();

        return view('guru/presensi/rekap-absen/print-kbm-rekap-absen', compact('auth_data', 'semester_aktif', 'data_kelas', 'data_siswa', 'data_presensi'));
    }
}
