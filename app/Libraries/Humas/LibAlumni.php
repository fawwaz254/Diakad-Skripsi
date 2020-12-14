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
    $studentCandidateRelation = ['calon_siswa' => function($query) { 
      return $query->select('nm_c_siswa'); 
    }];

    return Alumni::select(['id_siswa', 'status', 'tahun_lulus'])->with('calon_siswa')->get();

    
    return Alumni::select('status')->with([$studentCandidateRelation, $studentRelation])->get();
  }

}