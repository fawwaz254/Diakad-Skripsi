<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;
use Cloudder;

class LibGlobal
{
	// Fungsi singkat nama
	/* FIKRIE 16-04-2016 */
	static function singkatNama($str, $panjangKarakter) {
        $str = ucwords(trim($str));
        
		if(strlen($str) > $panjangKarakter){
			$words = explode(' ', $str);
			if(strlen($words[0]) == 1 || strlen($words[0]) == 2){
				$jumlah_huruf = strlen($words[0]) + strlen($words[1]) + 1; 
				if($jumlah_huruf > $panjangKarakter){
					return $words[0].' '.strtoupper(substr($words[1], 0, 1));
				}else{
					return $words[0].' '.$words[1];
				}
			}else if(strlen($words[0]) > $panjangKarakter){
				return substr($words[0], 0, $panjangKarakter);
			}else{
				return $words[0];
			}
		}
		else{
			return $str;
		}
	}

	/**
	 * Fungsi enkripsi (by Fikrie)
	 * @param string $string
	 * @param string $key
	 * @return string 
	 * @author M Fikrie Ramadhan (21-12-2016)
	 */
	static function Encrypt($string)
	{
		$key = "3be5cel4luc3t14cel4m4ny4";
		$result = '';

		for ($i = 0; $i < strlen($string); $i++)
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key)) - 1, 1);
			$char = chr(ord($char) + ord($keychar));
			$result .= $char;
		}

		return str_replace(array('+', '/'), array('-', '_'), base64_encode($result));
		//return base64_encode($result);
	}

	/**
	 * Fungsi dekripsi (by Fikrie)
	 * @param string $string
	 * @param string $key
	 * @return string 
	 * @author M Fikrie Ramadhan (21-12-2016)
	 */
	static function Decrypt($string)
	{
		$key = "3be5cel4luc3t14cel4m4ny4";
		$result = '';
		$string = base64_decode(str_replace(array('-', '_'), array('+', '/'), $string));

		for($i=0; $i<strlen($string); $i++)
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key)) - 1, 1);
			$char = chr(ord($char) - ord($keychar));
			$result .= $char;
		}

		return $result;
	}

	/**
	 * Fungsi upload to cloudinary (by Rio)
	 * @param string $string
	 * @param string $key
	 * @return string 
	 * @author Rio Ramadhan D (17-01-2019)
	 */

	static function uploadCloudinary(Request $request){
		$image_name = $request->file('image_name')->getRealPath();;
 
		Cloudder::upload($image_name, null, [ 'folder' => 'diakad/smawh2/' ]);
 
		list($width, $height) = getimagesize($image_name);

		$image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
		return 'Success '.$image_url;

	}
	
}