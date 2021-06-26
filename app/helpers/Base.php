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
        $id   = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

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
    function web_response($status, $message = "Ok", $path = '', $link = '')
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
     * @param any $message ,
     * @param int $status,
     * @return array
     */
    function error_response($message, $status = 300)
    {
        if ($message instanceof Exception) {
            $message = isDebugMode() ? $message->getMessage() : 'Failed code ' . $message->getLine();
        }

        return [
            'status' => $status,
            'message' => $message
        ];
    }
}

if (!function_exists('isDebugMode')) {
    /**
     * check is debug mode of the system active
     * @return array
     */
    function isDebugMode()
    {
        return env('APP_DEBUG', 'true') == 'true';
    }
}

if (!function_exists('indonesiaDate')) {
    /**
     * convert 'Y-m-d' date to 'd F Y' format
     * @return string
     */
    function indonesiaDate($date)
    {
        if(!empty($date)){
            $months = [
                1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];
    
            $split = explode('-', $date);
            return $split[2] . ' ' . $months[(int)$split[1]] . ' ' . $split[0];
        }else{
            return '';
        }
    }
}

if (!function_exists('api_response')) {
    function api_response($statusCode, $message = null, $payloads = null, $headers = []){
        return response()->json([
                'status_code' 	=> $statusCode,
                'status_text' 	=> 'Success',
                'message' 	=> $message,
                'data' => $payloads
            ], 
            200, $headers, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT
        );
    }
}