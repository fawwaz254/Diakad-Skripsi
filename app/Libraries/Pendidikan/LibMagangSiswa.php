<?php

namespace App\Libraries\Pendidikan;

use App\Models\Magang as MagangSiswa;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\RekananMagang as RekananMagang;
use App\Models\PengambilanMagang as PengajuanSiswaMagang;
use App\Models\KomponenMagang as KomponenMagang;

use App\Models\Siswa as Siswa;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibMagangSiswa
{
    /** Magang Siswa **/
    static function fetchDataMagangSiswa($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $magangSiswa = MagangSiswa::select('id_magang', 'nm_magang', 'keterangan_magang')
                ->where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->orderBy('nm_magang', 'asc')->get();
        }
        // get mode edit
        else {
            $magangSiswa = MagangSiswa::where('id_magang', '=', $id)->first();
        }

        return $magangSiswa;
    }

    static function fetchDataRekananMagang($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $rekananMagang = RekananMagang::select('id_rekanan_magang', 'nm_rekanan_magang', 'nomor_telp_rekanan_magang', 'nomor_hp_rekanan_magang', 'alamat_rekanan_magang', 'tgl_awal_kerjasama', 'tgl_akhir_kerjasama', 'kuota_rekanan_magang', 'contact_person_rekanan_magang')
                ->where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->orderBy('nm_rekanan_magang', 'asc')->get();
        }
        // get mode edit
        else {
            $rekananMagang = RekananMagang::where('id_rekanan_magang', '=', $id)->first();
        }

        return $rekananMagang;
    }

    static function fetchDataPeriodeMagang($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $periodeMagang = PeriodeMagang::select('periode_magang.id_periode_magang', 'semester.tahun_ajaran', 'semester.id_semester', 'semester.nm_semester', 'magang.nm_magang', 'periode_magang.nm_periode_magang', 'periode_magang.besar_biaya', 'periode_magang.tgl_magang_mulai', 'periode_magang.tgl_magang_selesai', 'periode_magang.is_aktif', 'periode_magang.nomor_sk_periode_magang')
                ->join('magang', 'magang.id_magang', '=', 'periode_magang.id_magang')
                ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                ->where('magang.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->orderBy('semester.tahun_ajaran', 'desc')
                ->orderBy('semester.nm_semester', 'desc')
                ->orderBy('periode_magang.tgl_magang_mulai', 'desc')
                ->get();
        }
        // get mode edit
        elseif ($id == 'periodeAktif') {
            $periodeMagang = PeriodeMagang::select('periode_magang.id_periode_magang', 'semester.tahun_ajaran', 'semester.nm_semester', 'magang.nm_magang', 'periode_magang.nm_periode_magang', 'periode_magang.besar_biaya', 'periode_magang.tgl_magang_mulai', 'periode_magang.tgl_magang_selesai', 'periode_magang.is_aktif', 'periode_magang.nomor_sk_periode_magang')
                ->join('magang', 'magang.id_magang', '=', 'periode_magang.id_magang')
                ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                ->where('magang.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->where('periode_magang.is_aktif', '=', '1')
                ->orderBy('semester.tahun_ajaran', 'desc')
                ->orderBy('semester.nm_semester', 'desc')
                ->orderBy('periode_magang.tgl_magang_mulai', 'desc')
                ->get();
        } else {
            $periodeMagang = PeriodeMagang::join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')->where('periode_magang.id_periode_magang', '=', $id)->first();
        }

        return $periodeMagang;
    }

    static function fetchDataPengajuanSiswaMagang($auth_data, $id_periode_magang, $id_rekanan_magang, $nis_nama_siswa)
    {
        if ($id_periode_magang != "0") {
            if ($id_rekanan_magang != "0") {
                if ($nis_nama_siswa != "0") {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->leftJoin('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->leftJoin('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->leftJoin('rekanan_magang', function ($join) use ($id_rekanan_magang) {
                            $join->on('rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
                                ->where('rekanan_magang.id_rekanan_magang', '=', $id_rekanan_magang);
                        })
                        ->leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where(function ($query) use ($nis_nama_siswa) {
                            $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                                ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%');
                        })
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                } else {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->leftJoin('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->leftJoin('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->leftJoin('rekanan_magang', function ($join) use ($id_rekanan_magang) {
                            $join->on('rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
                                ->where('rekanan_magang.id_rekanan_magang', '=', $id_rekanan_magang);
                        })
                        ->leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                }
            } else {
                if ($nis_nama_siswa != "0") {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->leftJoin('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->leftJoin('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->leftJoin('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                        ->leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where(function ($query) use ($nis_nama_siswa) {
                            $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                                ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%');
                        })
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                } else {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->leftJoin('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->leftJoin('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->leftJoin('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                        ->leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                }
            }
        } else {
            if ($nis_nama_siswa != "0") {
                $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                    ->leftJoin('pengambilan_magang', function ($join) {
                        $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                            ->where('pengambilan_magang.status_magang', '<>', 10);
                    })
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                    ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                    ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                    ->leftJoin('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                    ->leftJoin('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                    ->leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                    ->where(function ($query) use ($nis_nama_siswa) {
                        $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                            ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%');
                    })
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
            } else {
                $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                    ->leftJoin('pengambilan_magang', function ($join) {
                        $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                            ->where('pengambilan_magang.status_magang', '<>', 10);
                    })
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                    ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                    ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                    ->leftJoin('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                    ->leftJoin('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                    ->leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
            }
        }

        return $siswa;
    }

    static function fetchDataPengajuanSiswaMagangDetailPeriode($auth_data, $id_periode_magang)
    {
        $siswa = PengajuanSiswaMagang::select(
            'pengambilan_magang.id_pengambilan_magang',
            'pengambilan_magang.id_periode_magang',
            'pengambilan_magang.id_rekanan_magang',
            'periode_magang.nm_periode_magang',
            'rekanan_magang.nm_rekanan_magang',
            'siswa.nis_siswa',
            'pengguna.nm_pengguna',
            'pengambilan_magang.nilai_angka',
            'siswa.id_siswa'
        )
            ->join('periode_magang', function ($q) {
                $q->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')->whereNull('periode_magang.deleted_at');
            })
            ->join('rekanan_magang', function ($q) {
                $q->on('rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')->whereNull('rekanan_magang.deleted_at');
            })
            ->join('siswa', function ($q) {
                $q->on('siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')->whereNull('siswa.deleted_at');
            })
            ->join('pengguna', function ($q) {
                $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')->whereNull('pengguna.deleted_at');
            })
            ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang)
            ->where('status_magang', 1)
            ->get();

        return $siswa;
    }

    static function fetchDataPengajuanSiswaMagangDetail($auth_data, $id_periode_magang)
    {
        $pengambilanMagang = PengajuanSiswaMagang::select(
            'pengambilan_magang.id_pengambilan_magang',
            'pengambilan_magang.id_periode_magang',
            'pengambilan_magang.id_rekanan_magang',
            'periode_magang.nm_periode_magang',
            'semester.tahun_ajaran',
            'semester.nm_semester',
            'siswa.nis_siswa',
            'pengguna.nm_pengguna',
            'kelas.nm_kelas'
        )
            ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
            ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
            ->join('siswa', 'siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->where('pengambilan_magang.id_pengambilan_magang', '=', $id_periode_magang)
            ->first();

        return $pengambilanMagang;
    }

    static function fetchDataSiswaMagang($auth_data, $id_periode_magang)
    {
        $siswa = PengajuanSiswaMagang::join('siswa', 'siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
            ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
            ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
            ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang)
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();

        return $siswa;
    }

    static function fetchDataKomponenNilaiMagang($auth_data, $id_periode_magang, $id = null)
    {
        // get mode view
        if ($id == null) {
            $komponenMagang = PeriodeMagang::select('periode_magang.id_periode_magang', 'semester.tahun_ajaran', 'semester.nm_semester', 'magang.nm_magang', 'periode_magang.nm_periode_magang')
                ->join('magang', 'magang.id_magang', '=', 'periode_magang.id_magang')
                ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                ->where('magang.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang)
                ->orderBy('semester.tahun_ajaran', 'desc')
                ->orderBy('semester.nm_semester', 'desc')
                ->get();
        }
        // get mode edit
        else {
            $komponenMagang = KomponenMagang::where('komponen_magang.id_komponen_magang', '=', $id)->first();
        }

        return $komponenMagang;
    }

    static function fetchDataKomponenNilaiMagangDetail($auth_data, $id_periode_magang, $id = null)
    {
        // get mode view
        if ($id == null) {
            $komponenMagang = PeriodeMagang::select(
                'periode_magang.id_periode_magang',
                'semester.tahun_ajaran',
                'semester.nm_semester',
                'magang.nm_magang',
                'periode_magang.nm_periode_magang',
                'komponen_magang.id_komponen_magang',
                'komponen_magang.nm_komponen_magang',
                'komponen_magang.persentase_komponen_magang',
                'komponen_magang.urutan_komponen_magang'
            )
                ->join('magang', 'magang.id_magang', '=', 'periode_magang.id_magang')
                ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                ->join('komponen_magang', 'komponen_magang.id_periode_magang', '=', 'periode_magang.id_periode_magang')
                ->where('magang.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->where('komponen_magang.deleted_by', '=', null)
                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang)
                ->orderBy('semester.tahun_ajaran', 'desc')
                ->orderBy('semester.nm_semester', 'desc')
                ->orderBy('komponen_magang.urutan_komponen_magang', 'asc')
                ->get();
        }
        // get mode edit
        else {
            $komponenMagang = KomponenMagang::where('komponen_magang.id_komponen_magang', '=', $id)->first();
        }

        return $komponenMagang;
    }

    static function fetchDataApproveSiswaMagang($auth_data, $id_periode_magang, $id_rekanan_magang, $nis_nama_siswa)
    {
        if ($id_periode_magang != "0") {
            if ($id_rekanan_magang != "0") {
                if ($nis_nama_siswa != "0") {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->join('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->join('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->join('rekanan_magang', function ($join) use ($id_rekanan_magang) {
                            $join->on('rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
                                ->where('rekanan_magang.id_rekanan_magang', '=', $id_rekanan_magang);
                        })
                        ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where(function ($query) use ($nis_nama_siswa) {
                            $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                                ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%');
                        })
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                } else {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->join('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->join('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->join('rekanan_magang', function ($join) use ($id_rekanan_magang) {
                            $join->on('rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
                                ->where('rekanan_magang.id_rekanan_magang', '=', $id_rekanan_magang);
                        })
                        ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                }
            } else {
                if ($nis_nama_siswa != "0") {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->join('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->join('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                        ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where(function ($query) use ($nis_nama_siswa) {
                            $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                                ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%');
                        })
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                } else {
                    $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                        ->join('pengambilan_magang', function ($join) {
                            $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                                ->where('pengambilan_magang.status_magang', '<>', 10);
                        })
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->join('periode_magang', function ($join) use ($id_periode_magang) {
                            $join->on('periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                                ->where('periode_magang.id_periode_magang', '=', $id_periode_magang);
                        })
                        ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                        ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->get();
                }
            }
        } else {
            if ($nis_nama_siswa != "0") {
                $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                    ->join('pengambilan_magang', function ($join) {
                        $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                            ->where('pengambilan_magang.status_magang', '<>', 10);
                    })
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                    ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                    ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                    ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                    ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                    ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                    ->where(function ($query) use ($nis_nama_siswa) {
                        $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                            ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%');
                    })
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
            } else {
                $siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'siswa.id_siswa', 'periode_magang.id_periode_magang', 'periode_magang.nm_periode_magang', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.kuota_rekanan_magang')
                    ->join('pengambilan_magang', function ($join) {
                        $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
                            ->where('pengambilan_magang.status_magang', '<>', 10);
                    })
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                    ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                    ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                    ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
                    ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_pengambilan_magang')
                    ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
            }
        }

        return $siswa;
    }
}
