<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Pengguna as Pengguna;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class StatusSiswaController extends BaseController{

    public function viewStatusSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/status-siswa/view-status-siswa',compact('auth_data'));

    }

    public function addStatusSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_status_pengguna = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/status-siswa/add-status-siswa',compact('auth_data','id_status_pengguna'));

    }

    public function editStatusSiswa($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_status_pengguna = $this->fetchDataStatusSiswa($auth_data, $id);

        return view('pendidikan/data-akademik/status-siswa/edit-status-siswa',compact('auth_data','data_status_pengguna'));

    }

    public function datatablesStatusSiswa(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataStatusSiswa($auth_data);

        return Datatables::of($list_data)
                ->addColumn('status_aktif', function($item){
                    if($item->aktif_status_pengguna == 0){
                        return "Keluar/Non-Aktif";
                    }
                    else{
                        return "Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_status_pengguna
                    );
                    return $data;
                })
                ->make(true);
    }

    public function fetchDataStatusSiswa($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $statusPengguna = StatusPengguna::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->where('status_join_table','=',3)->orderBy('aktif_status_pengguna', 'desc')->orderBy('nm_status_pengguna', 'asc')->get();
        }
        // get mode edit
        else{
            $statusPengguna = StatusPengguna::where('id_status_pengguna','=',$id)->first();
        }

        return $statusPengguna;
    }

    // Action POST
    public function actionStatusSiswa(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_status_pengguna' => 'required',
            'aktif_status_pengguna' => 'required',
            //'kode_status_pengguna' => 'required',
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

            if($mode == 'add') {
                $statusPengguna = StatusPengguna::where('kode_status_pengguna','=',$input->kode_status_pengguna)
                        ->where('status_join_table','=',3)
                        ->where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                        ->first();

                if($statusPengguna) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Status Siswa!'
                    ];
                }
                else {        
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    
                    $statusPengguna                         = new StatusPengguna;
                    $statusPengguna->id_status_pengguna     = $id;
                    $statusPengguna->nm_status_pengguna     = $input->nm_status_pengguna;
                    $statusPengguna->aktif_status_pengguna  = $input->aktif_status_pengguna;
                    $statusPengguna->kode_status_pengguna   = $input->kode_status_pengguna;
                    $statusPengguna->status_join_table      = 3;
                    $statusPengguna->id_sekolah             = $input->auth_data->pengguna->id_sekolah;
                    $statusPengguna->created_by             = $input->auth_data->pengguna->id_pengguna;
                    $statusPengguna->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-akademik/status-siswa',
                        'message' => 'Save Status Siswa successfully'
                    ];
                }
            }
            elseif($mode == 'edit'){
                // make object to find id
                $statusPengguna                         = StatusPengguna::find($id);
                $statusPengguna->nm_status_pengguna     = $input->nm_status_pengguna;
                $statusPengguna->aktif_status_pengguna  = $input->aktif_status_pengguna;
                $statusPengguna->updated_by             = $input->auth_data->pengguna->id_pengguna;
                $statusPengguna->updated_at             = $now;
                $statusPengguna->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/status-siswa',
                    'message' => 'Update Status Siswa successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($pengguna = Pengguna::where('id_status_pengguna',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Status Pengguna'
                    ]; 
                }
                else{
                    // make object to find id
                    $statusPengguna               = StatusPengguna::find($id);
                    $statusPengguna->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $statusPengguna->save();

                    $statusPengguna->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Status Siswa successfully'
                    ];
                }
            }
        }
    }

}