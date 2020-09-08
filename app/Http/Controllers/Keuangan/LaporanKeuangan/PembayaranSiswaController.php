<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PembayaranBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;

class PembayaranSiswaController extends BaseController
{
    public function viewPembayaranSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/laporan-keuangan/pembayaran-siswa/view-pembayaran-siswa', compact('auth_data'));
    }

    public function datatablesPembayaranSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna')->with(['tagihan_biaya.detail_biaya' => function ($q) {
            $q->with('biaya');
        }]);

        if (!empty($input->start_date) && !empty($input->end_date)) {
            $list_data = $list_data->whereBetween('tgl_pembayaran', [$input->start_date.' 00:00:00', $input->end_date.' 23:59:59']);
        }

        $temp_list_data = $list_data->get();
        $total = $temp_list_data->sum('besar_pembayaran');

        return Datatables::of($list_data)
                ->addColumn('tanggal_bayar', function ($item) {
                    return date_format(date_create($item->tgl_pembayaran), "d M Y H:i").' WIB';
                })
                ->addColumn('keterangan_bayar', function ($item) {
                    if(!empty($item->tagihan_biaya->detail_biaya->id_bulan)){
                        return $item->tagihan_biaya->detail_biaya->biaya->nm_biaya.' bulan '.Carbon::createFromFormat('m', $item->tagihan_biaya->detail_biaya->id_bulan)->format('F');
                    }else{
                        return $item->tagihan_biaya->detail_biaya->biaya->nm_biaya;
                    }
                })
                ->with('total', number_format($total))
                ->make(true);
    }
}
