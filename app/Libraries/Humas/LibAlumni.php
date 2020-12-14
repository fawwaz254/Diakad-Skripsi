<?php

namespace App\Libraries\Keuangan;

use App\Models\Alumni;
use App\Models\AlumniIdle;
use App\Models\AlumniBusiness;
use App\Models\AlumniWorkplace;
use App\Models\AlumniUniversity;
use Illuminate\Support\Facades\DB;


class LibAlumni {
  
  public static function store($data)
  {
    return Alumni::insert($data);
  }

  public static function storeWorkplace($data)
  {
    return AlumniWorkplace::insert($data);
  }

  public static function storeUniversity($data)
  {
    return AlumniUniversity::insert($data);
  }

  public static function storeBusiness($data)
  {
    return AlumniBusiness::insert($data);
  }

  public static function storeIdleAlumni($data)
  {
    return AlumniIdle::insert($data);
  }

  public static function getAlumnis()
  {
    return DB::table('alumni')
      ->join('calon_siswa_baru as siswa', 'alumni.id_c_siswa', '=', 'siswa.id_c_siswa')
      ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
      ->select('alumni.id_alumni', 'siswa.nm_c_siswa', 'alumni.tahun_lulus', 'jurusan.nm_jurusan', 'alumni.status')
      ->get();
  }

}