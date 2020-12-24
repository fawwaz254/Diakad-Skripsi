<?php

namespace App\Libraries\Humas;

use App\Models\Instansi;
use Illuminate\Support\Facades\DB;


class LibKerjasama {
  
  public static function storeInstansi($data)
  {
    return Instansi::create($data);
  }
}