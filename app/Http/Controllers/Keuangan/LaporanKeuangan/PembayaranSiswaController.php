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
        $list_data = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna');

        if (!empty($input->start_date) && !empty($input->end_date)) {
            $list_data = $list_data->whereBetween('tgl_pembayaran', [$input->start_date, $input->end_date]);
        }

        $temp_list_data = $list_data->get();
        $total = $temp_list_data->sum('besar_pembayaran');

        return Datatables::of($list_data)
                ->addColumn('tanggal_bayar', function ($item) {
                    return date_format(date_create($item->tgl_pembayaran), "d M Y H:i").' WIB';
                })
                ->with('total', number_format($total))
                ->make(true);
    }
}
