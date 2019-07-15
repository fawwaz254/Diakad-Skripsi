<?php

namespace App\Libraries\Pendidikan;

use App\Models\Siswa as Siswa;
use App\Models\WaliMurid as WaliMurid;
use App\Models\Admisi as Admisi;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;


use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibSiswa
{
    /** GET SISWA BY ID_PENGGUNA WALI MURID **/
    static function fetchDataSiswaWaliMurid($auth_data, $id_pengguna) {

        // get id_wali_murid
        $wali_murid = WaliMurid::where('id_pengguna','=',$id_pengguna)->first();
        $id_wali_murid = $wali_murid->id_wali_murid;

        $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->where('siswa.id_wali_murid','=',$id_wali_murid)
                    ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->get();

        return $siswa;
    }

    /** GET SISWA BY ID_PENGGUNA SISWA **/
    static function fetchDataSiswaByPengguna($auth_data, $id_pengguna) {

        $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->where('pengguna.id_pengguna','=',$id_pengguna)
                    ->first();

        return $siswa;
    }
    /** ========== **/

    /** SISWA **/
	static function fetchDataSiswa($auth_data, $id_kelas = null, $id = null){

        // get all siswa order by kelas
        if ($id_kelas == null && $id == null){
            $siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.id_kelas', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna','=',1)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
        }
        // get siswa by kelas
        elseif ($id == null){
            $siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.id_kelas', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->where('siswa.id_kelas','=',$id_kelas)
                    ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $siswa = Siswa::join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')->where('id_siswa','=',$id)->first();
        }

        return $siswa;
    }
    /** ========== **/

    static function fetchCariSiswaDetail($auth_data, $nis_nama_siswa){
        $siswa = Siswa::select('siswa.nis_siswa','siswa.nisn_siswa','siswa.thn_masuk_siswa','pengguna.nm_pengguna','kelas.nm_kelas','jurusan.nm_jurusan','status_pengguna.nm_status_pengguna','calon_siswa_baru.asal_sekolah','calon_siswa_baru.alamat_jalan','calon_siswa_baru.alamat_dusun','calon_siswa_baru.alamat_kelurahan','calon_siswa_baru.alamat_rt','calon_siswa_baru.alamat_rw','calon_siswa_baru.alamat_kecamatan','calon_siswa_baru.alamat_kodepos','calon_siswa_baru.kode_voucher','jalur.nm_jalur','calon_siswa_baru.nomor_hp','calon_siswa_ortu.nomor_hp_ortu','provinsi.nm_provinsi','kota.nm_kota','siswa.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
            ->join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
            ->join('status_pengguna','pengguna.id_status_pengguna','=','status_pengguna.id_status_pengguna')
            ->join('calon_siswa_baru','siswa.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->join('jalur_siswa', function ($join) {
                            $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                        })
            ->join('jalur','jalur_siswa.id_jalur','=','jalur.id_jalur')
            ->leftJoin('calon_siswa_ortu','calon_siswa_ortu.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->leftJoin('provinsi','calon_siswa_baru.alamat_provinsi','=','provinsi.id_provinsi')
            ->leftJoin('kota','calon_siswa_baru.alamat_kota','=','kota.id_kota')
            ->where(function ($query) use ($nis_nama_siswa) {
                    $query->where('siswa.nis_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('siswa.nisn_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('pengguna.nm_pengguna', 'like', '%'.$nis_nama_siswa.'%');
             })
            ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return $siswa;
    }
    static function fetchAdmisiSiswa($auth_data, $nis_nama_siswa){
        $siswa = Siswa::select('siswa.nis_siswa','siswa.nisn_siswa','siswa.thn_masuk_siswa','pengguna.nm_pengguna','kelas.nm_kelas','jurusan.nm_jurusan','status_pengguna.nm_status_pengguna','calon_siswa_baru.asal_sekolah','calon_siswa_baru.alamat_jalan','calon_siswa_baru.alamat_dusun','calon_siswa_baru.alamat_kelurahan','calon_siswa_baru.alamat_rt','calon_siswa_baru.alamat_rw','calon_siswa_baru.alamat_kecamatan','calon_siswa_baru.alamat_kodepos','calon_siswa_baru.kode_voucher','jalur.nm_jalur','calon_siswa_baru.nomor_hp','calon_siswa_ortu.nomor_hp_ortu','provinsi.nm_provinsi','kota.nm_kota','siswa.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
            ->join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
            ->join('status_pengguna','pengguna.id_status_pengguna','=','status_pengguna.id_status_pengguna')
            ->join('calon_siswa_baru','siswa.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->join('jalur_siswa', function ($join) {
                            $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                        })
            ->join('jalur','jalur_siswa.id_jalur','=','jalur.id_jalur')
            ->leftJoin('calon_siswa_ortu','calon_siswa_ortu.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->leftJoin('provinsi','calon_siswa_baru.alamat_provinsi','=','provinsi.id_provinsi')
            ->leftJoin('kota','calon_siswa_baru.alamat_kota','=','kota.id_kota')
            ->where(function ($query) use ($nis_nama_siswa) {
                    $query->where('siswa.nis_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('siswa.nisn_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('pengguna.nm_pengguna', 'like', '%'.$nis_nama_siswa.'%');
             })
            ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)->first();

        return $siswa;
    }
    static function fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna){
        $siswa = Siswa::select('siswa.nis_siswa','siswa.nisn_siswa','siswa.thn_masuk_siswa','pengguna.nm_pengguna','kelas.nm_kelas','jurusan.nm_jurusan','status_pengguna.nm_status_pengguna','calon_siswa_baru.asal_sekolah','calon_siswa_baru.alamat_jalan','calon_siswa_baru.alamat_dusun','calon_siswa_baru.alamat_kelurahan','calon_siswa_baru.alamat_rt','calon_siswa_baru.alamat_rw','calon_siswa_baru.alamat_kecamatan','calon_siswa_baru.alamat_kodepos','calon_siswa_baru.kode_voucher','jalur.nm_jalur','calon_siswa_baru.nomor_hp','calon_siswa_ortu.nomor_hp_ortu','provinsi.nm_provinsi','kota.nm_kota','pengguna.id_pengguna')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
            ->join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
            ->join('status_pengguna','pengguna.id_status_pengguna','=','status_pengguna.id_status_pengguna')
            ->join('calon_siswa_baru','siswa.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->join('jalur_siswa', function ($join) {
                            $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                        })
            ->join('jalur','jalur_siswa.id_jalur','=','jalur.id_jalur')
            ->leftJoin('calon_siswa_ortu','calon_siswa_ortu.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->leftJoin('provinsi','calon_siswa_baru.alamat_provinsi','=','provinsi.id_provinsi')
            ->leftJoin('kota','calon_siswa_baru.alamat_kota','=','kota.id_kota')
            ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah);

            if($id_jurusan != "0") {
                $siswa = $siswa->where('kelas.id_jurusan','=',$id_jurusan);
            }
            if($id_kelas != "0") {
                $siswa = $siswa->where('siswa.id_kelas','=',$id_kelas);
            }
            if($thn_masuk_siswa != "0") {
                $siswa = $siswa->where('siswa.thn_masuk_siswa','=',$thn_masuk_siswa);
            }
            if($id_jalur != "0") {
                $siswa = $siswa->where('jalur_siswa.id_jalur','=',$id_jalur);
            }
            if($id_status_pengguna != "0") {
                $siswa = $siswa->where('pengguna.id_status_pengguna','=',$id_status_pengguna);
            }

            $siswa = $siswa->get();
            
            return $siswa;
    }

    static function fetchDataDetailSiswa($auth_data, $nis_siswa){
        $siswa = Siswa::select('siswa.id_siswa','pengguna.id_pengguna','siswa.nis_siswa','siswa.nisn_siswa','siswa.thn_masuk_siswa','pengguna.nm_pengguna','kelas.nm_kelas','jurusan.nm_jurusan','status_pengguna.nm_status_pengguna','calon_siswa_baru.asal_sekolah','calon_siswa_baru.alamat_jalan','calon_siswa_baru.alamat_dusun','calon_siswa_baru.alamat_kelurahan','calon_siswa_baru.alamat_rt','calon_siswa_baru.alamat_rw','calon_siswa_baru.alamat_kecamatan','calon_siswa_baru.alamat_kodepos','calon_siswa_baru.kode_voucher','jalur.nm_jalur','calon_siswa_baru.nomor_hp','calon_siswa_ortu.nomor_hp_ortu','provinsi.nm_provinsi','kota.nm_kota','calon_siswa_baru.jenis_kelamin','calon_siswa_baru.tgl_lahir','calon_siswa_baru.id_kota_lahir','calon_siswa_ortu.nm_ayah','calon_siswa_ortu.nm_ibu','calon_siswa_ortu.alamat_jalan_ortu','calon_siswa_ortu.alamat_dusun_ortu','calon_siswa_ortu.alamat_kelurahan_ortu','calon_siswa_ortu.almat_rt_ortu','calon_siswa_ortu.alamat_rw_ortu','calon_siswa_ortu.alamat_kecamatan_ortu','calon_siswa_ortu.alamat_kodepos_ortu','calon_siswa_ortu.alamat_kota_ortu','calon_siswa_ortu.alamat_provinsi_ortu', 'calon_siswa_baru.nik_siswa','calon_siswa_baru.nomor_akta_lahir','calon_siswa_baru.id_agama','calon_siswa_baru.kewarganegaraan','calon_siswa_baru.nm_kewarganegaraan','calon_siswa_baru.id_kebutuhan_khusus','agama.nm_agama','kebutuhan_khusus.nm_kebutuhan_khusus','jenis_tinggal.nm_jenis_tinggal','calon_siswa_baru.id_jenis_tinggal','jenis_transportasi.nm_jenis_transportasi','calon_siswa_baru.nomor_kks','calon_siswa_baru.anak_ke','calon_siswa_baru.is_penerima_kps','calon_siswa_baru.nomor_kps','calon_siswa_baru.is_punya_kip','calon_siswa_baru.nomor_kip','calon_siswa_baru.nm_tertera_kip','calon_siswa_baru.is_layak_pip','calon_siswa_baru.id_jenis_layak_pip','calon_siswa_ortu.nik_ayah','calon_siswa_ortu.nik_ibu','calon_siswa_ortu.nik_wali','calon_siswa_ortu.nm_wali','calon_siswa_ortu.tgl_lahir_ayah','calon_siswa_ortu.tgl_lahir_ibu','calon_siswa_ortu.tgl_lahir_wali','calon_siswa_baru.id_jenis_transportasi','calon_siswa_baru.id_c_siswa','calon_siswa_baru.*')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
            ->join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
            ->join('status_pengguna','pengguna.id_status_pengguna','=','status_pengguna.id_status_pengguna')
            ->join('calon_siswa_baru','siswa.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->join('jalur_siswa', function ($join) {
                            $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                        })
            ->join('jalur','jalur_siswa.id_jalur','=','jalur.id_jalur')
            ->leftJoin('calon_siswa_ortu','calon_siswa_ortu.id_c_siswa','=','calon_siswa_baru.id_c_siswa')
            ->leftJoin('provinsi','calon_siswa_baru.alamat_provinsi','=','provinsi.id_provinsi')
            ->leftJoin('kota','calon_siswa_baru.alamat_kota','=','kota.id_kota')
            ->leftJoin('agama','agama.id_agama','=','calon_siswa_baru.id_agama')
            ->leftJoin('kebutuhan_khusus','calon_siswa_baru.id_kebutuhan_khusus','=','kebutuhan_khusus.id_kebutuhan_khusus')
            ->leftJoin('jenis_tinggal','calon_siswa_baru.id_jenis_tinggal','=','jenis_tinggal.id_jenis_tinggal')
            ->leftJoin('jenis_transportasi','calon_siswa_baru.id_jenis_transportasi','=','jenis_transportasi.id_jenis_transportasi')
            ->leftJoin('jenis_layak_pip','jenis_layak_pip.id_jenis_layak_pip','=','calon_siswa_baru.id_jenis_layak_pip')
            ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
            ->where('siswa.nis_siswa','=',$nis_siswa)->first();

        return $siswa;
    }
    static function aktivitasAdmisi($auth_data,$nis_siswa){
        $admisi = Admisi::select('admisi.ips','admisi.ipk','semester.nm_semester','semester.tahun_ajaran','status_pengguna.nm_status_pengguna')
          ->join('siswa','admisi.id_siswa','=','siswa.id_siswa')
          ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
          ->join('semester','semester.id_semester','=','admisi.id_semester')
          ->join('status_pengguna','status_pengguna.id_status_pengguna','=','admisi.id_status_pengguna')
          ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
          ->where('siswa.nis_siswa','=',$nis_siswa)
          ->get();

          return $admisi;
    }
    /** PENGAMBILAN SISWA BY id_kelas_mp **/
    static function fetchDataSiswaKelasMp($auth_data, $id_kelas_mp, $pertemuan_ke = null){

        if(! empty($pertemuan_ke)) {
            $siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'kelas_mp.id_kelas_mp', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('pengambilan_mp','pengambilan_mp.id_siswa','=','siswa.id_siswa')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','pengambilan_mp.id_kelas_mp')
                    ->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
                    ->leftJoin('presensi_mp', function ($join) use ($pertemuan_ke) {
                            $join->on('presensi_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                 ->where('presensi_mp.pertemuan_ke', '=', $pertemuan_ke);
                        })
                    ->where('kelas_mp.id_kelas_mp','=',$id_kelas_mp)
                    ->where('status_pengguna.aktif_status_pengguna','=',1)
                    ->where('pengambilan_mp.status_apv_pengambilan_mp','=',1)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('pengguna.nm_pengguna', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
        }
        else {
            $siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'kelas_mp.id_kelas_mp', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('pengambilan_mp','pengambilan_mp.id_siswa','=','siswa.id_siswa')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','pengambilan_mp.id_kelas_mp')
                    ->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
                    ->where('kelas_mp.id_kelas_mp','=',$id_kelas_mp)
                    ->where('status_pengguna.aktif_status_pengguna','=',1)
                    ->where('pengambilan_mp.status_apv_pengambilan_mp','=',1)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('pengguna.nm_pengguna', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();
        }

        return $siswa;
    }
    /** ========== **/    

    /** PENGAMBILAN SISWA BY id_ujian_mp **/
    static function fetchDataSiswaUjianMp($auth_data, $id_ujian_mp){

        $siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'ujian_mp.id_ujian_mp', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('ujian_mp_presensi','ujian_mp_presensi.id_siswa','=','siswa.id_siswa')
                    ->join('ujian_mp','ujian_mp.id_ujian_mp','=','ujian_mp_presensi.id_ujian_mp')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','ujian_mp.id_kelas_mp')
                    ->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
                    ->where('ujian_mp.id_ujian_mp','=',$id_ujian_mp)
                    ->where('status_pengguna.aktif_status_pengguna','=',1)
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('pengguna.nm_pengguna', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();

        return $siswa;
    }
    /** ========== **/ 

    /** JADWAL KBM SISWA with HARI BY id_pengguna **/    
    static function fetchDataJadwalKBM($auth_data, $id_pengguna, $id_semester = null) {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $jadwalKBM = Siswa::select('guru.id_guru', 'guru.id_pengguna', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.id_kelas_mp', 'semester.tahun_ajaran', 'semester.nm_semester', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.jam_mulai', 'jadwal_jam.menit_mulai', 'jadwal_jam.jam_selesai', 'jadwal_jam.menit_selesai', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'pengampu_mp.pjmp_pengampu_mp')
                    ->join('pengambilan_mp','pengambilan_mp.id_siswa','=','siswa.id_siswa')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','pengambilan_mp.id_kelas_mp')
                    ->join('pengampu_mp','pengampu_mp.id_kelas_mp','=','kelas_mp.id_kelas_mp')
                    ->join('guru','guru.id_guru','=','pengampu_mp.id_guru')                    
                    ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                    ->join('semester','semester.id_semester','=','pengambilan_mp.id_semester')
                    ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
                    ->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
                    ->join('jadwal_kelas_mp','jadwal_kelas_mp.id_kelas_mp','=','kelas_mp.id_kelas_mp')
                    ->join('ruangan','ruangan.id_ruangan','=','jadwal_kelas_mp.id_ruangan')
                    ->join('jadwal_hari','jadwal_hari.id_jadwal_hari','=','jadwal_kelas_mp.id_jadwal_hari')
                    ->join('jadwal_jam','jadwal_jam.id_jadwal_jam','=','jadwal_kelas_mp.id_jadwal_jam')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->where('pengambilan_mp.status_apv_pengambilan_mp','=',1)
                    ->where('pengampu_mp.pjmp_pengampu_mp','=',1)
                    ->where('semester.id_sekolah','=',$auth_data->pengguna->id_sekolah);
                    if (! empty($id_semester)) {
                        $jadwalKBM = $jadwalKBM->where('semester.id_semester','=',$id_semester);
                    }
                    $jadwalKBM = $jadwalKBM->orderBy('jadwal_hari.kode_jadwal_hari', 'asc')
                    ->orderBy('jadwal_jam.jam_mulai', 'asc')
                    ->orderBy('jadwal_jam.menit_mulai', 'asc')
                    ->orderBy('semester.thn_akademik_semester', 'desc')
                    ->orderBy('semester.nm_semester', 'desc')
                    ->get();

        return $jadwalKBM;
    }
    /** ========== **/

    /** JADWAL UTS SISWA with HARI BY id_pengguna **/
    static function fetchDataJadwalUTS($auth_data, $id_pengguna, $id_semester = null){

        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $jadwalUTS = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'pengampu_mp.pjmp_uts', 'ujian_mp.is_online')
                    ->join('ujian_mp_presensi','ujian_mp_presensi.id_siswa','=','siswa.id_siswa')
                    ->join('ujian_mp','ujian_mp.id_ujian_mp','=','ujian_mp_presensi.id_ujian_mp')
                    ->join('kegiatan','kegiatan.id_kegiatan','=','ujian_mp.id_kegiatan')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','ujian_mp.id_kelas_mp')
                    ->join('semester','semester.id_semester','=','kelas_mp.id_semester')
                    ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
                    ->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
                    ->join('pengampu_mp','pengampu_mp.id_kelas_mp','=','kelas_mp.id_kelas_mp')
                    ->join('guru','guru.id_guru','=','pengampu_mp.id_guru')                    
                    ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->where('kegiatan.kode_kegiatan','=',"UTS")
                    ->where('kegiatan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('pengampu_mp.pjmp_uts','=',1);
                    if (! empty($id_semester)) {
                        $jadwalUTS = $jadwalUTS->where('semester.id_semester','=',$id_semester);
                    }
                    $jadwalUTS = $jadwalUTS->orderBy('ujian_mp.tgl_ujian_mp', 'asc')
                    ->orderBy('ujian_mp.jam_mulai', 'asc')
                    ->orderBy('ujian_mp.jam_selesai', 'asc')
                    ->orderBy('semester.thn_akademik_semester', 'desc')
                    ->orderBy('semester.nm_semester', 'desc')
                    ->get();

        return $jadwalUTS;
    }
    /** ========== **/ 

    /** JADWAL UAS SISWA with HARI BY id_pengguna **/
    static function fetchDataJadwalUAS($auth_data, $id_pengguna, $id_semester = null){

        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $jadwalUAS = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'ujian_mp.id_ujian_mp', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'kelas.nm_kelas', 'pengampu_mp.pjmp_uas', 'ujian_mp.is_online')
                    ->join('ujian_mp_presensi','ujian_mp_presensi.id_siswa','=','siswa.id_siswa')
                    ->join('ujian_mp','ujian_mp.id_ujian_mp','=','ujian_mp_presensi.id_ujian_mp')
                    ->join('kegiatan','kegiatan.id_kegiatan','=','ujian_mp.id_kegiatan')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','ujian_mp.id_kelas_mp')
                    ->join('semester','semester.id_semester','=','kelas_mp.id_semester')
                    ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
                    ->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
                    ->join('pengampu_mp','pengampu_mp.id_kelas_mp','=','kelas_mp.id_kelas_mp')
                    ->join('guru','guru.id_guru','=','pengampu_mp.id_guru')                    
                    ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->where('kegiatan.kode_kegiatan','=',"UAS")
                    ->where('kegiatan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('pengampu_mp.pjmp_uas','=',1);
                    if (! empty($id_semester)) {
                        $jadwalUAS = $jadwalUAS->where('semester.id_semester','=',$id_semester);
                    }
                    $jadwalUAS = $jadwalUAS->orderBy('ujian_mp.tgl_ujian_mp', 'asc')
                    ->orderBy('ujian_mp.jam_mulai', 'asc')
                    ->orderBy('ujian_mp.jam_selesai', 'asc')
                    ->orderBy('semester.thn_akademik_semester', 'desc')
                    ->orderBy('semester.nm_semester', 'desc')
                    ->get();

        return $jadwalUAS;
    }
    /** ========== **/ 

    /** JADWAL MAGANG SISWA BY id_pengguna **/    
    static function fetchDataMagang($auth_data, $id_pengguna) {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $magangSiswa = Siswa::select('semester.tahun_ajaran', 'semester.nm_semester', 'magang.nm_magang', 'periode_magang.nm_periode_magang', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.alamat_rekanan_magang', 'periode_magang.tgl_magang_mulai', 'periode_magang.tgl_magang_selesai', 'pengambilan_magang.nilai_angka', 'pengambilan_magang.nilai_huruf', 'pengambilan_magang.is_tampil')
                    ->join('pengambilan_magang','pengambilan_magang.id_siswa','=','siswa.id_siswa')
                    ->join('periode_magang','periode_magang.id_periode_magang','=','pengambilan_magang.id_periode_magang')
                    ->join('semester','semester.id_semester','=','periode_magang.id_semester')
                    ->join('magang','magang.id_magang','=','periode_magang.id_magang')
                    ->join('rekanan_magang','rekanan_magang.id_rekanan_magang','=','pengambilan_magang.id_rekanan_magang')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->where('pengambilan_magang.status_apv_pengambilan_magang','=',1)
                    ->where('pengambilan_magang.status_magang','<>',10)
                    ->where('magang.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('periode_magang.tgl_magang_mulai', 'asc')
                    ->orderBy('periode_magang.tgl_magang_selesai', 'asc')
                    ->orderBy('semester.thn_akademik_semester', 'desc')
                    ->orderBy('semester.nm_semester', 'desc')
                    ->get();

        return $magangSiswa;
    }
    /** ========== **/

    /** TAGIHAN SISWA BY id_pengguna **/    
    static function fetchTagihanSiswa($auth_data, $id_pengguna) {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $tagihanBiaya = TagihanBiaya::select('tagihan_biaya.id_tagihan_biaya', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'jalur.nm_jalur', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"))
                                ->join('detail_biaya','detail_biaya.id_detail_biaya','=','tagihan_biaya.id_detail_biaya')
                                ->join('biaya_sekolah','biaya_sekolah.id_biaya_sekolah','=','detail_biaya.id_biaya_sekolah')
                                ->leftJoin('jalur','jalur.id_jalur','=','biaya_sekolah.id_jalur')
                                ->join('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','biaya_sekolah.id_kelompok_biaya')
                                ->join('semester','semester.id_semester','=','biaya_sekolah.id_semester')
                                ->join('biaya','biaya.id_biaya','=','detail_biaya.id_biaya')
                                ->leftJoin('jenis_detail_biaya','jenis_detail_biaya.id_jenis_detail_biaya','=','detail_biaya.id_jenis_detail_biaya')
                                ->leftJoin('bulan','bulan.id_bulan','=','detail_biaya.id_bulan')
                                ->where('tagihan_biaya.is_tagih','=',1)
                                ->where('tagihan_biaya.id_siswa','=',$id_siswa)
                                ->orderBy('semester.thn_akademik_semester', 'desc')
                                ->orderBy('semester.nm_semester', 'desc')
                                ->orderBy('tagihan_biaya.besar_biaya', 'desc')
                                ->orderBy('kelompok_biaya.status_kelompok_biaya', 'asc')
                                ->orderBy('kelompok_biaya.nm_kelompok_biaya', 'asc')
                                ->orderBy('biaya.nm_biaya', 'asc')
                                ->get();

        return $tagihanBiaya;
    }
    /** ========== **/

    /** RIWAYAT BAYAR SISWA BY id_pengguna **/    
    static function fetchPembayaranSiswa($auth_data, $id_pengguna) {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $pembayaranSiswa = PembayaranBiaya::select('siswa.id_siswa', 'tagihan_biaya.id_tagihan_biaya', 'pembayaran_biaya.id_pembayaran_biaya', 'kelompok_biaya.nm_kelompok_biaya', 's_biaya.tahun_ajaran as tahun_ajaran_biaya', 's_biaya.nm_semester as nm_semester_biaya', 'jalur.nm_jalur', 'biaya.nm_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 's_bayar.tahun_ajaran as tahun_ajaran_bayar', 's_bayar.nm_semester as nm_semester_bayar', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'pembayaran_biaya.besar_pembayaran', 'pembayaran_biaya.tgl_pembayaran', 'bank.nm_bank', 'bank_via.nm_bank_via', 'pembayaran_biaya.nomor_transaksi')
                    ->join('tagihan_biaya','tagihan_biaya.id_tagihan_biaya','=','pembayaran_biaya.id_tagihan_biaya')
                    ->join('siswa','siswa.id_siswa','=','tagihan_biaya.id_siswa')
                    ->join('detail_biaya','detail_biaya.id_detail_biaya','=','tagihan_biaya.id_detail_biaya')
                    ->leftJoin('jenis_detail_biaya','jenis_detail_biaya.id_jenis_detail_biaya','=','detail_biaya.id_jenis_detail_biaya')
                    ->leftJoin('bulan','bulan.id_bulan','=','detail_biaya.id_bulan')
                    ->join('biaya_sekolah','biaya_sekolah.id_biaya_sekolah','=','detail_biaya.id_biaya_sekolah')
                    ->join('kelompok_biaya','kelompok_biaya.id_kelompok_biaya','=','biaya_sekolah.id_kelompok_biaya')
                    ->join('biaya','biaya.id_biaya','=','detail_biaya.id_biaya')
                    ->join('semester AS s_biaya','s_biaya.id_semester','=','biaya_sekolah.id_semester')
                    ->leftJoin('jalur','jalur.id_jalur','=','biaya_sekolah.id_jalur')
                    ->join('semester AS s_bayar','s_bayar.id_semester','=','pembayaran_biaya.id_semester_bayar')
                    ->join('staff','staff.id_staff','=','pembayaran_biaya.id_staff_bayar')
                    ->join('pengguna','pengguna.id_pengguna','=','staff.id_pengguna')
                    ->leftJoin('bank','bank.id_bank','=','pembayaran_biaya.id_bank')
                    ->leftJoin('bank_via','bank_via.id_bank_via','=','pembayaran_biaya.id_bank_via')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->where('biaya.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('pembayaran_biaya.tgl_pembayaran', 'desc')
                    ->orderBy('s_bayar.thn_akademik_semester', 'desc')
                    ->orderBy('s_bayar.nm_semester', 'desc')
                    ->orderBy('detail_biaya.id_bulan', 'asc')
                    ->get();

        return $pembayaranSiswa;
    }
    /** ========== **/

    /** PELANGGARAN NON-KBM SISWA BY id_pengguna **/    
    static function fetchPelanggaranNonKBM($auth_data, $id_pengguna) {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $pelanggaranNonKBM = Siswa::select('siswa.id_siswa', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'jenis_tindakan.nm_jenis_tindakan', 'tindakan_pelanggaran.catatan_tindakan_pelanggaran', 'tindakan_pelanggaran.tgl_tindakan_pelanggaran', 'tindakan_pelanggaran.aktor_input_tindakan_pelanggaran')
                    ->join('pelanggaran_siswa','pelanggaran_siswa.id_siswa','=','siswa.id_siswa')
                    ->leftJoin('tindakan_pelanggaran','tindakan_pelanggaran.id_pelanggaran_siswa','=','pelanggaran_siswa.id_pelanggaran_siswa')
                    ->leftJoin('jenis_tindakan','jenis_tindakan.id_jenis_tindakan','=','tindakan_pelanggaran.id_jenis_tindakan')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->orderBy('pelanggaran_siswa.tgl_pelanggaran', 'desc')
                    ->get();

        return $pelanggaranNonKBM;
    }
    /** ========== **/

    /** PELANGGARAN KBM SISWA BY id_pengguna **/    
    static function fetchPelanggaranKBM($auth_data, $id_pengguna) {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna','=',$id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $pelanggaranKBM = Siswa::select('siswa.id_siswa', 'semester.tahun_ajaran', 'semester.nm_semester', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'presensi_mp_pelanggaran.catatan_pelanggaran', 'presensi_mp.tgl_entry', 'presensi_mp_pelanggaran.is_sudah_tindakan', 'jenis_tindakan.nm_jenis_tindakan', 'tindakan_pelanggaran.catatan_tindakan_pelanggaran', 'tindakan_pelanggaran.tgl_tindakan_pelanggaran', 'tindakan_pelanggaran.aktor_input_tindakan_pelanggaran')
                    ->join('presensi_mp_pelanggaran','presensi_mp_pelanggaran.id_siswa','=','siswa.id_siswa')
                    ->join('presensi_mp','presensi_mp.id_presensi_mp','=','presensi_mp_pelanggaran.id_presensi_mp')
                    ->join('kelas_mp','kelas_mp.id_kelas_mp','=','presensi_mp.id_kelas_mp')
                    ->join('pengampu_mp','pengampu_mp.id_kelas_mp','=','kelas_mp.id_kelas_mp')
                    ->join('guru','guru.id_guru','=','pengampu_mp.id_guru')                    
                    ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                    ->join('semester','semester.id_semester','=','kelas_mp.id_semester')
                    ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
                    ->leftJoin('tindakan_pelanggaran','tindakan_pelanggaran.id_presensi_mp_pelanggaran','=','presensi_mp_pelanggaran.id_presensi_mp_pelanggaran')
                    ->leftJoin('jenis_tindakan','jenis_tindakan.id_jenis_tindakan','=','tindakan_pelanggaran.id_jenis_tindakan')
                    ->where('siswa.id_siswa','=',$id_siswa)
                    ->where('pengampu_mp.pjmp_pengampu_mp','=',1)
                    ->orderBy('presensi_mp.tgl_entry', 'desc')
                    ->get();

        return $pelanggaranKBM;
    }
    /** ========== **/


}