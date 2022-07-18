<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PeriodeWisuda as PeriodeWisuda;
use App\Models\PengajuanWisuda as PengajuanWisuda;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibWisuda;

use Auth;
use DB;
use Session;
use Validator;

class PeriodeWisudaController extends BaseController{

    public function viewPeriodeWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/wisuda/periode-wisuda/view-periode-wisuda',compact('auth_data'));

    }

    public function addPeriodeWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_wisuda = LibWisuda::fetchDataWisuda($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_periode_wisuda = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/wisuda/periode-wisuda/add-periode-wisuda',compact('auth_data','data_wisuda','data_semester','id_periode_wisuda'));

    }

    public function editPeriodeWisuda($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_wisuda = LibWisuda::fetchDataWisuda($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_periode_wisuda = LibWisuda::fetchDataPeriodeWisuda($auth_data, $id);

        // convert format date
        $tgl_mulai = strftime( "%d %B %Y", strtotime($data_periode_wisuda->tgl_bayar_mulai));
        $tgl_selesai = strftime( "%d %B %Y", strtotime($data_periode_wisuda->tgl_bayar_selesai));

        return view('pendidikan/wisuda/periode-wisuda/edit-periode-wisuda',compact('auth_data','data_wisuda','data_semester','data_periode_wisuda','tgl_mulai','tgl_selesai'));

    }

    public function datatablesPeriodeWisuda(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibWisuda::fetchDataPeriodeWisuda($auth_data);

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('besar_biaya', function($item){
                    return number_format($item->besar_biaya);
                })
                ->addColumn('tgl_mulai', function($item){
                    return strftime( "%d %B %Y", strtotime($item->tgl_bayar_mulai));
                })
                ->addColumn('tgl_selesai', function($item){
                    return strftime( "%d %B %Y", strtotime($item->tgl_bayar_selesai));
                })
                ->addColumn('is_aktif', function($item){
                    if($item->is_aktif == 0){
                        return "Non-Aktif";
                    }
                    else{
                        return "Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_periode_wisuda
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionPeriodeWisuda(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_wisuda' => 'required',
            'id_semester' => 'required',
            'nm_periode_wisuda' => 'required',
            'besar_biaya' => 'required',
            'tgl_bayar_mulai' => 'required',
            'tgl_bayar_selesai' => 'required',
            'is_aktif' => 'required'
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
            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $periodeWisuda                      = new PeriodeWisuda;
                $periodeWisuda->id_periode_wisuda   = $id;
                $periodeWisuda->id_wisuda           = $input->id_wisuda;
                $periodeWisuda->id_semester         = $input->id_semester;
                $periodeWisuda->nm_periode_wisuda   = $input->nm_periode_wisuda;
                $periodeWisuda->besar_biaya         = $input->besar_biaya;
                $periodeWisuda->tgl_bayar_mulai     = date_format(date_create($input->tgl_bayar_mulai),"Y-m-d");
                $periodeWisuda->tgl_bayar_selesai   = date_format(date_create($input->tgl_bayar_selesai),"Y-m-d");
                $periodeWisuda->is_aktif            = $input->is_aktif;
                $periodeWisuda->created_by          = $input->auth_data->pengguna->id_pengguna;
                $periodeWisuda->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wisuda/periode-wisuda',
                    'message' => 'Save Periode Wisuda successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $periodeWisuda                      = PeriodeWisuda::find($id);
                $periodeWisuda->id_wisuda           = $input->id_wisuda;
                $periodeWisuda->id_semester         = $input->id_semester;
                $periodeWisuda->nm_periode_wisuda   = $input->nm_periode_wisuda;
                $periodeWisuda->besar_biaya         = $input->besar_biaya;
                $periodeWisuda->tgl_bayar_mulai     = date_format(date_create($input->tgl_bayar_mulai),"Y-m-d");
                $periodeWisuda->tgl_bayar_selesai   = date_format(date_create($input->tgl_bayar_selesai),"Y-m-d");
                $periodeWisuda->is_aktif            = $input->is_aktif;
                $periodeWisuda->updated_by          = $input->auth_data->pengguna->id_pengguna;
                $periodeWisuda->updated_at          = $now;
                $periodeWisuda->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wisuda/periode-wisuda',
                    'message' => 'Update Periode Wisuda successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($pengajuanWisuda = PengajuanWisuda::where('id_periode_wisuda',$id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Periode Wisuda'
                    ]; 
                }
                else {
                    // make object to find id
                    $periodeWisuda               = PeriodeWisuda::find($id);
                    $periodeWisuda->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $periodeWisuda->save();

                    $periodeWisuda->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Periode Wisuda successfully'
                    ];
                }
            }
        }
    }

}