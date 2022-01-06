<?php

namespace App\Http\Controllers\Humas\Absensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PresensiPengguna;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class HistoriAbsensiController extends BaseController{

    public function viewHistoriAbsensi(Request $request,$id_pengguna = null, $start_date = null, $end_date = null){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(empty($start_date) || empty($end_date)){
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);
        $guru = LibGuru::fetchDataAllGuru($auth_data);

        $presences = PresensiPengguna::where('id_pengguna',$id_pengguna)->whereBetween('date', [$start_date, $end_date])->get();

        $hasil = [];

        foreach($dates as $key => $value){

            $hasil[$key]['tanggal'] = $value->format('d');
            $hasil[$key]['hari'] = $value->format('l');
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '-';
            $hasil[$key]['notes'] = '-';

            $attendance = $presences->where('date',$value->format('Y-m-d'))->first();

            if($attendance){

                if($attendance->check_in){
                    $hasil[$key]['check_in'] = $attendance->check_in;
                }

                if($attendance->check_out){
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }

                if($attendance->status){
                    $hasil[$key]['status'] = $attendance->status;
                }

                if($attendance->notes){
                    $hasil[$key]['notes'] = $attendance->notes;
                }

            }

        }

    	return view('humas/absensi/histori-absensi/view-histori-absensi',compact('auth_data','id_pengguna','start_date','end_date','dates','guru','hasil'));
    }

}