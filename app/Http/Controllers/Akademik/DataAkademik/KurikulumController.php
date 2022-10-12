<?php

namespace App\Http\Controllers\Akademik\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum as Kurikulum;
use App\Models\KurikulumMp as KurikulumMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class KurikulumController extends BaseController{

    public function viewKurikulum(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/data-akademik/kurikulum/view-kurikulum',compact('auth_data'));

    }

    public function addKurikulum(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kurikulum = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('akademik/data-akademik/kurikulum/add-kurikulum',compact('auth_data','data_jurusan','data_semester','id_kurikulum'));

    }

    public function editKurikulum($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kurikulum = LibAkademik::fetchDataKurikulum($auth_data, $id);

        // convert format date
        $berlaku_mulai = strftime( "%d %B %Y", strtotime($data_kurikulum->berlaku_mulai));
        $berlaku_sampai = strftime( "%d %B %Y", strtotime($data_kurikulum->berlaku_sampai));

        return view('akademik/data-akademik/kurikulum/edit-kurikulum',compact('auth_data','data_jurusan','data_semester','data_kurikulum','berlaku_mulai','berlaku_sampai'));


    }

    public function datatablesKurikulum(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibAkademik::fetchDataKurikulum($auth_data);

        return Datatables::of($list_data)
                ->addColumn('semester_mulai', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('berlaku_mulai', function($item){
                    return strftime( "%d %B %Y", strtotime($item->berlaku_mulai));
                })
                ->addColumn('berlaku_sampai', function($item){
                    return strftime( "%d %B %Y", strtotime($item->berlaku_sampai));
                })
                ->addColumn('status_aktif', function($item){
                    if($item->is_aktif == 0){
                        return "Non-Aktif";
                    }
                    else{
                        return "Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kurikulum
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKurikulum(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jurusan'            => 'required',
            'id_semester_mulai'     => 'required',
            'nm_kurikulum'          => 'required',
            'tahun_kurikulum'       => 'required',
            'nomor_sk_kurikulum'    => 'required',
            'keterangan_kurikulum'  => 'required',
            'berlaku_mulai'         => 'required',
            'berlaku_sampai'        => 'required'
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

                $kurikulum                              = new Kurikulum;
                $kurikulum->id_kurikulum                = $id;
                $kurikulum->id_jurusan                  = $input->id_jurusan;
                $kurikulum->id_semester_mulai           = $input->id_semester_mulai;
                $kurikulum->nm_kurikulum                = $input->nm_kurikulum;
                $kurikulum->tahun_kurikulum             = $input->tahun_kurikulum;
                $kurikulum->nomor_sk_kurikulum          = $input->nomor_sk_kurikulum;
                $kurikulum->keterangan_kurikulum        = $input->keterangan_kurikulum;
                // convert format date
                $kurikulum->berlaku_mulai               = date_format(date_create($input->berlaku_mulai),"Y-m-d");
                $kurikulum->berlaku_sampai              = date_format(date_create($input->berlaku_sampai),"Y-m-d");
                $kurikulum->is_aktif                    = 0;
                $kurikulum->created_by                  = $input->auth_data->pengguna->id_pengguna;
                $kurikulum->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/kurikulum',
                    'message' => 'Save Kurikulum Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $kurikulum                              = Kurikulum::find($id);
                $kurikulum->id_jurusan                  = $input->id_jurusan;
                $kurikulum->id_semester_mulai           = $input->id_semester_mulai;
                $kurikulum->nm_kurikulum                = $input->nm_kurikulum;
                $kurikulum->tahun_kurikulum             = $input->tahun_kurikulum;
                $kurikulum->nomor_sk_kurikulum          = $input->nomor_sk_kurikulum;
                $kurikulum->keterangan_kurikulum        = $input->keterangan_kurikulum;
                // convert format date
                $kurikulum->berlaku_mulai               = date_format(date_create($input->berlaku_mulai),"Y-m-d");
                $kurikulum->berlaku_sampai              = date_format(date_create($input->berlaku_sampai),"Y-m-d");
                $kurikulum->updated_by                  = $input->auth_data->pengguna->id_pengguna;
                $kurikulum->updated_at                  = $now;
                $kurikulum->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/kurikulum',
                    'message' => 'Update Kurikulum Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($kurikulumMp = KurikulumMp::where('id_kurikulum',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kurikulum'
                    ]; 
                }
                else{
                    // make object to find id
                    $kurikulum               = Kurikulum::find($id);
                    $kurikulum->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kurikulum->save();

                    $kurikulum->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kurikulum Successfully'
                    ];
                }
            }
        }
    }


}
