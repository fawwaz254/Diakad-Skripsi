<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PembayaranBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class PembayaranSiswaTahunanController extends BaseController
{
    public function viewPembayaranSiswaTahunan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/laporan-keuangan/pembayaran-siswa-tahunan/view-pembayaran-siswa-tahunan', compact('auth_data'));
    }

    public function dataPembayaranSiswaTahunan(Request $request, $year = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PembayaranBiaya::with('tagihan_biaya.kelas.jurusan');

        if ($year !== null) {
            $list_data = $list_data->whereYear('tgl_pembayaran', $year);
        }

        $temp_list_data = $list_data->get();
        $list_data = [];
        foreach ($temp_list_data as $key => $value){
            $list_data[] = [
                'jumlah_pembayaran' => $value->besar_pembayaran,
                'kelas' => $value->tagihan_biaya->kelas->tingkat,
                'jurusan' => $value->tagihan_biaya->kelas->jurusan->nm_jurusan,
                'tgl_bayar' => $value->tgl_pembayaran
            ];
        }
        $total = $temp_list_data->sum('besar_pembayaran');
        $groupedList = collect($list_data)->groupBy(function($item, $key){
            return $item['kelas'] . ' ' . $item['jurusan'];
        });
        $listGrup = $groupedList->map(function($row){
            return number_format($row->sum('jumlah_pembayaran'));
        });
        
        $data = [
            'listData' => $listGrup,
            'total' => number_format($total)
        ];
        return response()->json($data); 
    }
}
