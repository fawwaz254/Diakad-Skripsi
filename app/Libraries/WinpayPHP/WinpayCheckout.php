<?php

namespace App\Libraries\WinpayPHP;

use Exception;
use Illuminate\Support\Facades\Http;

class WinpayCheckout
{
    private static $SANDBOX_URL = 'https://checkout.bmstaging.id/api';
    private static $API_URL = 'https://checkout.winpay.id/api';

    protected $secret_key;
    protected $key;

    function __construct()
    {
        $this->key = env('WINPAY_KEY', 'BX5AZ2QWEFC');
        $this->secret_key = env('WINPAY_SECRET_KEY', '277c371424c137832d44c26c2be475e692b4aa4b');
    }

    public static function getApiUrl()
    {
        return (env('WINPAY_PRODUCTION', false) ? WinpayCheckout::$API_URL : WinpayCheckout::$SANDBOX_URL);
    }

    public function getSignature($timestamp)
    {
        return hash_hmac('sha256', $timestamp, $this->secret_key);
    }

    public function getHeaderAuth($timestamp)
    {
        $array = [
            'X-Winpay-Timestamp' => $timestamp,
            'X-Winpay-Key' => $this->key,
            'X-Winpay-Signature' => WinpayCheckout::getSignature($timestamp),
        ];
        return $array;
    }

    public static function getRelativeUrl($route)
    {
        return WinpayCheckout::getApiUrl() . '/' . $route;
    }

    public function process($array_payload)
    {
        $timestamp = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:sP');
        // {
        //     "customer": {
        //         "name": "",
        //         "email": "",
        //         "phone": ""
        //     },
        //     "invoice": {
        //         "ref": "TRANSAKSI_NO",
        //         "products": [
        //             {
        //                 "name": "PRODUCT_NAME",
        //                 "qty": 1,
        //                 "price": 350000
        //             }
        //         ]
        //     },
        //     "back_url": "https://example.com",
        //     "interval": 120
        // }

        try {
            $response = Http::withHeaders(WinpayCheckout::getHeaderAuth($timestamp))
                ->withBody(json_encode($array_payload),'application/json')
                ->post(WinpayCheckout::getRelativeUrl('create'));

            $res_body = json_decode($response->body());

            return $res_body;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function find($uid)
    {
        $timestamp = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:sP');

        try {
            $response = Http::withHeaders(WinpayCheckout::getHeaderAuth($timestamp))
                ->get(WinpayCheckout::getRelativeUrl('find/' . $uid));

            $res_body = json_decode($response->body());

            return $res_body;
        } catch (Exception $e) {
            return $e;
        }
    }
}
