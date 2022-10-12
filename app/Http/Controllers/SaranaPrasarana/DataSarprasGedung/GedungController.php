<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasGedung;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Gedung as Gedung;
use App\Models\Ruangan as Ruangan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class GedungController extends BaseController{

    public function viewGedung(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sarana-prasarana/data-sarpras-gedung/gedung/view-gedung',compact('auth_data'));

    }

    public function addGedung(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_gedung = LibDataSarpras::fetchDataJenisGedung($auth_data);
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_gedung = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sarana-prasarana/data-sarpras-gedung/gedung/add-gedung',compact('auth_data','data_jenis_gedung','id_gedung'));

    }

    public function editGedung($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_gedung = LibDataSarpras::fetchDataJenisGedung($auth_data);

        $data_gedung = LibDataSarpras::fetchDataGedung($auth_data, $id);

        return view('sarana-prasarana/data-sarpras-gedung/gedung/edit-gedung',compact('auth_data','data_jenis_gedung','data_gedung'));

    }

    public function datatablesGedung(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataSarpras::fetchDataGedung($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_gedung
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionGedung(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jenis_gedung' => 'required',
            'kode_gedung' => 'required',
            'nm_gedung' => 'required',
            'lokasi_gedung' => 'required',
            'deskripsi_gedung' => 'required'
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

                $gedung                         = new Gedung;
                $gedung->id_gedung              = $id;
                $gedung->id_jenis_gedung        = $input->id_jenis_gedung;
                $gedung->kode_gedung            = $input->kode_gedung;
                $gedung->nm_gedung              = $input->nm_gedung;
                $gedung->lokasi_gedung          = $input->lokasi_gedung;
                $gedung->deskripsi_gedung       = $input->deskripsi_gedung;
                $gedung->id_sekolah             = $input->auth_data->pengguna->id_sekolah;
                $gedung->created_by             = $input->auth_data->pengguna->id_pengguna;
                $gedung->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-gedung/gedung',
                    'message' => 'Save Gedung Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $gedung                         = Gedung::find($id);
                $gedung->id_jenis_gedung        = $input->id_jenis_gedung;
                $gedung->kode_gedung            = $input->kode_gedung;
                $gedung->nm_gedung              = $input->nm_gedung;
                $gedung->lokasi_gedung          = $input->lokasi_gedung;
                $gedung->deskripsi_gedung       = $input->deskripsi_gedung;
                $gedung->updated_by             = $input->auth_data->pengguna->id_pengguna;
                $gedung->updated_at             = $now;
                $gedung->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-gedung/gedung',
                    'message' => 'Update Gedung Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($ruangan = Ruangan::where('id_gedung',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Gedung'
                    ]; 
                }
                else{
                    // make object to find id
                    $gedung               = Gedung::find($id);
                    $gedung->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $gedung->save();

                    $gedung->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Gedung Successfully'
                    ];
                }
            }
        }
    }


}