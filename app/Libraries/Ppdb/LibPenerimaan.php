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
            $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online')
                    ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
                    ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
                    ->where('penerimaan.id_penerimaan','=',$id)
                    ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->first();
        } else {
            /** get all data penerimaan */
            $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online')
                    ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
                    ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
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
        $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online')
                ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
                ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
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
            ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
            ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
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
                ->selectRaw("(SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_submit_form IS NOT NULL) AS jumlah_submit_form,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_proses_verifikasi IS NOT NULL AND status_verifikasi = 2) AS jumlah_antri_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_submit_verifikasi IS NOT NULL AND status_verifikasi = 3) AS jumlah_verifikasi_kembali,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND tgl_verifikasi_dokumen IS NOT NULL AND status_verifikasi = 1) AS jumlah_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru JOIN voucher ON voucher.kode_voucher = calon_siswa_baru.kode_voucher AND voucher.id_penerimaan = calon_siswa_baru.id_penerimaan WHERE calon_siswa_baru.id_penerimaan = penerimaan.id_penerimaan AND voucher.tgl_bayar IS NOT NULL) AS jumlah_bayar")
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
                ->selectRaw("(SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan) AS jumlah_p1,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_2 = jurusan.id_jurusan) AS jumlah_p2,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_3 = jurusan.id_jurusan) AS jumlah_p3,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan_jurusan.id_penerimaan AND penerimaan_jurusan.id_jurusan = jurusan.id_jurusan AND tgl_proses_verifikasi IS NOT NULL AND status_verifikasi = 2) AS jumlah_antri_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan_jurusan.id_penerimaan AND penerimaan_jurusan.id_jurusan = jurusan.id_jurusan AND tgl_verifikasi_dokumen IS NOT NULL AND status_verifikasi = 1) AS jumlah_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru JOIN voucher ON voucher.kode_voucher = calon_siswa_baru.kode_voucher AND voucher.id_penerimaan = calon_siswa_baru.id_penerimaan WHERE calon_siswa_baru.id_penerimaan = penerimaan_jurusan.id_penerimaan AND penerimaan_jurusan.id_jurusan = jurusan.id_jurusan AND voucher.tgl_bayar IS NOT NULL) AS jumlah_bayar")
                ->join('penerimaan_jurusan','penerimaan_jurusan.id_jurusan','=','jurusan.id_jurusan')
                ->where('penerimaan_jurusan.id_penerimaan','=',$id_penerimaan)
                ->orderBy('jurusan.nm_jurusan', 'asc')
                ->get();

        return $jurusan;
    }

    /** 
     * Get data detail pendaftaran
     * @param String id_penerimaan
     * @return Object calon_siswa_baru
     */
    static function fetchDataDetailPendaftaran($auth_data, $id_penerimaan)
    {
        /** get all data penerimaan */
        $calon_siswa_baru = CalonSiswaBaru::select('jurusan.id_jurusan', 'jurusan.nm_jurusan')
                ->selectRaw("(SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_1 = jurusan.id_jurusan) AS jumlah_p1,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_2 = jurusan.id_jurusan) AS jumlah_p2,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_pilihan_jurusan_3 = jurusan.id_jurusan) AS jumlah_p3,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan_jurusan.id_penerimaan AND penerimaan_jurusan.id_jurusan = jurusan.id_jurusan AND tgl_proses_verifikasi IS NOT NULL AND status_verifikasi = 2) AS jumlah_antri_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru WHERE calon_siswa_baru.id_penerimaan = penerimaan_jurusan.id_penerimaan AND penerimaan_jurusan.id_jurusan = jurusan.id_jurusan AND tgl_verifikasi_dokumen IS NOT NULL AND status_verifikasi = 1) AS jumlah_verifikasi,
                                (SELECT COUNT(id_c_siswa) FROM calon_siswa_baru JOIN voucher ON voucher.kode_voucher = calon_siswa_baru.kode_voucher AND voucher.id_penerimaan = calon_siswa_baru.id_penerimaan WHERE calon_siswa_baru.id_penerimaan = penerimaan_jurusan.id_penerimaan AND penerimaan_jurusan.id_jurusan = jurusan.id_jurusan AND voucher.tgl_bayar IS NOT NULL) AS jumlah_bayar")
                ->join('penerimaan_jurusan','penerimaan_jurusan.id_jurusan','=','jurusan.id_jurusan')
                ->where('penerimaan_jurusan.id_penerimaan','=',$id_penerimaan)
                ->orderBy('jurusan.nm_jurusan', 'asc')
                ->get();

        return $calon_siswa_baru;
    }
  
}
