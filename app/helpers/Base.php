<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

/**
 * create auth_data helper if doesnt exists
 * @return auth_data
 */
if (!function_exists('auth_data')) {
    function auth_data()
    {
      return Session::get('auth_data');
    }
}

/**
 * create genereate_id if doesnt exists
 * @return String
 */
if (!function_exists('generate_id')) {
  function generate_id()
  {
    $now  = Carbon::now(env('APP_TIMEZONE', '')); 
    $id   = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();
    
    return $id;
  }
}

/**
 * create get_moduls if doesnt exists
 * @return array
 */
if (!function_exists('get_moduls')) {
  function get_moduls()
  {    
    return auth_data()->moduls;
  }
}

/**
 * create storeFileToCloud if doesnt exists, 
 * @param string $path,
 * @param File $file
 * @param string $access
 * @return string
 */
if (!function_exists('storeFileToCloud')) {
  function storeFileToCloud($path, $file, $access = 'public')
  {    
    $prefix = auth_data()->sekolah_data->nm_prefix;
    return Storage::disk('spaces')->putFile("$prefix/$path/$id", $file, $access);
  }
}