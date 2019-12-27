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

class TagihanController extends BaseController
{
    public function viewTagihan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('siswa/keuangan/tagihan/view-tagihan', compact('auth_data'));
    }

    public function datatablesTagihan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        /*$nmeng = array('January', 'February', 'March', '');
        $nmtur = array('Januari', 'Februari', 'Maret, '');
        $dt = 'January 22, 2012';
        $dt = str_ireplace($nmeng, $nmtur, $dt);*/

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchTagihanSiswa($auth_data, $data_anak_murid_aktif->id_pengguna);

        return Datatables::of($list_data)
                ->addColumn('biaya_sekolah', function ($item) {
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran." ".$item->nm_semester.")";
                })
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
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
                ->addColumn('denda_biaya', function ($item) {
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function ($item) {
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->make(true);
    }
}
