<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Libraries\LibGlobal;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\WinpayPHP\Winpay;

use App\Models\PembayaranBiaya;
use App\Models\PembayaranTrs;
use App\Models\PembayaranTrsDetail;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TagihanBiaya;

use Auth;
use DB;
use Carbon\Carbon;
use Validator;
use Yajra\Datatables\Datatables;

class PembayaranOnlineController extends BaseController
{
    public function viewIndex(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/sim/pembayaran-online/view-pembayaran-online', compact('auth_data'));
    }

    public function viewAdd(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $winpay = new Winpay;
        $grup_payment_channel = $winpay->getPaymentChannel();

        return view('keuangan/sim/pembayaran-online/add-pembayaran-online', compact('auth_data', 'data_kelas', 'grup_payment_channel'));
    }

    public function viewDetail(Request $request, $id)
    {
        $item = PembayaranTrs::find($id);

        $winpay = new Winpay;
        return redirect($winpay->getRedirectStatusPayment($item->payment_code, $item->token));
    }

    public static function datatablesTagihan($id_pengguna)
    {
        // get id_siswa
        $siswa = Siswa::where('id_pengguna', '=', $id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $tagihanBiaya = TagihanBiaya::select('tagihan_biaya.id_tagihan_biaya', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'jalur.nm_jalur', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"))
                                ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                                ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                                ->leftJoin('jalur', 'jalur.id_jalur', '=', 'biaya_sekolah.id_jalur')
                                ->join('kelompok_biaya', 'kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
                                ->join('semester', 'semester.id_semester', '=', 'biaya_sekolah.id_semester')
                                ->join('biaya', 'biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                                ->leftJoin('jenis_detail_biaya', 'jenis_detail_biaya.id_jenis_detail_biaya', '=', 'detail_biaya.id_jenis_detail_biaya')
                                ->leftJoin('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
                                ->where('tagihan_biaya.is_tagih', '=', 1)
                                ->where('tagihan_biaya.is_request', '=', 0)
                                ->where('tagihan_biaya.id_siswa', '=', $id_siswa)
                                ->orderBy('semester.kode_semester', 'asc')
                                ->orderBy('bulan.id_bulan', 'asc')
                                ->get();

        return Datatables::of($tagihanBiaya)
                ->addColumn('jenis_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function ($item) {
                    return "Rp".number_format($item->besar_biaya);
                })
                ->make(true);
    }

    public function datatables(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PembayaranTrs::with('siswa', 'siswa.pengguna');

        if (!empty($input->start_date) && !empty($input->end_date)) {
            $list_data = $list_data->whereBetween('created_at', [$input->start_date.' 00:00:00', $input->end_date.' 23:59:59']);
        }

        return Datatables::of($list_data)
                ->addColumn('status', function ($item) {
                    if($item->status_pembayaran == 0){
                        return 'Waiting for payment';
                    }else if($item->status_pembayaran == 1){
                        return 'Success';
                    }else if($item->status_pembayaran == 10){
                        return 'Expired';
                    }else{
                        return '';
                    }
                })
                ->addColumn('tanggal_bayar', function ($item) {
                    if(!empty($item->tgl_pembayaran)){
                        return date_format(date_create($item->tgl_pembayaran), "d M Y H:i").' WIB';
                    }else{
                        return '-';
                    }
                })
                ->addColumn('action', function ($item) {
                    if($item->status_pembayaran == 0){
                        return url('payment/detail/'.$item->id_pembayaran_trs);
                    }else{
                        return null;
                    }
                })
                ->make(true);
    }
    
    public function actionSave(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required',
            'payment_channel' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json([
                'status' 	=> 300,
                'message' => $validator->errors()->first(),
            ]);
        } 

        DB::beginTransaction();
        
        try {
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $siswa = Siswa::find($input->id_siswa);

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
                'path' => 'sim/pembayaran-online',
                'data' => json_encode($return_array)
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => 'Terdapat error '.$e->getMessage()
            ]);
        }
    }

    public function actionCallback(Request $request, $id)
    {
        $item = PembayaranTrs::find($id);

        $winpay = new Winpay;
        return redirect($winpay->getStatusUrl($item->payment_code, $item->token));
    }

    public function actionPayment(Request $request, $id_transaksi){
        $input = json_decode(file_get_contents('php://input'));

        $message = 'DECLINED';
        DB::beginTransaction();
        try {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $sekolah = Sekolah::orderBy('id_sekolah')->first();
            $semester_aktif = Semester::where('id_sekolah', $sekolah->id_sekolah)->where('is_aktif_semester', 1)->first();

            if($input->response_code == '00'){
                if($transaksi = PembayaranTrs::where('id_pembayaran_trs', $id_transaksi)->where('nomor_transaksi', $input->no_reff)->where('status_pembayaran', 0)->first()){
                    $transaksi->status_pembayaran = 1;
                    $transaksi->id_semester_bayar     = $semester_aktif->id_semester;
                    $transaksi->tgl_pembayaran        = $now;
                    $transaksi->save();

                    $tagihan_biaya = $transaksi->tagihan_biaya;
                    $tagihan_biaya->is_tagih = 0;
                    $tagihan_biaya->save();
                    
                    $id = $sekolah->prefix.strtotime($now).uniqid();
                    $pembayaran                            = new PembayaranBiaya;
                    $pembayaran->id_pembayaran_biaya       = $id;
                    $pembayaran->id_tagihan_biaya          = $tagihan_biaya->id_tagihan_biaya;
                    $pembayaran->id_semester_bayar         = $transaksi->id_semester_bayar;
                    $pembayaran->besar_pembayaran          = $transaksi->besar_pembayaran;
                    $pembayaran->tgl_pembayaran            = $transaksi->tgl_pembayaran;
                    $pembayaran->nomor_transaksi           = $transaksi->nomor_transaksi;
                    $pembayaran->keterangan                = $transaksi->keterangan;
                    $pembayaran->save();

                    $message = 'ACCEPTED';
                }
            }

            DB::commit();

            return $message;
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => 'Terdapat error'
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

    public function ajaxGetSiswaByKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $input->kelas);

        return $data_siswa;
    }
}
