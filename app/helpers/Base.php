<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

if (!function_exists('auth_data')) {
    /**
     * get auth data from session
     * @return auth_data
     */
    function auth_data()
    {
      return Session::get('auth_data');
    }
}


if (!function_exists('generate_id')) {
  /**
   * generate custom uuid by school prefix
   * @return String
   */
  function generate_id()
  {
    $now  = Carbon::now(env('APP_TIMEZONE', '')); 
    $id   = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();
    
    return $id;
  }
}

if (!function_exists('get_moduls')) {
  /**
   * get modules that authorized to active user
   * @return array
   */
  function get_moduls()
  {    
    return auth_data()->moduls;
  }
}

if (!function_exists('storeFileToCloud')) {
  /**
   * handle store file to cloud server 
   * @param string $path,
   * @param string $id
   * @param File $file
   * @param string $access
   * @return string
   */
  function storeFileToCloud($path, $id, $file, $access = 'public')
  {    
    $prefix = auth_data()->sekolah_data->nm_prefix;
    return Storage::disk('spaces')->putFile("$prefix/$path/$id", $file, $access);
  }
}

if (!function_exists('removeFileFromCloud')) {
  /**
   * handle remove file resource from cloud server by filename 
   * @param string $filename,
   * @return any
   */
  function removeFileFromCloud($filename)
  {
    return Storage::disk('spaces')->delete($filename);
  }
}


if (!function_exists('web_response')) {
  /**
   * return web response of web operation
   * @param string $path,
   * @param string $message,
   * @param int $status,
   * @return array
   */
  function web_response($path = null, $message = "Ok", $status = 202)
  {
    return [
      'status' => $status,
      'path' => $path,
      'message' => $message
    ];
  }
}

if (!function_exists('error_response')) {
  /**
   * return error response of web operation
   * @param string $message,
   * @param int $status,
   * @return array
   */
  function error_response($message, $status = 300)
  {
    return [
      'status' => $status,
      'message' => $message
    ];
  }
}