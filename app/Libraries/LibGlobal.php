<?php

namespace App\Libraries;

use Cloudder;
use App\Models\NotifikasiPengguna;

class LibGlobal
{
	static function insertUpdateUserInCenter($pengguna){
		// $pengguna[] = [
        //     "id_pengguna" => "A8bT515358553655b8b4b05a6d86",
        //     "id_sekolah" => "A8bT515358553135b8b4ad12f588",
        //     "username" => "admin"
        // ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://center.diakad.id/api/insertuser', [
            'json' => [
                "api_name" => "CENTRE DIAKAD",
                "api_key" => "base64:+aUPwXFZXgNiGOx1Q3Ctq4bLDMnwztfB1cj3te+A9yo=",
                "pengguna" => $pengguna
            ]
        ]);
        return $response->getBody();
	}

	static function sendNotification($token, $payload, $notifikasi = null){
		// $api_key = 'AAAA_AQhHeg:APA91bFTVFqKHe-ov_KZy3pvZmZ7ZrrFw69mN-yG_SR_2BgvvfaFr4csjQXhkI2STQ55a_--79hyQSB-iicFF-ERFP3W8R3byO36ycA4QwoxaPMFsCmUMnlGsDp5YvnODCfnP5ZC5AR3';
		$api_key = 'MjNlNTliYzQtY2EzYS00NjlkLWEwZTktYjc4ZGZiZDI1MTQz'; // OneSignal RESTAPI KEY
		$app_id = '8c075562-351e-4fdf-9b1d-1448a3a79503'; // OneSignal APP_ID
    
		// $fields = array (
		// 	'to' => $token, 
		// 	'priority' => 'high', 
		// 	'content_available' => true, 
		// 	'data' => $payload
		// );
		
		$fields = array (
			'included_segments' => null,
			'app_id' => $app_id,
			'contents' => ['id'=> $payload['body'], 'en'=> $payload['body']],
			'headings' => ['id'=> $payload['title'], 'en'=> $payload['title']],
			'data' => $payload,
			'url' => null,
			'chrome_web_image' => null,
			'include_player_ids' => [$token]
		);

		// $headers = array (
		// 	'Authorization: key='.$api_key, 
		// 	'Content-Type: application/json'
		// );
		
		$headers = array (
			'Content-Type: application/json; charset=utf-8',
        	'Authorization: Basic ' . $api_key
		);

		// $url = 'https://fcm.googleapis.com/fcm/send';
		$url = 'https://onesignal.com/api/v1/notifications';

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); 
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($fields) );

		$result = curl_exec ( $ch );
		curl_close ( $ch );

		if(!empty($notifikasi)){
			$notifikasi_pengguna = new NotifikasiPengguna;
			$notifikasi_pengguna->id_notifikasi_pengguna 	= $notifikasi['id'];
			$notifikasi_pengguna->id_pengguna 				= $notifikasi['id_pengguna'];
			$notifikasi_pengguna->id_sekolah 				= $notifikasi['id_sekolah'];
			$notifikasi_pengguna->isi_notifikasi			= $notifikasi['isi_notifikasi'];
			$notifikasi_pengguna->created_by				= $notifikasi['created_by'];
			$notifikasi_pengguna->save();
		}

		return $result;
	}
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