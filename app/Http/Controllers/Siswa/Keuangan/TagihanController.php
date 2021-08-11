<?php

namespace App\Http\Controllers\Siswa\Keuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\WinpayPHP\Winpay;
use App\Models\PembayaranTrs;
use App\Models\PembayaranTrsDetail;
use App\Models\TagihanBiaya;
use App\Models\Siswa;

use Auth;
use DB;
use Session;
use Validator;

class TagihanController extends BaseController{

    public function viewTagihan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $cek_winpay = env("WINPAY_PRIVATE_KEY1");

        if($cek_winpay==""){
            $grup_payment_channel = null;
        }

        else{
            $winpay = new Winpay;
            $grup_payment_channel = $winpay->getPaymentChannel();
        }

        $pembayaran_aktif = PembayaranTrs::with('siswa', 'siswa.pengguna')->where('id_siswa', $siswa->id_siswa)->where('status_pembayaran', 0)->orderBy('created_at', 'desc')->get();

    	return view('siswa/keuangan/tagihan/view-tagihan',compact('auth_data', 'grup_payment_channel', 'pembayaran_aktif'));

    }

    public function datatablesTagihan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        /*$nmeng = array('January', 'February', 'March', '');
        $nmtur = array('Januari', 'Februari', 'Maret, '');
        $dt = 'January 22, 2012';
        $dt = str_ireplace($nmeng, $nmtur, $dt);*/

        $list_data = LibSiswa::fetchTagihanSiswa($auth_data, $auth_data->pengguna->id_pengguna);

        return Datatables::of($list_data)
                ->addColumn('biaya_sekolah', function($item){
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran." ".$item->nm_semester.")";
                })
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('jenis_biaya', function($item){
                    if($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." ".$item->nm_bulan."";
                    }
                    else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function($item){
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('denda_biaya', function($item){
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function($item){
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_tagihan_biaya
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionGenerate(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $validator = Validator::make($request->all(), [
            'payment_channel' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json([
                'status' 	=> 300,
                'message' => $validator->errors()->first(),
            ]);
        } 

        if (empty($input->id_tagihan_biaya)) {
            return response()->json([
                'status' 	=> 300,
                'message' => 'Mohon pilih siswa terlebih dahulu',
            ]);
        } 

        DB::beginTransaction();
        
        try {
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $siswa = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

            if(!empty($siswa->id_wali_murid)){
                $wali_murid_phone = $siswa->wali_murid->nomor_hp_wali_murid;
                $wali_murid_email = '';
            }else{
                $wali_murid_phone = '';
                $wali_murid_email = '';
            }

            $winpay = new Winpay;

            $pembayaran_trs = new PembayaranTrs;
            $pembayaran_trs->id_pembayaran_trs = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            $pembayaran_trs->id_siswa = $siswa->id_siswa;
            $pembayaran_trs->nomor_transaksi = $this->generateNumberTransaction($now, $auth_data->sekolah_data->prefix);
            $pembayaran_trs->id_semester_bayar = '';
            $pembayaran_trs->status_pembayaran = 0;
            $pembayaran_trs->created_by = $auth_data->pengguna->id_pengguna;
            $pembayaran_trs->save();

            $nomor = 1;
            $subtotal_pembayaran = 0;
            $items = array();
            foreach($input->id_tagihan_biaya as $id_tagihan_biaya){
                $tagihan_biaya = TagihanBiaya::with('detail_biaya', 'siswa', 'detail_biaya.biaya', 'detail_biaya.bulan')->where('id_siswa', $siswa->id_siswa)->where('id_tagihan_biaya', $id_tagihan_biaya)->where('is_tagih', 1)->where('is_request', 0)->first();
                $tagihan_biaya->is_request = 1;
                $tagihan_biaya->save();
                
                if($nomor == 1){
                    if($tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4){
                        $trs_keterangan = $tagihan_biaya->detail_biaya->biaya->nm_biaya.' '.$tagihan_biaya->detail_biaya->bulan->nm_bulan;
                    }else{
                        $trs_keterangan = $tagihan_biaya->detail_biaya->biaya->nm_biaya;
                    }
                }else{
                    if($tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4){
                        $trs_keterangan .= ', '.$tagihan_biaya->detail_biaya->biaya->nm_biaya.' '.$tagihan_biaya->detail_biaya->bulan->nm_bulan;
                    }else{
                        $trs_keterangan .= ', '.$tagihan_biaya->detail_biaya->biaya->nm_biaya;
                    }
                }

                $pembayaran_trs_detail = new PembayaranTrsDetail;
                $pembayaran_trs_detail->id_pembayaran_trs_detail = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pembayaran_trs_detail->id_pembayaran_trs = $pembayaran_trs->id_pembayaran_trs;
                $pembayaran_trs_detail->id_tagihan_biaya = $tagihan_biaya->id_tagihan_biaya;
                $pembayaran_trs_detail->besar_pembayaran = $tagihan_biaya->besar_biaya;
                $pembayaran_trs_detail->save();

                $subtotal_pembayaran += $tagihan_biaya->besar_biaya;
                $nomor++;

                if($tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4){
                    $item_title = $tagihan_biaya->detail_biaya->biaya->nm_biaya.' '.$tagihan_biaya->detail_biaya->bulan->nm_bulan;
                }else{
                    $item_title = $tagihan_biaya->detail_biaya->biaya->nm_biaya;
                }

                $items[] = array(
                    "name" => $item_title,
                    "sku" => $tagihan_biaya->id_jenis_detail_biaya,
                    "qty" => 1,
                    "unitPrice" => $tagihan_biaya->besar_biaya,
                    "desc" => $tagihan_biaya->detail_biaya->biaya->nm_biaya
                );
            }

            
            $pembayaran_trs->besar_pembayaran = $subtotal_pembayaran;
            $pembayaran_trs->keterangan = $trs_keterangan;
            $pembayaran_trs->save();

            $params = array(
                'callback' => url('payment/callback/'.$pembayaran_trs->id_pembayaran_trs),
                'listener' => url('payment/notification/'.$pembayaran_trs->id_pembayaran_trs),
                'order_id' => $pembayaran_trs->nomor_transaksi,
                'usr_phone' => $wali_murid_phone,
                'usr_email' => $wali_murid_email,
                'usr_name' => $tagihan_biaya->siswa->pengguna->nm_pengguna,
                'items' => $items,
                'amount' => $pembayaran_trs->besar_pembayaran,
                'exp_date' => $now->addDays(1)->format('YmdHis'),
            );
            
            $return_array = $winpay->checkout($input->payment_channel, $params);

            $url = $return_array->data->url_payment;
            $parts = parse_url($url);
            parse_str($parts['query'], $query);

            $pembayaran_trs->token = $query['payid'];
            $pembayaran_trs->payment_channel = $return_array->data->payment_method;
            $pembayaran_trs->payment_code = $return_array->data->payment_method_code;
            $pembayaran_trs->fee_admin = $return_array->data->fee_admin;
            $pembayaran_trs->save();

            DB::commit();

            return response()->json([
                'status' 	=> 202,
                'message' => 'Success',
                'path' => 'keuangan/tagihan',
                'data' => json_encode($return_array)
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => 'Terdapat error '
            ]);
        }
    }

    public function generateNumberTransaction($tanggal_transaksi, $kode_sekolah){
        $tanggal_totime = strtotime($tanggal_transaksi);

        $tahun = date("Y", $tanggal_totime);
        $bulan = date("m", $tanggal_totime);

        $max_nomor_transaksi = DB::select('SELECT MAX(nomor_transaksi) AS maxID FROM `pembayaran_trs`')[0]->maxID; 
        if ($max_nomor_transaksi == '') { 
            $nomor_transaksi = $tahun . "" . $bulan . "000001"; 
        } else { 
            $max_id = substr($max_nomor_transaksi, 0, 12); 
            $nomor_urut = (int) substr($max_id, 6, 6); 
            $nomor_bulan = (int) substr($max_id, 4, 2); 
            if ($nomor_bulan != $bulan) { 
                $nomor_urut = 1; 
            } else {
                $nomor_urut++;
            } 
            $nomor_transaksi = $tahun . "" . $bulan . sprintf("%06s", $nomor_urut) . "-" .$kode_sekolah;
        }
        return $nomor_transaksi;
    }

}