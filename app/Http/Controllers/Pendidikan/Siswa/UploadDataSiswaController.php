<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\Siswa as Siswa;
use App\Models\Kelas as Kelas;
use App\Models\Jalur as Jalur;
use App\Models\Semester as Semester;
use App\Models\CalonSiswaBaru as CalonSiswaBaru;
use App\Models\CalonSiswaSekolah as CalonSiswaSekolah;
use App\Models\CalonSiswaOrtu as CalonSiswaOrtu;
use App\Models\CalonSiswaFisik as CalonSiswaFisik;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Penerimaan as Penerimaan;

use Auth;
use Excel;
use DB;
use Session;
use Validator;

class UploadDataSiswaController extends BaseController
{
    public function viewUploadDataSiswa(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

    	return view('pendidikan/siswa/upload-data-siswa/view-upload-data-siswa',compact('auth_data'));
  	}
  	public function downloadFileExcel(){
        $file= public_path(). "/excel/ContohFileExcelUploadDataSiswa.xls";
        $headers = [
              'Content-Type' => 'application/xls',
           ];

        return response()->download($file, 'ContohFileExcelUploadDataSiswa.xls', $headers);

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
                	$siswa = Siswa::where('nis_siswa','=',(int)$value->nis)->orWhere('nisn_siswa','=',(int)$value->nisn)->first();
                	if($siswa == null){
                		//find id_status_pengguna
                		$status 		= StatusPengguna::select('id_status_pengguna')
			                			->where('nm_status_pengguna','=',$value->status_siswa)
			                			->where('status_join_table','=','3')
			                			->first();

                		// find id_kelas
                		$kelas 			= Kelas::where('nm_kelas','=',$value->kelas)->first();

                		//find id_jalur
                		$jalur 			= Jalur::where('nm_jalur','=',$value->jalur)->first();

                		//find jenis_kelamin
                		if($value->jenis_kelamin == "Laki-laki"){
                			$jenis_kelamin = 1;
                		}elseif($value->jenis_kelamin == "Perempuan"){
                			$jenis_kelamin = 2;
                		}else{
                			$jenis_kelamin = null;
                		}

                		//find id_semester
                		$semester_masuk 	= Semester::where('kode_semester','=',$value->semester_masuk)->first();

                		//find id_penerimaan
                		$id_penerimaan 		= Penerimaan::where('jenis_penerimaan','=','2')->where('tahun_penerimaan','=',(int)$value->tahun_masuk)->first(); 

                		if($id_penerimaan == null || $semester_masuk == null || $jenis_kelamin == null || $jalur == null || $kelas == null || $status == null){
                			$arr[] = [];
                		}else{
                			//generate id
	                		$id_siswa 			= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
	                		$id_pengguna 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
	                		$id_c_siswa 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
	                		$id_admisi 			= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
	                		$id_jalur_siswa 	= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();


	                		$arr[]= [
	                				'id_siswa' 			=> $id_siswa,
	                				'id_pengguna' 		=> $id_pengguna,
	                				'id_c_siswa' 		=> $id_c_siswa,
	                				'id_admisi' 		=> $id_admisi,
	                				'id_jalur_siswa' 	=> $id_jalur_siswa,
	                				'id_penerimaan' 	=> $id_penerimaan->id_penerimaan,
	                    			'nis' 				=> (int)$value->nis, 
	                    		  	'nisn' 				=> (int)$value->nisn, 
	                    		  	'nama_lengkap' 		=> $value->nama_lengkap,
	                    		  	'status_siswa' 		=> $status->id_status_pengguna,
	                    		  	'kelas' 			=> $kelas->id_kelas,
	                    		  	'tahun_masuk' 		=> (int)$value->tahun_masuk,
	                    		  	'jalur' 			=> $jalur->id_jalur,
	                    		  	'jenis_kelamin' 	=> $jenis_kelamin,
	                    		  	'semester_masuk' 	=> $semester_masuk['id_semester'],
	                    		  	'id_sekolah' 		=> $input->auth_data->pengguna->id_sekolah,
	                    		  	'created_by'		=> $input->auth_data->pengguna->id_pengguna
	                    		];
                		}
                	}
                }
                if(!empty($arr)){
                   DB::beginTransaction();
                	try {
                		foreach ($arr as $data_siswa) {
                			DB::table('calon_siswa_baru')->insert(
							    [
							    	'id_c_siswa' 	=> $data_siswa['id_c_siswa'], 
							    	'id_penerimaan' => $data_siswa['id_penerimaan'],
							    	'nm_c_siswa' 	=> $data_siswa['nama_lengkap'],
							    	'jenis_kelamin' => $data_siswa['jenis_kelamin'],
							    	'nisn_siswa' 	=> $data_siswa['nisn'],
							    	'created_at'	=> $now,
							    	'created_by' 	=> $data_siswa['created_by']
								]
							);

							DB::table('calon_siswa_fisik')->insert(
							    [
									'id_c_siswa' 	=> $data_siswa['id_c_siswa'],
									'created_at'	=> $now,
							    	'created_by' 	=> $data_siswa['created_by']						    
								]
							);

							DB::table('calon_siswa_ortu')->insert(
							    [
									'id_c_siswa' 	=> $data_siswa['id_c_siswa'],
									'created_at'	=> $now,
							    	'created_by' 	=> $data_siswa['created_by']					    	
								]
							);

							DB::table('calon_siswa_sekolah')->insert(
							    [
									'id_c_siswa' 	=> $data_siswa['id_c_siswa'],
									'created_at'	=> $now,
							    	'created_by' 	=> $data_siswa['created_by']
								]
							);

							DB::table('pengguna')->insert(
							    [
							    	'id_pengguna' 			=> $data_siswa['id_pengguna'],
							    	'id_status_pengguna' 	=> $data_siswa['status_siswa'],
							    	'id_sekolah' 			=> $data_siswa['id_sekolah'],
							    	'nm_pengguna'			=> $data_siswa['nama_lengkap'],
							    	'username' 				=> $data_siswa['nis'],
							    	'password'				=> Hash::make($data_siswa['nis']),
							    	'must_change_password' 	=> 1,
									'status_join_table' 	=> 3,
									'created_at'			=> $now,
							    	'created_by' 			=> $data_siswa['created_by']
								]
							);

							DB::table('siswa')->insert(
							    [	
							    	'id_siswa' 	 			=> $data_siswa['id_siswa'],
							    	'id_pengguna' 			=> $data_siswa['id_pengguna'],
							    	'id_c_siswa' 			=> $data_siswa['id_c_siswa'],
							    	'id_kelompok_biaya'		=> null,
							    	'id_kelas' 	 			=> $data_siswa['kelas'],
							    	'nis_siswa'	 			=> $data_siswa['nis'],
							    	'nisn_siswa'			=> $data_siswa['nisn'],
									'thn_masuk_siswa'		=> $data_siswa['tahun_masuk'],
									'created_at'			=> $now,
							    	'created_by' 			=> $data_siswa['created_by']
								]
							);

							DB::table('admisi')->insert(
							    [
							    	'id_admisi' 			=> $data_siswa['id_admisi'],
							    	'id_siswa' 	 			=> $data_siswa['id_siswa'],
							    	'id_semester' 			=> $data_siswa['semester_masuk'],
							    	'id_status_pengguna' 	=> $data_siswa['status_siswa'],
									'id_jalur' 				=> $data_siswa['jalur'],
									'created_at'			=> $now,
							    	'created_by' 			=> $data_siswa['created_by']					    	
								]
							);

							DB::table('jalur_siswa')->insert(
							    [
							    	'id_jalur_siswa' 		=> $data_siswa['id_jalur_siswa'],
							    	'id_siswa' 	 			=> $data_siswa['id_siswa'],
							    	'id_semester' 			=> $data_siswa['semester_masuk'],
							    	'id_jalur' 				=> $data_siswa['jalur'],
							    	'id_admisi' 			=> $data_siswa['id_admisi'],
									'is_jalur_aktif' 		=> 1,
									'created_at'			=> $now,
							    	'created_by' 			=> $data_siswa['created_by']					    	
								]
							);

							DB::table('role_pengguna')->insert(
							    [	
							    	'id_pengguna' 				=> $data_siswa['id_pengguna'],
							    	'id_role' 					=> 3,
							    	'keterangan_role_pengguna' 	=> "Input Pendidikan",
									'is_aktif'					=> 1,
									'created_at'				=> $now,
							    	'created_by' 				=> $data_siswa['created_by']
								]
							);

                		}
                		DB::commit();
                		return [
                                'status' => 203, // SUCCESS AND LOAD TABLE
                                'message' => 'Upload Data Siswa Berhasil!'
                        ];
                	}
                	catch (\Exception $e) {
	                    DB::rollback();
	                    // something went wrong
	                    return [
	                                'status' 	=> 203, // GAGAL
	                                'message'	=> 'Upload Data Siswa Gagal'
	                            ];
	                } 
                }else{
                	return [
	                	'status' 	=> 300, // FAILED
	                	'message' 	=> "File Excel Anda Kosong"
                	];
                }
            }
        }      
    } 
}
