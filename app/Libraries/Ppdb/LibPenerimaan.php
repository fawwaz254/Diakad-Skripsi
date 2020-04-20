<?php

namespace App\Libraries\Ppdb;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

use App\Models\Penerimaan as Penerimaan;
use App\Models\Jurusan as Jurusan;
use App\Models\CalonSiswaBaru as CalonSiswaBaru;

use Carbon\Carbon;
use Auth;
use DB;

/** 
 * Penawaran Jurusan Controller
 * @author irianto
 */
class LibPenerimaan
{
    /** 
     * Get data penerimaan by id and get all data penerimaan
     * @param String id_penerimaan
     * @return Object penerimaan
     */
    static function fetchDataPenerimaan($auth_data, $id = null)
    {
        if($id != null) {
            /** get data penerimaan by id_penerimaan */
            $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.gelombang_penerimaan', 'penerimaan.tahun_penerimaan', 'penerimaan.nm_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.jml_pilihan_jurusan', 'penerimaan.tgl_awal_registrasi', 'penerimaan.tgl_akhir_registrasi', 'penerimaan.tgl_awal_verifikasi', 'penerimaan.tgl_akhir_verifikasi', 'penerimaan.tgl_penetapan', 'penerimaan.tgl_pengumuman', 'penerimaan.tgl_awal_voucher', 'penerimaan.tgl_akhir_voucher', 'penerimaan.is_pendaftaran_online', 'penerimaan.is_verifikasi', 'penerimaan.is_bayar_voucher', 'penerimaan.nomor_rekening_transfer', 'penerimaan.biaya_daftar_ulang', 'penerimaan.jenis_penerimaan', 'penerimaan.is_aktif', 'penerimaan.biaya_daftar_ulang')
                    ->leftJoin('jalur', function($q){
                        $q->on('jalur.id_jalur','=','penerimaan.id_jalur')
                            ->whereNull('jalur.deleted_at');
                    })
                    ->leftJoin('semester', function($q){
                        $q->on('semester.id_semester','=','penerimaan.id_semester')
                            ->whereNull('semester.deleted_at');
                    })
                    ->where('penerimaan.id_penerimaan','=',$id)
                    ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->first();
        } else {
            /** get all data penerimaan */
            $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.gelombang_penerimaan', 'penerimaan.tahun_penerimaan', 'penerimaan.nm_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.jml_pilihan_jurusan', 'penerimaan.tgl_awal_registrasi', 'penerimaan.tgl_akhir_registrasi', 'penerimaan.tgl_awal_verifikasi', 'penerimaan.tgl_akhir_verifikasi', 'penerimaan.tgl_penetapan', 'penerimaan.tgl_pengumuman', 'penerimaan.tgl_awal_voucher', 'penerimaan.tgl_akhir_voucher', 'penerimaan.is_pendaftaran_online', 'penerimaan.is_verifikasi', 'penerimaan.is_bayar_voucher', 'penerimaan.nomor_rekening_transfer', 'penerimaan.biaya_daftar_ulang', 'penerimaan.jenis_penerimaan', 'penerimaan.is_aktif', 'penerimaan.biaya_daftar_ulang')
                    ->leftJoin('jalur', function($q){
                        $q->on('jalur.id_jalur','=','penerimaan.id_jalur')
                            ->whereNull('jalur.deleted_at');
                    })
                    ->leftJoin('semester', function($q){
                        $q->on('semester.id_semester','=','penerimaan.id_semester')
                            ->whereNull('semester.deleted_at');
                    })
                    ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('penerimaan.jenis_penerimaan','=','1')
                    ->orderBy('penerimaan.tahun_penerimaan', 'desc')
                    ->orderBy('penerimaan.nm_semester_penerimaan', 'asc')
                    ->orderBy('penerimaan.gelombang_penerimaan', 'asc')
                    ->orderBy('penerimaan.id_jalur', 'asc')
                    ->orderBy('penerimaan.nm_penerimaan', 'asc')
                    ->get();
        }
        return $penerimaan;
    }

    /** 
     * Get data penerimaan by id and get all data penerimaan
     * @param String id_penerimaan
     * @return Object penerimaan
     */
    static function fetchDataPenerimaanAllJenisPenerimaan($auth_data)
    {
        /** get all data penerimaan */
        $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online', 'penerimaan.biaya_daftar_ulang')
                ->leftJoin('jalur', function($q){
                    $q->on('jalur.id_jalur','=','penerimaan.id_jalur')
                        ->whereNull('jalur.deleted_at');
                })
                ->leftJoin('semester', function($q){
                    $q->on('semester.id_semester','=','penerimaan.id_semester')
                        ->whereNull('semester.deleted_at');
                })
                ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                ->orderBy('penerimaan.tahun_penerimaan', 'desc')
                ->orderBy('penerimaan.nm_semester_penerimaan', 'asc')
                ->orderBy('penerimaan.gelombang_penerimaan', 'asc')
                ->orderBy('penerimaan.id_jalur', 'asc')
                ->orderBy('penerimaan.nm_penerimaan', 'asc')
                ->get();

        return $penerimaan;
    }

    /** 
     * Get all data penerimaan 
     * Where not in $id_penerimaan 
     * Where same year
     * @param String id_penerimaan
     * @return Object penerimaan
     */
    static function fetchDataPindahPenerimaan($auth_data, $id)
    {
        $penerimaan = Penerimaan::select(
            'penerimaan.id_penerimaan', 'penerimaan.id_jalur',
            'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.is_pendaftaran_online')
            ->leftJoin('jalur', function($q){
                $q->on('jalur.id_jalur','=','penerimaan.id_jalur')
                    ->whereNull('jalur.deleted_at');
            })
            ->leftJoin('semester', function($q){
                $q->on('semester.id_semester','=','penerimaan.id_semester')
                    ->whereNull('semester.deleted_at');
            })
            ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
            ->where('id_penerimaan', '!=', $id)
            ->orderBy('penerimaan.tahun_penerimaan', 'desc')
            ->orderBy('penerimaan.nm_semester_penerimaan', 'asc')
            ->orderBy('penerimaan.gelombang_penerimaan', 'asc')
            ->orderBy('penerimaan.id_jalur', 'asc')
            ->orderBy('penerimaan.nm_penerimaan', 'asc')
            ->get();

        return $penerimaan;
    }

    /** 
     * Get data report pendaftaran
     * @param 
     * @return Object penerimaan
     */
    static function fetchDataReportPendaftaran($auth_data)
    {
        /** get all data penerimaan */
        $penerimaan = Penerimaan::select('id_penerimaan', 'tahun_penerimaan', 'id_jalur', 'nm_penerimaan', 'gelombang_penerimaan')
                ->selectRaw("(SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_submit_form IS NOT NULL AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_submit_form,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_proses_verifikasi IS NOT NULL AND status_verifikasi = 2 AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_antri_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_submit_verifikasi IS NOT NULL AND status_verifikasi = 3 AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_verifikasi_kembali,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_verifikasi_dokumen IS NOT NULL AND status_verifikasi = 1 AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru JOIN voucher ON voucher.kode_voucher = calon_siswa_baru.kode_voucher AND voucher.id_penerimaan = calon_siswa_baru.id_penerimaan WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND voucher.tgl_bayar IS NOT NULL AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_bayar")
                ->where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                ->orderBy('tahun_penerimaan', 'desc')
                ->orderBy('nm_semester_penerimaan', 'asc')
                ->orderBy('gelombang_penerimaan', 'asc')
                ->orderBy('id_jalur', 'asc')
                ->orderBy('nm_penerimaan', 'asc')
                ->get();

        return $penerimaan;
    }

    /** 
     * Get data rekap pendaftaran
     * @param String id_penerimaan
     * @return Object jurusan
     */
    static function fetchDataRekapPendaftaran($auth_data, $id_penerimaan)
    {
        /** get all data penerimaan */
        $jurusan = Jurusan::select('jurusan.id_jurusan', 'jurusan.nm_jurusan')
                ->selectRaw("(SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_p1,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_2 = jurusan.id_jurusan AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_p2,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_3 = jurusan.id_jurusan AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_p3,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan AND tgl_submit_form IS NOT NULL AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_submit_form,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan AND tgl_proses_verifikasi IS NOT NULL AND status_verifikasi = 2 AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_antri_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan AND tgl_verifikasi_dokumen IS NOT NULL AND status_verifikasi = 1 AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru JOIN voucher ON voucher.kode_voucher = calon_siswa_baru.kode_voucher AND voucher.id_penerimaan = calon_siswa_baru.id_penerimaan WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan AND voucher.tgl_bayar IS NOT NULL AND calon_siswa_baru.deleted_at IS NULL) AS jumlah_bayar")
                ->join('penerimaan_jurusan', function($q){
                    $q->on('penerimaan_jurusan.id_jurusan','=','jurusan.id_jurusan')
                        ->whereNull('penerimaan_jurusan.deleted_at');
                })
                ->where('penerimaan_jurusan.id_penerimaan','=',$id_penerimaan)
                ->orderBy('jurusan.nm_jurusan', 'asc')
                ->get();

        return $jurusan;
    }

    /** 
     * Get data jurusan detail pendaftaran
     * @param String id_penerimaan
     * @return Object jurusan
     */
    static function fetchDataJurusanDetailPendaftaran($auth_data, $id_penerimaan)
    {
        /** get all data penerimaan */
        $jurusan = Jurusan::select('jurusan.id_jurusan', 'jurusan.nm_jurusan')
                ->join('penerimaan_jurusan', function($q){
                    $q->on('penerimaan_jurusan.id_jurusan','=','jurusan.id_jurusan')
                        ->whereNull('penerimaan_jurusan.deleted_at');
                })
                ->where('penerimaan_jurusan.id_penerimaan','=',$id_penerimaan)
                ->orderBy('jurusan.nm_jurusan', 'asc')
                ->get();

        return $jurusan;
    }

    /** 
     * Get data detail pendaftaran
     * @param String id_penerimaan, id_jurusan
     * @return Object calon_siswa_baru
     */
    static function fetchDataDetailPendaftaran($auth_data, $id_penerimaan, $id_jurusan)
    {
        /** get all data penerimaan */
        $calon_siswa_baru = CalonSiswaBaru::select('calon_siswa_baru.id_c_siswa', 'jurusan.id_jurusan', 'calon_siswa_baru.nomor_ujian', 'calon_siswa_baru.kode_voucher', 'calon_siswa_baru.nm_c_siswa', 'calon_siswa_baru.nomor_hp', 'calon_siswa_sekolah.nm_sekolah_asal', 'calon_siswa_ortu.nm_ayah', 'calon_siswa_ortu.nm_ibu', 'calon_siswa_ortu.nomor_telp_ortu', 'calon_siswa_ortu.nomor_hp_ortu')
                ->join('jurusan', function($q){
                    $q->on('jurusan.id_jurusan','=','calon_siswa_baru.id_pilihan_jurusan_1')
                        ->whereNull('jurusan.deleted_at');
                })
                ->join('calon_siswa_sekolah', function($q){
                    $q->on('calon_siswa_sekolah.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
                        ->whereNull('calon_siswa_sekolah.deleted_at');
                })
                ->join('calon_siswa_ortu', function($q){
                    $q->on('calon_siswa_ortu.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
                        ->whereNull('calon_siswa_ortu.deleted_at');
                })
                ->where('calon_siswa_baru.id_pilihan_jurusan_1','=',$id_jurusan)
                ->where('calon_siswa_baru.id_penerimaan','=',$id_penerimaan)
                ->whereNotNull('calon_siswa_baru.tgl_submit_form')
                ->orderBy('calon_siswa_baru.nm_c_siswa', 'asc');

        return $calon_siswa_baru;
    }

    /** 
     * Get data calon siswa utk penetapan
     * @param String id_penerimaan
     * @return Object calon_siswa_baru
     */
    static function fetchDataCalonSiswaPenetapan($auth_data, $id_penerimaan)
    {
        /** get all data penerimaan */
        $calon_siswa_baru = CalonSiswaBaru::select('calon_siswa_baru.id_c_siswa', 'calon_siswa_baru.kode_voucher', 'calon_siswa_baru.nm_c_siswa', 'calon_siswa_baru.nomor_hp', 'calon_siswa_sekolah.nm_sekolah_asal', 'jurusan.nm_jurusan')
                ->join('calon_siswa_sekolah', function($q){
                    $q->on('calon_siswa_sekolah.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
                        ->whereNull('calon_siswa_sekolah.deleted_at');
                })
                ->join('jurusan', function($q){
                    $q->on('jurusan.id_jurusan','=','calon_siswa_baru.id_pilihan_jurusan_1')
                        ->whereNull('jurusan.deleted_at');
                })
                ->where('calon_siswa_baru.id_penerimaan','=',$id_penerimaan)
                ->where('calon_siswa_baru.status_verifikasi','=', 1)
                ->whereNotNull('calon_siswa_baru.tgl_verifikasi_dokumen')
                ->whereNull('calon_siswa_baru.nomor_ujian')
                ->orderBy('calon_siswa_baru.kode_voucher', 'asc');

        return $calon_siswa_baru;
    }
  
}
