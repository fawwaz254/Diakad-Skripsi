<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Semester as Semester;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class NamaSemesterController extends BaseController{

    public function viewNamaSemester(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/nama-semester/view-nama-semester',compact('auth_data'));

    }

    public function addNamaSemester(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_semester = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/nama-semester/add-nama-semester',compact('auth_data','id_semester'));

    }

    public function editNamaSemester($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id);

        return view('pendidikan/data-akademik/nama-semester/edit-nama-semester',compact('auth_data','data_semester'));

    }

    public function datatablesNamaSemester(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return Datatables::of($list_data)
                ->addColumn('status_aktif', function($item){
                    if($item->is_aktif_semester == 0){
                        return "Non-Aktif";
                    }
                    else{
                        return "Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_semester
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionNamaSemester(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required',
            'nm_semester' => 'required',
            'thn_akademik_semester' => 'required',
            'kode_semester' => 'required',
            'is_aktif_semester' => 'required'
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

                $semester                           = new Semester;
                $semester->id_semester              = $id;
                $semester->tahun_ajaran             = $input->tahun_ajaran;
                $semester->nm_semester              = $input->nm_semester;
                $semester->thn_akademik_semester    = $input->thn_akademik_semester;
                $semester->kode_semester            = $input->kode_semester;
                $semester->is_aktif_semester        = $input->is_aktif_semester;
                $semester->id_sekolah               = $input->auth_data->pengguna->id_sekolah;
                $semester->created_by               = $input->auth_data->pengguna->id_pengguna;
                $semester->save();

                if($input->is_aktif_semester == 1){
                    $data_semester  = Semester::where('id_semester', "<>", $id)->get();

                    foreach ($data_semester as $semester) {
                        $semester->is_aktif_semester    = 0;
                        $semester->updated_by           = $input->auth_data->pengguna->id_pengguna;
                        $semester->updated_at           = $now;
                        $semester->save();
                    }
                    
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/nama-semester',
                    'message' => 'Save Nama Semester successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $semester                           = Semester::find($id);
                $semester->tahun_ajaran             = $input->tahun_ajaran;
                $semester->nm_semester              = $input->nm_semester;
                $semester->thn_akademik_semester    = $input->thn_akademik_semester;
                $semester->kode_semester            = $input->kode_semester;
                $semester->is_aktif_semester        = $input->is_aktif_semester;
                $semester->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $semester->updated_at               = $now;
                $semester->save();

                if($input->is_aktif_semester == 1){
                    $data_semester  = Semester::where('id_semester', "<>", $id)->get();
                    
                    foreach ($data_semester as $semester) {
                        $semester->is_aktif_semester    = 0;
                        $semester->updated_by           = $input->auth_data->pengguna->id_pengguna;
                        $semester->updated_at           = $now;
                        $semester->save();
                    }
                    
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/nama-semester',
                    'message' => 'Update Nama Semester successfully'
                ];
            }
            elseif($mode == 'delete') {
                // make object to find id
                $semester               = Semester::find($id);

                if($semester->is_aktif_semester == 1) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Active Semester'
                    ]; 
                }
                else {
                    $semester->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $semester->save();

                    $semester->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Nama Semester successfully'
                    ]; 
                }
                
            }
        }
    }

}