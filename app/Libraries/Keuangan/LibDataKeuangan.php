<?php

namespace App\Libraries\Keuangan;

use App\Models\JenisDetailBiaya as JenisDetailBiaya;
use App\Models\Bulan as Bulan;

use App\Models\Biaya as Biaya;
use App\Models\KelompokBiayaInternal as KelompokBiayaInternal;
use App\Models\DetailBiayaInternal as DetailBiayaInternal;
use App\Models\KelompokBiaya as KelompokBiaya;
use App\Models\BiayaSekolah as BiayaSekolah;
use App\Models\DetailBiaya as DetailBiaya;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\Siswa as Siswa;
use App\Models\PemasukanBiayaKategori as PemasukanBiayaKategori;
use App\Models\PemasukanBiayaSubkategori as PemasukanBiayaSubkategori;
use App\Models\PemasukanBiaya as PemasukanBiaya;
use App\Models\PengeluaranBiayaKategori as PengeluaranBiayaKategori;
use App\Models\PengeluaranBiayaSubkategori as PengeluaranBiayaSubkategori;
use App\Models\PengeluaranBiaya as PengeluaranBiaya;
use App\Models\KategoriRapb as KategoriRapb;
use App\Models\SubkategoriRapb as SubkategoriRapb;
use App\Models\KetSubkategoriRapb as KetSubkategoriRapb;
use App\Models\PembayaranBiaya;
use App\Models\Semester as Semester;
use App\Models\Rapb as Rapb;
use App\Models\Realisasi as Realisasi;
use App\Models\RealisasiPembayaran as RealisasiPembayaran;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DateTime;
use DB;

class LibDataKeuangan
{
    /** DATA MASTER **/
    public static function fetchDataJenisDetailBiaya($auth_data)
    {
        $jenisDetailBiaya = JenisDetailBiaya::orderBy('id_jenis_detail_biaya', 'asc')->get();

        return $jenisDetailBiaya;
    }

    public static function fetchDataBulan($auth_data)
    {
        $bulan = Bulan::orderBy('id_bulan', 'asc')->get();

        return $bulan;
    }
    /** ========== **/

    /** NAMA BIAYA **/
    public static function fetchDataNamaBiaya($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $biaya = Biaya::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_biaya', 'asc')->get();
        }
        // get mode edit
        else {
            $biaya = Biaya::where('id_biaya', '=', $id)->first();
        }

        return $biaya;
    }
    /** ========== **/

    /** KELOMPOK BIAYA INTERNAL **/
    public static function fetchDataBiayaInternal($auth_data, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $biayaInternal = KelompokBiayaInternal::select('kelompok_biaya_internal.id_kelompok_biaya_internal', 'kelompok_biaya_internal.is_aktif', 'biaya.id_biaya', 'biaya.nm_biaya', 'kelompok_biaya_internal.nm_kelompok_biaya_internal')
                                ->join('biaya', function ($q) {
                                    $q->on('biaya.id_biaya', '=', 'kelompok_biaya_internal.id_biaya')
                                        ->whereNull('biaya.deleted_at');
                                })
                                ->where('kelompok_biaya_internal.is_aktif', '=', 1)
                                ->where('biaya.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->orderBy('kelompok_biaya_internal.nm_kelompok_biaya_internal', 'desc');

            if ($is_datatable == null) {
                $biayaInternal = $biayaInternal->get();
            }
        }
        // get mode edit
        else {
            $biayaInternal = KelompokBiayaInternal::where('id_kelompok_biaya_internal', '=', $id)->first();
        }

        return $biayaInternal;
    }
    /** ========== **/

    /** KELOMPOK BIAYA INTERNAL **/
    public static function fetchDataDetailBiayaInternal($auth_data, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $detailBiayaInternal = DetailBiayaInternal::select('detail_biaya_internal.id_detail_biaya_internal', 'kelompok_biaya_internal.id_kelompok_biaya_internal', 'biaya.id_biaya', 'biaya.nm_biaya', 'kelompok_biaya_internal.nm_kelompok_biaya_internal', 'detail_biaya_internal.nm_detail_biaya_internal', 'detail_biaya_internal.besar_biaya')
                                ->join('kelompok_biaya_internal', function ($q) {
                                    $q->on('kelompok_biaya_internal.id_kelompok_biaya_internal', '=', 'detail_biaya_internal.id_kelompok_biaya_internal')
                                        ->whereNull('kelompok_biaya_internal.deleted_at');
                                })
                                ->join('biaya', function ($q) {
                                    $q->on('biaya.id_biaya', '=', 'kelompok_biaya_internal.id_biaya')
                                        ->whereNull('biaya.deleted_at');
                                })
                                ->where('biaya.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('kelompok_biaya_internal.nm_kelompok_biaya_internal', 'desc')
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->orderBy('detail_biaya_internal.nm_detail_biaya_internal', 'desc');

            if ($is_datatable == null) {
                $detailBiayaInternal = $detailBiayaInternal->get();
            }
        }
        // get mode edit
        else {
            $detailBiayaInternal = DetailBiayaInternal::where('id_detail_biaya_internal', '=', $id)->first();
        }

        return $detailBiayaInternal;
    }
    /** ========== **/
    
    /** KELOMPOK BIAYA **/
    public static function fetchDataKelompokBiaya($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $kelompokBiaya = KelompokBiaya::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('status_kelompok_biaya', 'asc')
                                ->orderBy('nm_kelompok_biaya', 'asc')
                                ->get();
        }
        // get mode edit
        else {
            $kelompokBiaya = KelompokBiaya::where('id_kelompok_biaya', '=', $id)->first();
        }

        return $kelompokBiaya;
    }
    /** ========== **/

    /** BIAYA SEKOLAH **/
    public static function fetchDataBiayaSekolah($auth_data, $valid = null, $id = null, $is_datatables = null)
    {

        // get mode view
        if ($id == null) {
            $biayaSekolah = BiayaSekolah::select('biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'biaya_sekolah.id_jalur', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'jalur.nm_jalur', 'biaya_sekolah.besar_biaya_sekolah', 'biaya_sekolah.validasi_biaya_sekolah', 'biaya_sekolah.keterangan_biaya_sekolah')
                                ->join('kelompok_biaya', function ($q) {
                                    $q->on('kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
                                        ->whereNull('kelompok_biaya.deleted_at');
                                })
                                ->join('semester', function ($q) {
                                    $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
                                        ->whereNull('semester.deleted_at');
                                })
                                ->leftJoin('jalur', function ($q) {
                                    $q->on('jalur.id_jalur', '=', 'biaya_sekolah.id_jalur')
                                        ->whereNull('jalur.deleted_at');
                                })
                                ->where('kelompok_biaya.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
            if (! empty($valid)) {
                $biayaSekolah = $biayaSekolah->where('biaya_sekolah.validasi_biaya_sekolah', '=', $valid);
            }
            $biayaSekolah = $biayaSekolah->orderBy('semester.thn_akademik_semester', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('kelompok_biaya.status_kelompok_biaya', 'asc')
                                ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc');
            
            if(empty($is_datatables)){
                $biayaSekolah = $biayaSekolah->get();
            }
        }
        // get mode edit
        else {
            $biayaSekolah = BiayaSekolah::where('id_biaya_sekolah', '=', $id)->first();
        }

        return $biayaSekolah;
    }
    /** ========== **/

    /** DETAIL BIAYA **/
    public static function fetchDataDetailBiaya($auth_data, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $detailBiaya = DetailBiaya::select('detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'b_internal.nm_biaya as nm_biaya_internal', 'kelompok_biaya_internal.nm_kelompok_biaya_internal', 'detail_biaya.validasi_biaya', 'detail_biaya.besar_biaya', 'detail_biaya.keterangan_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'jalur.nm_jalur')
                                ->join('biaya_sekolah', function ($q) {
                                    $q->on('biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                                        ->whereNull('biaya_sekolah.deleted_at');
                                })
                                ->leftJoin('jalur', function ($q) {
                                    $q->on('jalur.id_jalur', '=', 'biaya_sekolah.id_jalur')
                                        ->whereNull('jalur.deleted_at');
                                })
                                ->join('kelompok_biaya', function ($q) {
                                    $q->on('kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
                                        ->whereNull('kelompok_biaya.deleted_at');
                                })
                                ->join('semester', function ($q) {
                                    $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
                                        ->whereNull('semester.deleted_at');
                                })
                                ->join('biaya', function ($q) {
                                    $q->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                                        ->whereNull('biaya.deleted_at');
                                })
                                ->leftJoin('kelompok_biaya_internal', function ($q) {
                                    $q->on('kelompok_biaya_internal.id_kelompok_biaya_internal', '=', 'detail_biaya.id_kelompok_biaya_internal')
                                        ->whereNull('kelompok_biaya_internal.deleted_at');
                                })
                                ->leftJoin('jenis_detail_biaya', function ($q) {
                                    $q->on('jenis_detail_biaya.id_jenis_detail_biaya', '=', 'detail_biaya.id_jenis_detail_biaya')
                                        ->whereNull('jenis_detail_biaya.deleted_at');
                                })
                                ->leftJoin('bulan', function ($q) {
                                    $q->on('bulan.id_bulan', '=', 'detail_biaya.id_bulan')
                                        ->whereNull('bulan.deleted_at');
                                })
                                ->leftJoin('biaya as b_internal', function ($q) {
                                    $q->on('b_internal.id_biaya', '=', 'kelompok_biaya_internal.id_biaya')
                                        ->whereNull('b_internal.deleted_at');
                                })
                                ->where('biaya_sekolah.validasi_biaya_sekolah', '=', 1)
                                ->where('biaya.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('semester.thn_akademik_semester', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('kelompok_biaya.status_kelompok_biaya', 'asc')
                                ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc')
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->orderBy('jenis_detail_biaya.nm_jenis_detail_biaya', 'asc')
                                ->orderBy('bulan.id_bulan', 'asc');

            if ($is_datatable == null) {
                $detailBiaya = $detailBiaya->get();
            }
        }
        // get mode edit
        else {
            $detailBiaya = DetailBiaya::where('id_detail_biaya', '=', $id)->first();
        }

        return $detailBiaya;
    }
    /** ========== **/

    /** KELOMPOK BIAYA SISWA **/
    public static function fetchDataBiayaSiswa($auth_data, $ada = null, $id = null, $id_kelas = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.id_kelompok_biaya', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'kelompok_biaya.nm_kelompok_biaya', 'kelompok_biaya.status_kelompok_biaya')
                                ->leftJoin('kelompok_biaya', function ($q) {
                                    $q->on('kelompok_biaya.id_kelompok_biaya', '=', 'siswa.id_kelompok_biaya')
                                        ->whereNull('kelompok_biaya.deleted_at');
                                })
                                ->leftJoin('kelas', function ($q) {
                                    $q->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                                        ->whereNull('kelas.deleted_at');
                                })
                                ->join('pengguna', function ($q) {
                                    $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                                        ->whereNull('pengguna.deleted_at');
                                })
                                ->join('status_pengguna', function ($q) {
                                    $q->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                                        ->whereNull('status_pengguna.deleted_at');
                                })
                                ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                                ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
            if ($ada == "0") {
                $siswa = $siswa->whereNull('siswa.id_kelompok_biaya');
            } else {
                $siswa = $siswa->whereNotNull('siswa.id_kelompok_biaya');
            }
            $siswa = $siswa->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->orderBy('pengguna.nm_pengguna', 'asc')
                        ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc');

            if (!empty($id_kelas) && $id_kelas != false) {
                $siswa = $siswa->where('siswa.id_kelas', $id_kelas);
            }

            if ($is_datatable == null) {
                $siswa = $siswa->get();
            }
        }
        // get mode edit
        else {
            $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.id_kelompok_biaya', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'kelompok_biaya.nm_kelompok_biaya', 'kelompok_biaya.status_kelompok_biaya')
                                ->leftJoin('kelompok_biaya', function ($q) {
                                    $q->on('kelompok_biaya.id_kelompok_biaya', '=', 'siswa.id_kelompok_biaya')
                                        ->whereNull('kelompok_biaya.deleted_at');
                                })
                                ->leftJoin('kelas', function ($q) {
                                    $q->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                                        ->whereNull('kelas.deleted_at');
                                })
                                ->join('pengguna', function ($q) {
                                    $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                                        ->whereNull('pengguna.deleted_at');
                                })
                                ->join('status_pengguna', function ($q) {
                                    $q->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                                        ->whereNull('status_pengguna.deleted_at');
                                })
                                ->where('siswa.id_siswa', '=', $id)
                                ->first();
        }

        return $siswa;
    }
    /** ========== **/

    /** VIEW SISWA TAGIHAN **/
    public static function fetchDataSiswaTagihan($auth_data, $thn_masuk_siswa, $id_semester, $id_kelompok_biaya, $id_jalur, $is_datatable = null)
    {
        $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.id_kelompok_biaya', 'calon_siswa_baru.kode_voucher', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'siswa.thn_masuk_siswa', 'kelas.nm_kelas', 'kelompok_biaya.nm_kelompok_biaya', 'kelompok_biaya.status_kelompok_biaya', 'jalur.nm_jalur'/*,
            DB::raw("(SELECT COUNT(*)
                        FROM tagihan_biaya tb
                        JOIN detail_biaya db ON db.id_detail_biaya = tb.id_detail_biaya
                        JOIN biaya_sekolah bs ON bs.id_biaya_sekolah = db.id_biaya_sekolah
                        WHERE tb.id_siswa = siswa.id_siswa AND tb.deleted_at IS NULL AND bs.id_semester = ?) AS jml_tagihan_detail_biaya", [$id_semester])*/)
                            ->join('calon_siswa_baru', function ($q) {
                                $q->on('calon_siswa_baru.id_c_siswa', '=', 'siswa.id_c_siswa')
                                    ->whereNull('calon_siswa_baru.deleted_at');
                            })
                            ->leftJoin('kelompok_biaya', function ($q) {
                                $q->on('kelompok_biaya.id_kelompok_biaya', '=', 'siswa.id_kelompok_biaya')
                                    ->whereNull('kelompok_biaya.deleted_at');
                            })
                            ->leftJoin('kelas', function ($q) {
                                $q->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                                    ->whereNull('kelas.deleted_at');
                            })
                            ->leftJoin('jalur_siswa', function ($join) {
                                $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                         ->where('jalur_siswa.is_jalur_aktif', '=', 1)
                                         ->whereNull('jalur_siswa.deleted_at');
                            })
                            ->leftJoin('jalur', function ($q) {
                                $q->on('jalur.id_jalur', '=', 'jalur_siswa.id_jalur')
                                    ->whereNull('jalur.deleted_at');
                            })
                            ->join('pengguna', function ($q) {
                                $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                                    ->whereNull('pengguna.deleted_at');
                            })
                            ->join('status_pengguna', function ($q) {
                                $q->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                                    ->whereNull('status_pengguna.deleted_at');
                            })
                            ->where('status_pengguna.aktif_status_pengguna', '=', 1)
                            ->where('siswa.thn_masuk_siswa', '=', $thn_masuk_siswa)
                            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        if ($id_kelompok_biaya != "0") {
            $siswa = $siswa->where('siswa.id_kelompok_biaya', '=', $id_kelompok_biaya);
        }
        if ($id_jalur != "0") {
            $siswa = $siswa->where('jalur.id_jalur', '=', $id_jalur);
        }

        $siswa = $siswa->orderBy('kelas.nm_kelas', 'asc')
                            ->orderBy('siswa.nis_siswa', 'asc')
                            ->orderBy('pengguna.nm_pengguna', 'asc')
                            ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc');

        if ($is_datatable == null) {
            $siswa = $siswa->get();
        }

        return $siswa;
    }
    /** ========== **/

    /** KATEGORI PEMASUKAN **/
    public static function fetchDataKategoriPemasukan($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $kategoriPemasukan = PemasukanBiayaKategori::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pemasukan_biaya_kategori', 'asc')->get();
        }
        // get mode edit
        else {
            $kategoriPemasukan = PemasukanBiayaKategori::where('id_pemasukan_biaya_kategori', '=', $id)->first();
        }

        return $kategoriPemasukan;
    }
    /** ========== **/

    /** SUBKATEGORI PEMASUKAN **/
    public static function fetchDataSubkategoriPemasukan($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $subkategoriPemasukan = PemasukanBiayaSubkategori::select('pemasukan_biaya_kategori.id_pemasukan_biaya_kategori', 'pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori', 'pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'pemasukan_biaya_kategori.keterangan_pemasukan_biaya_kategori', 'pemasukan_biaya_subkategori.keterangan_pemasukan_biaya_subkategori')
                                ->join('pemasukan_biaya_kategori', function ($q) {
                                    $q->on('pemasukan_biaya_kategori.id_pemasukan_biaya_kategori', '=', 'pemasukan_biaya_subkategori.id_pemasukan_biaya_kategori')
                                        ->whereNull('pemasukan_biaya_kategori.deleted_at');
                                })
                                ->where('pemasukan_biaya_kategori.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'asc')
                                ->orderBy('pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'asc')
                                ->get();
        }
        // get mode edit
        else {
            $subkategoriPemasukan = PemasukanBiayaSubkategori::where('id_pemasukan_biaya_subkategori', '=', $id)->first();
        }

        return $subkategoriPemasukan;
    }
    /** ========== **/

    /** PEMASUKAN **/
    public static function fetchDataPemasukan($auth_data, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $pemasukan = PemasukanBiaya::select('pemasukan_biaya.id_pemasukan_biaya', 'pemasukan_biaya_kategori.id_pemasukan_biaya_kategori', 'pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori', 'pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'semester.tahun_ajaran', 'semester.nm_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'pemasukan_biaya.tgl_pemasukan_biaya', 'pemasukan_biaya.besar_pemasukan_biaya', 'pemasukan_biaya.keterangan_pemasukan_biaya', 'pemasukan_biaya.is_upload_file')
                                ->join('pemasukan_biaya_subkategori', function ($q) {
                                    $q->on('pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori', '=', 'pemasukan_biaya.id_pemasukan_biaya_subkategori')
                                        ->whereNull('pemasukan_biaya_subkategori.deleted_at');
                                })
                                ->join('pemasukan_biaya_kategori', function ($q) {
                                    $q->on('pemasukan_biaya_kategori.id_pemasukan_biaya_kategori', '=', 'pemasukan_biaya_subkategori.id_pemasukan_biaya_kategori')
                                        ->whereNull('pemasukan_biaya_kategori.deleted_at');
                                })
                                ->join('semester', function ($q) {
                                    $q->on('semester.id_semester', '=', 'pemasukan_biaya.id_semester')
                                        ->whereNull('semester.deleted_at');
                                })
                                ->leftJoin('pengguna', function ($q) {
                                    $q->on('pengguna.id_pengguna', '=', 'pemasukan_biaya.created_by')
                                        ->whereNull('pengguna.deleted_at');
                                })
                                ->where('pemasukan_biaya_kategori.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('semester.tahun_ajaran', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'asc')
                                ->orderBy('pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'asc');

            if ($is_datatable == null) {
                $pemasukan = $pemasukan->get();
            }
        }
        // get mode edit
        else {
            $pemasukan = PemasukanBiaya::where('id_pemasukan_biaya', '=', $id)->first();
        }

        return $pemasukan;
    }
    /** ========== **/
    
    /** KATEGORI PENGELUARAN **/
    public static function fetchDataKategoriPengeluaran($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $kategoriPengeluaran = PengeluaranBiayaKategori::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengeluaran_biaya_kategori', 'asc')->get();
        }
        // get mode edit
        else {
            $kategoriPengeluaran = PengeluaranBiayaKategori::where('id_pengeluaran_biaya_kategori', '=', $id)->first();
        }

        return $kategoriPengeluaran;
    }
    /** ========== **/

    /** SUBKATEGORI PENGELUARAN **/
    public static function fetchDataSubkategoriPengeluaran($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $subkategoriPengeluaran = PengeluaranBiayaSubkategori::select('pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori', 'pengeluaran_biaya_subkategori.id_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'pengeluaran_biaya_kategori.keterangan_pengeluaran_biaya_kategori', 'pengeluaran_biaya_subkategori.keterangan_pengeluaran_biaya_subkategori')
                                ->join('pengeluaran_biaya_kategori', function ($q) {
                                    $q->on('pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori', '=', 'pengeluaran_biaya_subkategori.id_pengeluaran_biaya_kategori')
                                        ->whereNull('pengeluaran_biaya_kategori.deleted_at');
                                })
                                ->where('pengeluaran_biaya_kategori.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'asc')
                                ->orderBy('pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'asc')
                                ->get();
        }
        // get mode edit
        else {
            $subkategoriPengeluaran = PengeluaranBiayaSubkategori::where('id_pengeluaran_biaya_subkategori', '=', $id)->first();
        }

        return $subkategoriPengeluaran;
    }
    /** ========== **/

    /** PENGELUARAN **/
    public static function fetchDataPengeluaran($auth_data, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $pengeluaran = PengeluaranBiaya::select('pengeluaran_biaya.id_pengeluaran_biaya', 'pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori', 'pengeluaran_biaya_subkategori.id_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'semester.tahun_ajaran', 'semester.nm_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'pengeluaran_biaya.tgl_pengeluaran_biaya', 'pengeluaran_biaya.besar_pengeluaran_biaya', 'pengeluaran_biaya.keterangan_pengeluaran_biaya', 'pengeluaran_biaya.is_upload_file')
                                ->join('pengeluaran_biaya_subkategori', function ($q) {
                                    $q->on('pengeluaran_biaya_subkategori.id_pengeluaran_biaya_subkategori', '=', 'pengeluaran_biaya.id_pengeluaran_biaya_subkategori')
                                        ->whereNull('pengeluaran_biaya_subkategori.deleted_at');
                                })
                                ->join('pengeluaran_biaya_kategori', function ($q) {
                                    $q->on('pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori', '=', 'pengeluaran_biaya_subkategori.id_pengeluaran_biaya_kategori')
                                        ->whereNull('pengeluaran_biaya_kategori.deleted_at');
                                })
                                ->join('semester', function ($q) {
                                    $q->on('semester.id_semester', '=', 'pengeluaran_biaya.id_semester')
                                        ->whereNull('semester.deleted_at');
                                })
                                ->leftJoin('pengguna', function ($q) {
                                    $q->on('pengguna.id_pengguna', '=', 'pengeluaran_biaya.created_by')
                                        ->whereNull('pengguna.deleted_at');
                                })
                                ->where('pengeluaran_biaya_kategori.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                ->orderBy('semester.tahun_ajaran', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'asc')
                                ->orderBy('pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'asc');

            if ($is_datatable == null) {
                $pengeluaran = $pengeluaran->get();
            }
        }
        // get mode edit
        else {
            $pengeluaran = PengeluaranBiaya::where('id_pengeluaran_biaya', '=', $id)->first();
        }

        return $pengeluaran;
    }
    /** ========== **/

    /** KATEGORI RAPB **/
    public static function fetchDataKategoriRapb($auth_data, $jenis = null, $id = null)
    {

        // get mode view
        if ($id == null) {
            $kategoriRapb = KategoriRapb::select('*')
                                ->addSelect(
                                    DB::raw("(SELECT COUNT(*) FROM subkategori_rapb 
                                            WHERE subkategori_rapb.id_kategori_rapb = kategori_rapb.id_kategori_rapb 
                                            AND subkategori_rapb.deleted_at IS NULL) 
                                            AS jml_subkategori_rapb")
                                )
                                ->where('id_sekolah', '=', $auth_data->pengguna->id_sekolah);

            if ($jenis == 1) {
                $kategoriRapb = $kategoriRapb->where('tipe_kategori_rapb', '=', 1);
            } elseif ($jenis == 2) {
                $kategoriRapb = $kategoriRapb->where('tipe_kategori_rapb', '=', 2);
            }
                                
            $kategoriRapb = $kategoriRapb->orderBy('kode_kategori_rapb', 'asc')
                                                    ->orderBy('nm_kategori_rapb', 'asc')
                                                    ->get();
        }
        // get mode edit
        else {
            $kategoriRapb = KategoriRapb::where('id_kategori_rapb', '=', $id)->first();
        }

        return $kategoriRapb;
    }
    /** ========== **/

    /** SUBKATEGORI PEMASUKAN **/
    public static function fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id = null)
    {

        // get mode view
        if ($id == null) {
            $subkategoriRapb = SubkategoriRapb::select('kategori_rapb.id_kategori_rapb', 'subkategori_rapb.id_subkategori_rapb', 'kategori_rapb.kode_kategori_rapb', 'kategori_rapb.nm_kategori_rapb', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'subkategori_rapb.deskripsi_subkategori_rapb')
                                ->addSelect(
                                    DB::raw("(SELECT COUNT(*) FROM ket_subkategori_rapb 
                                            WHERE ket_subkategori_rapb.id_subkategori_rapb = subkategori_rapb.id_subkategori_rapb 
                                            AND ket_subkategori_rapb.deleted_at IS NULL) 
                                            AS jml_ket_subkategori_rapb")
                                )
                                ->join('kategori_rapb', function ($q) {
                                    $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                                        ->whereNull('kategori_rapb.deleted_at');
                                })
                                ->where('kategori_rapb.id_kategori_rapb', '=', $id_kategori_rapb)
                                ->orderBy('subkategori_rapb.kode_subkategori_rapb', 'asc')
                                ->orderBy('subkategori_rapb.nm_subkategori_rapb', 'asc')
                                ->get();
        }
        // get mode edit
        else {
            $subkategoriRapb = SubkategoriRapb::select('kategori_rapb.id_kategori_rapb', 'subkategori_rapb.id_subkategori_rapb', 'kategori_rapb.kode_kategori_rapb', 'kategori_rapb.nm_kategori_rapb', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'subkategori_rapb.deskripsi_subkategori_rapb')
                                ->join('kategori_rapb', function ($q) {
                                    $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                                        ->whereNull('kategori_rapb.deleted_at');
                                })
                                ->where('subkategori_rapb.id_subkategori_rapb', '=', $id)->first();
        }

        return $subkategoriRapb;
    }
    /** ========== **/

    /** KETERANGAN SUBKATEGORI PEMASUKAN **/
    public static function fetchDataKetSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb, $id = null)
    {

        // get mode view
        if ($id == null) {
            $ketSubkategoriRapb = KetSubkategoriRapb::select('kategori_rapb.id_kategori_rapb', 'subkategori_rapb.id_subkategori_rapb', 'ket_subkategori_rapb.id_ket_subkategori_rapb', 'kategori_rapb.nm_kategori_rapb', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'ket_subkategori_rapb.kode_ket_subkategori_rapb', 'ket_subkategori_rapb.nm_ket_subkategori_rapb')
                                ->join('subkategori_rapb', function ($q) {
                                    $q->on('subkategori_rapb.id_subkategori_rapb', '=', 'ket_subkategori_rapb.id_subkategori_rapb')
                                        ->whereNull('subkategori_rapb.deleted_at');
                                })
                                ->join('kategori_rapb', function ($q) {
                                    $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                                        ->whereNull('kategori_rapb.deleted_at');
                                })
                                ->where('subkategori_rapb.id_subkategori_rapb', '=', $id_subkategori_rapb)
                                ->orderBy('ket_subkategori_rapb.kode_ket_subkategori_rapb', 'asc')
                                ->orderBy('ket_subkategori_rapb.nm_ket_subkategori_rapb', 'asc')
                                ->get();
        }
        // get mode edit
        else {
            $ketSubkategoriRapb = KetSubkategoriRapb::where('id_ket_subkategori_rapb', '=', $id)->first();
        }

        return $ketSubkategoriRapb;
    }
    /** ========== **/

    /** RAPB **/
    public static function fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id = null, $is_datatable = null, $is_realisasi = null)
    {
        $semester_mulai = Semester::where('id_semester', '=', $id_semester_mulai)->first();
        $semester_selesai = Semester::where('id_semester', '=', $id_semester_selesai)->first();

        $kode_semester_mulai = $semester_mulai->kode_semester;
        $kode_semester_selesai = $semester_selesai->kode_semester;

        // get mode view
        if ($id == null) {
            $rapb = Rapb::select('rapb.id_rapb', 'rapb.id_semester_mulai', 'rapb.id_semester_selesai', 'rapb.id_subkategori_rapb', 'rapb.id_unit_kerja', 'kategori_rapb.id_kategori_rapb', 's_mulai.tahun_ajaran AS tahun_ajaran_mulai', 's_mulai.nm_semester AS nm_semester_mulai', 's_selesai.tahun_ajaran AS tahun_ajaran_selesai', 's_selesai.nm_semester AS nm_semester_selesai', 'kategori_rapb.tipe_kategori_rapb', 'kategori_rapb.jenis_kategori_rapb', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'unit_kerja.nm_unit_kerja', 'rapb.dana_perkiraan_rapb', 'rapb.tgl_rapb', 'rapb.prioritas_rapb', 'p_unit.nm_pengguna AS nm_kepala_unit', 'p_keuangan.nm_pengguna AS nm_kepala_keuangan')
                            ->addSelect(
                                DB::raw("(SELECT SUM(dana_realisasi) FROM realisasi 
                                            WHERE realisasi.id_rapb = rapb.id_rapb 
                                            AND realisasi.deleted_at IS NULL) 
                                            AS jml_realisasi")
                            )
                                ->join('semester AS s_mulai', function ($q) {
                                    $q->on('s_mulai.id_semester', '=', 'rapb.id_semester_mulai')
                                        ->whereNull('s_mulai.deleted_at');
                                })
                                ->join('semester AS s_selesai', function ($q) {
                                    $q->on('s_selesai.id_semester', '=', 'rapb.id_semester_selesai')
                                        ->whereNull('s_selesai.deleted_at');
                                })
                                ->join('subkategori_rapb', function ($q) {
                                    $q->on('subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                                        ->whereNull('subkategori_rapb.deleted_at');
                                })
                                ->join('kategori_rapb', function ($q) {
                                    $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                                        ->whereNull('kategori_rapb.deleted_at');
                                })
                                ->join('unit_kerja', function ($q) {
                                    $q->on('unit_kerja.id_unit_kerja', '=', 'rapb.id_unit_kerja')
                                        ->whereNull('unit_kerja.deleted_at');
                                })
                                ->leftJoin('pengguna AS p_unit', function ($q) {
                                    $q->on('p_unit.id_pengguna', '=', 'rapb.id_pengguna_kepala_unit')
                                        ->whereNull('p_unit.deleted_at');
                                })
                                ->leftJoin('pengguna AS p_keuangan', function ($q) {
                                    $q->on('p_keuangan.id_pengguna', '=', 'rapb.id_pengguna_kepala_keuangan')
                                        ->whereNull('p_keuangan.deleted_at');
                                })
                                ->where('s_mulai.kode_semester', '>=', $kode_semester_mulai)
                                ->where('s_selesai.kode_semester', '<=', $kode_semester_selesai)
                                ->where('kategori_rapb.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

            if ($is_realisasi != null) {
                $rapb = $rapb->whereNotNull('rapb.id_pengguna_kepala_unit')
                                                    ->whereNotNull('rapb.id_pengguna_kepala_keuangan');

                if ($is_realisasi == 1) {
                    $rapb = $rapb->where('rapb.prioritas_rapb', '=', 1);
                } elseif ($is_realisasi == 2) {
                    $rapb = $rapb->where('rapb.prioritas_rapb', '=', 2);
                } elseif ($is_realisasi == 3) {
                    $rapb = $rapb->where('rapb.prioritas_rapb', '=', 3);
                }
            }

            $rapb = $rapb->orderBy('unit_kerja.nm_unit_kerja', 'asc');

            if ($is_realisasi != null) {
                $rapb = $rapb->orderBy(DB::raw('rapb.dana_perkiraan_rapb - jml_realisasi'), 'asc');
            }

            $rapb = $rapb->orderBy('kategori_rapb.tipe_kategori_rapb', 'asc')
                                                ->orderBy('rapb.prioritas_rapb', 'desc')
                                                ->orderBy('rapb.tgl_rapb', 'desc');
                                
            if ($is_datatable == null) {
                $rapb = $rapb->get();
            }
        }
        // get mode edit
        else {
            $rapb = Rapb::select('rapb.id_rapb', 'rapb.id_semester_mulai', 'rapb.id_semester_selesai', 'rapb.id_subkategori_rapb', 'rapb.id_unit_kerja', 'kategori_rapb.id_kategori_rapb', 's_mulai.tahun_ajaran AS tahun_ajaran_mulai', 's_mulai.nm_semester AS nm_semester_mulai', 's_selesai.tahun_ajaran AS tahun_ajaran_selesai', 's_selesai.nm_semester AS nm_semester_selesai', 'kategori_rapb.tipe_kategori_rapb', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'unit_kerja.nm_unit_kerja', 'rapb.dana_perkiraan_rapb', 'rapb.tgl_rapb', 'rapb.prioritas_rapb')
                        ->addSelect(
                            DB::raw("(SELECT SUM(dana_realisasi) FROM realisasi 
                                        WHERE realisasi.id_rapb = rapb.id_rapb 
                                        AND realisasi.deleted_at IS NULL) 
                                        AS jml_realisasi")
                        )
                        ->join('semester AS s_mulai', 's_mulai.id_semester', '=', 'rapb.id_semester_mulai')
                        ->join('semester AS s_selesai', 's_selesai.id_semester', '=', 'rapb.id_semester_selesai')
                        ->join('subkategori_rapb', 'subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                        ->join('kategori_rapb', 'kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                        ->join('unit_kerja', 'unit_kerja.id_unit_kerja', '=', 'rapb.id_unit_kerja')
                        ->where('rapb.id_rapb', '=', $id)
                        ->first();
        }

        return $rapb;
    }
    /** ========== **/

    /** Realisasi RAPB **/
    public static function fetchDataRealisasi($auth_data, $id_rapb, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $realisasi = Realisasi::select(
                'realisasi.id_realisasi',
                'realisasi.id_semester_realisasi',
                'realisasi.id_rapb',
                'realisasi.id_unit_kerja',
                'realisasi.id_ket_subkategori_rapb',
                'rapb.id_subkategori_rapb',
                'kategori_rapb.tipe_kategori_rapb',
                'subkategori_rapb.kode_subkategori_rapb',
                'subkategori_rapb.nm_subkategori_rapb',
                'semester.tahun_ajaran',
                'semester.nm_semester',
                'unit_kerja.nm_unit_kerja',
                'realisasi.id_rpb_sarpras',
                'realisasi.nm_realisasi',
                'ket_subkategori_rapb.kode_ket_subkategori_rapb',
                'ket_subkategori_rapb.nm_ket_subkategori_rapb',
                'realisasi.termin_dana_realisasi',
                'realisasi.is_hutang_realisasi',
                'realisasi.dana_realisasi',
                'realisasi.tgl_realisasi',
                'p_cek_keuangan.nm_pengguna AS nm_cek_keuangan',
                'p_keuangan.nm_pengguna AS nm_kepala_keuangan',
                's_sarpras.tahun_ajaran AS tahun_ajaran_sarpras',
                's_sarpras.nm_semester AS nm_semester_sarpras',
                'uk_sarpras.nm_unit_kerja AS nm_unit_kerja_sarpras',
                'buku_alat.nm_buku_alat',
                'ruangan.nm_ruangan',
                'inventaris_ruangan.nm_inventaris_ruangan',
                'rpb_sarpras_supplier.harga_approve_supplier', 
                'rpb_sarpras_supplier.qty_approve_supplier', 
                'rpb_sarpras_supplier.termin_approve_supplier',
                'rpb_sarpras.tgl_rpb_sarpras',
                'rpb_sarpras.prioritas_rpb_sarpras'
            )
                                ->join('semester', 'semester.id_semester', '=', 'realisasi.id_semester_realisasi')
                                ->join('rapb', 'rapb.id_rapb', '=', 'realisasi.id_rapb')
                                ->join('subkategori_rapb', 'subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                                ->join('kategori_rapb', 'kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                                ->join('unit_kerja', 'unit_kerja.id_unit_kerja', '=', 'realisasi.id_unit_kerja')
                                ->leftJoin('ket_subkategori_rapb', 'ket_subkategori_rapb.id_ket_subkategori_rapb', '=', 'realisasi.id_ket_subkategori_rapb')
                                ->leftJoin('rpb_sarpras', function ($q) {
                                    $q->on('rpb_sarpras.id_rpb_sarpras', '=', 'realisasi.id_rpb_sarpras')
                                        ->whereNotNull('rpb_sarpras.id_pengguna_kepala_unit')
                                        ->whereNotNull('rpb_sarpras.id_pengguna_kepala_sarpras')
                                        ->whereNotNull('rpb_sarpras.id_pengguna_kepala_sarpras_approve')
                                        ->whereNull('rpb_sarpras.deleted_at');
                                })
                                ->leftJoin('rpb_sarpras_supplier', function ($q) {
                                    $q->on('rpb_sarpras_supplier.id_rpb_sarpras', '=', 'rpb_sarpras.id_rpb_sarpras')
                                        ->where('rpb_sarpras_supplier.is_approve', 1)
                                        ->whereNull('rpb_sarpras_supplier.deleted_at');
                                })
                                ->leftJoin('semester AS s_sarpras', 's_sarpras.id_semester', '=', 'rpb_sarpras.id_semester')
                                ->leftJoin('unit_kerja AS uk_sarpras', 'uk_sarpras.id_unit_kerja', '=', 'rpb_sarpras.id_unit_kerja')
                                ->leftJoin('buku_alat', 'buku_alat.id_buku_alat', '=', 'rpb_sarpras.id_buku_alat')
                                ->leftJoin('inventaris_ruangan', 'inventaris_ruangan.id_inventaris_ruangan', '=', 'rpb_sarpras.id_inventaris_ruangan')
                                ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'inventaris_ruangan.id_ruangan')
                                ->leftJoin('pengguna AS p_cek_keuangan', 'p_cek_keuangan.id_pengguna', '=', 'realisasi.id_pengguna_cek_keuangan')
                                ->leftJoin('pengguna AS p_keuangan', 'p_keuangan.id_pengguna', '=', 'realisasi.id_pengguna_kepala_keuangan')
                                ->where('realisasi.id_rapb', '=', $id_rapb)
                                ->orderBy('semester.tahun_ajaran', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('unit_kerja.nm_unit_kerja', 'asc')
                                ->orderBy('realisasi.tgl_realisasi', 'desc');
                                
            if ($is_datatable == null) {
                $realisasi = $realisasi->get();
            }
        }
        // get mode edit
        else {
            $realisasi = Realisasi::select(
                'realisasi.id_realisasi',
                'realisasi.id_semester_realisasi',
                'realisasi.id_rapb',
                'realisasi.id_unit_kerja',
                'realisasi.id_ket_subkategori_rapb',
                'rapb.id_subkategori_rapb',
                'kategori_rapb.tipe_kategori_rapb',
                'subkategori_rapb.kode_subkategori_rapb',
                'subkategori_rapb.nm_subkategori_rapb',
                'semester.tahun_ajaran',
                'semester.nm_semester',
                'unit_kerja.nm_unit_kerja',
                'realisasi.id_rpb_sarpras',
                'realisasi.nm_realisasi',
                'ket_subkategori_rapb.kode_ket_subkategori_rapb',
                'ket_subkategori_rapb.nm_ket_subkategori_rapb',
                'realisasi.termin_dana_realisasi',
                'realisasi.is_hutang_realisasi',
                'realisasi.dana_realisasi',
                'realisasi.tgl_realisasi',
                's_sarpras.tahun_ajaran AS tahun_ajaran_sarpras',
                's_sarpras.nm_semester AS nm_semester_sarpras',
                'uk_sarpras.nm_unit_kerja AS nm_unit_kerja_sarpras',
                'buku_alat.nm_buku_alat',
                'ruangan.nm_ruangan',
                'inventaris_ruangan.nm_inventaris_ruangan',
                'rpb_sarpras_supplier.harga_approve_supplier', 
                'rpb_sarpras_supplier.qty_approve_supplier', 
                'rpb_sarpras_supplier.termin_approve_supplier',
                'rpb_sarpras.tgl_rpb_sarpras',
                'rpb_sarpras.prioritas_rpb_sarpras'
            )
                        ->join('semester', 'semester.id_semester', '=', 'realisasi.id_semester_realisasi')
                        ->join('rapb', 'rapb.id_rapb', '=', 'realisasi.id_rapb')
                        ->join('subkategori_rapb', 'subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                        ->join('kategori_rapb', 'kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                        ->join('unit_kerja', 'unit_kerja.id_unit_kerja', '=', 'realisasi.id_unit_kerja')
                        ->leftJoin('ket_subkategori_rapb', 'ket_subkategori_rapb.id_ket_subkategori_rapb', '=', 'realisasi.id_ket_subkategori_rapb')
                        ->leftJoin('rpb_sarpras', function ($q) {
                            $q->on('rpb_sarpras.id_rpb_sarpras', '=', 'realisasi.id_rpb_sarpras')
                                ->whereNotNull('rpb_sarpras.id_pengguna_kepala_unit')
                                ->whereNotNull('rpb_sarpras.id_pengguna_kepala_sarpras')
                                ->whereNotNull('rpb_sarpras.id_pengguna_kepala_sarpras_approve')
                                ->whereNull('rpb_sarpras.deleted_at');
                        })
                        ->leftJoin('rpb_sarpras_supplier', function ($q) {
                            $q->on('rpb_sarpras_supplier.id_rpb_sarpras', '=', 'rpb_sarpras.id_rpb_sarpras')
                                ->where('rpb_sarpras_supplier.is_approve', 1)
                                ->whereNull('rpb_sarpras_supplier.deleted_at');
                        })
                        ->leftJoin('semester AS s_sarpras', 's_sarpras.id_semester', '=', 'rpb_sarpras.id_semester')
                        ->leftJoin('unit_kerja AS uk_sarpras', 'uk_sarpras.id_unit_kerja', '=', 'rpb_sarpras.id_unit_kerja')
                        ->leftJoin('buku_alat', 'buku_alat.id_buku_alat', '=', 'rpb_sarpras.id_buku_alat')
                        ->leftJoin('inventaris_ruangan', 'inventaris_ruangan.id_inventaris_ruangan', '=', 'rpb_sarpras.id_inventaris_ruangan')
                        ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'inventaris_ruangan.id_ruangan')
                        ->where('realisasi.id_realisasi', '=', $id)
                        ->first();
        }

        return $realisasi;
    }

    public static function fetchDataRealisasiPembayaran($auth_data, $id_realisasi, $id = null, $is_datatable = null)
    {

        // get mode view
        if ($id == null) {
            $realisasiPembayaran = RealisasiPembayaran::select('realisasi_pembayaran.*', 'pengguna.nm_pengguna')
                                ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'realisasi_pembayaran.id_pengguna_kepala_keuangan')
                                ->where('id_realisasi', '=', $id_realisasi)
                                ->orderBy('termin_ke', 'asc');
                                
            if ($is_datatable == null) {
                $realisasiPembayaran = $realisasiPembayaran->get();
            }
        }
        // get mode edit
        else {
            $realisasiPembayaran = RealisasiPembayaran::where('id_realisasi_pembayaran', '=', $id)
                        ->first();
        }

        return $realisasiPembayaran;
    }
    /** ========== **/

    /** Get Laporan Keuangan */
    public static function fetchDataLaporanKeuangan($auth_data, $start_date = null, $end_date = null)
    {
        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $dataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')->with(['tagihan_biaya.detail_biaya' => function ($q) {
            $q->with('biaya');
        }]);

        if (!empty($start_date) && !empty($end_date)) {
            $dataPembayaran = $dataPembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        $allDataPembayaran = $dataPembayaran->get();

        foreach($allDataPembayaran as $x){
            $date = new DateTime($x->tgl_pembayaran);

            if($x->tagihan_biaya->detail_biaya->id_bulan === null){ // untuk non-SPP
                $keterangan = $x->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' - ' . $x->tagihan_biaya->keterangan;
                
                $tempDataLaporan[] = [
                    'tanggal' => $date->format('Y-m-d'),
                    'keterangan' => $x->tagihan_biaya->siswa->pengguna->nm_pengguna .' - '. $keterangan,
                    'nominal' => $x->besar_pembayaran,
                    // 'tahun_ajaran' => ,
                    'nm_tipe' => 'debit',
                    'tipe' => 1, //penerimaan
                ];
            } else { // untuk SPP
                $keterangan = $x->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' - ' . Carbon::createFromFormat('m', $x->tagihan_biaya->detail_biaya->id_bulan)->format('F');

                foreach($x->tagihan_biaya->detail_biaya->kelompok_biaya_internal->detail_biaya_internal as $spp){
                    $tempDataLaporan[] = [
                        'tanggal' => $date->format('Y-m-d'),
                        'keterangan' => $x->tagihan_biaya->siswa->pengguna->nm_pengguna .' - '. $keterangan . ' (' .$spp->nm_detail_biaya_internal . ')',
                        'nominal' => $spp->besar_biaya,
                        // 'tahun_ajaran' => ,
                        'nm_tipe' => 'debit',
                        'tipe' => 1, //penerimaan
                    ];
                }
            }
        }
        
        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }
        $allDataRealisasi = $dataRealisasi->get();

        foreach($allDataRealisasi as $x){
            $keterangan = $x->rapb->subkategori->kategori->nm_kategori_rapb . ' - ' . $x->nm_realisasi;
            $tipe = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'keterangan' => $keterangan,
                'nominal' => $x->dana_realisasi,
                // 'tahun_ajaran' => ,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'tipe' => $tipe,
            ];
        }

        // reordering by date descending
        foreach(collect($tempDataLaporan)->sortByDesc('tanggal') as $data){
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');
        
        $data = [
            'laporan' => collect($dataLaporan)->sortByDesc('tanggal'),
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit
        ];

        return $data;
    }
    
    public static function fetchDataLaporanKeuanganInternal($auth_data, $start_date = null, $end_date = null)
    {
        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $allBiaya = Biaya::get();

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        $allDataPembayaran = $pembayaran->get();

        foreach($allBiaya as $kategori){
            $pembayaran = $allDataPembayaran->where('tagihan_biaya.detail_biaya.biaya.nm_biaya', '=', $kategori->nm_biaya)->groupBy('tagihan_biaya.detail_biaya.biaya_sekolah.semester.thn_akademik_semester');
            
            foreach($pembayaran as $ta => $value){
                $string = $value->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya;
                
                foreach($value as $data){
                    $details = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal->detail_biaya_internal;
                    $date           = new DateTime($data->tgl_pembayaran);
                    $ket_biaya      = $data->tagihan_biaya->detail_biaya->keterangan_biaya;
                    $sum            = $value->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)
                                            ->sum('besar_pembayaran');
                    $count          = $value->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)
                                            ->count();
                    $keyTempData    = $kategori->nm_biaya . '-' . $ket_biaya . '-' . $ta;
                    $tahun_ajaran   = $data->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;

                    if(count($details) > 0){
                        foreach($details as $x){
                            $tempDataLaporan[$keyTempData . $x->nm_detail_biaya_internal] = [
                                'tanggal' => $date->format('Y-m-d'),
                                'nominal' => $x->besar_biaya * $count,
                                'frekuensi' => $count,
                                'tipe' => 1,
                                'nm_tipe' => 'debit',
                                'keterangan' => $string . ' - ' . $x->nm_detail_biaya_internal . ' ' . $count . 'x '. number_format($x->besar_biaya) .' (' . $tahun_ajaran . ')',
                                'tahun_ajaran' => $tahun_ajaran
                            ];
                        }
                    } else {
                        $tempDataLaporan[$keyTempData] = [
                            'tanggal' => $date->format('Y-m-d'),
                            'nominal' => $sum,
                            'frekuensi' => $count,
                            'tipe' => 1,
                            'nm_tipe' => 'debit',
                            'keterangan' => $string . ((trim($ket_biaya) == "-" || $ket_biaya == null) ? null : ' - ' . $ket_biaya) . ' ' . $count . 'x (' . $tahun_ajaran . ')',
                            'tahun_ajaran' => $tahun_ajaran
                        ];
                    }
                }
            }
        }
        // dd($tempDataLaporan);
        
        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }
        $allDataRealisasi = $dataRealisasi->get();

        foreach($allDataRealisasi as $x){
            $keterangan = $x->rapb->subkategori->kategori->nm_kategori_rapb . ' - ' . $x->nm_realisasi;
            $tipe       = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date       = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'keterangan' => $keterangan,
                'nominal' => $x->dana_realisasi,
                'frekuensi' => null,
                'tahun_ajaran' => null,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'tipe' => $tipe,
            ];
        }
        
        // reordering by date descending
        foreach(collect($tempDataLaporan)->sortByDesc('tanggal') as $data){
            $dataLaporan[] = $data;
        }
        // dd($dataLaporan);

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');
        
        $data = [
            'laporan' => $dataLaporan,
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit
        ];

        return $data;
    }
    /** ========== */


    /** Merubah angka menjadi kalimat **/
    public static function getTerbilang(int $number) {
        $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($number < 12)
            return " " . $angka[$number];
        else if ($number < 20)
            return self::getTerbilang($number - 10) . " belas";
        else if ($number < 100)
            return self::getTerbilang($number / 10) . " puluh" . self::getTerbilang($number % 10);
        else if ($number < 200)
            return " seratus" . self::getTerbilang($number - 100);
        else if ($number < 1000)
            return self::getTerbilang($number / 100) . " ratus" . self::getTerbilang($number % 100);
        else if ($number < 2000)
            return " seribu" . self::getTerbilang($number - 1000);
        else if ($number < 1000000)
            return self::getTerbilang($number / 1000) . " ribu" . self::getTerbilang($number % 1000);
        else if ($number < 1000000000)
            return self::getTerbilang($number / 1000000) . " juta" . self::getTerbilang($number % 1000000);
    }
    /** =========== */
}
