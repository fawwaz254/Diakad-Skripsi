<?php

namespace App\Http\Controllers\Sekretariat\DataSekretariat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipLoker as ArsipLoker;
use App\Models\ArsipDokumen as ArsipDokumen;
use App\Models\UnitKerja as UnitKerja;

use Auth;
use DB;
use Session;
use Validator;

class DataLokerAlmariController extends BaseController
{
    public function viewDataLokerAlmari(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sekretariat/data-sekretariat/data-loker-almari/view-data-loker-almari',compact('auth_data'));
    }

    public function addDataLokerAlmari(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $unit = UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return view('sekretariat/data-sekretariat/data-loker-almari/add-data-loker-almari',compact('auth_data','unit'));
    }

    public function editDataLokerAlmari(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $unit 	= UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $arsip 	= ArsipLoker::where('id_arsip_loker','=',$id)->first();

        return view('sekretariat/data-sekretariat/data-loker-almari/edit-data-loker-almari',compact('auth_data','unit','arsip'));
    }

    public function datatablesDataLokerAlmari(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = ArsipLoker::select('arsip_loker.id_arsip_loker','unit_kerja.nm_unit_kerja','arsip_loker.nm_arsip_loker','arsip_loker.id_unit_kerja')
        	->join('unit_kerja','unit_kerja.id_unit_kerja','=','arsip_loker.id_unit_kerja')
        	->where('arsip_loker.id_sekolah','=',$auth_data->pengguna->id_sekolah);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_arsip_loker
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionDataLokerAlmari(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_arsip_loker' => 'required',
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
                
                $arsip 					= new ArsipLoker;
                $arsip->id_arsip_loker	= $id;
                $arsip->nm_arsip_loker	= $input->nm_arsip_loker;
                $arsip->id_unit_kerja 	= $input->id_unit_kerja;
                $arsip->id_sekolah 		= $auth_data->pengguna->id_sekolah;
                $arsip->created_at 		= $now;
                $arsip->created_by		= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-loker-almari',
                    'message' => 'Save Data Loker Almari successfully'
                ];
            }
            elseif($mode == 'edit'){
            	$arsip 					= ArsipLoker::find($id);
            	$arsip->nm_arsip_loker	= $input->nm_arsip_loker;
            	$arsip->id_unit_kerja	= $input->id_unit_kerja;
 				$arsip->updated_at 		= $now;
                $arsip->updated_by		= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-loker-almari',
                    'message' => 'Save Data Loker Almari successfully'
                ];
            }
            elseif($mode == 'delete'){
            	if(ArsipDokumen::where('id_arsip_loker','=',$id)->first()){
            		 return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Arsip Loker/Almari gagal'
                    ];
            	}else{
            		$arsip 	= ArsipLoker::find($id);
            		$arsip->deleted_by	= $input->auth_data->pengguna->id_pengguna;
            		$arsip->deleted_at 	= $now;
            		$arsip->save();

            		$arsip->delete();

            		return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Arsip Loker/Almari successfully'
                    ];
                }
            }
        }
    }
}
