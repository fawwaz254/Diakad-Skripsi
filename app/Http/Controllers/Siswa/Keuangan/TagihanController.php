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

        $winpay = new Winpay;
        $grup_payment_channel = $winpay->getPaymentChannel();

        $pembayaran_aktif = PembayaranTrs::with('siswa', 'siswa.pengguna')->where('id_siswa', $siswa->id_siswa)->where('status_pembayaran', 0)->get();

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

}