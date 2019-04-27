<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use App\Models\Kelas as Kelas;
use App\Models\Siswa as Siswa;
use App\Models\WaliMurid as WaliMurid;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;

use Auth;
use DB;
use Session;
use Validator;

class SettingWaliMuridController extends BaseController
{
    public function viewSettingWaliMurid(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_wali_murid      = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();


        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/siswa/setting-wali-murid/view-kelas-setting-wali-murid',compact('auth_data','data_kelas', 'id_wali_murid'));
    }
    public function actionViewSettingWaliMurid(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'siswa/setting-wali-murid/view-kelas/'.$input->id_kelas
                    ];   
                }
            }
            public function viewKelasWaliMurid(Request $request, $id_kelas = null){
        # code...
                $input = (object) $request->input();
                $auth_data = $input->auth_data;

                $data_kelas = Kelas::join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
                ->where('jurusan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                ->get();


                $kelas = Kelas::where('id_kelas','=',$id_kelas)->first();

                return view('pendidikan/siswa/setting-wali-murid/view-setting-wali-murid',compact('auth_data', 'data_kelas', 'kelas', 'id_kelas'));
            }

            public function editWaliMurid(Request $request, $id){
        # code...
                $input = (object) $request->input();
                $auth_data = $input->auth_data;

                $siswa = Siswa::where('id_siswa','=',$id)->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')->first();
                $wali_murid = WaliMurid::where('is_aktif','=',1)->get();

                //sudah punya wali murid, edit
                if($siswa->id_wali_murid != NULL){
                   $wali_murid = WaliMurid::where('id_wali_murid','=',$siswa->id_wali_murid)->first();
               }

               return view('pendidikan/siswa/setting-wali-murid/edit-setting-wali-murid',compact('auth_data','siswa','wali_murid'));

           }

           public function datatablesWaliMurid(Request $request, $id_kelas){
            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $list_data = Siswa::leftJoin('wali_murid','wali_murid.id_wali_murid','=','siswa.id_wali_murid')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->where('id_kelas','=',$id_kelas)->get();
            return Datatables::of($list_data)
            ->addColumn('nisn_siswa', function($item){
                return $item->nisn_siswa;
            })
            ->addColumn('action', function($item){
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
        }

        public function actionSettingWaliMurid(Request $request, $mode, $id = null){

            $input = (object) $request->input();

            $validator = Validator::make($request->all(), [
                'nomor_hp_wali_murid' => 'required'
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

            // ACTION ADD
            if($mode == 'edit'){
            	$siswa = Siswa::where('id_siswa','=',$id)->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')->first();
		        //sudah punya wali murid, edit
              if($siswa->id_wali_murid != NULL){
                  $data_waliMurid = WaliMurid::where('id_wali_murid','=',$siswa->id_wali_murid)->first();

                  $waliMurid 						= WaliMurid::find($siswa->id_wali_murid);
                  $waliMurid->nm_wali_murid 		= $input->nm_wali_murid;
                  $waliMurid->nomor_hp_wali_murid	= $input->nomor_hp_wali_murid;
                  $waliMurid->is_aktif			= 1;
                  $waliMurid->updated_by			= $input->auth_data->pengguna->id_pengguna;
                  $waliMurid->updated_at			= $now;
                  $waliMurid->save();

                  $pengguna 						= Pengguna::find($data_waliMurid->id_pengguna);
                  $pengguna->nm_pengguna			= $input->nm_wali_murid;
                  $pengguna->username	            = $input->nomor_hp_wali_murid;
                  $pengguna->password 			    = Hash::make($input->nomor_hp_wali_murid);
                  $pengguna->must_change_password	= 1;
                  $pengguna->status_join_table	    = 4;
                  $pengguna->updated_at			    = $now;
                  $pengguna->updated_by			    = $input->auth_data->pengguna->id_pengguna;
                  $pengguna->save();

                  return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'siswa/setting-wali-murid/view-kelas/'.$siswa->id_kelas,
                            'message' => 'Update Data Wali Murid Berhasil!'
                        ]; 				
                    }
		        //belum punya data wali murid
                    else{
                       $data_waliMurid = WaliMurid::where('nomor_hp_wali_murid','=',$input->nomor_hp_wali_murid)->first();
	                //data wali murid belum ada
                       if($data_waliMurid == NULL){
	                	//generate id_pengguna untuk wali murid
                          $id_pengguna 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                          $id_wali_murid		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                          $status_pengguna	= StatusPengguna::where('status_join_table','=','4')->where('aktif_status_pengguna','=','1')->first();

                          $waliMurid 						= new WaliMurid;
                          $waliMurid->id_wali_murid		    = $id_wali_murid;
                          $waliMurid->id_pengguna			= $id_pengguna;
                          $waliMurid->nm_wali_murid 		= $input->nm_wali_murid;
                          $waliMurid->nomor_hp_wali_murid	= $input->nomor_hp_wali_murid;
                          $waliMurid->is_aktif			    = 1;
                          $waliMurid->created_by			= $input->auth_data->pengguna->id_pengguna;
                          $waliMurid->created_at			= $now;
                          $waliMurid->save();

                          $pengguna 						= new Pengguna;
                          $pengguna->id_pengguna			= $id_pengguna;
                          $pengguna->id_status_pengguna	= $status_pengguna->id_status_pengguna;
                          $pengguna->id_sekolah			= $input->auth_data->pengguna->id_sekolah;
                          $pengguna->nm_pengguna			= $input->nm_wali_murid;
                          $pengguna->username         	= $input->nomor_hp_wali_murid;
                          $pengguna->password 			= Hash::make($input->nomor_hp_wali_murid);
                          $pengguna->must_change_password	= 1;
                          $pengguna->status_join_table	= 4;
                          $pengguna->created_by			= $input->auth_data->pengguna->id_pengguna;
                          $pengguna->created_at			= $now;
                          $pengguna->save();

                          $rolePengguna                   = new RolePengguna;
                          $rolePengguna->id_role          = 4;
                          $rolePengguna->id_pengguna      = $id_pengguna;
                          $rolePengguna->keterangan_role_pengguna = "Input Wali Murid";
                          $rolePengguna->is_aktif         = 1;
                          $rolePengguna->created_by           = $input->auth_data->pengguna->id_pengguna;
                          $rolePengguna->created_at           = $now;
                          $rolePengguna->save(); 

                          $siswa_waliMurid				      = Siswa::find($id);
                          $siswa_waliMurid->is_orang_tua      = $input->is_orang_tua;
                          $siswa_waliMurid->id_wali_murid	  = $id_wali_murid;
                          $siswa_waliMurid->save();

                          return [
	                            'status' => 202, // SUCCESS AND LOAD CONTENT
                                'path' => 'siswa/setting-wali-murid/view-kelas/'.$siswa->id_kelas,
                                'message' => 'Update Data Wali Murid Berhasil!'
                            ];
                        } 
	                //data wali murid sudah ada
                        else{
                          $siswa_waliMurid				    = Siswa::find($id);
                          $siswa_waliMurid->id_wali_murid	= $data_waliMurid->id_wali_murid;
                          $siswa_waliMurid->is_orang_tua    = $input->is_orang_tua;
                          $siswa_waliMurid->save(); 

                          return [
	                            'status' => 202, // SUCCESS AND LOAD CONTENT
                                'path' => 'siswa/setting-wali-murid/view-kelas/'.$siswa->id_kelas,
                                'message' => 'Update Data Wali Murid Berhasil!'
                            ];
                        }						
                    }
                }
                elseif($mode == 'add') {
                    $wali_murid = WaliMurid::where('nomor_hp_wali_murid','=',$input->nomor_hp_wali_murid)->first();

                    if($wali_murid == null) {
                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                        $id_pengguna        = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $status_pengguna    = StatusPengguna::where('status_join_table','=','4')->where('aktif_status_pengguna','=','1')->first();
                        
                        DB::beginTransaction();
                        try{
                            DB::table('pengguna')->insert(
                                [
                                    'id_pengguna'           => $id_pengguna, 
                                    'id_status_pengguna'    => $status_pengguna->id_status_pengguna,
                                    'id_sekolah'            => $input->auth_data->pengguna->id_sekolah,
                                    'nm_pengguna'           => $input->nm_wali_murid,
                                    'username'              => $input->nomor_hp_wali_murid,
                                    'password'              => Hash::make($input->nomor_hp_wali_murid),
                                    'gelar_depan'           => $input->gelar_depan,
                                    'gelar_belakang'        => $input->gelar_belakang,
                                    'must_change_password'  => 1,
                                    'status_join_table'     => 4,
                                    'created_at'            => $now,
                                    'created_by'            => $input->auth_data->pengguna->id_pengguna
                                ]
                            );

                            DB::table('wali_murid')->insert(
                                [
                                    'id_pengguna'           => $id_pengguna, 
                                    'id_wali_murid'         => $id,
                                    'nm_wali_murid'         => $input->nm_wali_murid,
                                    'nomor_hp_wali_murid'   => $input->nomor_hp_wali_murid,
                                    'is_aktif'              => 1,
                                    'created_at'            => $now,
                                    'created_by'            => $input->auth_data->pengguna->id_pengguna
                                ]
                            );
                            DB::table('role_pengguna')->insert(
                                [
                                    'id_role'               => 4,
                                    'id_pengguna'           => $id_pengguna,
                                    'keterangan_role_pengguna'  => "Input Wali Murid",
                                    'is_aktif'              => 1,
                                    'created_at'            => $now,
                                    'created_by'            => $input->auth_data->pengguna->id_pengguna
                                ]
                            );
                            DB::commit();
                            return [
                                    'status' => 203, // SUCCESS AND LOAD TABLE
                                    'message' => 'Input Data Wali Murid Berhasil'
                            ];
                        }
                        catch (\Exception $e) {
                            DB::rollback();
                            // something went wrong
                            return [
                                        'status'    => 203, // GAGAL
                                        'message'   => 'Tambah Data Wali Murid Gagal'
                                    ];
                        } 

                    }else{
                        return [
                            'status' => 203, // GAGAL
                            'message' => 'Data Wali Murid Sudah Ada. Silahkan Masukkan Nomor HP Lain!'
                        ];
                }
            }
        }
    }  
}
