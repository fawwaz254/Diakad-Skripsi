<?php

namespace App\Http\Controllers\SumberDaya\DataSumberDaya;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Pengguna as Pengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class StatusAktifTendikController extends BaseController{

    public function viewStatusAktifTendik(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sumber-daya/data-sumber-daya/status-aktif-tendik/view-status-aktif-tendik',compact('auth_data'));

    }

    public function addStatusAktifTendik(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_status_pengguna = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sumber-daya/data-sumber-daya/status-aktif-tendik/add-status-aktif-tendik',compact('auth_data','id_status_pengguna'));

    }

    public function editStatusAktifTendik($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_status_pengguna = LibDataSumberDaya::fetchDataStatusAktifTendik($auth_data, $id);

        return view('sumber-daya/data-sumber-daya/status-aktif-tendik/edit-status-aktif-tendik',compact('auth_data','data_status_pengguna'));

    }

    public function datatablesStatusAktifTendik(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSumberDaya::fetchDataStatusAktifTendik($auth_data);

        return Datatables::of($list_data)
                ->addColumn('aktif_status_pengguna', function($item){
                    if($item->aktif_status_pengguna == 0) {
                        return "Keluar/Non-Aktif";
                    }
                    elseif($item->aktif_status_pengguna == 1) {
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

    // Action POST
    public function actionStatusAktifTendik(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_status_pengguna'     => 'required',
            'aktif_status_pengguna'  => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $statusPengguna                         = new StatusPengguna;
                $statusPengguna->id_status_pengguna     = $id;
                $statusPengguna->status_join_table      = 1;
                $statusPengguna->nm_status_pengguna     = $input->nm_status_pengguna;
                $statusPengguna->aktif_status_pengguna  = $input->aktif_status_pengguna;
                $statusPengguna->id_sekolah             = $input->auth_data->pengguna->id_sekolah;
                $statusPengguna->created_by             = $input->auth_data->pengguna->id_pengguna;
                $statusPengguna->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/status-aktif-tendik',
                    'message' => 'Save Status Aktif Tendik Successfully'
                ];
            }
            elseif($mode == 'edit') {
                // make object to find id
                $statusPengguna                         = StatusPengguna::find($id);
                $statusPengguna->nm_status_pengguna     = $input->nm_status_pengguna;
                $statusPengguna->aktif_status_pengguna  = $input->aktif_status_pengguna;
                $statusPengguna->updated_by             = $input->auth_data->pengguna->id_pengguna;
                $statusPengguna->updated_at             = $now;
                $statusPengguna->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/status-aktif-tendik',
                    'message' => 'Update Status Aktif Tendik Successfully'
                ];
            }
            elseif($mode == 'delete') {
                if($pengguna = Pengguna::where('id_status_pengguna',$id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Status Aktif Tendik'
                    ]; 
                }
                else {
                    // make object to find id
                    $statusPengguna               = StatusPengguna::find($id);
                    $statusPengguna->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $statusPengguna->save();

                    $statusPengguna->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Status Aktif Tendik Successfully'
                    ];
                }
            }
        }
    }

}