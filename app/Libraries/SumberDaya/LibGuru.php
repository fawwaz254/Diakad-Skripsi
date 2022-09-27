<?php

namespace App\Libraries\SumberDaya;

use App\Models\Guru as Guru;
use App\Models\KelasMp as KelasMp;
use App\Models\KomponenMp as KomponenMp;
use App\Models\WaliKelas as WaliKelas;
use App\Models\BkKelas;
use App\Models\HomeVisit as HomeVisit;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use App\Models\KomponenEkskul;
use App\Models\PembinaEkskulSet;
use App\Models\SubKomponenMp;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibGuru
{
    /** ALL GURU **/
    public static function fetchDataAllGuru($auth_data, $id = null, $is_datatable = null)
    {

        // get all guru
        if ($id == null) {
            $guru = Guru::select(
                'guru.id_guru',
                'guru.id_pengguna',
                'pengguna.id_status_pengguna',
                'guru.jenis_jabatan',
                'pengguna.nm_pengguna',
                'pengguna.gelar_depan',
                'pengguna.gelar_belakang',
                'guru.nip_guru',
                'unit_kerja.nm_unit_kerja',
                'status_pengguna.nm_status_pengguna',
                DB::raw("(SELECT COUNT(*) FROM pengampu_mp 
                            JOIN kelas_mp ON kelas_mp.id_kelas_mp = pengampu_mp.id_kelas_mp 
                            JOIN semester ON semester.id_semester = kelas_mp.id_semester 
                            WHERE pengampu_mp.id_guru = guru.id_guru 
                            AND semester.is_aktif_semester = 1 AND pengampu_mp.deleted_at IS NULL) 
                            AS jml_mengajar_semester_aktif")
            )
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                    ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                    ->join('unit_kerja', 'unit_kerja.id_unit_kerja', '=', 'guru.id_unit_kerja')
                    ->with('pengguna')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                    ->orderBy('pengguna.nm_pengguna', 'asc');
            if ($is_datatable == null) {
                $guru = $guru->get();
            }
        }
        // get mode edit
        else {
            $guru = Guru::select('guru.*', 'pengguna.id_status_pengguna', 'pengguna.nm_pengguna', 'pengguna.id_status_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang')
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                    ->where('guru.id_guru', '=', $id)
                    ->first();
        }

        return $guru;
    }
    /** ========== **/

    /** KELAS GURU PENGAMPU BY id_pengguna **/
    public static function fetchDataKelasGuru($auth_data, $id_pengguna, $id_semester = null)
    {
        // get id_guru
        $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
        $id_guru = $guru->id_guru;

        $kelasGuru = Guru::select('guru.id_guru', 'guru.id_pengguna', 'kelas_mp.id_kelas_mp', 'semester.tahun_ajaran', 'semester.nm_semester', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'pengampu_mp.pjmp_pengampu_mp')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1)
                    ->whereNull('kelas_mp.deleted_at')
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc');

        if (! empty($id_semester)) {
            $kelasGuru = $kelasGuru->where('kelas_mp.id_semester', '=', $id_semester)
                            ->get();
        } else {
            $kelasGuru = $kelasGuru->get();
        }

        return $kelasGuru;
    }
    /** ========== **/

    /** JADWAL KBM GURU with HARI BY id_pengguna **/
    public static function fetchDataJadwalKBM($auth_data, $id_pengguna, $id_semester = null, $id_kelas_mp = null, $id_jadwal_kelas_mp = null, $pertemuan_ke = null)
    {
        // get id_guru
        if (!empty($id_pengguna)) {
            $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
            $id_guru = $guru->id_guru;
        }

        if (! empty($id_kelas_mp) && ! empty($pertemuan_ke)) {
            $jadwalKBM = Guru::select('guru.id_guru', 'guru.id_pengguna', 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'pengampu_mp.pjmp_pengampu_mp', 'presensi_mp.pertemuan_ke', 'presensi_mp.uraian_materi', 'presensi_mp.waktu_mulai', 'presensi_mp.waktu_selesai')
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
                    ->leftJoin('presensi_mp', function ($join) use ($pertemuan_ke) {
                        $join->on('presensi_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                 ->where('presensi_mp.pertemuan_ke', '=', $pertemuan_ke)
                                 ->whereNull('presensi_mp.deleted_at');
                    })
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('kelas_mp.id_kelas_mp', '=', $id_kelas_mp)
                    ->orderBy('presensi_mp.pertemuan_ke', 'desc')
                    ->first();
        } else {
            $jadwalKBM = Guru::select('guru.id_guru', 'guru.id_pengguna', 'kelas_mp.id_kelas_mp', 'jadwal_kelas_mp.id_jadwal_kelas_mp', 'semester.tahun_ajaran', 'semester.nm_semester', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'jadwal_hari.id_jadwal_hari', 'jadwal_hari.nm_jadwal_hari', 'jj.jam_mulai', 'jj.menit_mulai', 'jjs.jam_selesai', 'jjs.menit_selesai', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'pengampu_mp.pjmp_pengampu_mp')
                    ->join('pengampu_mp', function ($q) {
                        $q->on('pengampu_mp.id_guru', '=', 'guru.id_guru')
                            ->whereNull('pengampu_mp.deleted_at');
                    })
                    ->join('kelas_mp', function ($q) {
                        $q->on('kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                            ->whereNull('kelas_mp.deleted_at');
                    })
                    ->join('semester', function ($q) {
                        $q->on('semester.id_semester', '=', 'kelas_mp.id_semester')
                            ->whereNull('semester.deleted_at');
                    })
                    ->join('mata_pelajaran', function ($q) {
                        $q->on('mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                            ->whereNull('mata_pelajaran.deleted_at');
                    })
                    ->join('kelas', function ($q) {
                        $q->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                            ->whereNull('kelas.deleted_at');
                    })
                    ->join('jadwal_kelas_mp', function ($q) {
                        $q->on('jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                            ->whereNull('jadwal_kelas_mp.deleted_at');
                    })
                    ->join('ruangan', function ($q) {
                        $q->on('ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
                            ->whereNull('ruangan.deleted_at');
                    })
                    ->join('jadwal_hari', function ($q) {
                        $q->on('jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
                            ->whereNull('jadwal_hari.deleted_at');
                    })
                    ->join('jadwal_jam AS jj', function ($q) {
                        $q->on('jj.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
                            ->whereNull('jj.deleted_at');
                    })
                    ->join('jadwal_jam AS jjs', function ($q) {
                        $q->on('jjs.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam_selesai')
                            ->whereNull('jjs.deleted_at');
                    });

            if (! empty($id_pengguna)) {
                $jadwalKBM = $jadwalKBM->where('pengampu_mp.id_guru', '=', $id_guru);
            }

            if (! empty($id_kelas_mp)) {
                $jadwalKBM = $jadwalKBM->where('kelas_mp.id_kelas_mp', '=', $id_kelas_mp)
                        ->first();
            } elseif (! empty($id_jadwal_kelas_mp)) {
                $jadwalKBM = $jadwalKBM->where('jadwal_kelas_mp.id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)
                        ->first();
            } elseif (! empty($id_semester)) {
                $jadwalKBM = $jadwalKBM->where('kelas_mp.id_semester', '=', $id_semester)
                            ->orderBy('jadwal_hari.kode_jadwal_hari', 'asc')
                            ->orderBy('jj.jam_mulai', 'asc')
                            ->orderBy('jj.menit_mulai', 'asc')
                            ->orderBy('kelas.nm_kelas', 'asc')
                            ->orderBy('kelas.tingkat', 'asc')
                            ->get();
            } elseif (empty($id_semester)) {
                $jadwalKBM = $jadwalKBM->orderBy('kelas.nm_kelas', 'asc')
                            ->orderBy('kelas.tingkat', 'asc')
                            ->orderBy('jadwal_hari.kode_jadwal_hari', 'asc')
                            ->orderBy('jj.jam_mulai', 'asc')
                            ->orderBy('jj.menit_mulai', 'asc')
                            ->orderBy('semester.thn_akademik_semester', 'desc')
                            ->orderBy('semester.nm_semester', 'desc')
                            ->get();
            }
        }

        return $jadwalKBM;
    }
    /** ========== **/

    /** JADWAL UTS GURU BY SEMESTER **/
    public static function fetchDataJadwalUTS($auth_data, $id_pengguna, $id_semester, $is_online = 0, $id_ujian_mp = null)
    {
        // get id_guru
        $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
        $id_guru = $guru->id_guru;

        if ($is_online == 0 || $is_online != null) {
            if (! empty($id_ujian_mp)) {
                $jadwalUTS = Guru::select('guru.id_guru', 'guru.id_pengguna', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'pengampu_mp.pjmp_uts', 'ujian_mp.is_online')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->join('ujian_mp', 'ujian_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                    ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('ujian_mp.id_ujian_mp', '=', $id_ujian_mp)
                    ->where('kegiatan.kode_kegiatan', '=', "UTS")
                    ->where('kegiatan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('ujian_mp.is_online', '=', $is_online)
                    ->first();
            } else {
                $jadwalUTS = Guru::select('guru.id_guru', 'guru.id_pengguna', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'pengampu_mp.pjmp_uts', 'ujian_mp.is_online')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->join('ujian_mp', 'ujian_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                    ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                    ->join('ujian_mp_ruangan', 'ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
                    ->join('ruangan', 'ruangan.id_ruangan', '=', 'ujian_mp_ruangan.id_ruangan')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('kelas_mp.id_semester', '=', $id_semester)
                    ->where('kegiatan.kode_kegiatan', '=', "UTS")
                    ->where('kegiatan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('ujian_mp.is_online', '=', $is_online)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('ujian_mp.tgl_ujian_mp', 'asc')
                    ->orderBy('ujian_mp.jam_mulai', 'asc')
                    ->get();
            }
        } else {
            $jadwalUTS = Guru::select('guru.id_guru', 'guru.id_pengguna', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'pengampu_mp.pjmp_uts', 'ujian_mp.is_online')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->join('ujian_mp', 'ujian_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                    ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                    ->join('ujian_mp_ruangan', 'ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
                    ->join('ruangan', 'ruangan.id_ruangan', '=', 'ujian_mp_ruangan.id_ruangan')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('kelas_mp.id_semester', '=', $id_semester)
                    ->where('kegiatan.kode_kegiatan', '=', "UTS")
                    ->where('kegiatan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('ujian_mp.tgl_ujian_mp', 'asc')
                    ->orderBy('ujian_mp.jam_mulai', 'asc')
                    ->get();
        }

        return $jadwalUTS;
    }
    /** ========== **/

    /** JADWAL UAS GURU BY SEMESTER **/
    public static function fetchDataJadwalUAS($auth_data, $id_pengguna, $id_semester, $is_online = 100, $id_ujian_mp = null)
    {
        // get id_guru
        $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
        $id_guru = $guru->id_guru;

        if ($is_online == 0 || $is_online == 1) {
            if (! empty($id_ujian_mp)) {
                $jadwalUAS = Guru::select('guru.id_guru', 'guru.id_pengguna', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'pengampu_mp.pjmp_uts', 'ujian_mp.is_online')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->join('ujian_mp', 'ujian_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                    ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('ujian_mp.id_ujian_mp', '=', $id_ujian_mp)
                    ->where('kegiatan.kode_kegiatan', '=', "UAS")
                    ->where('kegiatan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('ujian_mp.is_online', '=', $is_online)
                    ->first();
            } else {
                $jadwalUAS = Guru::select('guru.id_guru', 'guru.id_pengguna', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'pengampu_mp.pjmp_uas', 'ujian_mp.is_online')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->join('ujian_mp', 'ujian_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                    ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                    ->join('ujian_mp_ruangan', 'ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
                    ->join('ruangan', 'ruangan.id_ruangan', '=', 'ujian_mp_ruangan.id_ruangan')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('kelas_mp.id_semester', '=', $id_semester)
                    ->where('kegiatan.kode_kegiatan', '=', "UAS")
                    ->where('kegiatan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('ujian_mp.is_online', '=', $is_online)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('ujian_mp.tgl_ujian_mp', 'asc')
                    ->orderBy('ujian_mp.jam_mulai', 'asc')
                    ->get();
            }
        } else {
            $jadwalUAS = Guru::select('guru.id_guru', 'guru.id_pengguna', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'pengampu_mp.pjmp_uas', 'ujian_mp.is_online')
                    ->join('pengampu_mp', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengampu_mp.id_kelas_mp')
                    ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->join('ujian_mp', 'ujian_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                    ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                    ->join('ujian_mp_ruangan', 'ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
                    ->join('ruangan', 'ruangan.id_ruangan', '=', 'ujian_mp_ruangan.id_ruangan')
                    ->where('pengampu_mp.id_guru', '=', $id_guru)
                    ->where('kelas_mp.id_semester', '=', $id_semester)
                    ->where('kegiatan.kode_kegiatan', '=', "UAS")
                    ->where('kegiatan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('ujian_mp.tgl_ujian_mp', 'asc')
                    ->orderBy('ujian_mp.jam_mulai', 'asc')
                    ->get();
        }

        return $jadwalUAS;
    }
    /** ========== **/

    /** Kelas Mata Pelajaran **/
    public static function fetchDataKelasMp($auth_data, $id_kelas_mp)
    {
        $kelasMp = KelasMp::select('kelas_mp.id_kelas_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'semester.nm_semester', 'semester.tahun_ajaran')
                        ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                        ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                        ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
                        ->where('kelas_mp.id_kelas_mp', '=', $id_kelas_mp)
                        ->first();

        return $kelasMp;
    }
    /** ========== **/

    /** Komponen Nilai **/
    public static function fetchDataKomponenNilai($auth_data, $id_kelas_mp, $id = null)
    {
        // get mode view
        if ($id == null) {
            $komponenMp = KomponenMp::select('komponen_mp.id_komponen_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'komponen_mp.nm_komponen_mp', 'komponen_mp.persentase_komponen_mp', 'komponen_mp.urutan_komponen_mp')
                        ->join('kelas_mp', function($join){
                            $join->on('kelas_mp.id_kelas_mp', '=', 'komponen_mp.id_kelas_mp');
                            $join->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('mata_pelajaran', function($join){
                            $join->on('mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran');
                            $join->whereNull('mata_pelajaran.deleted_at');
                        })
                        ->join('kelas', function($join){
                            $join->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas');
                            $join->whereNull('kelas.deleted_at');
                        })
                        ->where('kelas_mp.id_kelas_mp', '=', $id_kelas_mp)
                        ->orderBy('komponen_mp.urutan_komponen_mp', 'asc')
                        ->with('sub_komponen_mp')
                        ->get();
        }
        // get mode edit
        else {
            $komponenMp = KomponenMp::where('komponen_mp.id_komponen_mp', '=', $id)->first();
        }

        return $komponenMp;
    }
    /** ========== **/
    
    /** Sub Komponen Nilai **/
    public static function fetchDataSubKomponenNilai($auth_data, $id_komponen_mp, $id = null)
    {
        // get mode view
        if ($id == null) {
            $subKomponenMp = SubKomponenMp::select('komponen_mp.id_komponen_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'komponen_mp.nm_komponen_mp', 'komponen_mp.persentase_komponen_mp', 'komponen_mp.urutan_komponen_mp', 'subkomponen_mp.id_subkomponen_mp', 'subkomponen_mp.kd_subkomponen_mp', 'subkomponen_mp.nm_subkomponen_mp', 'subkomponen_mp.type_subkomponen_mp')
                        ->join('komponen_mp', 'komponen_mp.id_komponen_mp', '=', 'subkomponen_mp.id_komponen_mp')
                        ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'komponen_mp.id_kelas_mp')
                        ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                        ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                        ->where('komponen_mp.id_komponen_mp', '=', $id_komponen_mp)
                        ->orderBy('komponen_mp.urutan_komponen_mp', 'asc')
                        ->get();
        }
        // get mode edit
        else {
            $subKomponenMp = SubKomponenMp::find($id);
        }

        return $subKomponenMp;
    }
    /** ========== **/

    /** Komponen Nilai EKSKUL **/
    public static function fetchDataKomponenNilaiEkskul($auth_data, $id_semester, $id_ekskul, $id = null)
    {
        // get mode view
        if ($id == null) {
            $komponenEkskul = KomponenEkskul::select('komponen_ekskul.id_komponen_ekskul', 'ekskul.nm_ekskul', 'komponen_ekskul.nm_komponen_ekskul', 'komponen_ekskul.persentase_komponen_ekskul', 'komponen_ekskul.urutan_komponen_ekskul')
                        ->join('ekskul', function($join){
                            $join->on('ekskul.id_ekskul', '=', 'komponen_ekskul.id_ekskul');
                            $join->whereNull('ekskul.deleted_at');
                        })
                        ->join('semester', function($join){
                            $join->on('semester.id_semester', '=', 'komponen_ekskul.id_semester');
                            $join->whereNull('semester.deleted_at');
                        })
                        ->where('komponen_ekskul.id_ekskul', '=', $id_ekskul)
                        ->where('komponen_ekskul.id_semester', '=', $id_semester)
                        ->orderBy('komponen_ekskul.urutan_komponen_ekskul', 'asc')
                        ->get();
        }
        // get mode edit
        else {
            $komponenEkskul = KomponenEkskul::with('semester', 'ekskul')->find($id);
        }

        return $komponenEkskul;
    }
    /** ========== **/

    /** Wali Kelas **/
    public static function fetchDataWaliKelas($auth_data, $id_kelas, $id = null)
    {

        // get mode view
        if ($id == null) {
            $waliKelas = WaliKelas::select('wali_kelas.id_wali_kelas', 'wali_kelas.id_guru', 'wali_kelas.id_kelas', 'wali_kelas.id_semester', 'kelas.nm_kelas', 'pengguna.nm_pengguna as nm_wali_kelas', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'semester.tahun_ajaran', 'semester.nm_semester', 'wali_kelas.is_aktif')
                ->join('kelas', 'kelas.id_kelas', '=', 'wali_kelas.id_kelas')
                ->join('guru', 'guru.id_guru', '=', 'wali_kelas.id_guru')
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                ->join('semester', 'semester.id_semester', '=', 'wali_kelas.id_semester')
                ->where('wali_kelas.id_kelas', '=', $id_kelas)
                ->orderBy('semester.thn_akademik_semester', 'asc')
                ->orderBy('semester.nm_semester', 'asc')
                ->with('guru', 'guru.pengguna')
                ->get();
        }
        // get mode edit
        else {
            $waliKelas = WaliKelas::where('wali_kelas.id_wali_kelas', '=', $id)->first();
        }

        return $waliKelas;
    }

    public static function fetchDataBkKelas($auth_data, $id_kelas, $id = null)
    {

        // get mode view
        if ($id == null) {
            $bkKelas = BkKelas::select('bk_kelas.id_bk_kelas', 'bk_kelas.id_pengguna', 'bk_kelas.id_kelas', 'bk_kelas.id_semester', 'kelas.nm_kelas', 'pengguna.nm_pengguna as nm_bk_kelas', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'semester.tahun_ajaran', 'semester.nm_semester', 'bk_kelas.is_aktif')
                ->join('kelas', 'kelas.id_kelas', '=', 'bk_kelas.id_kelas')
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'bk_kelas.id_pengguna')
                ->join('semester', 'semester.id_semester', '=', 'bk_kelas.id_semester')
                ->where('bk_kelas.id_kelas', '=', $id_kelas)
                ->orderBy('semester.thn_akademik_semester', 'asc')
                ->orderBy('semester.nm_semester', 'asc')
                ->with('pengguna')
                ->get();
        }
        // get mode edit
        else {
            $bkKelas = BkKelas::where('bk_kelas.id_bk_kelas', '=', $id)->first();
        }

        return $bkKelas;
    }

    /** JADWAL KBM GURU with HARI BY id_pengguna **/
    public static function fetchDataJadwalKBMByKelas($auth_data, $id_semester = null, $id_kelas = null)
    {
        $jadwalKBM = JadwalKelasMp::with(['kelas_mp.mata_pelajaran', 'ruangan', 'jadwal_hari', 'kelas_mp'])
                ->whereHas('kelas_mp', function ($q) use ($id_semester, $id_kelas) {
                    $q->where('id_semester', $id_semester)->where('id_kelas', $id_kelas);
                })
                ->get();

        return $jadwalKBM;
    }
    /** ========== **/

    /** Wali Kelas By Semester **/
    public static function fetchDataWaliKelasBySemester($auth_data, $id_guru, $id_semester, $id = null)
    {
        $waliKelas = WaliKelas::select('wali_kelas.id_wali_kelas', 'wali_kelas.id_kelas', 'wali_kelas.id_semester', 'kelas.nm_kelas', 'pengguna.nm_pengguna as nm_wali_kelas', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'semester.tahun_ajaran', 'semester.nm_semester', 'wali_kelas.is_aktif')
                ->join('kelas', 'kelas.id_kelas', '=', 'wali_kelas.id_kelas')
                ->join('guru', 'guru.id_guru', '=', 'wali_kelas.id_guru')
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                ->join('semester', 'semester.id_semester', '=', 'wali_kelas.id_semester')
                ->where('wali_kelas.id_guru', '=', $id_guru)
                ->where('wali_kelas.id_semester', '=', $id_semester)
                ->orderBy('semester.thn_akademik_semester', 'asc')
                ->orderBy('semester.nm_semester', 'asc')
                ->first();

        return $waliKelas;
    }
    /** ========== **/

    /** Home Visit **/
    public static function fetchDataHomeVisit($auth_data, $id_semester, $id_guru, $id = null)
    {

        // get mode view
        if ($id == null) {
            $homeVisit = HomeVisit::select('home_visit.id_home_visit', 'home_visit.id_semester', 'home_visit.id_guru_wali_kelas', 'home_visit.id_siswa', 'semester.tahun_ajaran', 'semester.nm_semester', 'p_guru.nm_pengguna as nm_guru', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_siswa.nm_pengguna as nm_siswa', 'home_visit.nomor_hp_wali_murid', 'home_visit.alamat_wali_murid', 'home_visit.rangkuman_home_visit', 'home_visit.is_berkas_lengkap', 'home_visit.id_guru_kesiswaan', 'p_guru_kesiswaan.nm_pengguna as nm_guru_kesiswaan', 'p_guru_kesiswaan.gelar_depan as gelar_depan_kesiswaan', 'p_guru_kesiswaan.gelar_belakang as gelar_belakang_kesiswaan', 'p_tendik_kesiswaan.nm_pengguna as nm_tendik_kesiswaan', 'p_tendik_kesiswaan.gelar_depan as gelar_depan_tendik_kesiswaan', 'p_tendik_kesiswaan.gelar_belakang as gelar_belakang_tendik_kesiswaan')
                ->join('semester', 'semester.id_semester', '=', 'home_visit.id_semester')
                ->join('guru', 'guru.id_guru', '=', 'home_visit.id_guru_wali_kelas')
                ->join('pengguna as p_guru', 'p_guru.id_pengguna', '=', 'guru.id_pengguna')
                ->join('siswa', 'siswa.id_siswa', '=', 'home_visit.id_siswa')
                ->join('pengguna as p_siswa', 'p_siswa.id_pengguna', '=', 'siswa.id_pengguna')
                ->leftJoin('guru as guru_kesiswaan', 'guru_kesiswaan.id_guru', '=', 'home_visit.id_guru_kesiswaan')
                ->leftJoin('pengguna as p_guru_kesiswaan', 'p_guru_kesiswaan.id_pengguna', '=', 'guru_kesiswaan.id_pengguna')
                ->leftJoin('pengguna as p_tendik_kesiswaan', 'p_tendik_kesiswaan.id_pengguna', '=', 'home_visit.updated_by')
                ->where('home_visit.id_semester', '=', $id_semester)
                ->where('home_visit.id_guru_wali_kelas', '=', $id_guru)
                ->orderBy('siswa.nis_siswa', 'asc')
                ->get();
        }
        // get mode edit
        else {
            $homeVisit = HomeVisit::where('home_visit.id_home_visit', '=', $id)->first();
        }

        return $homeVisit;
    }
    /** ========== **/

    /** Monitoring Kelas Kosong **/
    public static function fetchDataKelasKosong($auth_data)
    {
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // 2019-01-08
        $tgl = $now->toDateString();
        // Senin = 1, Selasa = 2, dst
        $hari = $now->dayOfWeekIso;
        $jam = $now->hour;
        $menit = $now->minute;

        $kelasKosong = JadwalKelasMp::select('jadwal_kelas_mp.id_jadwal_kelas_mp', 'presensi_mp.id_presensi_mp', 'kelas_mp.id_kelas_mp', 'kelas.nm_kelas', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'pengguna.nm_pengguna as guru_pengampu')
                ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'jadwal_kelas_mp.id_kelas_mp')
                ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                ->join('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
                ->leftJoin('presensi_mp', function ($join) use ($tgl, $hari) {
                    $join->on('presensi_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                 ->where('DATE(presensi_mp.tgl_entry)', '=', $tgl)
                                 ->where('WEEKDAY(presensi_mp.tgl_entry)', '=', $hari - 1);
                })
                ->leftJoin('pengampu_mp', function ($join) {
                    $join->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                 ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1);
                })
                ->leftJoin('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
                ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                ->where('jadwal_kelas_mp.id_jadwal_hari', '=', $hari)
                ->whereBetween('HOUR('.$now.')', ['jadwal_jam.jam_mulai', 'jadwal_jam.jam_selesai'])
                ->whereBetween('MINUTE('.$now.')', ['jadwal_jam.menit_mulai', 'jadwal_jam.menit_selesai'])
                ->orderBy('kelas.tingkat', 'asc')
                ->orderBy('kelas.nm_kelas', 'asc')
                ->get();

        return $kelasKosong;
    }
    /** ========== **/

    /** EKSKUL GURU PEMBINA EKSKUL BY id_pengguna **/
    public static function fetchDataEkskulGuru($auth_data, $id_pengguna)
    {
        // get id_guru
        $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
        $id_guru = $guru->id_guru;

        // untuk pembina apakah ada semester/tahun_ajaran ?
        $ekskulGuru = PembinaEkskulSet::with('ekskul')->where('id_guru', $id_guru)->where('is_aktif', 1)->get();

        return $ekskulGuru;
    }
    /** ========== **/
}
