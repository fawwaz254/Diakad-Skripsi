<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\StandarNilai as StandarNilai;
use App\Models\PeraturanNilai as PeraturanNilai;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class NilaiMutuController extends BaseController{

    public function viewNilaiMutu(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/nilai-mutu/view-nilai-mutu',compact('auth_data'));

    }

    public function addNilaiMutu(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_standar_nilai = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/nilai-mutu/add-nilai-mutu',compact('auth_data','id_standar_nilai'));

    }

    public function editNilaiMutu($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_nilai_mutu = LibDataAkademik::fetchDataNilaiMutu($auth_data, $id);

        return view('pendidikan/data-akademik/nilai-mutu/edit-nilai-mutu',compact('auth_data','data_nilai_mutu'));

    }

    public function datatablesNilaiMutu(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataAkademik::fetchDataNilaiMutu($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_standar_nilai
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionNilaiMutu(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_standar_nilai' => 'required',
            'mutu_standar_nilai' => 'required',
            'keterangan_standar_nilai' => 'required'
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

                $standarNilai                               = new StandarNilai;
                $standarNilai->id_standar_nilai             = $id;
                $standarNilai->nm_standar_nilai             = $input->nm_standar_nilai;
                $standarNilai->mutu_standar_nilai           = $input->mutu_standar_nilai;
                $standarNilai->keterangan_standar_nilai     = $input->keterangan_standar_nilai;
                $standarNilai->id_sekolah                   = $input->auth_data->pengguna->id_sekolah;
                $standarNilai->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $standarNilai->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/nilai-mutu',
                    'message' => 'Save Nilai Mutu Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $standarNilai                               = StandarNilai::find($id);
                $standarNilai->nm_standar_nilai             = $input->nm_standar_nilai;
                $standarNilai->mutu_standar_nilai           = $input->mutu_standar_nilai;
                $standarNilai->keterangan_standar_nilai     = $input->keterangan_standar_nilai;
                $standarNilai->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $standarNilai->updated_at                   = $now;
                $standarNilai->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/nilai-mutu',
                    'message' => 'Update Nilai Mutu Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($peraturanNilai = PeraturanNilai::where('id_standar_nilai',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Nilai Mutu'
                    ]; 
                }
                else{
                    // make object to find id
                    $standarNilai               = StandarNilai::find($id);
                    $standarNilai->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $standarNilai->save();

                    $standarNilai->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Nilai Mutu Successfully'
                    ];
                }
            }
        }
    }


}