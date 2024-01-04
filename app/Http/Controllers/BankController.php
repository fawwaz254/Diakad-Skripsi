<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use App\Models\PembayaranTrs;
use App\Models\Sekolah;
use Carbon\Carbon;

class BankController extends Controller
{
    public function processJwt(Request $request)
    {     
        $jwtEncoded = $request->input('JwtEncoded');
        $kode       = $request->input('Kode');

        //Validasi Signature
        try{
        $config     = Configuration::forUnsecuredSigner();
        $token      = $config->parser()->parse($jwtEncoded);
        //! Untuk Konfigurasi
        //? $secretKey = InMemory::base64Encoded('crGyqM0orACXsibBpa5HyU9hFlsOHWCrnlQENXmzY6wrZSXOdtBbbdoVxf8lmDkss');
        //? $signer = new Sha256();
        //? $config = Configuration::forSymmetricSigner($signer, $secretKey);
        
        } catch (\Exception $e) {
        
            return response()->json([
            'Kode' => 01, 'Keterangan' => 'Invalid signature (jwt)'
        ]); }

        //Validasi Decode
        try{

        $claims     = $token->claims();
        $kodetrn    = $claims->get('kodetrn');
        $acno       = $claims->get('acno'); //nis siswa
        $nama       = $claims->get('nama'); 
        $nominal    = $claims->get('nominal');
       
        } catch (\Exception $e) {
            return response()->json([
                'Kode' => 98,
                'Keterangan' => 'Decode jwt error (format jwt error)'
            ]);
        }

        //Validasi Institusi
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $sekolah = Sekolah::first();
        if($kode == 'A0001'){ //Create VA
    
            $VA = new PembayaranTrs()
            $VA->id_pembayaran_trs =  $sekolah->prefix . strtotime($now) . uniqid();


        // return response()->json(['Kode ' => 05, 'Keterangan' => 'Institusi tidak aktif']);
            
        }elseif($kode == 'A0002'){//Create Tagihan
        
        return response()->json(['Kode ' => 05, 'Keterangan' => 'Institusi tidak aktif']);
        }elseif($kode == 'A0003'){ //Update Tagihan
       
          
        
        
        }elseif($kode == 'A0004'){//Update Fee
        
        return response()->json(['Kode ' => 05, 'Keterangan' => 'Institusi tidak aktif']);
        } else{
            return response()->json(['Kode ' => 97, 'Keterangan' => 'kodetrn tidak terdaftar']);
        }
            

        return  response()->json([
            'Kode' => 00,
            'Keterangan' => 'Sukses'
        ]);


     

    }}
