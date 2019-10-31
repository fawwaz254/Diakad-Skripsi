<?php

namespace App\Http\Controllers\PPDB\Penetapan;;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Penetapan as Penetapan;
use App\Models\CalonSiswaBaru as CalonSiswaBaru;
use App\Models\Semester as Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

use Auth;
use DB;
use Session;
use Validator;

class PersidanganController extends BaseController {

    public function viewPersidangan(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester_tahun = LibDataAkademik::fetchDataTahunSemester($auth_data);
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);
        //dd($penerimaan);
        /*$grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });; */

    	return view('ppdb/penetapan/persidangan/view-persidangan',compact('auth_data','data_semester_tahun','penerimaan'));
    }

    public function addPenetapan(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
/*
        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $data_semester_tahun = LibDataAkademik::fetchDataTahunSemester($auth_data);

        $data_semester_nama = LibDataAkademik::fetchDataNmSemester($auth_data);
*/
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_penetapan = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('ppdb/penetapan/data-penetapan/add-penetapan',compact('auth_data','id_penetapan'));
    }

    public function editPenetapan($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan = DB::table('penetapan')->where('id_penetapan',$id)->first();
       // dd($data_penetapan);
        return view('ppdb/penetapan/data-penetapan/edit-penetapan',compact('auth_data','data_penetapan'));

    }

    public function datatablesPenetapan(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        //$list_data = LibPenerimaan::fetchDataPenerimaanAllJenisPenerimaan($auth_data);
        $list_data = Penetapan::orderBy('id_penetapan','desc')->get();

        return Datatables::of($list_data)
                ->addColumn('nm_penetapan', function($item) {
                    if( ! empty($item->nm_penetapan)){
                        return $item->nm_penetapan;
                    }
                    else{
                        return "-";
                    }
                })
                ->addColumn('periode', function($item) {
                    if( ! empty($item->periode)){
                        return $item->periode;
                    }
                    else{
                        return "-";
                    }
                })                
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_penetapan
                    );
                    return $data;
                })
                ->make(true);
    }


    // Action POST
    public function actionPenetapan(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_penetapan'          => 'required',
            'nomor_sk_penetapan'    => 'required',
            'tgl_penetapan'         => 'required',
            'periode'               => 'required',
            'is_aktif'              => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            /*if($mode != 'delete') {
                // get id_semester from tahun and nama semester
                $semester = Semester::select('id_semester')
                                    ->where('thn_akademik_semester','=',$input->tahun_penerimaan)
                                    ->where('nm_semester','=',$input->nm_semester_penerimaan)
                                    ->where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                    ->first();

                // apabila jenis penerimaan untuk siswa lama sebelum siakad
                if($input->jenis_penerimaan == 2) {
                    $id_semester = 0;
                    $id_jalur = 0;
                }
                elseif($input->jenis_penerimaan == 1) {
                    $id_semester = $semester->id_semester;
                    $id_jalur = $input->id_jalur;
                }
            }*/

            // ACTION ADD
            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $penetapan                            = new Penetapan;
                $penetapan->id_penetapan              = $id;
                
                $penetapan->nm_penetapan              = $input->nm_penetapan;
                $penetapan->nomor_sk_penetapan       = $input->nomor_sk_penetapan;
                
                $penetapan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan),"Y-m-d");

                $penetapan->periode                     = $input->periode;
                $penetapan->is_aktif                   = $input->is_aktif;
                $penetapan->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                $penetapan->created_by                 = $input->auth_data->pengguna->id_pengguna;
                $penetapan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penetapan/data-penetapan',
                    'message' => 'Save Penetapan successfully'
                ];
            }
            elseif($mode == 'edit') {
                // make object to find id
                $penetapan                             = Penetapan::find($id);

                $penetapan->id_penetapan              = $id;
                
                $penetapan->nm_penetapan              = $input->nm_penetapan;
                $penetapan->nomor_sk_penetapan       = $input->nomor_sk_penetapan;
                
                $penetapan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan),"Y-m-d");

                $penetapan->periode                     = $input->periode;
                $penetapan->is_aktif                   = $input->is_aktif;
                $penetapan->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                
                $penetapan->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $penetapan->updated_at                 = $now;
                $penetapan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penetapan/data-penetapan',
                    'message' => 'Update Penetapan successfully'
                ];
            }
            elseif($mode == 'delete') {
                
                    // make object to find id
                    $penetapan               = Penetapan::find($id);
                    $penetapan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $penetapan->save();

                    $penetapan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Penetapan successfully'
                    ];
                
            }
        }
    }

}