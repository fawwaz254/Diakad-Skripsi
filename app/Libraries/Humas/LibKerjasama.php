<?php

namespace App\Libraries\Humas;

use App\Models\Instansi;
use App\Models\Kerjasama;
use App\Models\JenisKerjasama;
use Illuminate\Support\Facades\DB;


class LibKerjasama {
  
  public static function storeInstansi($data)
  {
    return Instansi::create($data);
  }

  public static function storeJenisKerjasama($data)
  {
    return JenisKerjasama::create($data);
  }

  public static function storeKerjasama($data)
  {
    return Kerjasama::create($data);
  }

  public static function getInstansi()
  {
    return Instansi::all();
  }

  public static function getJenisKerjasama()
  {
    return JenisKerjasama::all();
  }

  public static function getKerjasama()
  {
    return Kerjasama::with('instansi', 'jenisKerjasama')->get();
  }
}