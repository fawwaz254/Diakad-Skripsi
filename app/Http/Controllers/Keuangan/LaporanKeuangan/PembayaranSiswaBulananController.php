<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\PembayaranBiaya;
use App\Models\Bulan;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;

class PembayaranSiswaBulananController extends BaseController
{
    public function viewPembayaranSiswaBulanan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $bulan = Bulan::orderBy('id_bulan')->get();

        $now = Carbon::today(env('APP_TIMEZONE', 'Asia/Jakarta'));
		$id_bulan = $now->month;
		$id_tahun = $now->year;

        $data = $this->getPembayaranSiswaBulanan($id_bulan,$id_tahun);
        $grup = $data['grup'];
        $total = $data['total'];

        return view('keuangan/laporan-keuangan/pembayaran-siswa-bulanan/view-pembayaran-siswa-bulanan', compact('auth_data','bulan','grup','total','id_bulan','id_tahun'));
    }

    public function dataPembayaranSiswaBulanan(Request $request){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = $this->getPembayaranSiswaBulanan($input->bulan,$input->tahun);
        $grup = $data['grup'];
        $total = $data['total'];

        return response()->json([
        	'grup'=>$grup,
        	'total'=>$total
        ]);

    }

    public function getPembayaranSiswaBulanan($bulan,$tahun){

    	$data = PembayaranBiaya::with('tagihan_biaya.siswa.kelas.jurusan')
				->whereMonth('tgl_pembayaran',$bulan)
				->whereYear('tgl_pembayaran',$tahun)
				->get();

        $collect = array();

        foreach ($data as $key => $value) {
        	$collect[$key]['besar_pembayaran'] = $value->besar_pembayaran;
        	$collect[$key]['tingkat'] = $value->tagihan_biaya->siswa->kelas->tingkat;
        	$collect[$key]['jurusan'] = $value->tagihan_biaya->siswa->kelas->jurusan->nm_jurusan;
        }

        $collect = collect($collect);

        $grup = $collect->groupBy(function ($item, $key){

		            return 'Kelas '.$item['tingkat'].' | Jurusan '.$item['jurusan'];

		        })->map(function ($row) {

                    return '('.$row->count('*').' Siswa) Rp'.number_format($row->sum('besar_pembayaran'));

          		});

        $total = '('.$collect->count('*').' Siswa) Rp'.number_format($collect->sum('besar_pembayaran'));

        $callback['grup'] = $grup;
        $callback['total'] = $total;

        return $callback;


    }

}
