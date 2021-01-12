<?php

namespace App\Http\Controllers\Humas\KegiatanHarian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Bulan;
use App\Models\KegiatanHarianPertanyaan;
use App\Models\KegiatanHarianJawaban;
use App\Models\KegiatanHarianKategori;
use App\Models\Pengguna;
use App\Models\PengisianKegiatanHarian;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Excel;
use Session;
use Validator;

class RekapKesehatanController extends BaseController{

    public function viewRekapKesehatan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('humas/kegiatan-harian/rekap-kesehatan/view-rekap-kesehatan',compact('auth_data'));
    }

    public function viewRekapFormKesehatan(Request $request, $id_bulan = null, $tahun = null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if(empty($id_bulan)){
            $id_bulan = $now->month;
        }

        if(empty($tahun)){
            $tahun = $now->year;
        }
        
        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_pengguna = Pengguna::whereIn('status_join_table', [1,2])->orderBy('nm_pengguna')->get();

        $data_pengisian = PengisianKegiatanHarian::whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_pengguna->pluck('id_pengguna'))->get();

        return view('humas/kegiatan-harian/rekap-kesehatan/view-rekap-kesehatan-guru-tendik',compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_pengguna', 'data_pengisian', 'tahun'));
    }

    public function downloadRekapFormKesehatan(Request $request, $id_bulan = null, $tahun = null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if(empty($id_bulan)){
            $id_bulan = $now->month;
        }

        if(empty($tahun)){
            $tahun = $now->year;
        }
        
        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_pengguna = Pengguna::whereIn('status_join_table', [1,2])->orderBy('nm_pengguna')->get();

        $data_pengisian = PengisianKegiatanHarian::whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_pengguna->pluck('id_pengguna'))->get();

        return Excel::create('Download Data Rekap Kesehatan Guru & Tendik Bulan '. $bulan->nm_bulan, function ($excel) use ($auth_data, $dates, $data_bulan, $bulan, $data_pengguna, $data_pengisian) {
            $excel->sheet('New sheet', function ($sheet) use ($auth_data, $dates, $data_bulan, $bulan, $data_pengguna, $data_pengisian) {
                $sheet->loadView('humas/kegiatan-harian/rekap-kesehatan/download-rekap-kesehatan-guru-tendik', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_pengguna', 'data_pengisian', 'tahun'));
            });
        })->download('xls');
    }

}