<?php

use App\Libraries\WinpayPHP\WinpayCheckout;
use App\Models\PembayaranBiaya;
use App\Models\PembayaranTrs;
use App\Models\PembayaranTrsDetail;
use App\Models\Semester;
use App\Models\TagihanBiaya;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('trx:check', function () {
    set_time_limit(-1);

    $semester_aktif = Semester::where('is_aktif_semester', 1)->first();
    $list_data = PembayaranTrs::whereNull('payment_channel')->where('created_at', '<', '2024-09-10 17:36:56')->get();
    $winpay_checkout = new WinpayCheckout;
    
    foreach ($list_data as $i => $trx) {
        $time = Carbon::now();
        $response = $winpay_checkout->find($trx->token);

        if(isset($response->responseCode) && $response->responseCode == '4040300'){
            $trx->payment_channel = 'NOT FOUND';
            $trx->save();
        }

        if(isset($response->responseCode) && $response->responseCode == '2000300'){
            $trx->payment_channel = $response->responseData->status;
            $trx->save();
        }

        if($trx->payment_channel == 'PAID'){
            $now = Carbon::parse($response->responseData->payment->created_at);
            $trx->status_pembayaran = 1;
            $trx->id_semester_bayar = $semester_aktif->id_semester;
            $trx->tgl_pembayaran = $now;
            $trx->fee_admin = $response->responseData->payment->fee;
            $trx->save();

            $data_transaksi_detail = PembayaranTrsDetail::where('id_pembayaran_trs', $trx->id_pembayaran_trs)->get();

            foreach ($data_transaksi_detail as $transaksi_detail) {
                $tagihan_biaya = TagihanBiaya::find($transaksi_detail->id_tagihan_biaya);
                $tagihan_biaya->is_tagih = 0;
                $tagihan_biaya->tgl_pelunasan = $trx->tgl_pembayaran;
                $tagihan_biaya->besar_pembayaran = $tagihan_biaya->besar_pembayaran + $transaksi_detail->besar_pembayaran;
                $tagihan_biaya->save();

                $id = 'Pm62h' . strtotime($now) . uniqid();
                $pembayaran = new PembayaranBiaya();
                $pembayaran->id_pembayaran_biaya = $id;
                $pembayaran->id_tagihan_biaya = $tagihan_biaya->id_tagihan_biaya;
                $pembayaran->id_semester_bayar = $trx->id_semester_bayar;
                $pembayaran->besar_pembayaran = $transaksi_detail->besar_pembayaran;
                $pembayaran->tgl_pembayaran = $trx->tgl_pembayaran;
                $pembayaran->nomor_transaksi = $trx->nomor_transaksi;
                $pembayaran->keterangan = $trx->keterangan;
                $pembayaran->save();
            }

        }
        
        echo $trx->nomor_transaksi . ' -> Elapsed time: ' . $time->diffForHumans(null, true) . PHP_EOL;

        if ($i % 10 === 0) {
            flush(); // Flush the buffer every 10 rows
        }
    }

    $this->comment('SUCCESS');
})->purpose('GENERATE ZIP FROM SURVEYOR');