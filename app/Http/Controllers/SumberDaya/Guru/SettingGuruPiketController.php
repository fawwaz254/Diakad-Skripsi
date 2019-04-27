<?php

namespace App\Http\Controllers\SumberDaya\Guru;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\Guru as Guru;
use App\Models\PengampuMp as PengampuMp;
use App\Models\GuruPiket as GuruPiket;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibGuru;

use Illuminate\Support\Facades\Hash;

use Auth;
use DB;
use Session;
use Validator;


class SettingGuruPiketController extends BaseController
{
   	public function viewSettingGuruPiket(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/guru/setting-guru-piket/view-setting-guru-piket',compact('auth_data'));
    }

    public function addSettingGuruPiket(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/guru/setting-guru-piket/view-add-setting-guru-piket',compact('auth_data'));
    }

    public function editSettingGuruPiket(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = GuruPiket::select('guru.nip_guru','pengguna.nm_pengguna','guru_piket.id_guru_piket','guru_piket.is_aktif')->join('guru','guru.id_guru','=','guru_piket.id_guru')->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')->where('pengguna.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->where('guru_piket.id_guru_piket','=',$id)->first();

        return view('sumber-daya/guru/setting-guru-piket/view-edit-setting-guru-piket',compact('auth_data','guru'));
    }

    public function datatablesSettingGuruPiket(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = GuruPiket::select('guru.nip_guru','pengguna.nm_pengguna','guru_piket.id_guru_piket','unit_kerja.nm_unit_kerja','guru_piket.is_aktif')->join('guru','guru.id_guru','=','guru_piket.id_guru')->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')->join('unit_kerja','unit_kerja.id_unit_kerja','=','guru.id_unit_kerja')->where('pengguna.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
        		->addColumn('is_aktif', function($item){
                    if($item->is_aktif == 1) {
                        return "Aktif";
                    }
                    else {
                        return "Non-Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_guru_piket
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesAddGuruPiket(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = Guru::select('pengguna.nm_pengguna','guru.nip_guru','unit_kerja.nm_unit_kerja','guru.id_guru')
        ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
        ->join('unit_kerja','unit_kerja.id_unit_kerja','=','guru.id_unit_kerja')
        ->where('pengguna.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
       	->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('guru_piket')
                      ->whereRaw('guru_piket.id_guru = guru.id_guru');
            })
        ->get();

        return Datatables::of($list_data)
        		->addColumn('is_aktif', function($item){
                    if($item->is_aktif == "1") {
                        return "Aktif";
                    }
                    else {
                        return "Tidak aktif";
                    }
                })
                ->addColumn('checkbox', function($item){
                    $data = array(
                        'id' => $item->id_guru
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionSettingGuruPiket(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            if($mode == 'add'){
                DB::beginTransaction();

                try {
                    foreach ($input->id_guru as $id_guru) {
                        $guru                        	= new GuruPiket;
                        $guru->id_guru_piket	        = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $guru->id_guru 	            	= $id_guru;
                        $guru->created_by            	= $input->auth_data->pengguna->id_pengguna;
                        $guru->created_at            	= $now;
                        $guru->is_aktif 				= 1;
                        $guru->save();
                    }
                    DB::commit();
                    return [
                            'status' => 204, // SUCCESS AND LOAD CONTENT
                            'message' => 'Tambah Guru Piket Berhasil',
                            'path' => 'guru/setting-guru-piket/'
                    ];
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
                                'message' => 'Tambah Guru Piket Gagal'
                            ];
                }
        	}
        	elseif($mode == "edit"){
        		$guru 		= GuruPiket::find($id);
        		$guru->is_aktif	= $input->is_aktif;
        		$guru->updated_at	= $now;
        		$guru->updated_by	= $input->auth_data->pengguna->id_pengguna;
        		$guru->save();

        		return [
                            'status' => 204, // SUCCESS AND LOAD CONTENT
                            'message' => 'Edit Guru Piket Berhasil',
                            'path' => 'guru/setting-guru-piket/'
                    ];
        	}
        	elseif($mode == "delete"){
        		$guru 	= GuruPiket::find($id);
        		$guru->forceDelete();
                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Guru Piket Berhasil Dihapus'
                ];
        	}
        }
    }
}
