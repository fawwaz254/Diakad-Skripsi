<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

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