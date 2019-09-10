<?php

namespace App\Libraries\BimbinganKonseling;

use App\Models\JenisTindakan as JenisTindakan;
use App\Models\PelanggaranSiswa as PelanggaranSiswa;
use App\Models\PresensiMpPelanggaran as PresensiMpPelanggaran;
use App\Models\TindakanPelanggaran as TindakanPelanggaran;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibDataPelanggaran
{
    /** AMBIL DATA JENIS TINDAKAN **/
    static function fetchDataJenisTindakan($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jenisTindakan = JenisTindakan::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_jenis_tindakan', 'asc')->get();
        }
        // get mode edit
        else{
            $jenisTindakan = JenisTindakan::where('id_jenis_tindakan','=',$id)->first();
        }

        return $jenisTindakan;
    }
    /** ========== **/

    /** AMBIL DATA PELANGGARAN **/
    static function fetchDataInputPelanggaran($auth_data, $id_kelas = null, $id = null, $is_datatable = null) {

        // get mode view
        if ($id == null){
            // khusus wali kelas
            if(! empty($id_kelas)) {
                $pelanggaranSiswa = PelanggaranSiswa::select('pelanggaran_siswa.id_pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', 'pelanggaran_siswa.id_semester', 'pelanggaran_siswa.id_guru_input', 'pengguna.nm_pengguna', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_staff.nm_pengguna as nm_staff_input', 'p_staff.gelar_depan as gelar_depan_staff', 'p_staff.gelar_belakang as gelar_belakang_staff', 'semester.tahun_ajaran', 'semester.nm_semester', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'pelanggaran_siswa.created_by', 'kelas.nm_kelas')
                    ->join('siswa','siswa.id_siswa','=','pelanggaran_siswa.id_siswa')
                    ->join('kelas','siswa.id_kelas','=','kelas.id_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('semester','semester.id_semester','=','pelanggaran_siswa.id_semester')
                    ->leftJoin('guru','guru.id_guru','=','pelanggaran_siswa.id_guru_input')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('pengguna as p_staff','p_staff.id_pengguna','=','pelanggaran_siswa.created_by')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('siswa.id_kelas', '=', $id_kelas)
                    ->where('pelanggaran_siswa.aktor_input_pelanggaran', '=', 3)
                    ->orderBy('pelanggaran_siswa.tgl_pelanggaran', 'desc');

                    if ( $is_datatable == null ) {
                        $pelanggaranSiswa = $pelanggaranSiswa->get();
                    }
            }
            else { 
                $pelanggaranSiswa = PelanggaranSiswa::select('pelanggaran_siswa.id_pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', 'pelanggaran_siswa.id_semester', 'pelanggaran_siswa.id_guru_input', 'pengguna.nm_pengguna', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_staff.nm_pengguna as nm_staff_input', 'p_staff.gelar_depan as gelar_depan_staff', 'p_staff.gelar_belakang as gelar_belakang_staff', 'semester.tahun_ajaran', 'semester.nm_semester', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.catatan_pelanggaran_khusus', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'pelanggaran_siswa.created_by', 'kelas.nm_kelas')
                    ->join('siswa','siswa.id_siswa','=','pelanggaran_siswa.id_siswa')
                    ->join('kelas','siswa.id_kelas','=','kelas.id_kelas')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('semester','semester.id_semester','=','pelanggaran_siswa.id_semester')
                    ->leftJoin('guru','guru.id_guru','=','pelanggaran_siswa.id_guru_input')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('pengguna as p_staff','p_staff.id_pengguna','=','pelanggaran_siswa.created_by')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->orderBy('pelanggaran_siswa.tgl_pelanggaran', 'desc');

                    if ( $is_datatable == null ) {
                        $pelanggaranSiswa = $pelanggaranSiswa->get();
                    }
            }
        }
        // get mode edit
        else{
            $pelanggaranSiswa = PelanggaranSiswa::select('pelanggaran_siswa.id_pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', 'pelanggaran_siswa.id_semester', 'pelanggaran_siswa.id_guru_input', 'pengguna.nm_pengguna as nm_siswa', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_staff.nm_pengguna as nm_staff_input', 'p_staff.gelar_depan as gelar_depan_staff', 'p_staff.gelar_belakang as gelar_belakang_staff', 'semester.tahun_ajaran', 'semester.nm_semester', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.catatan_pelanggaran_khusus', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'pelanggaran_siswa.created_by', 'kelas.nm_kelas')
                                    ->join('siswa','siswa.id_siswa','=','pelanggaran_siswa.id_siswa')
                                    ->join('kelas','siswa.id_kelas','=','kelas.id_kelas')
                                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                    ->join('semester','semester.id_semester','=','pelanggaran_siswa.id_semester')
                                    ->leftJoin('guru','guru.id_guru','=','pelanggaran_siswa.id_guru_input')
                                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                                    ->leftJoin('pengguna as p_staff','p_staff.id_pengguna','=','pelanggaran_siswa.created_by')
                                    ->where('pelanggaran_siswa.id_pelanggaran_siswa','=',$id)
                                    ->first();
        }

        return $pelanggaranSiswa;
    }
    /** ========== **/

    /** AMBIL DATA PRESENSI MP PELANGGARAN **/
    static function fetchDataPresensiPelanggaran($auth_data, $id = null, $is_datatable = null){

        // get mode view
        if ($id == null){
            $presensiMpPelanggaran = PresensiMpPelanggaran::select('presensi_mp_pelanggaran.id_presensi_mp_pelanggaran', 'presensi_mp_pelanggaran.id_siswa', 'pengguna.nm_pengguna', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'presensi_mp_pelanggaran.catatan_pelanggaran', 'presensi_mp_pelanggaran.created_at', 'presensi_mp.pertemuan_ke', 'presensi_mp_pelanggaran.id_presensi_mp')
                        ->join('siswa','siswa.id_siswa','=','presensi_mp_pelanggaran.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('pengguna as p_guru','p_guru.id_pengguna','=','presensi_mp_pelanggaran.created_by')
                        ->join('presensi_mp','presensi_mp.id_presensi_mp','=','presensi_mp_pelanggaran.id_presensi_mp')
                        ->join('kelas_mp','kelas_mp.id_kelas_mp','=','presensi_mp.id_kelas_mp')
                        ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
                        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('presensi_mp_pelanggaran.is_sudah_tindakan','=',0)
                        ->orderBy('presensi_mp_pelanggaran.created_at', 'desc');

                        if ( $is_datatable == null ) {
                            $presensiMpPelanggaran = $presensiMpPelanggaran->get();
                        }
        }
        // get mode edit
        else{
            $presensiMpPelanggaran = PresensiMpPelanggaran::select('presensi_mp_pelanggaran.id_presensi_mp_pelanggaran', 'presensi_mp_pelanggaran.id_siswa', 'pengguna.nm_pengguna as nm_siswa', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'presensi_mp_pelanggaran.catatan_pelanggaran', 'presensi_mp_pelanggaran.created_at', 'presensi_mp.pertemuan_ke', 'presensi_mp_pelanggaran.id_presensi_mp')
                        ->join('siswa','siswa.id_siswa','=','presensi_mp_pelanggaran.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('pengguna as p_guru','p_guru.id_pengguna','=','presensi_mp_pelanggaran.created_by')
                        ->join('presensi_mp','presensi_mp.id_presensi_mp','=','presensi_mp_pelanggaran.id_presensi_mp')
                        ->join('kelas_mp','kelas_mp.id_kelas_mp','=','presensi_mp.id_kelas_mp')
                        ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
                        ->where('presensi_mp_pelanggaran.id_presensi_mp_pelanggaran','=',$id)
                        ->first();
        }

        return $presensiMpPelanggaran;
    }
    /** ========== **/

    /** AMBIL DATA PELANGGARAN **/
    static function fetchDataTindakanPelanggaran($auth_data, $status = null, $id = null, $is_datatable = null) {

        // get mode view
        if ($id == null){
            // belum ada tindakan
            if($status == "0") {
                $tindakanPelanggaran = PelanggaranSiswa::select('pelanggaran_siswa.id_pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', 'pelanggaran_siswa.id_guru_input', 'pengguna.nm_pengguna', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_staff.nm_pengguna as nm_staff_input', 'p_staff.gelar_depan as gelar_depan_staff', 'p_staff.gelar_belakang as gelar_belakang_staff', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.catatan_pelanggaran_khusus', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'pelanggaran_siswa.created_by')
                    ->join('siswa','siswa.id_siswa','=','pelanggaran_siswa.id_siswa')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->leftJoin('guru','guru.id_guru','=','pelanggaran_siswa.id_guru_input')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('pengguna as p_staff','p_staff.id_pengguna','=','pelanggaran_siswa.created_by')
                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('pelanggaran_siswa.is_sudah_tindakan', '=', 0)
                    ->orderBy('pelanggaran_siswa.tgl_pelanggaran', 'desc');

                    if ( $is_datatable == null ) {
                        $tindakanPelanggaran = $tindakanPelanggaran->get();
                    }
            }
            elseif($status == "1") { 
                $tindakanPelanggaran = TindakanPelanggaran::select('tindakan_pelanggaran.id_tindakan_pelanggaran', 'pelanggaran_siswa.id_pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', 'pelanggaran_siswa.id_guru_input', 
                    'pengguna.nm_pengguna as nm_siswa', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_staff.nm_pengguna as nm_staff_input', 'p_staff.gelar_depan as gelar_depan_staff', 'p_staff.gelar_belakang as gelar_belakang_staff', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.catatan_pelanggaran_khusus', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'pelanggaran_siswa.created_by',
                    'p_siswa_presensi.nm_pengguna as nm_siswa_presensi', 'p_guru_presensi.nm_pengguna as nm_guru_input_presensi', 'p_guru_presensi.gelar_depan as gelar_depan_guru_presensi', 'p_guru_presensi.gelar_belakang as gelar_belakang_guru_presensi', 'presensi_mp_pelanggaran.catatan_pelanggaran as catatan_pelanggaran_presensi', 'presensi_mp_pelanggaran.created_at as tgl_pelanggaran_presensi', 'presensi_mp_pelanggaran.is_sudah_tindakan as is_sudah_tindakan_presensi', 'presensi_mp_pelanggaran.created_by as created_by_presensi',
                    'jenis_tindakan.nm_jenis_tindakan', 'tindakan_pelanggaran.catatan_tindakan_pelanggaran', 'tindakan_pelanggaran.catatan_tindakan_pelanggaran_khusus', 'tindakan_pelanggaran.tgl_tindakan_pelanggaran', 'tindakan_pelanggaran.aktor_input_tindakan_pelanggaran', 'tindakan_pelanggaran.created_by as created_by_tindakan', 'p_tindakan.nm_pengguna as nm_input_tindakan', 'p_tindakan.gelar_depan as gelar_depan_tindakan', 'p_tindakan.gelar_belakang as gelar_belakang_tindakan'
                    )
                    ->join('jenis_tindakan','jenis_tindakan.id_jenis_tindakan','tindakan_pelanggaran.id_jenis_tindakan')
                    ->leftJoin('pelanggaran_siswa','pelanggaran_siswa.id_pelanggaran_siswa','=','tindakan_pelanggaran.id_pelanggaran_siswa')
                    ->leftJoin('presensi_mp_pelanggaran','presensi_mp_pelanggaran.id_presensi_mp_pelanggaran','=','tindakan_pelanggaran.id_presensi_mp_pelanggaran')
                    ->leftJoin('siswa','siswa.id_siswa','=','pelanggaran_siswa.id_siswa')
                    ->leftJoin('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->leftJoin('siswa as siswa_presensi','siswa_presensi.id_siswa','=','presensi_mp_pelanggaran.id_siswa')
                    ->leftJoin('pengguna as p_siswa_presensi','p_siswa_presensi.id_pengguna','=','siswa_presensi.id_pengguna')
                    ->leftJoin('guru','guru.id_guru','=','pelanggaran_siswa.id_guru_input')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('pengguna as p_staff','p_staff.id_pengguna','=','pelanggaran_siswa.created_by')
                    ->leftJoin('pengguna as p_guru_presensi','p_guru_presensi.id_pengguna','=','presensi_mp_pelanggaran.created_by')
                    ->join('pengguna as p_tindakan','p_tindakan.id_pengguna','=','tindakan_pelanggaran.created_by')
                    ->where('jenis_tindakan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->orderBy('pelanggaran_siswa.tgl_pelanggaran', 'desc');

                    if ( $is_datatable == null ) {
                        $tindakanPelanggaran = $tindakanPelanggaran->get();
                    }
            }
        }
        // get mode edit
        else{
            $tindakanPelanggaran = TindakanPelanggaran::select('tindakan_pelanggaran.id_tindakan_pelanggaran', 'pelanggaran_siswa.id_pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', 'pelanggaran_siswa.id_guru_input', 
                    'pengguna.nm_pengguna as nm_siswa', 'p_guru.nm_pengguna as nm_guru_input', 'p_guru.gelar_depan as gelar_depan_guru', 'p_guru.gelar_belakang as gelar_belakang_guru', 'p_staff.nm_pengguna as nm_staff_input', 'p_staff.gelar_depan as gelar_depan_staff', 'p_staff.gelar_belakang as gelar_belakang_staff', 'pelanggaran_siswa.catatan_pelanggaran', 'pelanggaran_siswa.catatan_pelanggaran_khusus', 'pelanggaran_siswa.tgl_pelanggaran', 'pelanggaran_siswa.aktor_input_pelanggaran', 'pelanggaran_siswa.is_sudah_tindakan', 'pelanggaran_siswa.created_by',
                    'p_siswa_presensi.nm_pengguna as nm_siswa_presensi', 'p_guru_presensi.nm_pengguna as nm_guru_input_presensi', 'p_guru_presensi.gelar_depan as gelar_depan_guru_presensi', 'p_guru_presensi.gelar_belakang as gelar_belakang_guru_presensi', 'presensi_mp_pelanggaran.catatan_pelanggaran as catatan_pelanggaran_presensi', 'presensi_mp_pelanggaran.created_at as tgl_pelanggaran_presensi', 'presensi_mp_pelanggaran.is_sudah_tindakan as is_sudah_tindakan_presensi', 'presensi_mp_pelanggaran.created_by as created_by_presensi',
                    'jenis_tindakan.id_jenis_tindakan', 'jenis_tindakan.nm_jenis_tindakan', 'tindakan_pelanggaran.catatan_tindakan_pelanggaran', 'tindakan_pelanggaran.catatan_tindakan_pelanggaran_khusus', 'tindakan_pelanggaran.tgl_tindakan_pelanggaran', 'tindakan_pelanggaran.aktor_input_tindakan_pelanggaran', 'tindakan_pelanggaran.created_by as created_by_tindakan'
                    )
                    ->join('jenis_tindakan','jenis_tindakan.id_jenis_tindakan','tindakan_pelanggaran.id_jenis_tindakan')
                    ->leftJoin('pelanggaran_siswa','pelanggaran_siswa.id_pelanggaran_siswa','=','tindakan_pelanggaran.id_pelanggaran_siswa')
                    ->leftJoin('presensi_mp_pelanggaran','presensi_mp_pelanggaran.id_presensi_mp_pelanggaran','=','tindakan_pelanggaran.id_presensi_mp_pelanggaran')
                    ->leftJoin('siswa','siswa.id_siswa','=','pelanggaran_siswa.id_siswa')
                    ->leftJoin('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->leftJoin('siswa as siswa_presensi','siswa_presensi.id_siswa','=','presensi_mp_pelanggaran.id_siswa')
                    ->leftJoin('pengguna as p_siswa_presensi','p_siswa_presensi.id_pengguna','=','siswa_presensi.id_pengguna')
                    ->leftJoin('guru','guru.id_guru','=','pelanggaran_siswa.id_guru_input')
                    ->leftJoin('pengguna as p_guru','p_guru.id_pengguna','=','guru.id_pengguna')
                    ->leftJoin('pengguna as p_staff','p_staff.id_pengguna','=','pelanggaran_siswa.created_by')
                    ->leftJoin('pengguna as p_guru_presensi','p_guru_presensi.id_pengguna','=','presensi_mp_pelanggaran.created_by')
                    ->where('tindakan_pelanggaran.id_tindakan_pelanggaran','=',$id)
                    ->first();
        }

        return $tindakanPelanggaran;
    }
    /** ========== **/


}