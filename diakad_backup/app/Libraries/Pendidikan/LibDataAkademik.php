<?php

namespace App\Libraries\Pendidikan;

use App\Models\Semester as Semester;
use App\Models\Jalur as Jalur;
use App\Models\Jurusan as Jurusan;
use App\Models\StandarNilai as StandarNilai;
use App\Models\Kegiatan as Kegiatan;
use App\Models\JadwalKegiatan as JadwalKegiatan;
use App\Models\RuanganKelas as RuanganKelas;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibDataAkademik
{
    /** SEMESTER **/
	static function fetchDataNamaSemester($auth_data, $id = null) {

        // get all
        if ($id == null){
            $semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();
        }
        // get by id
        else{
            $semester = Semester::where('id_semester','=',$id)->first();
        }

        return $semester;
    }

    static function fetchDataSemesterAktif($auth_data) {

        $semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('is_aktif_semester','=',1)
                        ->first();

        return $semester;
    }

    static function fetchDataTahunSemester($auth_data) {

        $semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('thn_akademik_semester', 'asc')
                        ->distinct()
                        ->get(['thn_akademik_semester']);

        return $semester;
    }

    static function fetchDataTahunAjaranSemester($auth_data) {

        $semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('tahun_ajaran', 'asc')
                        ->distinct()
                        ->get(['thn_akademik_semester', 'tahun_ajaran']);

        return $semester;
    }

    static function fetchDataNmSemester($auth_data) {

        $semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('nm_semester', 'asc')
                        ->distinct()
                        ->get(['nm_semester']);

        return $semester;
    }

    static function fetchDataRentangSemester($auth_data, $id_semester_mulai, $id_semester_selesai) {

        $semester_mulai = Semester::where('id_semester', '=', $id_semester_mulai)->first();
        $semester_selesai = Semester::where('id_semester', '=', $id_semester_selesai)->first();

        $kode_semester_mulai = $semester_mulai->kode_semester;
        $kode_semester_selesai = $semester_selesai->kode_semester;

        $semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->whereBetween('kode_semester', [$kode_semester_mulai, $kode_semester_selesai])
                        ->orderBy('thn_akademik_semester', 'asc')
                        ->orderBy('nm_semester', 'asc')
                        ->get();

        return $semester;
    }
    /** ========== **/

    /** JALUR **/
    static function fetchDataJalur($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jalur = Jalur::select('id_jalur', 'nm_jalur', 'kode_jalur', DB::raw("(SELECT COUNT(*) FROM jalur_siswa WHERE jalur_siswa.id_jalur = jalur.id_jalur AND jalur_siswa.is_jalur_aktif = 1 AND jalur_siswa.deleted_at IS NULL) AS jml_siswa"))->where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_jalur', 'asc')->get();
        }
        // get mode edit
        else{
            $jalur = Jalur::where('id_jalur','=',$id)->first();
        }

        return $jalur;
    }
    /** ========== **/

    /** JURUSAN **/
    static function fetchDataJurusan($auth_data, $id = null) {

        // get mode view
        if ($id == null){
            $jurusan = Jurusan::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_jurusan', 'asc')->get();
        }
        // get mode edit
        else{
            $jurusan = Jurusan::where('id_jurusan','=',$id)->first();
        }

        return $jurusan;
    }
    /** ========== **/

    /** NILAI MUTU **/
    static function fetchDataNilaiMutu($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $nilaiMutu = StandarNilai::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_standar_nilai', 'asc')->get();
        }
        // get mode edit
        else{
            $nilaiMutu = StandarNilai::where('id_standar_nilai','=',$id)->first();
        }

        return $nilaiMutu;
    }
    /** ========== **/

    /** KEGIATAN **/
    static function fetchDataKegiatan($auth_data, $id = null) {

        // get mode view
        if ($id == null){
            $kegiatan = Kegiatan::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('nm_kegiatan', 'asc')->get();
        }
        // get mode edit
        else{
            $kegiatan = Kegiatan::where('id_kegiatan','=',$id)->first();
        }

        return $kegiatan;
    }
    /** ========== **/

    /** KALENDER AKADEMIK **/
    static function fetchDataKalenderAkademik($auth_data, $id_semester, $id = null) {

        // get mode view
        if ($id == null){
            $kalenderAkademik = JadwalKegiatan::select('jadwal_kegiatan.id_jadwal_kegiatan','kegiatan.nm_kegiatan', 'kegiatan.deskripsi_kegiatan', 'kegiatan.deskripsi_kegiatan as deskripsi', 'semester.tahun_ajaran', 'semester.nm_semester', 'jadwal_kegiatan.tgl_mulai', 'jadwal_kegiatan.tgl_selesai')
                    ->join('kegiatan','kegiatan.id_kegiatan','=','jadwal_kegiatan.id_kegiatan')
                    ->join('semester','semester.id_semester','=','jadwal_kegiatan.id_semester')
                    ->where('jadwal_kegiatan.id_semester','=',$id_semester)
                    ->orderBy('jadwal_kegiatan.tgl_mulai', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $kalenderAkademik = JadwalKegiatan::join('kegiatan','kegiatan.id_kegiatan','=','jadwal_kegiatan.id_kegiatan')->join('semester','semester.id_semester','=','jadwal_kegiatan.id_semester')->where('jadwal_kegiatan.id_jadwal_kegiatan','=',$id)->first();
        }

        return $kalenderAkademik;
    }
    /** ========== **/
    
}