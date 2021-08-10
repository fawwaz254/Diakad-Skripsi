<?php

namespace App\Libraries\WinpayPHP;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class Winpay
{
    public static $private_key1; // PK 1
    public static $private_key2; // PK 2
    public static $merchant_key; // MK

    public static $isProduction = false;

    const SANDBOX_BASE_URL = 'https://sandbox-payment.winpay.id';
    const PRODUCTION_BASE_URL = 'https://secure-payment.winpay.id';

    function __construct()
	{
        Winpay::$private_key1 = config('winpay.private_key1','');
        Winpay::$private_key2 = config('winpay.private_key2','');
        Winpay::$merchant_key = config('winpay.merchant_key','');
        Winpay::$isProduction = config('winpay.production',false);
	}

    public static function getBaseUrl()
    {
      return Winpay::$isProduction ?
          Winpay::PRODUCTION_BASE_URL : Winpay::SANDBOX_BASE_URL;
    }

    public static function getCheckoutUrl($api_id){
        return Winpay::getBaseUrl() . '/checkout?payid=' .$api_id;
    }

    public static function getPaymentUrl($payment_channel, $api_id){
        return Winpay::getBaseUrl() . '/' .strtolower($payment_channel). '?payid=' .$api_id;
    }

    public static function getStatusUrl($payment_channel, $api_id){
        return Winpay::getBaseUrl() . '/guidance/index/' .strtolower($payment_channel). '?payid=' .$api_id;
    }

    public static function get($url, $data_hash)
    {
        return self::remoteCall($url, $data_hash, false);
    }

    public static function post($url, $data_hash)
    {
        return self::remoteCall($url, $data_hash, true);
    }
    
    public static function remoteCall($url, $data_hash, $post = true, $return_404 = false)
    { 
        $private_key1 = Winpay::$private_key1;
        $private_key2 = Winpay::$private_key2;
        $base64_encoded_private_key = base64_encode($private_key1 . ":" . $private_key2);

        $headers = array (
            'Authorization: Basic '.$base64_encoded_private_key, 
            'Content-Type: application/x-www-form-urlencoded'
        );

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

        // curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); 

        if ($post) {
            curl_setopt ( $ch, CURLOPT_POST, true );
            
            if ($data_hash) {
                $body = http_build_query($data_hash);
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, $body );
            } else {
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, '' );
            }
        }

        $result = curl_exec ( $ch );
        $info = curl_getinfo($ch);
        // curl_close ( $ch );

        if ($result === FALSE) {
            throw new WinpayException('CURL Error: ' . curl_error($ch), curl_errno($ch));
        }else {
            $result_array = json_decode($result);
            if ($info['http_code'] == 200) {
                return $result_array;
            }else {
                if ($return_404 && $info['http_code'] == 404)
                    return array( 'http_code' => $info['http_code'], 'message' => 'Your page is not found');

                $message = 'Error (' . $info['http_code'] . '): ' . $result;
                throw new WinpayException($message, $info['http_code']);
            }
        }
    }

    public static function getPaymentChannel($params = null){
        $result = Winpay::get(
            Winpay::getBaseUrl() . '/toolbar',
            $params);

        return $result->data->products;
    }

    public static function getToken($params = null){
        $result = Winpay::get(
            Winpay::getBaseUrl() . '/token',
            $params);

        return $result->data->token;
    }

    public static function postPayloads($payment_channel, $params = null){
        $result = Winpay::post(
            Winpay::getBaseUrl() . '/apiv2/'. $payment_channel,
            $params);

        return $result;
    }

    public static function checkout($payment_channel, $params){
        $token = Winpay::getToken();

        $payload = array(
            "cms"                           => "WINPAY API",
            "spi_callback"                  => $params['callback'],
            "url_listener"                  => $params['listener'],
            "spi_currency"                  => "IDR",
            "spi_item"                      => $params['items'],
            "spi_amount"                    => $params['amount'],
            "spi_signature"                 => Winpay::getSignature($params['order_id'], $params['amount']),
            "spi_token"                     => Winpay::getSpiToken(),
            "spi_merchant_transaction_reff" => $params['order_id'],
            "spi_billingPhone"              => $params['usr_phone'],
            "spi_billingEmail"              => $params['usr_email'],
            "spi_billingName"               => $params['usr_name'],
            "spi_paymentDate"               => $params['exp_date'],
            "get_link"                      => "yes"
        );

        $json_string = json_encode($payload);

        $messageEncrypted = Winpay::OpenSSLEncrypt($json_string, $token);
        $orderdata = substr($messageEncrypted, 0, 10). $token. substr($messageEncrypted, 10);
        
        $result = Winpay::postPayloads($payment_channel, array( 'orderdata' => $orderdata ));

        return $result;
    }

    public static function getRedirectStatusPayment($payment_channel, $api_id)
    {
        $status_url = self::getStatusUrl($payment_channel, $api_id);
        $check_status = self::remoteCall($status_url, null, false, true);

        if(!empty($check_status['http_code']) && $check_status['http_code'] == 404){
            return self::getPaymentUrl($payment_channel, $api_id);
        }else{
            return $status_url;
        }

    }

	public static function openSSLEncrypt($message, $key){
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = $key;
		$secret_iv = $key;
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		$output = openssl_encrypt($message, $encrypt_method, $key, 0, $iv);
		$output = trim(base64_encode($output));
		
		return $output;
	}

	public static function getSignature($order_id, $amount){
		$merchant_key = Winpay::$merchant_key;
		$spi_token = Winpay::getSpiToken();
		$spi_merchant_transaction_reff = $order_id;
		$spi_amount = $amount;
		$spi_amount = number_format($spi_amount,2,".","");
		$spi_signature = strtoupper(sha1( $spi_token . '|' . $merchant_key . '|' . $spi_merchant_transaction_reff . '|' . $spi_amount . '|0|0' ));
		return $spi_signature;
    }
    
    public static function getSpiToken(){
		return Winpay::$private_key1.Winpay::$private_key2;
	}
	
}