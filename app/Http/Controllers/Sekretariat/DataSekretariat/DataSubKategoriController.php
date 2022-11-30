<?php

namespace App\Http\Controllers\Sekretariat\DataSekretariat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipKategori as ArsipKategori;
use App\Models\ArsipSubkategori as ArsipSubkategori;
use App\Models\ArsipDokumen as ArsipDokumen;

use Auth;
use DB;
use Session;
use Validator;

class DataSubKategoriController extends BaseController
{
    public function viewDataSubKategori(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sekretariat/data-sekretariat/data-sub-kategori/view-data-sub-kategori',compact('auth_data'));
    }

    public function addDataSubKategori(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kategori = ArsipKategori::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return view('sekretariat/data-sekretariat/data-sub-kategori/add-data-sub-kategori',compact('auth_data','kategori'));
    }

    public function editDataSubKategori(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $unit 	= UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $arsip 	= ArsipSubKategori::where('id_arsip_subkategori','=',$id)->first();
        $kategori = ArsipKategori::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return view('sekretariat/data-sekretariat/data-sub-kategori/edit-data-sub-kategori',compact('auth_data','arsip','kategori'));
    }

    public function datatablesDataSubKategori(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = ArsipSubkategori::
       	join('arsip_kategori','arsip_kategori.id_arsip_kategori','=','arsip_subkategori.id_arsip_kategori')
        ->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_arsip_subkategori
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionDataSubKategori(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_arsip_subkategori'  => 'required',
            'id_arsip_kategori' 	=> 'required'
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
                
                $arsip 						= new ArsipSubkategori;
                $arsip->id_arsip_subkategori= $id;
                $arsip->id_arsip_kategori 	= $input->id_arsip_kategori;
                $arsip->nm_arsip_subkategori= $input->nm_arsip_subkategori;
                // $arsip->id_sekolah 			= $auth_data->pengguna->id_sekolah;
                $arsip->created_at 			= $now;
                $arsip->created_by			= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-sub-kategori',
                    'message' => 'Save Data Arsip Subkategori Successfully'
                ];
            }
            elseif($mode == 'edit'){
            	$arsip 						= ArsipSubkategori::find($id);
            	$arsip->nm_arsip_subkategori	= $input->nm_arsip_subkategori;
 				$arsip->updated_at 			= $now;
                $arsip->updated_by			= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-sub-kategori',
                    'message' => 'Save Data Arsip Kategori Successfully'
                ];
            }
            elseif($mode == 'delete'){
            	if(ArsipDokumen::where('id_arsip_subkategori','=',$id)->first()){
            		 return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Arsip Subkategori gagal'
                    ];
            	}else{
            		$arsip 	= ArsipSubkategori::find($id);
            		$arsip->deleted_by	= $input->auth_data->pengguna->id_pengguna;
            		$arsip->deleted_at 	= $now;
            		$arsip->save();

            		$arsip->delete();

            		return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Arsip Subkategori Successfully'
                    ];
                }
            }
        }
    }
}
