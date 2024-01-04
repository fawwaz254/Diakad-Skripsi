<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use App\Models\PembayaranTrs;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Semester;
use Carbon\Carbon;
use App\Models\TagihanBiaya;
use App\Models\PembayaranBiaya;
use DB;

class BankController extends BaseController
{
    public function processJwt(Request $request)
    {
        $jwtEncoded = $request->input('JwtEncoded');
        $kode       = $request->input('Kode');

        //Validasi Signature
        try {
            $config     = Configuration::forUnsecuredSigner();
            $token      = $config->parser()->parse($jwtEncoded);
            //! Untuk Konfigurasi
            //? $secretKey = InMemory::base64Encoded('crGyqM0orACXsibBpa5HyU9hFlsOHWCrnlQENXmzY6wrZSXOdtBbbdoVxf8lmDkss');
            //? $signer = new Sha256();
            //? $config = Configuration::forSymmetricSigner($signer, $secretKey);

        } catch (\Exception $e) {

            return response()->json([
                'Kode' => 01, 'Keterangan' => 'Invalid signature (jwt)'
            ]);
        }

        //Validasi Decode
        try {

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
        $siswa = Siswa::where('nis_siswa', $acno)->first();
        $semester = Semester::where('is_aktif_semester', '1')->first();

        if (empty($siswa)) {
            return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
        }

        if ($kode == 'A0001') { //Create VA
            $VA = new PembayaranTrs();
            $VA->id_pembayaran_trs =  $sekolah->prefix . strtotime($now) . uniqid();
            $VA->id_siswa   = $siswa->id_siswa;
            $VA->nomor_transasksi = '';
            $VA->besar_pembayaran = $nominal;
            $VA->status_pembayaran = '';
            $VA->token = '';
            $VA->id_semester_bayar = $semester->id_semester;
            $VA->tgl_pembayaran = $now->format("Y-m-d");
            $VA->keterangan = '';
            $VA->save();
        } elseif ($kode == 'A0002') { //Create Tagihan
            return response()->json(['Kode ' => 05, 'Keterangan' => 'Institusi tidak aktif']);
        } elseif ($kode == 'A0003') { //Update Tagihan

            DB::beginTransaction();
            try {

                $tagihan_biaya = TagihanBiaya::where('is_tagih', 1)->where('id_siswa', $siswa->id_siswa)->first();
                $tagihan_biaya->is_tagih = 0;
                $tagihan_biaya->tgl_pelunasan =  $now->format("Y-m-d");
                $tagihan_biaya->besar_pembayaran = $nominal;
                $tagihan_biaya->save();

                $pembayaran = new PembayaranBiaya;
                $pembayaran->id_pembayaran_biaya = $sekolah->prefix . strtotime($now) . uniqid();
                $pembayaran->id_tagihan_biaya = $tagihan_biaya->id_tagihan_biaya;
                $pembayaran->id_semester_bayar = $semester->id_semester;
                $pembayaran->besar_pembayaran = $nominal;
                $pembayaran->tgl_pembayaran =  $now->format("Y-m-d");
                $pembayaran->nomor_transaksi = '';
                $pembayaran->keterangan = 'dari Bank';
                $pembayaran->save();
                DB::commit();
            } catch (\Exception $e) {
                return response()->json([
                    'Kode' => 99,
                    'Keterangan' => $e->getMessage()
                ]);
            }
        } elseif ($kode == 'A0004') { //Update Fee
            $VA = PembayaranTrs::first();
            $VA->id_pembayaran_trs =  $sekolah->prefix . strtotime($now) . uniqid();
            $VA->id_siswa   = $siswa->id_siswa;
            $VA->nomor_transasksi = '';
            $VA->besar_pembayaran = $nominal;
            $VA->status_pembayaran = '';
            $VA->token = '';
            $VA->id_semester_bayar = $semester->id_semester;
            $VA->tgl_pembayaran = $now->format("Y-m-d");
            $VA->keterangan = '';
            $VA->save();
        } else {
            return response()->json(['Kode ' => 97, 'Keterangan' => 'kodetrn tidak terdaftar']);
        }

        return  response()->json([
            'Kode' => 00,
            'Keterangan' => 'Sukses'
        ]);
    }
}
