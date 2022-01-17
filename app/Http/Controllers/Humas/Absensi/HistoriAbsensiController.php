<?php

namespace App\Http\Controllers\Humas\Absensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PresensiPengguna;
use App\Models\Sekolah;
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
            $hasil[$key]['date'] = $value->format('Y-m-d');
            $hasil[$key]['tanggal'] = $value->format('d');
            $hasil[$key]['hari'] = $value->format('l');
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '-';
            $hasil[$key]['notes'] = '-';
            $hasil[$key]['id_presensi_pengguna'] = '-';
      


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

                if($attendance->id_presensi_pengguna){
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

               

            }

        }

    	return view('humas/absensi/histori-absensi/view-histori-absensi',compact('auth_data','id_pengguna','start_date','end_date','dates','guru','hasil'));
    }




    public function editHistoriAbsensi($id_presensi_pengguna, $start_date, $end_date)
{
    if($id_presensi_pengguna == '-')
    {
        return view('humas/absensi/histori-absensi/new-histori-absensi', compact('id_presensi','start_date','end_date'));}

 
  

    $presences = PresensiPengguna::where('id_presensi_pengguna',$id_presensi_pengguna)->first();




   return view('humas/absensi/histori-absensi/edit-histori-absensi', compact('presences','start_date','end_date'));
}


public function updateHistoriAbsensi(Request $request, $id_presensi_pengguna, $start_date, $end_date){
    

    $rules = [

        'check_out' => 'required',
        'check_in' => 'required',
        'status' => 'required',
        'notes' => 'required'

    ];

  

    $validatedData = $request->validate($rules);


    PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)
      ->update($validatedData);

      $ad =  PresensiPengguna::where('id_presensi_pengguna', $request->id_presensi_pengguna)->first();
    //   $ad->id_pengguna;
     


      return redirect('/humas#absensi/histori-absensi/'. $ad->id_pengguna .'/'. $start_date  .'/'. $end_date);


}

public function deleteHistoriAbsensi($id_presensi_pengguna){
    $ad =  PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
    PresensiPengguna::find($id_presensi_pengguna)->delete();


    //  return redirect('/humas#absensi/histori-absensi/'. $ad->id_pengguna  );
    // return response()->json([

    //     'success' => 'Record deleted successfully!'

    // ]);



    // return redirect('/humas#absensi/histori-absensi/'. $ad->id_pengguna  );
    // return response()->json([

    //     'success' => 'Record deleted successfully!'

    // ]);

    // if($hapus){
    //     return response()->json([
    //         'status' => 'success'
    //     ]);
    // }else{
    //     return response()->json([
    //         'status' => 'error'
    //     ]);
    // }


}



public function izinHistoriAbsensi($id_pengguna, $date, $start_date, $end_date){
   

    return view('humas/absensi/histori-absensi/new-histori-absensi', compact('id_pengguna','date','start_date','end_date'));

}

public function tambahHistoriAbsensi(Request $request, $id_pengguna, $date, $start_date, $end_date){

    // $rules = [

   
    //     'status' => 'required',
    //     'notes' => 'required',
    //     'date'  => 'required'

    // ];
    // $rules['id_presensi_pengguna'] =  $prefix.strtotime($now).uniqid();
    // $rules['date'] = $date;
    // $rules['id_pengguna'] = $id_pengguna;

    // $validatedData = $request->validate($rules);

    // PresensiPengguna::create($validatedData);
    $now = Carbon::now(env('APP_TIMEZONE', ''));
    $prefix = Sekolah::first()->prefix;
$uid = $prefix.strtotime($now).uniqid();
    PresensiPengguna::create([
      
        'id_presensi_pengguna'  => $uid,
        'id_pengguna'   => $id_pengguna,
        'date'  => $date,
        'status'    => $request->status,
        'notes' => $request->notes,
    ]);    

     


      return redirect('/humas#absensi/histori-absensi/'. $id_pengguna .'/'. $start_date  .'/'. $end_date);


}

}