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
use App\Models\Semester as Semester;
use App\Models\Rapb as Rapb;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibDataKeuangan
{
    /** DATA MASTER **/
    static function fetchDataJenisDetailBiaya($auth_data){

        $jenisDetailBiaya = JenisDetailBiaya::orderBy('id_jenis_detail_biaya', 'asc')->get();

        return $jenisDetailBiaya;
    }

    static function fetchDataBulan($auth_data){

        $bulan = Bulan::orderBy('id_bulan', 'asc')->get();

        return $bulan;
    }
    /** ========== **/

    /** NAMA BIAYA **/
    static function fetchDataNamaBiaya($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $biaya = Biaya::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_biaya', 'asc')->get();
        }
        // get mode edit
        else{
            $biaya = Biaya::where('id_biaya','=',$id)->first();
        }

        return $biaya;
    }
    /** ========== **/

    /** KELOMPOK BIAYA INTERNAL **/
    static function fetchDataBiayaInternal($auth_data, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $biayaInternal = KelompokBiayaInternal::select('kelompok_biaya_internal.id_kelompok_biaya_internal', 'biaya.id_biaya', 'biaya.nm_biaya', 'kelompok_biaya_internal.nm_kelompok_biaya_internal')
                                ->join('biaya','biaya.id_biaya','=','kelompok_biaya_internal.id_biaya')
                                ->where('biaya.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->orderBy('kelompok_biaya_internal.nm_kelompok_biaya_internal', 'desc');

                                if ( $is_datatable == null ) {
                                    $biayaInternal = $biayaInternal->get();
                                }
        }
        // get mode edit
        else{
            $biayaInternal = KelompokBiayaInternal::where('id_kelompok_biaya_internal','=',$id)->first();
        }

        return $biayaInternal;
    }
    /** ========== **/

    /** KELOMPOK BIAYA INTERNAL **/
    static function fetchDataDetailBiayaInternal($auth_data, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $detailBiayaInternal = DetailBiayaInternal::select('detail_biaya_internal.id_detail_biaya_internal', 'kelompok_biaya_internal.id_kelompok_biaya_internal', 'biaya.id_biaya', 'biaya.nm_biaya', 'kelompok_biaya_internal.nm_kelompok_biaya_internal', 'detail_biaya_internal.nm_detail_biaya_internal', 'detail_biaya_internal.besar_biaya')
                                ->join('kelompok_biaya_internal','kelompok_biaya_internal.id_kelompok_biaya_internal','=','detail_biaya_internal.id_kelompok_biaya_internal')
                                ->join('biaya','biaya.id_biaya','=','kelompok_biaya_internal.id_biaya')
                                ->where('biaya.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('kelompok_biaya_internal.nm_kelompok_biaya_internal', 'desc')
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->orderBy('detail_biaya_internal.nm_detail_biaya_internal', 'desc');

                                if ( $is_datatable == null ) {
                                    $detailBiayaInternal = $detailBiayaInternal->get();
                                }
        }
        // get mode edit
        else{
            $detailBiayaInternal = DetailBiayaInternal::where('id_detail_biaya_internal','=',$id)->first();
        }

        return $detailBiayaInternal;
    }
    /** ========== **/
    
    /** KELOMPOK BIAYA **/
    static function fetchDataKelompokBiaya($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $kelompokBiaya = KelompokBiaya::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('status_kelompok_biaya', 'asc')
                                ->orderBy('nm_kelompok_biaya', 'asc')
                                ->get();
        }
        // get mode edit
        else{
            $kelompokBiaya = KelompokBiaya::where('id_kelompok_biaya','=',$id)->first();
        }

        return $kelompokBiaya;
    }
    /** ========== **/

    /** BIAYA SEKOLAH **/
    static function fetchDataBiayaSekolah($auth_data, $valid = null, $id = null){

        // get mode view
        if ($id == null){
            $biayaSekolah = BiayaSekolah::select('biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'biaya_sekolah.id_jalur', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'jalur.nm_jalur', 'biaya_sekolah.besar_biaya_sekolah', 'biaya_sekolah.validasi_biaya_sekolah', 'biaya_sekolah.keterangan_biaya_sekolah')
                                ->join('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','biaya_sekolah.id_kelompok_biaya')
                                ->join('semester','semester.id_semester','=','biaya_sekolah.id_semester')
                                ->leftJoin('jalur','jalur.id_jalur','=','biaya_sekolah.id_jalur')
                                ->where('kelompok_biaya.id_sekolah','=',$auth_data->pengguna->id_sekolah);
            if( ! empty($valid)) {
                $biayaSekolah = $biayaSekolah->where('biaya_sekolah.validasi_biaya_sekolah','=',$valid);
            }
            $biayaSekolah = $biayaSekolah->orderBy('semester.thn_akademik_semester', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('kelompok_biaya.status_kelompok_biaya', 'asc')
                                ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc')
                                ->get();
        }
        // get mode edit
        else{
            $biayaSekolah = BiayaSekolah::where('id_biaya_sekolah','=',$id)->first();
        }

        return $biayaSekolah;
    }
    /** ========== **/

    /** DETAIL BIAYA **/
    static function fetchDataDetailBiaya($auth_data, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $detailBiaya = DetailBiaya::select('detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'b_internal.nm_biaya as nm_biaya_internal', 'kelompok_biaya_internal.nm_kelompok_biaya_internal', 'detail_biaya.validasi_biaya', 'detail_biaya.besar_biaya', 'detail_biaya.keterangan_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'jalur.nm_jalur')
                                ->join('biaya_sekolah','biaya_sekolah.id_biaya_sekolah','=','detail_biaya.id_biaya_sekolah')
                                ->leftJoin('jalur','jalur.id_jalur','=','biaya_sekolah.id_jalur')
                                ->join('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','biaya_sekolah.id_kelompok_biaya')
                                ->join('semester','semester.id_semester','=','biaya_sekolah.id_semester')
                                ->join('biaya','biaya.id_biaya','=','detail_biaya.id_biaya')
                                ->leftJoin('kelompok_biaya_internal','kelompok_biaya_internal.id_kelompok_biaya_internal','=','detail_biaya.id_kelompok_biaya_internal')
                                ->leftJoin('biaya as b_internal','b_internal.id_biaya','=','kelompok_biaya_internal.id_biaya')
                                ->leftJoin('jenis_detail_biaya','jenis_detail_biaya.id_jenis_detail_biaya','=','detail_biaya.id_jenis_detail_biaya')
                                ->leftJoin('bulan','bulan.id_bulan','=','detail_biaya.id_bulan')
                                ->where('biaya_sekolah.validasi_biaya_sekolah','=',1)
                                ->where('biaya.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('semester.thn_akademik_semester', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('kelompok_biaya.status_kelompok_biaya', 'asc')
                                ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc')
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->orderBy('jenis_detail_biaya.nm_jenis_detail_biaya', 'asc')
                                ->orderBy('bulan.id_bulan', 'asc');

                                if ( $is_datatable == null ) {
                                    $detailBiaya = $detailBiaya->get();
                                }
        }
        // get mode edit
        else{
            $detailBiaya = DetailBiaya::where('id_detail_biaya','=',$id)->first();
        }

        return $detailBiaya;
    }
    /** ========== **/

    /** KELOMPOK BIAYA SISWA **/
    static function fetchDataBiayaSiswa($auth_data, $ada = null, $id = null, $id_kelas = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.id_kelompok_biaya', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'kelompok_biaya.nm_kelompok_biaya', 'kelompok_biaya.status_kelompok_biaya')
                                ->leftJoin('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','siswa.id_kelompok_biaya')
                                ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                                ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                                ->where('status_pengguna.aktif_status_pengguna','=',1)
                                ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah);
            if($ada == "0") {
                $siswa = $siswa->whereNull('siswa.id_kelompok_biaya');
            }
            else {
                $siswa = $siswa->whereNotNull('siswa.id_kelompok_biaya');
            }
            $siswa = $siswa->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->orderBy('pengguna.nm_pengguna', 'asc')
                        ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc');

            if ( !empty($id_kelas) && $id_kelas != false ) {
                $siswa = $siswa->where('siswa.id_kelas', $id_kelas);
            }

            if ( $is_datatable == null ) {
                $siswa = $siswa->get();
            }
        }
        // get mode edit
        else{
            $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.id_kelompok_biaya', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'kelompok_biaya.nm_kelompok_biaya', 'kelompok_biaya.status_kelompok_biaya')
                                ->leftJoin('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','siswa.id_kelompok_biaya')
                                ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                                ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                                ->where('siswa.id_siswa','=',$id)
                                ->first();
        }

        return $siswa;
    }
    /** ========== **/

    /** VIEW SISWA TAGIHAN **/
    static function fetchDataSiswaTagihan($auth_data, $thn_masuk_siswa, $id_semester, $id_kelompok_biaya, $id_jalur, $is_datatable = null) {

        $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.id_kelompok_biaya', 'calon_siswa_baru.kode_voucher', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'siswa.thn_masuk_siswa', 'kelas.nm_kelas', 'kelompok_biaya.nm_kelompok_biaya', 'kelompok_biaya.status_kelompok_biaya', 'jalur.nm_jalur'/*, 
            DB::raw("(SELECT COUNT(*) 
                        FROM tagihan_biaya tb 
                        JOIN detail_biaya db ON db.id_detail_biaya = tb.id_detail_biaya 
                        JOIN biaya_sekolah bs ON bs.id_biaya_sekolah = db.id_biaya_sekolah
                        WHERE tb.id_siswa = siswa.id_siswa AND tb.deleted_at IS NULL AND bs.id_semester = ?) AS jml_tagihan_detail_biaya", [$id_semester])*/)
                            ->join('calon_siswa_baru','calon_siswa_baru.id_c_siswa','=','siswa.id_c_siswa')
                            ->leftJoin('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','siswa.id_kelompok_biaya')
                            ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                            ->leftJoin('jalur_siswa', function ($join) {
                                    $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                         ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                                })
                            ->leftJoin('jalur','jalur.id_jalur','=','jalur_siswa.id_jalur')
                            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                            ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                            ->where('status_pengguna.aktif_status_pengguna','=',1)
                            ->where('siswa.thn_masuk_siswa','=',$thn_masuk_siswa)
                            ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah);

        if($id_kelompok_biaya != "0") {
            $siswa = $siswa->where('siswa.id_kelompok_biaya','=',$id_kelompok_biaya);
        }
        if($id_jalur != "0") {
            $siswa = $siswa->where('jalur.id_jalur','=',$id_jalur);
        }

        $siswa = $siswa->orderBy('kelas.nm_kelas', 'asc')
                            ->orderBy('siswa.nis_siswa', 'asc')
                            ->orderBy('pengguna.nm_pengguna', 'asc')
                            ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc');

                            if ( $is_datatable == null ) {
                                $siswa = $siswa->get();
                            }

        return $siswa;
    }
    /** ========== **/

    /** KATEGORI PEMASUKAN **/
    static function fetchDataKategoriPemasukan($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $kategoriPemasukan = PemasukanBiayaKategori::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_pemasukan_biaya_kategori', 'asc')->get();
        }
        // get mode edit
        else{
            $kategoriPemasukan = PemasukanBiayaKategori::where('id_pemasukan_biaya_kategori','=',$id)->first();
        }

        return $kategoriPemasukan;
    }
    /** ========== **/

    /** SUBKATEGORI PEMASUKAN **/
    static function fetchDataSubkategoriPemasukan($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $subkategoriPemasukan = PemasukanBiayaSubkategori::select('pemasukan_biaya_kategori.id_pemasukan_biaya_kategori', 'pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori', 'pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'pemasukan_biaya_kategori.keterangan_pemasukan_biaya_kategori', 'pemasukan_biaya_subkategori.keterangan_pemasukan_biaya_subkategori')
                                ->join('pemasukan_biaya_kategori','pemasukan_biaya_kategori.id_pemasukan_biaya_kategori','=','pemasukan_biaya_subkategori.id_pemasukan_biaya_kategori')
                                ->where('pemasukan_biaya_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'asc')
                                ->orderBy('pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'asc')
                                ->get();
        }
        // get mode edit
        else{
            $subkategoriPemasukan = PemasukanBiayaSubkategori::where('id_pemasukan_biaya_subkategori','=',$id)->first();
        }

        return $subkategoriPemasukan;
    }
    /** ========== **/

    /** PEMASUKAN **/
    static function fetchDataPemasukan($auth_data, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $pemasukan = PemasukanBiaya::select('pemasukan_biaya.id_pemasukan_biaya', 'pemasukan_biaya_kategori.id_pemasukan_biaya_kategori', 'pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori', 'pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'semester.tahun_ajaran', 'semester.nm_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'pemasukan_biaya.tgl_pemasukan_biaya', 'pemasukan_biaya.besar_pemasukan_biaya', 'pemasukan_biaya.keterangan_pemasukan_biaya', 'pemasukan_biaya.is_upload_file')
                                ->join('pemasukan_biaya_subkategori','pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori','=','pemasukan_biaya.id_pemasukan_biaya_subkategori')
                                ->join('pemasukan_biaya_kategori','pemasukan_biaya_kategori.id_pemasukan_biaya_kategori','=','pemasukan_biaya_subkategori.id_pemasukan_biaya_kategori')
                                ->join('semester','semester.id_semester','=','pemasukan_biaya.id_semester')
                                ->leftJoin('pengguna','pengguna.id_pengguna','=','pemasukan_biaya.created_by')
                                ->where('pemasukan_biaya_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('semester.tahun_ajaran', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('pemasukan_biaya_kategori.nm_pemasukan_biaya_kategori', 'asc')
                                ->orderBy('pemasukan_biaya_subkategori.nm_pemasukan_biaya_subkategori', 'asc');

                                if ( $is_datatable == null ) {
                                    $pemasukan = $pemasukan->get();
                                }
        }
        // get mode edit
        else{
            $pemasukan = PemasukanBiaya::where('id_pemasukan_biaya','=',$id)->first();
        }

        return $pemasukan;
    }
    /** ========== **/
    
    /** KATEGORI PENGELUARAN **/
    static function fetchDataKategoriPengeluaran($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $kategoriPengeluaran = PengeluaranBiayaKategori::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_pengeluaran_biaya_kategori', 'asc')->get();
        }
        // get mode edit
        else{
            $kategoriPengeluaran = PengeluaranBiayaKategori::where('id_pengeluaran_biaya_kategori','=',$id)->first();
        }

        return $kategoriPengeluaran;
    }
    /** ========== **/

    /** SUBKATEGORI PENGELUARAN **/
    static function fetchDataSubkategoriPengeluaran($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $subkategoriPengeluaran = PengeluaranBiayaSubkategori::select('pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori', 'pengeluaran_biaya_subkategori.id_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'pengeluaran_biaya_kategori.keterangan_pengeluaran_biaya_kategori', 'pengeluaran_biaya_subkategori.keterangan_pengeluaran_biaya_subkategori')
                                ->join('pengeluaran_biaya_kategori','pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori','=','pengeluaran_biaya_subkategori.id_pengeluaran_biaya_kategori')
                                ->where('pengeluaran_biaya_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'asc')
                                ->orderBy('pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'asc')
                                ->get();
        }
        // get mode edit
        else{
            $subkategoriPengeluaran = PengeluaranBiayaSubkategori::where('id_pengeluaran_biaya_subkategori','=',$id)->first();
        }

        return $subkategoriPengeluaran;
    }
    /** ========== **/

    /** PENGELUARAN **/
    static function fetchDataPengeluaran($auth_data, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $pengeluaran = PengeluaranBiaya::select('pengeluaran_biaya.id_pengeluaran_biaya', 'pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori', 'pengeluaran_biaya_subkategori.id_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'semester.tahun_ajaran', 'semester.nm_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'pengeluaran_biaya.tgl_pengeluaran_biaya', 'pengeluaran_biaya.besar_pengeluaran_biaya', 'pengeluaran_biaya.keterangan_pengeluaran_biaya', 'pengeluaran_biaya.is_upload_file')
                                ->join('pengeluaran_biaya_subkategori','pengeluaran_biaya_subkategori.id_pengeluaran_biaya_subkategori','=','pengeluaran_biaya.id_pengeluaran_biaya_subkategori')
                                ->join('pengeluaran_biaya_kategori','pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori','=','pengeluaran_biaya_subkategori.id_pengeluaran_biaya_kategori')
                                ->join('semester','semester.id_semester','=','pengeluaran_biaya.id_semester')
                                ->leftJoin('pengguna','pengguna.id_pengguna','=','pengeluaran_biaya.created_by')
                                ->where('pengeluaran_biaya_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->orderBy('semester.tahun_ajaran', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('pengeluaran_biaya_kategori.nm_pengeluaran_biaya_kategori', 'asc')
                                ->orderBy('pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori', 'asc');

                                if ( $is_datatable == null ) {
                                    $pengeluaran = $pengeluaran->get();
                                }
        }
        // get mode edit
        else{
            $pengeluaran = PengeluaranBiaya::where('id_pengeluaran_biaya','=',$id)->first();
        }

        return $pengeluaran;
    }
    /** ========== **/

    /** KATEGORI RAPB **/
    static function fetchDataKategoriRapb($auth_data, $jenis = null, $id = null){

        // get mode view
        if ($id == null){
            $kategoriRapb = KategoriRapb::select('*')
                                ->addSelect(
                                    DB::raw("(SELECT COUNT(*) FROM subkategori_rapb 
                                            WHERE subkategori_rapb.id_kategori_rapb = kategori_rapb.id_kategori_rapb 
                                            AND subkategori_rapb.deleted_at IS NULL) 
                                            AS jml_subkategori_rapb"))
                                ->where('id_sekolah','=',$auth_data->pengguna->id_sekolah);

                                if($jenis == 1) {
                                    $kategoriRapb = $kategoriRapb->where('tipe_kategori_rapb','=',1);
                                }
                                elseif($jenis == 2) {
                                    $kategoriRapb = $kategoriRapb->where('tipe_kategori_rapb','=',2);
                                }
                                
                                $kategoriRapb = $kategoriRapb->orderBy('kode_kategori_rapb', 'asc')
                                                    ->orderBy('nm_kategori_rapb', 'asc')
                                                    ->get();
        }
        // get mode edit
        else{
            $kategoriRapb = KategoriRapb::where('id_kategori_rapb','=',$id)->first();
        }

        return $kategoriRapb;
    }
    /** ========== **/

    /** SUBKATEGORI PEMASUKAN **/
    static function fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id = null){

        // get mode view
        if ($id == null){
            $subkategoriRapb = SubkategoriRapb::select('kategori_rapb.id_kategori_rapb', 'subkategori_rapb.id_subkategori_rapb', 'kategori_rapb.nm_kategori_rapb', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'subkategori_rapb.deskripsi_subkategori_rapb')
                                ->join('kategori_rapb','kategori_rapb.id_kategori_rapb','=','subkategori_rapb.id_kategori_rapb')
                                ->where('kategori_rapb.id_kategori_rapb','=',$id_kategori_rapb)
                                ->orderBy('subkategori_rapb.kode_subkategori_rapb', 'asc')
                                ->orderBy('subkategori_rapb.nm_subkategori_rapb', 'asc')
                                ->get();
        }
        // get mode edit
        else{
            $subkategoriRapb = SubkategoriRapb::where('id_subkategori_rapb','=',$id)->first();
        }

        return $subkategoriRapb;
    }
    /** ========== **/

    /** RAPB **/
    static function fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id = null, $is_datatable = null){

        $semester_mulai = Semester::where('id_semester', '=', $id_semester_mulai)->first();
        $semester_selesai = Semester::where('id_semester', '=', $id_semester_selesai)->first();

        $kode_semester_mulai = $semester_mulai->kode_semester;
        $kode_semester_selesai = $semester_selesai->kode_semester;

        // get mode view
        if ($id == null){
            $rapb = Rapb::select('rapb.id_rapb', 'rapb.id_semester_mulai', 'rapb.id_semester_selesai', 'rapb.id_subkategori_rapb', 'rapb.id_unit_kerja', 's_mulai.tahun_ajaran AS tahun_ajaran_mulai', 's_mulai.nm_semester AS nm_semester_mulai', 's_selesai.tahun_ajaran AS tahun_ajaran_selesai', 's_selesai.nm_semester AS nm_semester_selesai', 'subkategori_rapb.kode_subkategori_rapb', 'subkategori_rapb.nm_subkategori_rapb', 'unit_kerja.nm_unit_kerja', 'rapb.dana_perkiraan_rapb', 'rapb.tgl_rapb', 'rapb.prioritas_rapb', 'p_unit.nm_pengguna AS nm_kepala_unit', 'p_keuangan.nm_pengguna AS nm_kepala_keuangan')
                                ->join('semester AS s_mulai','s_mulai.id_semester','=','rapb.id_semester_mulai')
                                ->join('semester AS s_selesai','s_selesai.id_semester','=','rapb.id_semester_selesai')
                                ->join('subkategori_rapb','subkategori_rapb.id_subkategori_rapb','=','rapb.id_subkategori_rapb')
                                ->join('kategori_rapb','kategori_rapb.id_kategori_rapb','=','subkategori_rapb.id_kategori_rapb')
                                ->join('unit_kerja','unit_kerja.id_unit_kerja','=','rapb.id_unit_kerja')
                                ->leftJoin('pengguna AS p_unit','p_unit.id_pengguna','=','rapb.id_pengguna_kepala_unit')
                                ->leftJoin('pengguna AS p_keuangan','p_keuangan.id_pengguna','=','rapb.id_pengguna_kepala_keuangan')
                                ->where('s_mulai.kode_semester','>=',$kode_semester_mulai)
                                ->where('s_selesai.kode_semester','<=',$kode_semester_selesai)
                                ->orderBy('unit_kerja.nm_unit_kerja', 'asc')
                                ->orderBy('rapb.prioritas_rapb', 'desc')
                                ->orderBy('rapb.tgl_rapb', 'desc');
                                
                                if ( $is_datatable == null ) {
                                    $rapb = $rapb->get();
                                }
        }
        // get mode edit
        else{
            $rapb = Rapb::where('id_rapb','=',$id)->first();
        }

        return $rapb;
    }
    /** ========== **/
}