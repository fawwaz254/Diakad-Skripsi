<?php

namespace App\Libraries\Humas;

use App\Models\Instansi;
use App\Models\JenisKerjaSama;
use Illuminate\Support\Facades\DB;


class LibKerjasama {
  
  public static function storeInstansi($data)
  {
    return Instansi::create($data);
  }

  public static function storeJenisKerjasama($data)
  {
    return JenisKerjaSama::create($data);
  }

  public static function getInstansi()
  {
    return Instansi::all();
  }

  public static function getJenisKerjasama()
  {
    return JenisKerjaSama::all();
  }
}