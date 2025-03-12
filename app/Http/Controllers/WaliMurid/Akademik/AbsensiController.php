<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Bulan;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Siswa;

class AbsensiController extends Controller
{
    public function viewLihatAbsensi(Request $request, $id_bulan = null, $tahun = null){
        # code..
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);
    
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

        $siswa = Siswa::where('id_pengguna',$data_anak_murid_aktif->id_pengguna)->first();

    	return view('siswa/akademik/lihat-absensi/view-lihat-absensi',compact('auth_data','data_bulan','bulan','tahun','dates','siswa'));

    }
}
