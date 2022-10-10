<?php

namespace App\Http\Controllers\Kesiswaan\Ekstrakurikuler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul as Ekskul;
use App\Models\PelatihEkskulSet as PelatihEkskulSet;
use App\Models\PelatihEkskul as PelatihEkskul;
use App\Models\Kelas as Kelas;
use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\EkskulWajib as EkskulWajib;
use App\Models\StatusPengguna as StatusPengguna;

use Auth;
use DB;
use Session;
use Validator;

class SettingPelatihEkskulController extends BaseController
{
	public function viewSettingPelatihEkskul(Request $request){
        # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		return view('kesiswaan/ekstrakurikuler/setting-pelatih-ekskul/view-setting-pelatih-ekskul',compact('auth_data'));
	}

    public function viewPelatihEkskul(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('kesiswaan/ekstrakurikuler/setting-pelatih-ekskul/view-pelatih-ekskul',compact('auth_data'));
    }

	public function addSettingPelatihEkskul(Request $request){
        # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

        // mengambil waktu sekarang
		$now    = Carbon::now(env('APP_TIMEZONE', ''));

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

		return view('kesiswaan/ekstrakurikuler/setting-pelatih-ekskul/add-setting-pelatih-ekskul',compact('auth_data'));

	}

	public function editSettingPelatihEkskul(Request $request,$id){
        # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

        // mengambil waktu sekarang
		$now    = Carbon::now(env('APP_TIMEZONE', ''));
		$pelatih = PelatihEkskul::join('pengguna','pengguna.id_pengguna','=','pelatih_ekskul.id_pengguna')->where('id_pelatih_ekskul','=',$id)->first();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

		return view('kesiswaan/ekstrakurikuler/setting-pelatih-ekskul/edit-setting-pelatih-ekskul',compact('auth_data','pelatih'));

	}

    public function editStatusSettingPelatihEkskul(Request $request,$id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now    = Carbon::now(env('APP_TIMEZONE', ''));
        $pelatih = PelatihEkskulSet::select()
        ->join('pelatih_ekskul','pelatih_ekskul.id_pelatih_ekskul','=','pelatih_ekskul_set.id_pelatih_ekskul')
        ->join('pengguna','pengguna.id_pengguna','=','pelatih_ekskul.id_pengguna')
        ->join('ekskul','ekskul.id_ekskul','=','pelatih_ekskul_set.id_ekskul')
        ->where('pelatih_ekskul_set.id_pelatih_ekskul_set','=',$id)
        ->first();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/ekstrakurikuler/setting-pelatih-ekskul/edit-status-assign-pelatih-ekskul',compact('auth_data','pelatih'));

    }

	public function assignSettingPelatihEkskul(Request $request,$id){
        # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

        // mengambil waktu sekarang
		$now    = Carbon::now(env('APP_TIMEZONE', ''));
		$pelatih = PelatihEkskul::select('pengguna.nm_pengguna','pelatih_ekskul.id_pelatih_ekskul','pelatih_ekskul.nomor_hp_pelatih_ekskul','pelatih_ekskul.alamat_pelatih_ekskul','pelatih_ekskul_set.is_aktif','ekskul.id_ekskul')
		->join('pengguna','pengguna.id_pengguna','=','pelatih_ekskul.id_pengguna')
		->leftJoin('pelatih_ekskul_set','pelatih_ekskul_set.id_pelatih_ekskul','=','pelatih_ekskul.id_pelatih_ekskul')
		->leftJoin('ekskul','ekskul.id_ekskul','=','pelatih_ekskul_set.id_ekskul')
		->where('pelatih_ekskul.id_pelatih_ekskul','=',$id)->first();
		$ekskul = Ekskul::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

		return view('kesiswaan/ekstrakurikuler/setting-pelatih-ekskul/assign-setting-pelatih-ekskul',compact('auth_data','pelatih','ekskul'));

	}

	public function datatablesSettingPelatihEkskul(Request $request){
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$list_data = PelatihEkskulSet::select('pelatih_ekskul_set.id_pelatih_ekskul_set','pelatih_ekskul.id_pelatih_ekskul','pengguna.nm_pengguna','pengguna.gelar_depan','pengguna.gelar_belakang','pelatih_ekskul.nomor_hp_pelatih_ekskul','pelatih_ekskul.alamat_pelatih_ekskul','pelatih_ekskul_set.is_aktif','ekskul.nm_ekskul')
        ->join('pelatih_ekskul','pelatih_ekskul.id_pelatih_ekskul','=','pelatih_ekskul_set.id_pelatih_ekskul')
        ->join('pengguna','pengguna.id_pengguna','=','pelatih_ekskul.id_pengguna')
        ->join('ekskul','ekskul.id_ekskul','=','pelatih_ekskul_set.id_ekskul')
        ->where('ekskul.id_sekolah','=',$auth_data->pengguna->id_sekolah)
        ->get();

		return Datatables::of($list_data)
		->addColumn('is_aktif', function($item){
			if($item->is_aktif == 0){
				return "Tidak Aktif";
			}
			else{
				return "Aktif";
			}
		})
		->addColumn('action', function($item){
			$data = array(
				'id' => $item->id_pelatih_ekskul,
                'id_pelatih_ekskul_set' => $item->id_pelatih_ekskul_set
			);
			return $data;
		})
		->make(true);
	}

    public function datatablesPelatihEkskul(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PelatihEkskul::select('pelatih_ekskul.id_pelatih_ekskul','pelatih_ekskul.nomor_hp_pelatih_ekskul','pelatih_ekskul.alamat_pelatih_ekskul','pelatih_ekskul.is_aktif','pengguna.nm_pengguna','pengguna.gelar_depan','pengguna.gelar_belakang')
        ->join('pengguna','pengguna.id_pengguna','=','pelatih_ekskul.id_pengguna')
        ->get();

        return Datatables::of($list_data)
        ->addColumn('is_aktif', function($item){
            if($item->is_aktif == 0){
                return "Tidak Aktif";
            }
            else{
                return "Aktif";
            }
        })
        ->addColumn('nm_pengguna', function($item){
                    if( ! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    }
                    elseif( ! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;   
                    }
                    elseif( ! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;   
                    }
                    else {
                        return $item->nm_pengguna; 
                    }
                })
        ->addColumn('action', function($item){
            $data = array(
                'id' => $item->id_pelatih_ekskul
            );
            return $data;
        })
        ->make(true);
    }

	public function actionSettingPelatihEkskul(Request $request, $mode, $id = null){

		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now(env('APP_TIMEZONE', ''));

		$validator = Validator::make($request->all(), [
			'nm_pengguna' 		=> 'required',
			'nomor_hp_pelatih_ekskul'			=> 'required',
			'alamat_pelatih_ekskul'		=> 'required'
		]);

		if($validator->fails() && $mode != 'delete' && $mode != 'assign' && $mode != 'unassign' && $mode != 'edit-status') {
			return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // ACTION ADD
        	if($mode == 'add') {
        		$id_pengguna 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        		$id_pelatih			= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        		$status_pengguna	= StatusPengguna::where('status_join_table','=','4')->where('aktif_status_pengguna','=','1')->first();

        		$pelatih 							= new PelatihEkskul;
        		$pelatih->id_pelatih_ekskul 		= $id_pelatih;
        		$pelatih->id_pengguna 				= $id_pengguna;
        		$pelatih->nomor_hp_pelatih_ekskul	= $input->nomor_hp_pelatih_ekskul;
        		$pelatih->alamat_pelatih_ekskul 	= $input->alamat_pelatih_ekskul;
        		$pelatih->is_aktif 					= $input->is_aktif;
        		$pelatih->created_by 				= $input->auth_data->pengguna->id_pengguna;
        		$pelatih->created_at 				= $now;
        		$pelatih->save(); 			

        		$pengguna 						= new Pengguna;
        		$pengguna->id_pengguna			= $id_pengguna;
        		$pengguna->id_status_pengguna	= $status_pengguna->id_status_pengguna;
        		$pengguna->id_sekolah			= $input->auth_data->pengguna->id_sekolah;
        		$pengguna->nm_pengguna			= $input->nm_pengguna;
        		$pengguna->gelar_depan 			= $input->gelar_depan;
        		$pengguna->gelar_belakang		= $input->gelar_belakang;
        		$pengguna->username         	= $input->nomor_hp_pelatih_ekskul;
        		$pengguna->nomor_hp_pengguna         	= $input->nomor_hp_pelatih_ekskul;
        		$pengguna->password 			= Hash::make($input->nomor_hp_pelatih_ekskul);
        		$pengguna->must_change_password	= 1;
        		$pengguna->status_join_table	= 5;
        		$pengguna->created_by			= $input->auth_data->pengguna->id_pengguna;
        		$pengguna->created_at			= $now;
        		$pengguna->save();

        		$rolePengguna                   = new RolePengguna;
        		$rolePengguna->id_role          = 13;
        		$rolePengguna->id_pengguna      = $id_pengguna;
        		$rolePengguna->keterangan_role_pengguna = "Input Pelatih";
        		$rolePengguna->is_aktif         = 1;
        		$rolePengguna->created_by           = $input->auth_data->pengguna->id_pengguna;
        		$rolePengguna->created_at           = $now;
        		$rolePengguna->save();

        		return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/setting-pelatih-ekskul/view-pelatih',
                    'message' => 'Save Data Pelatih  Ekskul Successfully'
                ];
            }
            elseif($mode == 'edit') {
            	$detail 		= PelatihEkskul::where('id_pelatih_ekskul','=',$id)->first();

            	$pelatih 							= PelatihEkskul::find($id);
            	$pelatih->nomor_hp_pelatih_ekskul 	= $input->nomor_hp_pelatih_ekskul;
            	$pelatih->alamat_pelatih_ekskul 	= $input->alamat_pelatih_ekskul;
            	$pelatih->is_aktif 					= $input->is_aktif;
            	$pelatih->updated_at				= $now;
            	$pelatih->updated_by				= $input->auth_data->pengguna->id_pengguna;
            	$pelatih->save();

            	$pengguna 			            = Pengguna::where('id_pengguna','=',$detail->id_pengguna)->first();
            	$pengguna->nm_pengguna 			= $input->nm_pengguna;
            	$pengguna->nomor_hp_pengguna 	= $input->nomor_hp_pelatih_ekskul;
            	$pengguna->gelar_depan 			= $input->gelar_depan;
        		$pengguna->gelar_belakang		= $input->gelar_belakang;
        		$pengguna->updated_at		    = $now;
            	$pengguna->updated_by		    = $input->auth_data->pengguna->id_pengguna;
        		$pengguna->save();

            	return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/setting-pelatih-ekskul/view-pelatih',
                    'message' => 'Save Data Pelatih  Ekskul Successfully'
                ];
           	}
            elseif($mode == 'edit-status') {
                $pelatih                            = PelatihEkskulSet::find($id);
                $pelatih->is_aktif                  = $input->is_aktif;
                $pelatih->updated_at                = $now;
                $pelatih->updated_by                = $input->auth_data->pengguna->id_pengguna;
                $pelatih->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/setting-pelatih-ekskul',
                    'message' => 'Save Data Pelatih  Ekskul Successfully'
                ];
            }
           	elseif ($mode == 'assign') {
           		if(PelatihEkskulSet::where('id_pelatih_ekskul','=',$input->id_pelatih)->where('id_ekskul','=',$input->id_ekskul)->first()){
           			$pelatih = PelatihEkskulSet::where('id_pelatih_ekskul','=',$input->id_pelatih)->where('id_ekskul','=',$input->id_ekskul)->first();
           			$pelatih->id_pelatih_ekskul 	= $input->id_pelatih;
           			$pelatih->id_ekskul 			= $input->id_ekskul;
           			$pelatih->is_aktif 				= $input->is_aktif;
           			$pelatih->updated_at				= $now;
	            	$pelatih->updated_by				= $input->auth_data->pengguna->id_pengguna;
	            	$pelatih->save();
           		}else{
           			$pelatih = new PelatihEkskulSet;
	           		$pelatih->id_pelatih_ekskul_set = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
	           		$pelatih->id_pelatih_ekskul 	= $input->id_pelatih;
	           		$pelatih->id_ekskul 			= $input->id_ekskul;
	           		$pelatih->is_aktif 				= $input->is_aktif;
	           		$pelatih->created_by 				= $input->auth_data->pengguna->id_pengguna;
	        		$pelatih->created_at 				= $now;
	           		$pelatih->save();
           		}
           		return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/setting-pelatih-ekskul',
                    'message' => 'Save Data Pelatih  Ekskul Successfully'
                ]; 			
           	}
            elseif ($mode == 'unassign') {
                    $ekskul                 = PelatihEkskulSet::find($id);
                    $ekskul->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                    $ekskul->save();

                    $ekskul->delete();
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Unassign Pelatih Ekskul Successfully'
                    ];          
            }
           	elseif ($mode == 'delete') {
           		if($pelatih = PelatihEkskulSet::where('id_pelatih_ekskul','=',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Data Pelatih'
                    ];  
                }
                else{
                    // make object to find id
                    $ekskul 				= PelatihEkskul::find($id);
                    $ekskul->deleted_by 	= $input->auth_data->pengguna->id_pengguna;
                    $ekskul->save();

                    $ekskul->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Pelatih Successfully'
                    ];
                }
           	}
        }
    }
}
 