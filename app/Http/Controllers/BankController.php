<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
// use Lcobucci\JWT\Signer\Hmac\Sha256;
// use Lcobucci\JWT\Signer\Key\InMemory;
use App\Models\PembayaranTrs;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Semester;
use Carbon\Carbon;
use App\Models\TagihanBiaya;
use App\Models\PembayaranBiaya;
use App\Models\PembayaranTrsDetail;
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
            // $nama       = $claims->get('nama');
            // $nominal    = $claims->get('nominal');
            $feenominal = $claims->get('feenominal');
            $idtrn      = $claims->get('idtrn');
            $id_tagihan = $claims->get('id_tagihan');
        } catch (\Exception $e) {
            return response()->json([
                'Kode' => 98,
                'Keterangan' => 'Decode jwt error (format jwt error)'
            ]);
        }

        //Validasi Institusi
        $now = Carbon::now();
        $sekolah = Sekolah::first();
        if ($kode == 'A0001') { //Create VA
            $cek_dup = PembayaranTrs::where('nomor_transaksi', $kodetrn)->first();
            if ($cek_dup) {
                return response()->json(['Kode ' => 03, 'Keterangan' => 'Duplicate kode VA']);
            }
            $siswa = Siswa::where('nis_siswa', $acno)->first();
            if ($siswa) {

                $VA = new PembayaranTrs;
                $VA->id_pembayaran_trs  = $sekolah->prefix . strtotime($now) . uniqid();
                $VA->id_siswa           = $siswa->id_siswa;
                $VA->nomor_transaksi   = $kodetrn;
                $VA->besar_pembayaran   = 0;
                $VA->status_pembayaran  = 0; //belum terbayar
                $VA->token              = '';
                $VA->id_semester_bayar  = 0;
                $VA->tgl_pembayaran     = null;
                $VA->keterangan         = '';
                $VA->save();
                return response()->json(['Kode ' => 00, 'Keterangan' => 'Sukses']);
            } else {
                return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
            }
        } elseif ($kode == 'A0002') { //Create Tagihan
            $pembayaran_trs = PembayaranTrs::where('nomor_transaksi', $kodetrn)->first();
            if ($pembayaran_trs) {
                $cek_dup = PembayaranTrsDetail::where('id_tagihan_biaya', $id_tagihan)->where('id_pembayaran_trs', $pembayaran_trs->id_pembayaran_trs)->first();
                if ($cek_dup) {
                    return response()->json(['Kode ' => 03, 'Keterangan' => 'Duplicate tagihan']);
                }
                $tagihan = TagihanBiaya::find($id_tagihan);
                if ($tagihan) {
                    $tagihan_biaya = new PembayaranTrsDetail;
                    $tagihan_biaya->id_pembayaran_trs_detail = $sekolah->prefix . strtotime($now) . uniqid();
                    $tagihan_biaya->id_pembayaran_trs = $pembayaran_trs->id_pembayaran_trs;
                    $tagihan_biaya->id_tagihan_biaya = $id_tagihan;
                    $tagihan_biaya->besar_pembayaran = $tagihan->besar_biaya;
                    $tagihan_biaya->save();
                    $pembayaran_trs->besar_pembayaran = $pembayaran_trs->besar_pembayaran + $tagihan->besar_biaya;
                    $pembayaran_trs->save();
                    return response()->json(['Kode ' => 00, 'Keterangan' => 'Sukses']);
                } else {
                    return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
                }
            } else {
                return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
            }
        } elseif ($kode == 'A0003') { //Update Tagihan
            // $pembayaran_trs = PembayaranTrs::where('nomor_transaksi', $kodetrn)->first();
            // if ($pembayaran_trs) {
            //     $tagihan_biaya = PembayaranTrsDetail::where('id_pembayaran_trs', $pembayaran_trs)->where('id_tagihan_biaya', '')->first();
            //     $tagihan = TagihanBiaya::find($id_tagihan);
            //     if ($tagihan_biaya &&  $tagihan) {
            //         $pembayaran_trs->besar_pembayaran = $pembayaran_trs->besar_pembayaran - $tagihan_biaya->besar_pembayaran +  $tagihan->besar_biaya;
            //         $pembayaran_trs->save();
            //         $tagihan_biaya->besar_pembayaran = $tagihan->besar_biaya;
            //         $tagihan_biaya->save();
            //     } else {
            //         return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
            //     }
            // } else {
            //     return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
            // }
        } elseif ($kode == 'A0004') { //Update Fee
            $pembayaran_trs = PembayaranTrs::where('nomor_transaksi', $kodetrn)->first();
            if ($pembayaran_trs) {
                if ($pembayaran_trs->status_pembayaran == '1') {
                    return response()->json(['Kode ' => 03, 'Keterangan' => 'Duplicate Update Fee']);
                }
                DB::beginTransaction();
                try {
                    $semester = Semester::where('is_aktif_semester', '1')->first();
                    //pembuatan kuitansi
                    $pembayaran_trs->fee_admin = $feenominal;
                    $pembayaran_trs->status_pembayaran = 1;
                    $pembayaran_trs->payment_code = $idtrn;
                    $pembayaran_trs->payment_channel = 'KBBS';
                    $pembayaran_trs->id_semester_bayar = $semester->id_semester;
                    $pembayaran_trs->tgl_pembayaran = $now->format("Y-m-d");
                    $pembayaran_trs->save();

                    $pembayaran_trs_detail = PembayaranTrsDetail::where('id_pembayaran_trs', $pembayaran_trs->id_pembayaran_trs)->get();
                    foreach ($pembayaran_trs_detail as $p) {
                        $tagihan_biaya = TagihanBiaya::find($p->id_tagihan_biaya);
                        if ($tagihan_biaya) {
                            $tagihan_biaya->is_tagih = 0;
                            $tagihan_biaya->tgl_pelunasan =  $now->format("Y-m-d");
                            $tagihan_biaya->besar_pembayaran = $tagihan_biaya->besar_biaya;
                            $tagihan_biaya->save();

                            $pembayaran = new PembayaranBiaya;
                            $pembayaran->id_pembayaran_biaya = $sekolah->prefix . strtotime($now) . uniqid();
                            $pembayaran->id_tagihan_biaya = $tagihan_biaya->id_tagihan_biaya;
                            $pembayaran->id_semester_bayar = $semester->id_semester;
                            $pembayaran->besar_pembayaran = $tagihan_biaya->besar_biaya;
                            $pembayaran->tgl_pembayaran =  $now->format("Y-m-d");
                            $pembayaran->nomor_transaksi = $kodetrn;
                            $pembayaran->keterangan = 'dari Bank';
                            $pembayaran->save();
                        } else {
                            return response()->json(['Kode ' => 97, 'Keterangan' => 'kodetrn tidak terdaftar']);
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    return response()->json([
                        'Kode' => 99,
                        'Keterangan' => $e->getMessage()
                    ]);
                }
                return response()->json(['Kode ' => 00, 'Keterangan' => 'Sukses']);
            } else {
                return response()->json(['Kode ' => 04, 'Keterangan' => 'kode institusi tidak terdaftar']);
            }
            return response()->json(['Kode ' => 97, 'Keterangan' => 'kodetrn tidak terdaftar']);
        }

        return  response()->json([
            'Kode' => 99,
            'Keterangan' => 'Error unknown'
        ]);
    }
}
