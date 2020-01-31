<?php

namespace App\Libraries\SaranaPrasarana;

use App\Models\JenisGedung as JenisGedung;
use App\Models\Gedung as Gedung;
use App\Models\JenisRuangan as JenisRuangan;
use App\Models\KerusakanRuangan as KerusakanRuangan;
use App\Models\Ruangan as Ruangan;
use App\Models\KondisiRuangan as KondisiRuangan;
use App\Models\InventarisRuangan as InventarisRuangan;
use App\Models\PemilikSarpras as PemilikSarpras;
use App\Models\JenisBukuAlat as JenisBukuAlat;
use App\Models\BukuAlat as BukuAlat;
use App\Models\KomplainSarpras as KomplainSarpras;
use App\Models\PerawatanSarpras as PerawatanSarpras;
use App\Models\RpbSarpras as RpbSarpras;
use App\Models\RpbSarprasSupplier as RpbSarprasSupplier;
use App\Models\Supplier as Supplier;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibDataSarpras
{
    /** JENIS GEDUNG **/
    static function fetchDataJenisGedung($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jenisGedung = JenisGedung::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->orderBy('nm_jenis_gedung', 'asc')
                            ->get();
        }
        // get mode edit
        else{
            $jenisGedung = JenisGedung::where('id_jenis_gedung','=',$id)->first();
        }

        return $jenisGedung;
    }
    /** ========== **/

    /** GEDUNG **/
    static function fetchDataGedung($auth_data, $id = null){
        // get mode view
        if ($id == null){
            $gedung = Gedung::select('gedung.id_gedung', 'gedung.id_jenis_gedung', 'jenis_gedung.nm_jenis_gedung', 'gedung.kode_gedung', 'gedung.nm_gedung', 'gedung.lokasi_gedung', 'gedung.deskripsi_gedung')
                    ->join('jenis_gedung','jenis_gedung.id_jenis_gedung','=','gedung.id_jenis_gedung')
                    ->where('gedung.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('gedung.kode_gedung', 'asc')
                    ->orderBy('gedung.nm_gedung', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $gedung = Gedung::where('id_gedung','=',$id)->first();
        }

        return $gedung;
    }
    /** ========== **/

    /** JENIS RUANGAN **/
    static function fetchDataJenisRuangan($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jenisRuangan = JenisRuangan::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->orderBy('tipe_ruangan', 'asc')
                            ->orderBy('nm_jenis_ruangan', 'asc')
                            ->get();
        }
        // get mode edit
        else{
            $jenisRuangan = JenisRuangan::where('id_jenis_ruangan','=',$id)->first();
        }

        return $jenisRuangan;
    }
    /** ========== **/

    /** JENIS RUANGAN **/
    static function fetchDataKerusakanRuangan($auth_data){

        $kerusakanRuangan = KerusakanRuangan::orderBy('kode_kerusakan_ruangan', 'asc')->get();

        return $kerusakanRuangan;
    }
    /** ========== **/

    /** RUANGAN **/
	static function fetchDataRuangan($auth_data, $tipe_ruangan = null, $id = null){

        // get mode view
        if ($id == null) {
            // tipe ruangan kelas atau non-kelas
            if ( ! empty($tipe_ruangan)) {
                $ruangan = Ruangan::select('ruangan.id_ruangan', 'ruangan.id_jenis_ruangan', 'ruangan.id_gedung', 'ruangan.id_pemilik_sarpras', 'jenis_ruangan.nm_jenis_ruangan', 'gedung.nm_gedung', 'pemilik_sarpras.nm_pemilik_sarpras', 'ruangan.nm_ruangan', 'ruangan.kapasitas_ruangan', 'ruangan.kapasitas_ujian', 'ruangan.deskripsi_ruangan', 'ruangan.is_aktif')
                        ->join('jenis_ruangan','jenis_ruangan.id_jenis_ruangan','=','ruangan.id_jenis_ruangan')
                        ->join('gedung','gedung.id_gedung','=','ruangan.id_gedung')
                        ->join('pemilik_sarpras','pemilik_sarpras.id_pemilik_sarpras','=','ruangan.id_pemilik_sarpras')
                        ->where('ruangan.is_aktif','=',1)
                        ->where('jenis_ruangan.tipe_ruangan','=',$tipe_ruangan)
                        ->where('gedung.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('ruangan.nm_ruangan', 'asc')->get();
            }
            // get all ruangan tanpa filter tipe ruangan
            else {
                $ruangan = Ruangan::select('ruangan.id_ruangan', 'ruangan.id_jenis_ruangan', 'ruangan.id_gedung', 'ruangan.id_pemilik_sarpras', 'jenis_ruangan.nm_jenis_ruangan', 'gedung.nm_gedung', 'pemilik_sarpras.nm_pemilik_sarpras', 'ruangan.nm_ruangan', 'ruangan.kapasitas_ruangan', 'ruangan.kapasitas_ujian', 'ruangan.deskripsi_ruangan', 'ruangan.is_aktif')
                        ->join('jenis_ruangan','jenis_ruangan.id_jenis_ruangan','=','ruangan.id_jenis_ruangan')
                        ->join('gedung','gedung.id_gedung','=','ruangan.id_gedung')
                        ->join('pemilik_sarpras','pemilik_sarpras.id_pemilik_sarpras','=','ruangan.id_pemilik_sarpras')
                        ->where('gedung.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('ruangan.nm_ruangan', 'asc')->get();
            }
        }
        // get mode edit
        else {
            $ruangan = Ruangan::join('jenis_ruangan','jenis_ruangan.id_jenis_ruangan','=','ruangan.id_jenis_ruangan')
                            ->where('ruangan.id_ruangan','=',$id)
                            ->first();
        }

        return $ruangan;
    }
    /** ========== **/

    /** KONDISI RUANGAN **/
    static function fetchDataKondisiRuangan($auth_data, $id = null){

        // get mode view
        if ($id == null) {
            $kondisi_ruangan = KondisiRuangan::select('kondisi_ruangan.id_kondisi_ruangan', 'kerusakan_ruangan.id_kerusakan_ruangan', 'ruangan.id_ruangan', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'kerusakan_ruangan.nm_kerusakan_ruangan', 'kondisi_ruangan.persentase_kerusakan_ruangan', 'kondisi_ruangan.keterangan_kerusakan_ruangan', 'ruangan.is_aktif')
                    ->join('kerusakan_ruangan','kerusakan_ruangan.id_kerusakan_ruangan','=','kondisi_ruangan.id_kerusakan_ruangan')
                    ->join('ruangan','ruangan.id_ruangan','=','kondisi_ruangan.id_ruangan')
                    ->join('gedung','gedung.id_gedung','=','ruangan.id_gedung')
                    ->where('gedung.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('kondisi_ruangan.persentase_kerusakan_ruangan', 'desc')
                    ->orderBy('ruangan.nm_ruangan', 'asc')
                    ->orderBy('kerusakan_ruangan.kode_kerusakan_ruangan', 'asc')
                    ->get();
        }
        // get mode edit
        else {
            $kondisi_ruangan = KondisiRuangan::join('ruangan','ruangan.id_ruangan','=','kondisi_ruangan.id_ruangan')->join('kerusakan_ruangan','kerusakan_ruangan.id_kerusakan_ruangan','=','kondisi_ruangan.id_kerusakan_ruangan')->where('kondisi_ruangan.id_kondisi_ruangan','=',$id)->first();
        }

        return $kondisi_ruangan;
    }
    /** ========== **/

    /** INVENTARIS RUANGAN **/
    static function fetchDataInventarisRuangan($auth_data, $id_ruangan = null, $id = null){

        // get mode view
        if ($id == null) {
            // filter by ruangan
            if (! empty($id_ruangan)) {
                $inventarisRuangan = InventarisRuangan::select('inventaris_ruangan.id_inventaris_ruangan', 'ruangan.id_ruangan', 'ruangan.nm_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan',  'inventaris_ruangan.kode_inventaris_ruangan',  'inventaris_ruangan.tgl_pembelian', 'inventaris_ruangan.jumlah_inventaris_ruangan', 'inventaris_ruangan.jumlah_kondisi_baik', 'inventaris_ruangan.jumlah_kondisi_rusak', 'inventaris_ruangan.spesifikasi_inventaris_ruangan', 'inventaris_ruangan.keterangan_inventaris_ruangan','ruangan.is_aktif')
                        ->join('ruangan','ruangan.id_ruangan','=','inventaris_ruangan.id_ruangan')
                        ->where('inventaris_ruangan.id_ruangan','=',$id_ruangan)
                        ->orderBy('inventaris_ruangan.nm_inventaris_ruangan', 'asc')
                        ->orderBy('ruangan.nm_ruangan', 'asc')
                        ->get();
            }
            // get all ruangan tanpa filter id_ruangan
            else {
                $inventarisRuangan = InventarisRuangan::select('inventaris_ruangan.id_inventaris_ruangan', 'ruangan.id_ruangan', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'inventaris_ruangan.nm_inventaris_ruangan', 'inventaris_ruangan.kode_inventaris_ruangan',  'inventaris_ruangan.tgl_pembelian', 'inventaris_ruangan.jumlah_inventaris_ruangan', 'inventaris_ruangan.jumlah_kondisi_baik', 'inventaris_ruangan.jumlah_kondisi_rusak', 'inventaris_ruangan.spesifikasi_inventaris_ruangan', 'inventaris_ruangan.keterangan_inventaris_ruangan', 'ruangan.is_aktif')
                        ->join('ruangan','ruangan.id_ruangan','=','inventaris_ruangan.id_ruangan')
                        ->join('gedung','gedung.id_gedung','=','ruangan.id_gedung')
                        ->where('gedung.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('inventaris_ruangan.nm_inventaris_ruangan', 'asc')
                        ->orderBy('ruangan.nm_ruangan', 'asc')
                        ->get();
            }
        }
        // get mode edit
        else {
            $inventarisRuangan = InventarisRuangan::join('ruangan','ruangan.id_ruangan','=','inventaris_ruangan.id_ruangan')
                            ->where('inventaris_ruangan.id_inventaris_ruangan','=',$id)
                            ->first();
        }

        return $inventarisRuangan;
    }
    /** ========== **/

    /** PEMILIK SARPRAS **/
    static function fetchDataPemilikSarpras($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $pemilikSarpras = PemilikSarpras::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->orderBy('nm_pemilik_sarpras', 'asc')
                            ->get();
        }
        // get mode edit
        else{
            $pemilikSarpras = PemilikSarpras::where('id_pemilik_sarpras','=',$id)->first();
        }

        return $pemilikSarpras;
    }
    /** ========== **/

    /** JENIS GEDUNG **/
    static function fetchDataJenisBukuAlat($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jenisBukuAlat = JenisBukuAlat::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->orderBy('kode_jenis_buku_alat', 'asc')
                            ->get();
        }
        // get mode edit
        else{
            $jenisBukuAlat = JenisBukuAlat::where('id_jenis_buku_alat','=',$id)->first();
        }

        return $jenisBukuAlat;
    }
    /** ========== **/

    /** BUKU/ALAT **/
    static function fetchDataBukuAlat($auth_data, $id = null){

        // get mode view
        if ($id == null) {
            $buku_alat = BukuAlat::select('buku_alat.id_buku_alat', 'jenis_buku_alat.id_jenis_buku_alat', 'mata_pelajaran.id_mata_pelajaran', 'jenis_buku_alat.kode_jenis_buku_alat', 'jenis_buku_alat.nm_jenis_buku_alat', 'buku_alat.nm_buku_alat', 'buku_alat.tingkat_pendidikan_buku_alat', 'mata_pelajaran.nm_mata_pelajaran', 'jurusan.nm_jurusan', 'buku_alat.kode_buku_alat',  'buku_alat.tgl_pembelian', 'buku_alat.jumlah_buku_alat', 'buku_alat.jumlah_kondisi_baik', 'buku_alat.jumlah_kondisi_rusak', 'buku_alat.keterangan_buku_alat')
                    ->join('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')
                    ->leftJoin('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','buku_alat.id_mata_pelajaran')
                    ->leftJoin('jurusan','jurusan.id_jurusan','=','mata_pelajaran.id_jurusan')
                    ->where('jenis_buku_alat.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('buku_alat.tingkat_pendidikan_buku_alat', 'asc')
                    ->orderBy('mata_pelajaran.nm_mata_pelajaran', 'asc')
                    ->orderBy('buku_alat.nm_buku_alat', 'asc')
                    ->get();
        }
        // get mode edit
        else {
            $buku_alat = BukuAlat::join('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')->where('buku_alat.id_buku_alat','=',$id)->first();
        }

        return $buku_alat;
    }
    /** ========== **/

    /** KOMPLAIN RUANGAN **/
    static function fetchDataKomplainRuangan($auth_data, $id_ruangan = null, $id = null){

        // get mode view
        if ($id == null) {
            // filter by ruangan
            if (! empty($id_ruangan)) {
                $komplain_ruangan = KomplainSarpras::select('komplain_sarpras.id_komplain_sarpras', 'ruangan.id_ruangan', 'jenis_ruangan.id_jenis_ruangan', 'inventaris_ruangan.id_inventaris_ruangan', 'siswa.id_siswa', 'p_siswa.id_pengguna as id_pengguna_siswa', 'guru.id_guru', 'p_guru.id_pengguna as id_pengguna_guru', 'ruangan.nm_ruangan', 'jenis_ruangan.nm_jenis_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'p_siswa.nm_pengguna as nm_pengguna_siswa', 'kelas.nm_kelas', 'siswa.nis_siswa', 'p_guru.nm_pengguna as nm_pengguna_guru', 'p_guru.gelar_depan', 'p_guru.gelar_belakang', 'komplain_sarpras.keterangan_komplain', 'komplain_sarpras.is_urgent', 'komplain_sarpras.is_sudah_perbaikan', 'komplain_sarpras.id_guru_sarpras', 'p_guru_sarpras.nm_pengguna as nm_pengguna_guru_sarpras', 'p_guru_sarpras.gelar_depan as gelar_depan_sarpras', 'p_guru_sarpras.gelar_belakang as gelar_belakang_sarpras', 'komplain_sarpras.keterangan_perbaikan')
                        ->join('ruangan','ruangan.id_ruangan','=','komplain_sarpras.id_ruangan')
                        ->join('jenis_ruangan','jenis_ruangan.id_jenis_ruangan','=','ruangan.id_jenis_ruangan')
                        ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','komplain_sarpras.id_inventaris_ruangan')
                        ->leftJoin('siswa','siswa.id_siswa','=','komplain_sarpras.id_siswa_komplain')
                        ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                        ->leftJoin('pengguna as p_siswa','p_siswa.id_pengguna','=','siswa.id_pengguna')
                        ->leftJoin('guru','guru.id_guru','=','komplain_sarpras.id_guru_komplain')
                        ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                        ->leftJoin('guru as guru_sarpras','guru_sarpras.id_guru','=','komplain_sarpras.id_guru_sarpras')
                        ->leftJoin('pengguna as p_guru_sarpras','p_guru_sarpras.id_pengguna','=','guru_sarpras.id_pengguna')
                        ->where('jenis_ruangan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('ruangan.is_aktif','=',1)
                        ->where('komplain_sarpras.id_ruangan','=',$id_ruangan)
                        ->orderBy('komplain_sarpras.is_urgent', 'desc')
                        ->orderBy('komplain_sarpras.is_sudah_perbaikan', 'asc')
                        ->orderBy('komplain_sarpras.created_at', 'desc')
                        ->get();
            }
            else {
                $komplain_ruangan = KomplainSarpras::select('komplain_sarpras.id_komplain_sarpras', 'ruangan.id_ruangan', 'jenis_ruangan.id_jenis_ruangan', 'inventaris_ruangan.id_inventaris_ruangan', 'siswa.id_siswa', 'p_siswa.id_pengguna as id_pengguna_siswa', 'guru.id_guru', 'p_guru.id_pengguna as id_pengguna_guru', 'ruangan.nm_ruangan', 'jenis_ruangan.nm_jenis_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'p_siswa.nm_pengguna as nm_pengguna_siswa', 'kelas.nm_kelas', 'siswa.nis_siswa', 'p_guru.nm_pengguna as nm_pengguna_guru', 'p_guru.gelar_depan', 'p_guru.gelar_belakang', 'komplain_sarpras.keterangan_komplain', 'komplain_sarpras.is_urgent', 'komplain_sarpras.is_sudah_perbaikan', 'komplain_sarpras.id_guru_sarpras', 'p_guru_sarpras.nm_pengguna as nm_pengguna_guru_sarpras', 'p_guru_sarpras.gelar_depan as gelar_depan_sarpras', 'p_guru_sarpras.gelar_belakang as gelar_belakang_sarpras', 'komplain_sarpras.keterangan_perbaikan')
                        ->join('ruangan','ruangan.id_ruangan','=','komplain_sarpras.id_ruangan')
                        ->join('jenis_ruangan','jenis_ruangan.id_jenis_ruangan','=','ruangan.id_jenis_ruangan')
                        ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','komplain_sarpras.id_inventaris_ruangan')
                        ->leftJoin('siswa','siswa.id_siswa','=','komplain_sarpras.id_siswa_komplain')
                        ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                        ->leftJoin('pengguna as p_siswa','p_siswa.id_pengguna','=','siswa.id_pengguna')
                        ->leftJoin('guru','guru.id_guru','=','komplain_sarpras.id_guru_komplain')
                        ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                        ->leftJoin('guru as guru_sarpras','guru.id_guru','=','komplain_sarpras.id_guru_sarpras')
                        ->leftJoin('pengguna as p_guru_sarpras','p_guru_sarpras.id_pengguna','=','guru_sarpras.id_pengguna')
                        ->where('jenis_ruangan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('ruangan.is_aktif','=',1)
                        ->orderBy('komplain_sarpras.is_urgent', 'desc')
                        ->orderBy('komplain_sarpras.is_sudah_perbaikan', 'asc')
                        ->orderBy('komplain_sarpras.created_at', 'desc')
                        ->get();
            }
        }
        // get mode edit
        else {
            $komplain_ruangan = KomplainSarpras::where('id_komplain_sarpras','=',$id)->first();
        }

        return $komplain_ruangan;
    }
    /** ========== **/

    /** KOMPLAIN BUKU/ALAT **/
    static function fetchDataKomplainBukuAlat($auth_data, $id_buku_alat = null, $id = null){

        // get mode view
        if ($id == null) {
            // filter by ruangan
            if (! empty($id_buku_alat)) {
                $komplain_buku_alat = KomplainSarpras::select('komplain_sarpras.id_komplain_sarpras', 'buku_alat.id_buku_alat', 'jenis_buku_alat.id_jenis_buku_alat', 'siswa.id_siswa', 'p_siswa.id_pengguna as id_pengguna_siswa', 'guru.id_guru', 'p_guru.id_pengguna as id_pengguna_guru', 'buku_alat.nm_buku_alat', 'jenis_buku_alat.nm_jenis_buku_alat', 'p_siswa.nm_pengguna as nm_pengguna_siswa', 'kelas.nm_kelas', 'siswa.nis_siswa', 'p_guru.nm_pengguna as nm_pengguna_guru', 'p_guru.gelar_depan', 'p_guru.gelar_belakang', 'komplain_sarpras.keterangan_komplain', 'komplain_sarpras.is_urgent', 'komplain_sarpras.is_sudah_perbaikan', 'komplain_sarpras.id_guru_sarpras', 'p_guru_sarpras.nm_pengguna as nm_pengguna_guru_sarpras', 'p_guru_sarpras.gelar_depan as gelar_depan_sarpras', 'p_guru_sarpras.gelar_belakang as gelar_belakang_sarpras', 'komplain_sarpras.keterangan_perbaikan')
                        ->join('buku_alat','buku_alat.id_buku_alat','=','komplain_sarpras.id_buku_alat')
                        ->join('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')
                        ->leftJoin('siswa','siswa.id_siswa','=','komplain_sarpras.id_siswa_komplain')
                        ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                        ->leftJoin('pengguna as p_siswa','p_siswa.id_pengguna','=','siswa.id_pengguna')
                        ->leftJoin('guru','guru.id_guru','=','komplain_sarpras.id_guru_komplain')
                        ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                        ->leftJoin('guru as guru_sarpras','guru_sarpras.id_guru','=','komplain_sarpras.id_guru_sarpras')
                        ->leftJoin('pengguna as p_guru_sarpras','p_guru_sarpras.id_pengguna','=','guru_sarpras.id_pengguna')
                        ->where('jenis_buku_alat.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('komplain_sarpras.id_buku_alat','=',$id_buku_alat)
                        ->orderBy('komplain_sarpras.is_urgent', 'desc')
                        ->orderBy('komplain_sarpras.is_sudah_perbaikan', 'asc')
                        ->orderBy('komplain_sarpras.created_at', 'desc')
                        ->get();
            }
            else {
                $komplain_buku_alat = KomplainSarpras::select('komplain_sarpras.id_komplain_sarpras', 'buku_alat.id_buku_alat', 'jenis_buku_alat.id_jenis_buku_alat', 'siswa.id_siswa', 'p_siswa.id_pengguna as id_pengguna_siswa', 'guru.id_guru', 'p_guru.id_pengguna as id_pengguna_guru', 'buku_alat.nm_buku_alat', 'jenis_buku_alat.nm_jenis_buku_alat', 'p_siswa.nm_pengguna as nm_pengguna_siswa', 'kelas.nm_kelas', 'siswa.nis_siswa', 'p_guru.nm_pengguna as nm_pengguna_guru', 'p_guru.gelar_depan', 'p_guru.gelar_belakang', 'komplain_sarpras.keterangan_komplain', 'komplain_sarpras.is_urgent', 'komplain_sarpras.is_sudah_perbaikan', 'komplain_sarpras.id_guru_sarpras', 'p_guru_sarpras.nm_pengguna as nm_pengguna_guru_sarpras', 'p_guru_sarpras.gelar_depan as gelar_depan_sarpras', 'p_guru_sarpras.gelar_belakang as gelar_belakang_sarpras', 'komplain_sarpras.keterangan_perbaikan')
                        ->join('buku_alat','buku_alat.id_buku_alat','=','komplain_sarpras.id_buku_alat')
                        ->join('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')
                        ->leftJoin('siswa','siswa.id_siswa','=','komplain_sarpras.id_siswa_komplain')
                        ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                        ->leftJoin('pengguna as p_siswa','p_siswa.id_pengguna','=','siswa.id_pengguna')
                        ->leftJoin('guru','guru.id_guru','=','komplain_sarpras.id_guru_komplain')
                        ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                        ->leftJoin('guru as guru_sarpras','guru.id_guru','=','komplain_sarpras.id_guru_sarpras')
                        ->leftJoin('pengguna as p_guru_sarpras','p_guru_sarpras.id_pengguna','=','guru_sarpras.id_pengguna')
                        ->where('jenis_buku_alat.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('komplain_sarpras.is_urgent', 'desc')
                        ->orderBy('komplain_sarpras.is_sudah_perbaikan', 'asc')
                        ->orderBy('komplain_sarpras.created_at', 'desc')
                        ->get();
            }
        }
        // get mode edit
        else {
            $komplain_buku_alat = KomplainSarpras::where('id_komplain_sarpras','=',$id)->first();
        }

        return $komplain_buku_alat;
    }
    /** ========== **/

    /** TANGGAPI KOMPLAIN **/
    static function fetchDataTanggapiKomplain($auth_data, $is_sudah_perbaikan, $id = null){

        // get mode view
        if ($id == null) {
            $komplainSarpras = KomplainSarpras::select('komplain_sarpras.id_komplain_sarpras', 'ruangan.nm_ruangan', 'jenis_ruangan.nm_jenis_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'buku_alat.nm_buku_alat', 'jenis_buku_alat.nm_jenis_buku_alat', 'p_siswa.nm_pengguna as nm_pengguna_siswa', 'kelas.nm_kelas', 'siswa.nis_siswa', 'p_guru.nm_pengguna as nm_pengguna_guru', 'p_guru.gelar_depan', 'p_guru.gelar_belakang', 'komplain_sarpras.keterangan_komplain', 'komplain_sarpras.is_urgent', 'komplain_sarpras.is_sudah_perbaikan', 'p_guru_sarpras.nm_pengguna as nm_pengguna_guru_sarpras', 'p_guru_sarpras.gelar_depan as gelar_depan_sarpras', 'p_guru_sarpras.gelar_belakang as gelar_belakang_sarpras', 'p_staff_sarpras.nm_pengguna as nm_pengguna_staff_sarpras', 'p_staff_sarpras.gelar_depan as gelar_depan_staff_sarpras', 'p_staff_sarpras.gelar_belakang as gelar_belakang_staff_sarpras', 'komplain_sarpras.tgl_perbaikan', 'komplain_sarpras.keterangan_perbaikan')
                    ->leftJoin('ruangan', function ($join) {
                        $join->on('ruangan.id_ruangan', '=', 'komplain_sarpras.id_ruangan')
                             ->where('ruangan.is_aktif', '=', 1);
                    })
                    ->leftJoin('jenis_ruangan', function ($join) use ($auth_data) {
                        $join->on('jenis_ruangan.id_jenis_ruangan', '=', 'ruangan.id_jenis_ruangan')
                             ->where('jenis_ruangan.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
                    })
                    ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','komplain_sarpras.id_inventaris_ruangan')
                    ->leftJoin('buku_alat','buku_alat.id_buku_alat','=','komplain_sarpras.id_buku_alat')
                    ->leftJoin('jenis_buku_alat', function ($join) use ($auth_data) {
                        $join->on('jenis_buku_alat.id_jenis_buku_alat', '=', 'buku_alat.id_jenis_buku_alat')
                             ->where('jenis_buku_alat.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
                    })
                    ->leftJoin('siswa','siswa.id_siswa','=','komplain_sarpras.id_siswa_komplain')
                    ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->leftJoin('pengguna as p_siswa','p_siswa.id_pengguna','=','siswa.id_pengguna')
                    ->leftJoin('guru','guru.id_guru','=','komplain_sarpras.id_guru_komplain')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('guru as guru_sarpras','guru_sarpras.id_guru','=','komplain_sarpras.id_guru_sarpras')
                    ->leftJoin('pengguna as p_guru_sarpras','p_guru_sarpras.id_pengguna','=','guru_sarpras.id_pengguna')
                    ->leftJoin('pengguna as p_staff_sarpras','p_staff_sarpras.id_pengguna','=','komplain_sarpras.updated_by');
                    if ($is_sudah_perbaikan == "0") {
                        $komplainSarpras = $komplainSarpras->where('komplain_sarpras.is_sudah_perbaikan','=',0);
                    }
                    elseif ($is_sudah_perbaikan == "1") {
                        $komplainSarpras = $komplainSarpras->where('komplain_sarpras.is_sudah_perbaikan','=',1);
                    }
                    $komplainSarpras = $komplainSarpras->orderBy('komplain_sarpras.is_urgent', 'desc')
                    ->orderBy('komplain_sarpras.created_at', 'desc')
                    ->get();
        }
        // get mode edit
        else {
            $komplainSarpras = KomplainSarpras::select('komplain_sarpras.id_komplain_sarpras', 'ruangan.nm_ruangan', 'jenis_ruangan.nm_jenis_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'buku_alat.nm_buku_alat', 'jenis_buku_alat.nm_jenis_buku_alat', 'p_siswa.nm_pengguna as nm_pengguna_siswa', 'kelas.nm_kelas', 'siswa.nis_siswa', 'p_guru.nm_pengguna as nm_pengguna_guru', 'p_guru.gelar_depan', 'p_guru.gelar_belakang', 'komplain_sarpras.keterangan_komplain', 'komplain_sarpras.is_urgent', 'komplain_sarpras.is_sudah_perbaikan', 'p_guru_sarpras.nm_pengguna as nm_pengguna_guru_sarpras', 'p_guru_sarpras.gelar_depan as gelar_depan_sarpras', 'p_guru_sarpras.gelar_belakang as gelar_belakang_sarpras', 'p_staff_sarpras.nm_pengguna as nm_pengguna_staff_sarpras', 'p_staff_sarpras.gelar_depan as gelar_depan_staff_sarpras', 'p_staff_sarpras.gelar_belakang as gelar_belakang_staff_sarpras', 'komplain_sarpras.tgl_perbaikan', 'komplain_sarpras.keterangan_perbaikan')
                    ->leftJoin('ruangan', function ($join) {
                        $join->on('ruangan.id_ruangan', '=', 'komplain_sarpras.id_ruangan')
                             ->where('ruangan.is_aktif', '=', 1);
                    })
                    ->leftJoin('jenis_ruangan', function ($join) use ($auth_data) {
                        $join->on('jenis_ruangan.id_jenis_ruangan', '=', 'ruangan.id_jenis_ruangan')
                             ->where('jenis_ruangan.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
                    })
                    ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','komplain_sarpras.id_inventaris_ruangan')
                    ->leftJoin('buku_alat','buku_alat.id_buku_alat','=','komplain_sarpras.id_buku_alat')
                    ->leftJoin('jenis_buku_alat', function ($join) use ($auth_data) {
                        $join->on('jenis_buku_alat.id_jenis_buku_alat', '=', 'buku_alat.id_jenis_buku_alat')
                             ->where('jenis_buku_alat.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
                    })
                    ->leftJoin('siswa','siswa.id_siswa','=','komplain_sarpras.id_siswa_komplain')
                    ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->leftJoin('pengguna as p_siswa','p_siswa.id_pengguna','=','siswa.id_pengguna')
                    ->leftJoin('guru','guru.id_guru','=','komplain_sarpras.id_guru_komplain')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('guru as guru_sarpras','guru_sarpras.id_guru','=','komplain_sarpras.id_guru_sarpras')
                    ->leftJoin('pengguna as p_guru_sarpras','p_guru_sarpras.id_pengguna','=','guru_sarpras.id_pengguna')
                    ->leftJoin('pengguna as p_staff_sarpras','p_staff_sarpras.id_pengguna','=','komplain_sarpras.updated_by')
                    ->where('komplain_sarpras.id_komplain_sarpras','=',$id)
                    ->first();
        }

        return $komplainSarpras;
    }
    /** ========== **/    

    /** PERAWATAN SARPRAS **/
    static function fetchDataPerawatanSarpras($auth_data, $is_sudah_perawatan, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null) {
            $perawatan_sarpras = PerawatanSarpras::select('perawatan_sarpras.id_perawatan_sarpras', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'jenis_ruangan.nm_jenis_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'jenis_buku_alat.nm_jenis_buku_alat', 'buku_alat.nm_buku_alat', 'perawatan_sarpras.tgl_perawatan', 'perawatan_sarpras.keterangan_perawatan', 'perawatan_sarpras.is_sudah_perawatan')
                    ->leftJoin('ruangan', function ($join) {
                        $join->on('ruangan.id_ruangan', '=', 'perawatan_sarpras.id_ruangan')
                             ->where('ruangan.is_aktif', '=', 1);
                    })
                    ->leftJoin('gedung','gedung.id_gedung','=','ruangan.id_gedung')
                    ->leftJoin('jenis_ruangan', function ($join) use ($auth_data) {
                        $join->on('jenis_ruangan.id_jenis_ruangan', '=', 'ruangan.id_jenis_ruangan')
                             ->where('jenis_ruangan.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
                    })
                    ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','perawatan_sarpras.id_inventaris_ruangan')
                    ->leftJoin('buku_alat','buku_alat.id_buku_alat','=','perawatan_sarpras.id_buku_alat')
                    ->leftJoin('jenis_buku_alat', function ($join) use ($auth_data) {
                        $join->on('jenis_buku_alat.id_jenis_buku_alat', '=', 'buku_alat.id_jenis_buku_alat')
                             ->where('jenis_buku_alat.id_sekolah', '=', $auth_data->pengguna->id_sekolah);
                    });
                    if ($is_sudah_perawatan == "0") {
                        $perawatan_sarpras = $perawatan_sarpras->where('perawatan_sarpras.is_sudah_perawatan','=',0);
                    }
                    elseif ($is_sudah_perawatan == "1") {
                        $perawatan_sarpras = $perawatan_sarpras->where('perawatan_sarpras.is_sudah_perawatan','=',1);
                    }
                    $perawatan_sarpras = $perawatan_sarpras->orderBy('perawatan_sarpras.tgl_perawatan', 'desc')
                        ->orderBy('ruangan.nm_ruangan', 'asc')
                        ->orderBy('inventaris_ruangan.nm_inventaris_ruangan', 'asc')
                        ->orderBy('buku_alat.nm_buku_alat', 'asc');

                    if ( $is_datatable == null ) {
                        $perawatan_sarpras = $perawatan_sarpras->get();
                    }
        }
        // get mode edit
        else {
            $perawatan_sarpras = PerawatanSarpras::where('id_perawatan_sarpras','=',$id)->first();
        }

        return $perawatan_sarpras;
    }
    /** ========== **/

    /** PENGADAAN SARPRAS **/
    static function fetchDataPengadaanSarpras($auth_data, $prioritas_rpb_sarpras = null, $id = null, $is_datatable = null, $is_realisasi == null){

        // get mode view
        if ($id == null) {
            if($is_realisasi == null) {
                $rpb_sarpras = RpbSarpras::select('rpb_sarpras.id_rpb_sarpras', 'rpb_sarpras.id_semester','rpb_sarpras.id_unit_kerja','rpb_sarpras.id_buku_alat','rpb_sarpras.id_inventaris_ruangan', 'semester.tahun_ajaran', 'semester.nm_semester', 'unit_kerja.nm_unit_kerja', 'jenis_buku_alat.nm_jenis_buku_alat', 'buku_alat.nm_buku_alat', 'ruangan.nm_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'rpb_sarpras.harga_satuan_rpb_sarpras', 'rpb_sarpras.qty_rpb_sarpras', 'rpb_sarpras.tgl_rpb_sarpras', 'rpb_sarpras.prioritas_rpb_sarpras', 'p_unit.nm_pengguna AS nm_kepala_unit', 'p_sarpras.nm_pengguna AS nm_kepala_sarpras', 'p_sarpras_approve.nm_pengguna AS nm_kepala_sarpras_approve')
                        ->addSelect(
                                    DB::raw("(SELECT COUNT(id_rpb_sarpras_supplier) FROM rpb_sarpras_supplier 
                                                WHERE rpb_sarpras_supplier.id_rpb_sarpras = rpb_sarpras.id_rpb_sarpras
                                                AND rpb_sarpras_supplier.is_approve = 1 
                                                AND rpb_sarpras_supplier.deleted_at IS NULL) 
                                                AS apv_supplier")
                                )
                        ->join('semester','semester.id_semester','=','rpb_sarpras.id_semester')
                        ->join('unit_kerja','unit_kerja.id_unit_kerja','=','rpb_sarpras.id_unit_kerja')
                        ->leftJoin('buku_alat','buku_alat.id_buku_alat','=','rpb_sarpras.id_buku_alat')
                        ->leftJoin('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')
                        ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','rpb_sarpras.id_inventaris_ruangan')
                        ->leftJoin('ruangan','ruangan.id_ruangan','=','inventaris_ruangan.id_ruangan')
                        ->leftJoin('pengguna AS p_unit', function ($q) {
                            $q->on('p_unit.id_pengguna', '=', 'rpb_sarpras.id_pengguna_kepala_unit')
                                ->whereNull('p_unit.deleted_at');
                        })
                        ->leftJoin('pengguna AS p_sarpras', function ($q) {
                            $q->on('p_sarpras.id_pengguna', '=', 'rpb_sarpras.id_pengguna_kepala_sarpras')
                                ->whereNull('p_sarpras.deleted_at');
                        })
                        ->leftJoin('pengguna AS p_sarpras_approve', function ($q) {
                            $q->on('p_sarpras_approve.id_pengguna', '=', 'rpb_sarpras.id_pengguna_kepala_sarpras_approve')
                                ->whereNull('p_sarpras_approve.deleted_at');
                        });
                        if ($prioritas_rpb_sarpras == 1) {
                            $rpb_sarpras = $rpb_sarpras->where('rpb_sarpras.prioritas_rpb_sarpras','=',1);
                        }
                        elseif ($prioritas_rpb_sarpras == 2) {
                            $rpb_sarpras = $rpb_sarpras->where('rpb_sarpras.prioritas_rpb_sarpras','=',2);
                        }
                        elseif ($prioritas_rpb_sarpras == 3) {
                            $rpb_sarpras = $rpb_sarpras->where('rpb_sarpras.prioritas_rpb_sarpras','=',3);
                        }
                        $rpb_sarpras = $rpb_sarpras->orderBy('rpb_sarpras.tgl_rpb_sarpras', 'desc')
                            ->orderBy('unit_kerja.nm_unit_kerja', 'asc');

                        if ( $is_datatable == null ) {
                            $rpb_sarpras = $rpb_sarpras->get();
                        }
            }
            else {
                $rpb_sarpras = RpbSarpras::select('rpb_sarpras.id_rpb_sarpras', 'rpb_sarpras.id_semester','rpb_sarpras.id_unit_kerja','rpb_sarpras.id_buku_alat','rpb_sarpras.id_inventaris_ruangan', 'semester.tahun_ajaran', 'semester.nm_semester', 'unit_kerja.nm_unit_kerja', 'jenis_buku_alat.nm_jenis_buku_alat', 'buku_alat.nm_buku_alat', 'ruangan.nm_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'rpb_sarpras_supplier.harga_approve_supplier', 'rpb_sarpras_supplier.qty_approve_supplier', 'rpb_sarpras_supplier.termin_approve_supplier', 'rpb_sarpras.tgl_rpb_sarpras', 'rpb_sarpras.prioritas_rpb_sarpras', 'p_unit.nm_pengguna AS nm_kepala_unit', 'p_sarpras.nm_pengguna AS nm_kepala_sarpras', 'p_sarpras_approve.nm_pengguna AS nm_kepala_sarpras_approve')
                        ->join('semester','semester.id_semester','=','rpb_sarpras.id_semester')
                        ->join('unit_kerja','unit_kerja.id_unit_kerja','=','rpb_sarpras.id_unit_kerja')
                        ->join('rpb_sarpras_supplier', function ($q) {
                            $q->on('rpb_sarpras_supplier.id_rpb_sarpras', '=', 'rpb_sarpras.id_rpb_sarpras')
                                ->where('rpb_sarpras_supplier.is_approve', 1)
                                ->whereNull('p_unit.deleted_at');
                        })
                        ->join('supplier','supplier.id_supplier','=','rpb_sarpras_supplier.id_supplier')
                        ->join('pengguna AS p_unit', function ($q) {
                            $q->on('p_unit.id_pengguna', '=', 'rpb_sarpras.id_pengguna_kepala_unit')
                                ->whereNull('p_unit.deleted_at');
                        })
                        ->join('pengguna AS p_sarpras', function ($q) {
                            $q->on('p_sarpras.id_pengguna', '=', 'rpb_sarpras.id_pengguna_kepala_sarpras')
                                ->whereNull('p_sarpras.deleted_at');
                        })
                        ->join('pengguna AS p_sarpras_approve', function ($q) {
                            $q->on('p_sarpras_approve.id_pengguna', '=', 'rpb_sarpras.id_pengguna_kepala_sarpras_approve')
                                ->whereNull('p_sarpras_approve.deleted_at');
                        })
                        ->leftJoin('buku_alat','buku_alat.id_buku_alat','=','rpb_sarpras.id_buku_alat')
                        ->leftJoin('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')
                        ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','rpb_sarpras.id_inventaris_ruangan')
                        ->leftJoin('ruangan','ruangan.id_ruangan','=','inventaris_ruangan.id_ruangan');

                        $rpb_sarpras = $rpb_sarpras->whereNotNull('rpb_sarpras.id_pengguna_kepala_unit')
                            ->whereNotNull('rpb_sarpras.id_pengguna_kepala_sarpras')
                            ->whereNotNull('rpb_sarpras.id_pengguna_kepala_sarpras_approve')
                            ->orderBy('rpb_sarpras.tgl_rpb_sarpras', 'desc')
                            ->orderBy('unit_kerja.nm_unit_kerja', 'asc');

                        if ( $is_datatable == null ) {
                            $rpb_sarpras = $rpb_sarpras->get();
                        }
            }
            
        }
        // get mode edit
        else {
            $rpb_sarpras = RpbSarpras::select('rpb_sarpras.id_rpb_sarpras', 'rpb_sarpras.id_semester','rpb_sarpras.id_unit_kerja','rpb_sarpras.id_buku_alat','rpb_sarpras.id_inventaris_ruangan', 'semester.tahun_ajaran', 'semester.nm_semester', 'unit_kerja.nm_unit_kerja', 'jenis_buku_alat.nm_jenis_buku_alat', 'buku_alat.nm_buku_alat', 'ruangan.nm_ruangan', 'inventaris_ruangan.nm_inventaris_ruangan', 'rpb_sarpras.harga_satuan_rpb_sarpras', 'rpb_sarpras.qty_rpb_sarpras', 'rpb_sarpras.tgl_rpb_sarpras', 'rpb_sarpras.prioritas_rpb_sarpras')
                                ->join('semester','semester.id_semester','=','rpb_sarpras.id_semester')
                                ->join('unit_kerja','unit_kerja.id_unit_kerja','=','rpb_sarpras.id_unit_kerja')
                                ->leftJoin('buku_alat','buku_alat.id_buku_alat','=','rpb_sarpras.id_buku_alat')
                                ->leftJoin('jenis_buku_alat','jenis_buku_alat.id_jenis_buku_alat','=','buku_alat.id_jenis_buku_alat')
                                ->leftJoin('inventaris_ruangan','inventaris_ruangan.id_inventaris_ruangan','=','rpb_sarpras.id_inventaris_ruangan')
                                ->leftJoin('ruangan','ruangan.id_ruangan','=','inventaris_ruangan.id_ruangan')
                                ->where('rpb_sarpras.id_rpb_sarpras','=',$id)
                                ->first();
        }

        return $rpb_sarpras;
    }
    /** ========== **/

    /** PENGADAAN SUPPLIER **/
    static function fetchDataPengadaanSarprasSupplier($auth_data, $id_rpb_sarpras, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null) {
            $rpb_sarpras_supplier = RpbSarprasSupplier::select('rpb_sarpras_supplier.id_rpb_sarpras_supplier', 'rpb_sarpras_supplier.id_rpb_sarpras','rpb_sarpras_supplier.id_supplier','supplier.nm_supplier','supplier.cp_supplier_1', 'rpb_sarpras_supplier.harga_supplier', 'rpb_sarpras_supplier.harga_penawaran', 'rpb_sarpras_supplier.qty_penawaran', 'rpb_sarpras_supplier.termin_penawaran', 'rpb_sarpras_supplier.harga_approve_supplier', 'rpb_sarpras_supplier.qty_approve_supplier', 'rpb_sarpras_supplier.termin_approve_supplier', 'rpb_sarpras_supplier.is_approve', 'pengguna.nm_pengguna', 'rpb_sarpras_supplier.tgl_approve')
                    ->join('supplier','supplier.id_supplier','=','rpb_sarpras_supplier.id_supplier')
                    ->leftJoin('pengguna', function ($q) {
                        $q->on('pengguna.id_pengguna', '=', 'rpb_sarpras_supplier.id_pengguna_approve')
                            ->whereNull('pengguna.deleted_at');
                    })
                    ->where('rpb_sarpras_supplier.id_rpb_sarpras', '=', $id_rpb_sarpras)
                    ->orderBy('rpb_sarpras_supplier.is_approve', 'desc')
                    ->orderBy('supplier.nm_supplier', 'asc');

                    if ( $is_datatable == null ) {
                        $rpb_sarpras_supplier = $rpb_sarpras_supplier->get();
                    }
        }
        // get mode edit
        else {
            $rpb_sarpras_supplier = RpbSarprasSupplier::select('rpb_sarpras_supplier.id_rpb_sarpras_supplier', 'rpb_sarpras_supplier.id_rpb_sarpras','rpb_sarpras_supplier.id_supplier','supplier.nm_supplier','supplier.cp_supplier_1', 'rpb_sarpras_supplier.harga_supplier', 'rpb_sarpras_supplier.harga_penawaran', 'rpb_sarpras_supplier.qty_penawaran', 'rpb_sarpras_supplier.termin_penawaran', 'rpb_sarpras_supplier.harga_approve_supplier', 'rpb_sarpras_supplier.qty_approve_supplier', 'rpb_sarpras_supplier.termin_approve_supplier', 'rpb_sarpras_supplier.is_approve', 'rpb_sarpras_supplier.tgl_approve')
                                ->join('supplier','supplier.id_supplier','=','rpb_sarpras_supplier.id_supplier')
                                ->where('rpb_sarpras_supplier.id_rpb_sarpras_supplier','=',$id)
                                ->first();
        }

        return $rpb_sarpras_supplier;
    }
    /** ========== **/

    /** SUPPLIER **/
    static function fetchDataSupplier($auth_data, $id = null, $is_datatable = null)
    {

        // get all guru
        if ($id == null) {
            $supplier = Supplier::select(
                'supplier.id_supplier',
                'supplier.id_pengguna',
                'supplier.nm_supplier',
                'supplier.cp_supplier_1',
                'supplier.cp_supplier_2',
                'supplier.alamat_supplier',
                'supplier.nomor_sk_kerjasama',
                'supplier.tgl_awal_kerjasama',
                'supplier.tgl_akhir_kerjasama'
            )
                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'supplier.id_pengguna')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->orderBy('supplier.nm_supplier', 'asc');
            if ($is_datatable == null) {
                $supplier = $supplier->get();
            }
        }
        // get mode edit
        else {
            $supplier = Supplier::where('supplier.id_supplier', '=', $id)
                    ->first();
        }

        return $supplier;
    }
    /** ========== **/

}