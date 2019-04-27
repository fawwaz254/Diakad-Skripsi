<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PemilikSarpras as PemilikSarpras;
use App\Models\Ruangan as Ruangan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class PemilikSarprasController extends BaseController{

    public function viewPemilikSarpras(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sarana-prasarana/data-sarpras-ruangan/pemilik-sarpras/view-pemilik-sarpras',compact('auth_data'));

    }

    public function addPemilikSarpras(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_pemilik_sarpras = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sarana-prasarana/data-sarpras-ruangan/pemilik-sarpras/add-pemilik-sarpras',compact('auth_data','id_pemilik_sarpras'));

    }

    public function editPemilikSarpras($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_pemilik_sarpras = LibDataSarpras::fetchDataPemilikSarpras($auth_data, $id);

        return view('sarana-prasarana/data-sarpras-ruangan/pemilik-sarpras/edit-pemilik-sarpras',compact('auth_data','data_pemilik_sarpras'));

    }

    public function datatablesPemilikSarpras(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataSarpras::fetchDataPemilikSarpras($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pemilik_sarpras
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionPemilikSarpras(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'kode_pemilik_sarpras' => 'required',
            'nm_pemilik_sarpras' => 'required'
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
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $pemilikSarpras                        = new PemilikSarpras;
                $pemilikSarpras->id_pemilik_sarpras    = $id;
                $pemilikSarpras->kode_pemilik_sarpras  = $input->kode_pemilik_sarpras;
                $pemilikSarpras->nm_pemilik_sarpras    = $input->nm_pemilik_sarpras;
                $pemilikSarpras->id_sekolah            = $input->auth_data->pengguna->id_sekolah;
                $pemilikSarpras->created_by            = $input->auth_data->pengguna->id_pengguna;
                $pemilikSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-ruangan/pemilik-sarpras',
                    'message' => 'Save Pemilik Sarpras successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $pemilikSarpras                        = PemilikSarpras::find($id);
                $pemilikSarpras->kode_pemilik_sarpras  = $input->kode_pemilik_sarpras;
                $pemilikSarpras->nm_pemilik_sarpras    = $input->nm_pemilik_sarpras;
                $pemilikSarpras->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $pemilikSarpras->updated_at            = $now;
                $pemilikSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-ruangan/pemilik-sarpras',
                    'message' => 'Update Pemilik Sarpras successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($ruangan = Ruangan::where('id_pemilik_sarpras',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Pemilik Sarpras'
                    ]; 
                }
                else{
                    // make object to find id
                    $pemilikSarpras               = PemilikSarpras::find($id);
                    $pemilikSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $pemilikSarpras->save();

                    $pemilikSarpras->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Pemilik Sarpras successfully'
                    ];
                }
            }
        }
    }


}