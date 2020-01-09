<?php

namespace App\Libraries\Akademik;

use App\Models\Kurikulum as Kurikulum;
use App\Models\KelasMp as KelasMp;
use App\Models\MataPelajaran as MataPelajaran;
use App\Models\JadwalJam as JadwalJam;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use App\Models\Semester;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibAkademik
{
    /** KURIKULUM **/
    public static function fetchDataKurikulum($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $kurikulum = Kurikulum::select('kurikulum.id_kurikulum', 'jurusan.nm_jurusan', 'semester.tahun_ajaran', 'semester.nm_semester', 'kurikulum.nm_kurikulum', 'kurikulum.tahun_kurikulum', 'kurikulum.nomor_sk_kurikulum', 'kurikulum.keterangan_kurikulum', 'kurikulum.berlaku_mulai', 'kurikulum.berlaku_sampai', 'kurikulum.is_aktif')
                            ->join('jurusan', 'jurusan.id_jurusan', '=', 'kurikulum.id_jurusan')
                            ->join('semester', 'semester.id_semester', '=', 'kurikulum.id_semester_mulai')
                            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                            ->orderBy('kurikulum.tahun_kurikulum', 'asc')
                            ->orderBy('kurikulum.is_aktif', 'desc')
                            ->whereNull('jurusan.deleted_at')
                            ->whereNull('semester.deleted_at')
                            ->get();
        }
        // get mode edit
        else {
            $kurikulum = Kurikulum::where('id_kurikulum', '=', $id)->first();
        }

        return $kurikulum;
    }

    public static function fetchDataKelasMp($auth_data, $id = null)
    {

        // get mode view
        $data = KelasMp::select('mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"), 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang')
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
            ->join('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
            ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->join('pengguna', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->where('kelas_mp.id_semester', '=', $id)
            ->where('pengampu_mp.pjmp_pengampu_mp', '=', '1')
            ->get();

        return $data;
    }
    /** ========== **/

    /** MATA PELAJARAN **/
    public static function fetchDataMataPelajaran($auth_data, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $mataPelajaran = MataPelajaran::select('mata_pelajaran.id_mata_pelajaran', 'jurusan.nm_jurusan', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.kredit_tatap_muka', 'mata_pelajaran.kredit_praktikum', 'mata_pelajaran.kredit_tutor', 'mata_pelajaran.kredit_prak_lapangan', 'mata_pelajaran.kredit_simulasi', 'mata_pelajaran.tingkat_semester', 'mata_pelajaran.nilai_kkm', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran')
                            ->join('jurusan', 'jurusan.id_jurusan', '=', 'mata_pelajaran.id_jurusan')
                            ->leftJoin('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
                            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                            ->orderBy('mata_pelajaran.kd_mata_pelajaran', 'asc')
                            ->orderBy('mata_pelajaran.nm_mata_pelajaran', 'asc');

            if ($is_datatable == null) {
                $mataPelajaran = $mataPelajaran->get();
            }
        }
        // get mode edit
        else {
            $mataPelajaran = MataPelajaran::where('id_mata_pelajaran', '=', $id)->first();
        }

        return $mataPelajaran;
    }
    /** ========== **/

    /** Usulan Mata Ajar **/
    public static function FetchDataUsulanMataAjar($auth_data, $id_semester, $is_datatable = null)
    {
        $kelas_mp = KelasMp::select(
            'mata_pelajaran.nm_mata_pelajaran',
            'mata_pelajaran.kd_mata_pelajaran',
            'kelas.nm_kelas',
            DB::raw("(SELECT COUNT(*) FROM jadwal_kelas_mp WHERE jadwal_kelas_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND jadwal_kelas_mp.deleted_at IS NULL) AS jml_jadwal"),
            DB::raw("(SELECT SUM(jjs.jam_ke - jj.jam_ke + 1) 
                        FROM jadwal_kelas_mp 
                        JOIN jadwal_jam AS jj ON jj.id_jadwal_jam = jadwal_kelas_mp.id_jadwal_jam
                        JOIN jadwal_jam AS jjs ON jjs.id_jadwal_jam = jadwal_kelas_mp.id_jadwal_jam_selesai 
                        WHERE jadwal_kelas_mp.id_kelas_mp = kelas_mp.id_kelas_mp 
                            AND jadwal_kelas_mp.deleted_at IS NULL) AS jml_jadwal_jam"),
            DB::raw("(SELECT COUNT(*) FROM pengampu_mp WHERE pengampu_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengampu_mp.deleted_at IS NULL) AS jml_pengampu"),
            DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"),
            'kelas_mp.id_kelas_mp',
            'mata_pelajaran.kredit_semester',
            'mata_pelajaran.tingkat_semester',
            'kelas_mp.nm_kelas_mp',
            'jenis_mata_pelajaran.nm_jenis_mata_pelajaran'
        )
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->where('kelas_mp.id_semester', '=', $id_semester)
            ->orderBy('mata_pelajaran.nm_mata_pelajaran', 'asc')
            ->orderBy('kelas.nm_kelas', 'asc')
            ->orderBy('mata_pelajaran.tingkat_semester', 'asc');

        if ($is_datatable == null) {
            $kelas_mp = $kelas_mp->first();
        }

        return $kelas_mp;
    }

    /** CEK JADWAL KRES **/
    public static function cekJadwalKelas($auth_data, $id_guru, $id_ruangan, $id_jadwal_hari, $id_jadwal_jam, $id_jadwal_jam_selesai)
    {
        $cek = array();
        $cek['guru'] = 1;
        $cek['ruangan'] = 1;

        $jadwalJamMulai = JadwalJam::where('id_jadwal_jam', '=', $id_jadwal_jam)->first();
        $jam_ke_mulai = $jadwalJamMulai->jam_ke;

        $jadwalJamSelesai = JadwalJam::where('id_jadwal_jam', '=', $id_jadwal_jam_selesai)->first();
        $jam_ke_selesai = $jadwalJamSelesai->jam_ke;

        $semester_aktif = Semester::where(['id_sekolah' => $auth_data->pengguna->id_sekolah, 'is_aktif_semester' => 1])->first();

        // cek by guru
        $cekGuru = KelasMp::join('pengampu_mp', function ($join) {
            $join->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                                        ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1)
                                                        ->whereNull('pengampu_mp.deleted_at');
        })
                            ->join('jadwal_kelas_mp', function ($q) {
                                $q->on('jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                    ->whereNull('jadwal_kelas_mp.deleted_at');
                            })
                            ->join('jadwal_jam AS jj', function ($q) {
                                $q->on('jj.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
                                    ->whereNull('jj.deleted_at');
                            })
                            ->join('jadwal_jam AS jjs', function ($q) {
                                $q->on('jjs.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam_selesai')
                                    ->whereNull('jjs.deleted_at');
                            })
                            ->where('pengampu_mp.id_guru', '=', $id_guru)
                            ->where('jadwal_kelas_mp.id_jadwal_hari', '=', $id_jadwal_hari)
                            ->where('kelas_mp.id_semester', '=', $semester_aktif->id_semester)
                            ->where(function ($query) use ($jam_ke_mulai, $jam_ke_selesai) {
                                $query->whereBetween('jj.jam_ke', [$jam_ke_mulai, $jam_ke_selesai])
                                        ->orWhereBetween('jjs.jam_ke', [$jam_ke_mulai, $jam_ke_selesai]);
                            })
                            ->first();

        // cek by ruangan
        $cekRuangan = JadwalKelasMp::join('jadwal_jam AS jj', function ($q) {
            $q->on('jj.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
                                            ->whereNull('jj.deleted_at');
        })
                                    ->join('jadwal_jam AS jjs', function ($q) {
                                        $q->on('jjs.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam_selesai')
                                            ->whereNull('jjs.deleted_at');
                                    })
                                    ->join('kelas_mp', function ($q) {
                                        $q->on('kelas_mp.id_kelas_mp', '=', 'jadwal_kelas_mp.id_kelas_mp')
                                            ->whereNull('kelas_mp.deleted_at');
                                    })
                                    ->where('id_ruangan', '=', $id_ruangan)
                                    ->where('id_jadwal_hari', '=', $id_jadwal_hari)
                                    ->where(function ($query) use ($jam_ke_mulai, $jam_ke_selesai) {
                                        $query->whereBetween('jj.jam_ke', [$jam_ke_mulai, $jam_ke_selesai])
                                                ->orWhereBetween('jjs.jam_ke', [$jam_ke_mulai, $jam_ke_selesai]);
                                    })
                                    ->where('kelas_mp.id_semester', '=', $semester_aktif->id_semester)
                                    ->first();

        if ($cekGuru) {
            $cek['guru'] = 0;
        }

        if ($cekRuangan) {
            $cek['ruangan'] = 0;
        }

        return $cek;
    }

    /** CEK JADWAL KELAS MP BERUBAH / TIDAK **/
    public static function cekJadwalKelasMpBerubah($id_kelas_mp, $id_ruangan, $id_jadwal_hari, $id_jadwal_jam, $id_jadwal_jam_selesai)
    {
        $cek_jadwal_kelas_mp = JadwalKelasMp::where('id_kelas_mp', $id_kelas_mp)
                                    ->where('id_ruangan', '=', $id_ruangan)
                                    ->where('id_jadwal_hari', '=', $id_jadwal_hari)
                                    ->where('id_jadwal_jam', '=', $id_jadwal_jam)
                                    ->where('id_jadwal_jam_selesai', '=', $id_jadwal_jam_selesai)
                                    ->first();

        if ($cek_jadwal_kelas_mp) {
            return false;
        } else {
            return true;
        }
    }
    /** ========== **/
}
