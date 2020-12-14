<?php

namespace App\Libraries\Keuangan;

use App\Models\Alumni;
use App\Models\AlumniIdle;
use App\Models\AlumniBusiness;
use App\Models\AlumniWorkplace;
use App\Models\AlumniUniversity;


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

    $studentMajorsRelation = ['jurusan' => function($query){
      return $query->select('id_jurusan', 'nm_jurusan');
    }];

    $studentCandidateRelation = ['calon_siswa' => function($query) use($studentMajorsRelation) { 
      return $query->select(['id_c_siswa','nm_c_siswa', 'id_jurusan'])->with($studentMajorsRelation); 
    }];

    return Alumni::select(['id_c_siswa', 'status', 'tahun_lulus'])->with(['calon_siswa', 'calon_siswa.jurusan'])->get();
  }

}