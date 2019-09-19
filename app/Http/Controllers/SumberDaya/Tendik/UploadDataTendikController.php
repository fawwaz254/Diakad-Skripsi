<?php

namespace App\Http\Controllers\SumberDaya\Tendik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use App\Models\PengampuMp as PengampuMp;
use App\Models\UnitKerja as UnitKerja;
use App\Models\StatusPengguna as StatusPengguna;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibGuru;

use Illuminate\Support\Facades\Hash;

use Excel;
use Auth;
use DB;
use Session;
use Validator;

class UploadDataTendikController extends BaseController
{
   public function viewUploadDataTendik(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/tendik/upload-data-tendik/view-upload-data-tendik',compact('auth_data'));

    }

    public function downloadFileExcel(){
        $file= public_path(). "/excel/ContohFileExcelUploadDataSumberDaya.xls";
        $headers = [
              'Content-Type' => 'application/xls',
           ];

        return response()->download($file, 'ContohFileExcelUploadDataSumberDaya.xls', $headers);
    }

    public function uploadFileExcel(Request $request){
    	$input = (object) $request->input();
	    $auth_data = $input->auth_data;
	    $now = Carbon::now(env('APP_TIMEZONE', ''));
        if($request->hasFile('file-excel')){
            $path = $request->file('file-excel')->getRealPath();
            $data = Excel::load($path)->get();
       		if($data->count()){
                foreach ($data as $key => $value) {
                	$staff = Staff::where('nip_staff','=',(int)$value->nip)->first();
                	if($staff == null){
                		//find id_status_pengguna
                		$status 		= StatusPengguna::select('id_status_pengguna')
			                			->where('nm_status_pengguna','=',$value->status_guru)
			                			->where('status_join_table','=','1')
			                			->first();

                		//find jenis_kelamin
                		if($value->jenis_kelamin == "L"){
                			$jenis_kelamin = 1;
                		}elseif($value->jenis_kelamin == "P"){
                			$jenis_kelamin = 2;
                		}else{
                			$jenis_kelamin = null;
                		}

                		//find Unit Kerja 
                		$unit = UnitKerja::select('id_unit_kerja')->where('nm_unit_kerja','=',$value->unit_kerja)->first(); 

                		if($unit == null || $jenis_kelamin == null || $status == null){
                			$arr[] = [];
                		}else{
                			//generate id
	                		$id_staff			= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
	                		$id_pengguna 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

	                		$arr[]= [
	                				'id_staff' 			=> $id_staff,
	                				'id_pengguna' 		=> $id_pengguna,
	                    			'nip' 				=> (int)$value->nip, 
	                    		  	'nama_lengkap' 		=> $value->nama_lengkap,
	                    		  	'status'	 		=> $status->id_status_pengguna,
	                    		  	'jenis_kelamin' 	=> $jenis_kelamin,
	                    		  	'unit_kerja' 		=> $unit->id_unit_kerja,
	                    		  	'id_sekolah' 		=> $input->auth_data->pengguna->id_sekolah,
	                    		  	'created_by'		=> $input->auth_data->pengguna->id_pengguna
	                    		];
                		}
                	}
                }
                if(!empty($arr)){
                   DB::beginTransaction();
                	try {
                		foreach ($arr as $data) {
                			
							DB::table('pengguna')->insert(
							    [
							    	'id_pengguna' 			=> $data['id_pengguna'],
							    	'id_status_pengguna' 	=> $data['status'],
							    	'id_sekolah' 			=> $data['id_sekolah'],
							    	'nm_pengguna'			=> $data['nama_lengkap'],
							    	'username' 				=> $data['nip'],
							    	'password'				=> Hash::make($data['nip']),
							    	'must_change_password' 	=> 1,
									'status_join_table' 	=> 1,
									'created_at'			=> $now,
							    	'created_by' 			=> $data['created_by']
								]
							);

							DB::table('staff')->insert(
							    [	
							    	'id_staff' 	 			=> $data['id_staff'],
							    	'id_pengguna' 			=> $data['id_pengguna'],
							    	'id_unit_kerja'			=> $data['unit_kerja'],
							    	'nip_staff'				=> $data['nip'],
							    	'jenis_kelamin'			=> $data['jenis_kelamin'],
									'created_at'			=> $now,
							    	'created_by' 			=> $data['created_by']
								]
							);

							DB::table('role_pengguna')->insert(
							    [	
							    	'id_pengguna' 				=> $data['id_pengguna'],
							    	'id_role' 					=> 15,
							    	'keterangan_role_pengguna' 	=> "Input Sumber Daya",
									'is_aktif'					=> 1,
									'created_at'				=> $now,
							    	'created_by' 				=> $data['created_by']
								]
							);

                		}
                		DB::commit();
                		return [
		                    'status' => 202, // SUCCESS AND LOAD CONTENT
		                    'path' => 'tendik/input-tendik',
		                    'message' => 'Save Tendik successfully'
		                ];
                	}
                	catch (\Exception $e) {
	                    DB::rollback();
	                    // something went wrong
	                    return [
	                                'status' 	=> 203, // GAGAL
	                                'message'	=> 'Upload Data Tendik Gagal'
	                            ];
	                } 
                }else{
                	return [
	                	'status' 	=> 300, // FAILED
	                	'message' 	=> "File Excel Anda Kosong"
                	];
                }
            }
        }else{
			return [
				'status' 	=> 300, // FAILED
				'message' 	=> "File Excel tidak ditemukan"
			];
		}
    }
}
