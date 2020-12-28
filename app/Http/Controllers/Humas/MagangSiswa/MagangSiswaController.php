<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Magang as MagangSiswa;
use App\Models\PeriodeMagang as PeriodeMagang;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;

use Auth;
use DB;
use Session;
use Validator;

class MagangSiswaController extends BaseController
{
  public function viewMagangSiswa(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

    return view('humas/magang-siswa/nama-magang/view-magang',compact('auth_data'));
  }
  public function datatablesMagangSiswa(Request $request){
      $input = (object) $request->input();
      $auth_data = $input->auth_data;
      $list_data = LibMagangSiswa::fetchDataMagangSiswa($auth_data);

      return Datatables::of($list_data)
              ->addColumn('action', function($item){
                  $data = array(
                      'id' => $item->id_magang
                  );
                  return $data;
              })
              ->make(true);
  }

  public function addMagangSiswa(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      // mengambil waktu sekarang
      $now = Carbon::now(env('APP_TIMEZONE', ''));

      $id_magang = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

      return view('humas/magang-siswa/nama-magang/add-magang',compact('auth_data','id_magang'));
  }

  public function editMagangSiswa($id, Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_magang = LibMagangSiswa::fetchDataMagangSiswa($auth_data, $id);

      return view('humas/magang-siswa/nama-magang/edit-magang',compact('auth_data','data_magang'));

  }

  public function actionMagang(Request $request, $mode, $id = null){
    $input = (object) $request->input();

    $validator = Validator::make($request->all(), [
        'nm_magang' => 'required',
        'keterangan_magang' => 'required'
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

            $magangSiswa                     = new MagangSiswa;
            $magangSiswa->id_magang          = $id;
            $magangSiswa->nm_magang         = $input->nm_magang;
            $magangSiswa->keterangan_magang  = $input->keterangan_magang;
            $magangSiswa->id_sekolah         = $input->auth_data->pengguna->id_sekolah;
            $magangSiswa->created_by         = $input->auth_data->pengguna->id_pengguna;
            $magangSiswa->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'magang-siswa/nama-magang',
                'message' => 'Save Magang Siswa successfully'
            ];
        }
        elseif($mode == 'edit'){
            // make object to find id
            $magangSiswa                     = MagangSiswa::find($id);
            $magangSiswa->nm_magang          = $input->nm_magang;
            $magangSiswa->keterangan_magang  = $input->keterangan_magang;
            $magangSiswa->updated_by         = $input->auth_data->pengguna->id_pengguna;
            $magangSiswa->updated_at         = $now;
            $magangSiswa->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'magang-siswa/nama-magang',
                'message' => 'Update Magang Siswa successfully'
            ];
        }
        elseif($mode == 'delete'){
            if($periodeMagang = PeriodeMagang::where('id_magang',$id)->first()){
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Failed To Delete Magang Siswa'
                ];
            }
            else{
                // make object to find id
                $magangSiswa               = MagangSiswa::find($id);
                $magangSiswa->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $magangSiswa->save();

                $magangSiswa->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Magang Siswa successfully'
                ];
            }
        }
      }
  }
}
