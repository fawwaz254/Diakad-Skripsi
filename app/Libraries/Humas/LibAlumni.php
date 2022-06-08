<?php

namespace App\Libraries\Humas;

use App\Models\Alumni;
use App\Models\AlumniMenunggu;
use App\Models\AlumniWirausaha;
use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniSmp;
use Illuminate\Support\Facades\DB;


class LibAlumni {
  
  public static function store($data)
  {
    return Alumni::insert($data);
  }

  public static function storeWorkplace($data)
  {
    return AlumniBekerja::insert($data);
  }

  public static function storeUniversity($data)
  {
    return AlumniKuliah::insert($data);
  }

  public static function storeBusiness($data)
  {
    return AlumniWirausaha::insert($data);
  }

  public static function storeIdleAlumni($data)
  {
    return AlumniMenunggu::insert($data);
  }
  
  public static function storeSMP($data)
  {
    return AlumniSmp::insert($data);
  }


  public static function getAlumnisSmpWithId($id)
  {
    return DB::table('alumni')
      ->join('calon_siswa_baru as siswa', 'alumni.id_c_siswa', '=', 'siswa.id_c_siswa')
      ->join('alumni_smp', 'alumni.id_alumni','=','alumni_smp.id_alumni')
      ->leftjoin('kelas', 'alumni.id_kelas', '=', 'kelas.id_kelas')
      ->select('alumni.id_alumni', 'siswa.nm_c_siswa', 'alumni.tahun_lulus','alumni_smp.nm_sekolah',  'alumni.status','alumni.status_verifikasi','kelas.nm_kelas')
      ->where('alumni.deleted_at', null)
      ->where('alumni.id_c_siswa', $id)
      ->get();
  }

  public static function getAlumnisWithId($id)
  {
    return DB::table('alumni')
      ->join('calon_siswa_baru as siswa', 'alumni.id_c_siswa', '=', 'siswa.id_c_siswa')
      ->join('alumni_smp', 'alumni.id_alumni','=','alumni_smp.id_alumni')
      ->leftjoin('kelas', 'alumni.id_kelas', '=', 'kelas.id_kelas')
      ->select('alumni.id_alumni', 'siswa.nm_c_siswa', 'alumni.tahun_lulus','alumni_smp.nm_sekolah',  'alumni.status','alumni.status_verifikasi','kelas.nm_kelas')
      ->where('alumni.deleted_at', null)
      ->where('alumni.id_c_siswa', $id)
      ->get();
  }


  public static function getAlumnisSmp()
  {
    return DB::table('alumni')
      ->join('calon_siswa_baru as siswa', 'alumni.id_c_siswa', '=', 'siswa.id_c_siswa')
      ->join('alumni_smp', 'alumni.id_alumni','=','alumni_smp.id_alumni')
      ->leftjoin('kelas', 'alumni.id_kelas', '=', 'kelas.id_kelas')
      ->select('alumni.id_alumni', 'siswa.nm_c_siswa', 'alumni.tahun_lulus','alumni_smp.nm_sekolah',  'alumni.status','alumni.status_verifikasi','kelas.nm_kelas')
      ->where('alumni.deleted_at', null)
      ->get();
  }

  public static function getAlumnis()
  {
    return DB::table('alumni')
      ->join('calon_siswa_baru as siswa', 'alumni.id_c_siswa', '=', 'siswa.id_c_siswa')
      ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
      ->leftjoin('kelas', 'alumni.id_kelas', '=', 'kelas.id_kelas')
      ->select('alumni.id_alumni', 'siswa.nm_c_siswa', 'alumni.tahun_lulus', 'jurusan.nm_jurusan', 'alumni.status','alumni.status_verifikasi','kelas.nm_kelas')
      ->where('alumni.deleted_at', null)
      ->get();
  }

}