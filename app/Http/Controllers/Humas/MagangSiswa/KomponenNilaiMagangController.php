<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\KomponenMagang as KomponenMagang;
use App\Models\NilaiMagang as NilaiMagang;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibMagangSiswa;

use Auth;
use DB;
use Session;
use Validator;

class KomponenNilaiMagangController extends BaseController
{
    public function viewKomponenNilaiMagang(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);

    	return view('humas/magang-siswa/komponen-nilai-magang/view-komponen-nilai-magang',compact('auth_data','data_periode'));

    }
    public function actionViewKelasKomponenNilaiMagang(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_periode_magang' => 'required'
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
                    'path' => 'magang-siswa/komponen-nilai-magang/view-periode/'.$input->id_periode_magang
                ];   
        }
    }
    public function viewKelasKomponenNilaiMagang(Request $request, $id_periode_magang){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode = LibMagangSiswa::fetchDataKomponenNilaiMagang($auth_data, $id_periode_magang);

        return view('humas/magang-siswa/komponen-nilai-magang/view-komponen-nilai-magang-periode',compact('auth_data','data_periode','id_periode_magang'));

    }
    public function datatablesKomponenNilaiMagang(Request $request, $id_periode_magang){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibMagangSiswa::fetchDataKomponenNilaiMagangDetail($auth_data, $id_periode_magang);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_komponen_magang
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addKomponenNilai(Request $request, $id_periode_magang){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode = LibMagangSiswa::fetchDataKomponenNilaiMagang($auth_data, $id_periode_magang);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_komponen_magang = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('humas/magang-siswa/komponen-nilai-magang/add-komponen-nilai-magang',compact('auth_data','data_periode','id_komponen_magang','id_periode_magang'));

    }

    public function editKomponenNilai(Request $request, $id_periode_magang, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode = LibMagangSiswa::fetchDataKomponenNilaiMagang($auth_data, $id_periode_magang);

        $list_data = LibMagangSiswa::fetchDataKomponenNilaiMagangDetail($auth_data, $id_periode_magang,$id);
 
        return view('humas/magang-siswa/komponen-nilai-magang/edit-komponen-nilai-magang',compact('auth_data','data_periode','list_data'));

    }

    public function actionKomponenNilaiMagang(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_komponen_magang' => 'required',
            'persentase_komponen_magang' => 'required',
            'urutan_komponen_magang' => 'required',
            // dari type hidden
            'id_periode_magang' => 'required'
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
                $komponenMagang = KomponenMagang::where('id_periode_magang','=',$input->id_periode_magang)->where('urutan_komponen_magang','=',$input->urutan_komponen_magang)->first();

                if($komponenMagang){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai  Magang(Urutan Sudah Ada)!'
                    ];
                }
                else{
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    
                    $komponenMagang                         	= new KomponenMagang;
                    $komponenMagang->id_komponen_magang         = $id;
                    $komponenMagang->id_periode_magang          = $input->id_periode_magang;
                    $komponenMagang->nm_komponen_magang         = $input->nm_komponen_magang;
                    $komponenMagang->persentase_komponen_magang = $input->persentase_komponen_magang;
                    $komponenMagang->urutan_komponen_magang         = $input->urutan_komponen_magang;
                    $komponenMagang->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $komponenMagang->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'magang-siswa/komponen-nilai-magang/view-periode/'.$input->id_periode_magang,
                        'message' => 'Save Komponen Nilai Magang Successfully'
                    ];
                }
            }
            elseif($mode == 'edit') {
                $komponenMagang = KomponenMagang::where('id_periode_magang','=',$input->id_periode_magang)->where('urutan_komponen_magang','=',$input->urutan_komponen_magang)->first();

                if($komponenMagang){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai Magang (Urutan Sudah Ada)!'
                    ];
                }
                else{
                    // make object to find id
                    $komponenMagang                             = KomponenMagang::find($id);
                    $komponenMagang->nm_komponen_magang         = $input->nm_komponen_magang;
                    $komponenMagang->persentase_komponen_magang = $input->persentase_komponen_magang;
                    $komponenMagang->urutan_komponen_magang     = $input->urutan_komponen_magang;
                    $komponenMagang->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                    $komponenMagang->updated_at                 = $now;
                    $komponenMagang->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'magang-siswa/komponen-nilai-magang/view-periode/'.$input->id_periode_magang,
                        'message' => 'Update Komponen Nilai Magang Successfully'
                    ];
                }
            }
            elseif($mode == 'delete') {
                if($nilaiMp = NilaiMagang::where('id_komponen_magang',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Komponen Magang Nilai'
                    ]; 
                }
                else {
                    // make object to find id
                    $komponenMagang               = KomponenMagang::find($id);
                    $komponenMagang->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $komponenMagang->save();

                    $komponenMagang->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Komponen Nilai Magang Successfully'
                    ];
                }
            }
        }
    }

}
