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

        $now = Carbon::today(env('APP_TIMEZONE', 'Asia/Jakarta'));
        $id_tahun = $now->year;
        
        $data = json_decode($this->dataPembayaranSiswaTahunan($request, $id_tahun)->getContent());

        $list_data = $data->listData;
        $total = $data->total;

        return view('keuangan/laporan-keuangan/pembayaran-siswa-tahunan/view-pembayaran-siswa-tahunan', compact('auth_data','list_data','total','id_tahun'));
    }

    public function dataPembayaranSiswaTahunan(Request $request, $year = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PembayaranBiaya::with('tagihan_biaya.siswa.kelas.jurusan');
        
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
                'tgl_bayar' => $value->tgl_pembayaran,
                'id_siswa' => $value->tagihan_biaya->siswa->id_siswa
            ];
        }
        // dd($list_data);
        $groupedList = collect($list_data)->groupBy(function($item, $key){
            return 'Kelas '.$item['kelas'].' | Jurusan '.$item['jurusan'];
        });
        $listGrup = $groupedList->map(function($row){
            $siswa = collect($row)->groupBy('id_siswa');
            return '('.$siswa->count('*').' Siswa) Rp'.number_format($row->sum('jumlah_pembayaran'));
        });
        
        $total = '('.collect($list_data)->groupBy('id_siswa')->count('*').' Siswa) Rp'.number_format($temp_list_data->sum('besar_pembayaran'));
        
        $data = [
            'listData' => $listGrup,
            'total' => $total
        ];
        return response()->json($data); 
    }
}
