<?php

namespace App\Http\Controllers\Akademik\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum as Kurikulum;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class AktivasiKurikulumController extends BaseController{

    public function viewAktivasiKurikulum(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/data-akademik/aktivasi-kurikulum/view-aktivasi-kurikulum',compact('auth_data'));

    }

    public function aktivasiKurikulum($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kurikulum = LibAkademik::fetchDataKurikulum($auth_data, $id);

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data, $data_kurikulum->id_jurusan);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $data_kurikulum->id_semester_mulai);

        // convert format date
        $berlaku_mulai = strftime( "%d %B %Y", strtotime($data_kurikulum->berlaku_mulai));
        $berlaku_sampai = strftime( "%d %B %Y", strtotime($data_kurikulum->berlaku_sampai));

        return view('akademik/data-akademik/aktivasi-kurikulum/action-aktivasi-kurikulum',compact('auth_data','data_jurusan','data_semester','data_kurikulum','berlaku_mulai','berlaku_sampai'));


    }

    public function datatablesAktivasiKurikulum(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibAkademik::fetchDataKurikulum($auth_data);

        return Datatables::of($list_data)
                ->addColumn('semester_mulai', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('berlaku_mulai', function($item){
                    return strftime( "%d %B %Y", strtotime($item->berlaku_mulai));
                })
                ->addColumn('berlaku_sampai', function($item){
                    return strftime( "%d %B %Y", strtotime($item->berlaku_sampai));
                })
                ->addColumn('status_aktif', function($item){
                    if($item->is_aktif == 0){
                        return "Non-Aktif";
                    }
                    else{
                        return "Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kurikulum
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionAktivasiKurikulum(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'is_aktif'            => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // mode Aktivasi
            if($mode == 'aktivasi'){
                // make object to find id
                $kurikulum                              = Kurikulum::find($id);
                $kurikulum->is_aktif                    = $input->is_aktif;
                $kurikulum->updated_by                  = $input->auth_data->pengguna->id_pengguna;
                $kurikulum->updated_at                  = $now;
                $kurikulum->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/aktivasi-kurikulum',
                    'message' => 'Update Aktivasi Kurikulum successfully'
                ];
            }
        }
    }


}
