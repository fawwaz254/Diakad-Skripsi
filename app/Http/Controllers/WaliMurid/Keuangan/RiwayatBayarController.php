<?php

namespace App\Http\Controllers\WaliMurid\Keuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class RiwayatBayarController extends BaseController
{
    public function viewRiwayatBayar(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('wali-murid/keuangan/riwayat-bayar/view-riwayat-bayar', compact('auth_data'));
    }

    public function datatablesRiwayatBayar(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        /*$nmeng = array('January', 'February', 'March', '');
        $nmtur = array('Januari', 'Februari', 'Maret, '');
        $dt = 'January 22, 2012';
        $dt = str_ireplace($nmeng, $nmtur, $dt);*/

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchPembayaranSiswa($auth_data, $data_anak_murid_aktif->id_pengguna);

        return Datatables::of($list_data)
                ->addColumn('biaya_sekolah', function ($item) {
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran_biaya." ".$item->nm_semester_biaya.")";
                })
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran_biaya." ".$item->nm_semester_biaya;
                })
                ->addColumn('jenis_biaya', function($item){
                    if($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." ".$item->nm_bulan;
                    }
                    else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function ($item) {
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('denda_biaya', function ($item) {
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function ($item) {
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->addColumn('nm_pengguna', function ($item) {
                    if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    } elseif (! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;
                    } elseif (! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna;
                    }
                })
                ->addColumn('tgl_pembayaran', function ($item) {
                    return strftime("%d %b %Y", strtotime($item->tgl_pembayaran));
                })
                ->addColumn('semester_bayar', function ($item) {
                    return $item->tahun_ajaran_bayar." ".$item->nm_semester_bayar;
                })
                ->addColumn('nm_bank', function ($item) {
                    if (! empty($item->nm_bank)) {
                        return $item->nm_bank_via." ".$item->nm_bank;
                    } else {
                        return "-";
                    }
                })
                ->make(true);
    }
}
