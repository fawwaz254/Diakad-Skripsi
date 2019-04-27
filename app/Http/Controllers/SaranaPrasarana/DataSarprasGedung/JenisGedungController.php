<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasGedung;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JenisGedung as JenisGedung;
use App\Models\Gedung as Gedung;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class JenisGedungController extends BaseController{

    public function viewJenisGedung(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sarana-prasarana/data-sarpras-gedung/jenis-gedung/view-jenis-gedung',compact('auth_data'));

    }

    public function addJenisGedung(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jenis_gedung = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sarana-prasarana/data-sarpras-gedung/jenis-gedung/add-jenis-gedung',compact('auth_data','id_jenis_gedung'));

    }

    public function editJenisGedung($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_gedung = LibDataSarpras::fetchDataJenisGedung($auth_data, $id);

        return view('sarana-prasarana/data-sarpras-gedung/jenis-gedung/edit-jenis-gedung',compact('auth_data','data_jenis_gedung'));

    }

    public function datatablesJenisGedung(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataSarpras::fetchDataJenisGedung($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_jenis_gedung
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionJenisGedung(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jenis_gedung' => 'required'
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

                $jenisGedung                        = new JenisGedung;
                $jenisGedung->id_jenis_gedung       = $id;
                $jenisGedung->nm_jenis_gedung       = $input->nm_jenis_gedung;
                $jenisGedung->id_sekolah            = $input->auth_data->pengguna->id_sekolah;
                $jenisGedung->created_by            = $input->auth_data->pengguna->id_pengguna;
                $jenisGedung->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-gedung/jenis-gedung',
                    'message' => 'Save Jenis Gedung successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $jenisGedung                        = JenisGedung::find($id);
                $jenisGedung->nm_jenis_gedung       = $input->nm_jenis_gedung;
                $jenisGedung->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $jenisGedung->updated_at            = $now;
                $jenisGedung->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-gedung/jenis-gedung',
                    'message' => 'Update Jenis Gedung successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($gedung = Gedung::where('id_jenis_gedung',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jenis Gedung'
                    ]; 
                }
                else{
                    // make object to find id
                    $jenisGedung               = JenisGedung::find($id);
                    $jenisGedung->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jenisGedung->save();

                    $jenisGedung->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jenis Gedung successfully'
                    ];
                }
            }
        }
    }


}