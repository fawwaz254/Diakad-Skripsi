<?php

namespace App\Http\Controllers\Sekretariat\DataSekretariat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipPemilik as ArsipPemilik;
use App\Models\ArsipDokumen as ArsipDokumen;
use App\Models\UnitKerja as UnitKerja;

use Auth;
use DB;
use Session;
use Validator;

class DataPemilikController extends BaseController
{
    public function viewDataPemilik(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sekretariat/data-sekretariat/data-pemilik/view-data-pemilik',compact('auth_data'));
    }

    public function addDataPemilik(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $unit = UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return view('sekretariat/data-sekretariat/data-pemilik/add-data-pemilik',compact('auth_data','unit'));
    }

    public function editDataPemilik(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $unit 	= UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $arsip 	= ArsipPemilik::where('id_arsip_pemilik','=',$id)->first();

        return view('sekretariat/data-sekretariat/data-pemilik/edit-data-pemilik',compact('auth_data','unit','arsip'));
    }

    public function datatablesDataPemilik(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = ArsipPemilik::select('arsip_pemilik.id_arsip_pemilik','unit_kerja.nm_unit_kerja','arsip_pemilik.nm_arsip_pemilik','arsip_pemilik.id_unit_kerja')
        	->join('unit_kerja','unit_kerja.id_unit_kerja','=','arsip_pemilik.id_unit_kerja')
        	->where('arsip_pemilik.id_sekolah','=',$auth_data->pengguna->id_sekolah);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_arsip_pemilik
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionDataPemilik(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_arsip_pemilik' => 'required',
            'id_unit_kerja' => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // ACTION ADD
            if($mode == 'add') {
               $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $arsip 					= new ArsipPemilik;
                $arsip->id_arsip_pemilik	= $id;
                $arsip->nm_arsip_pemilik	= $input->nm_arsip_pemilik;
                $arsip->id_unit_kerja 	= $input->id_unit_kerja;
                $arsip->id_sekolah 		= $auth_data->pengguna->id_sekolah;
                $arsip->created_at 		= $now;
                $arsip->created_by		= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-pemilik',
                    'message' => 'Save Data Arsip Pemilik successfully'
                ];
            }
            elseif($mode == 'edit'){
            	$arsip 					= ArsipPemilik::find($id);
            	$arsip->nm_arsip_pemilik	= $input->nm_arsip_pemilik;
            	$arsip->id_unit_kerja	= $input->id_unit_kerja;
 				$arsip->updated_at 		= $now;
                $arsip->updated_by		= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-pemilik',
                    'message' => 'Save Data Pemilik successfully'
                ];
            }
            elseif($mode == 'delete'){
            	if(ArsipDokumen::where('id_arsip_pemilik','=',$id)->first()){
            		 return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Arsip Pemilik gagal'
                    ];
            	}else{
            		$arsip 	= ArsipPemilik::find($id);
            		$arsip->deleted_by	= $input->auth_data->pengguna->id_pengguna;
            		$arsip->deleted_at 	= $now;
            		$arsip->save();

            		$arsip->delete();

            		return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Arsip Pemilik successfully'
                    ];
                }
            }
        }
    }
}
