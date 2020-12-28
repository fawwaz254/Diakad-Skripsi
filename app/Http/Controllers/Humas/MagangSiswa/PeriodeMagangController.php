<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\PengambilanMagang as PengajuanSiswaMagang;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class PeriodeMagangController extends BaseController
{
  public function viewPeriodeMagang(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      return view('humas/magang-siswa/periode-magang/view-periode-magang',compact('auth_data'));
  }

  public function addPeriodeMagang(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_magang = LibMagangSiswa::fetchDataMagangSiswa($auth_data);

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

      // mengambil waktu sekarang
      $now = Carbon::now(env('APP_TIMEZONE', ''));

      $id_periode_magang = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

      return view('humas/magang-siswa/periode-magang/add-periode-magang',compact('auth_data','data_magang','data_semester','id_periode_magang'));

  }

  public function editPeriodeMagang($id, Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_magang = LibMagangSiswa::fetchDataMagangSiswa($auth_data);

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

      $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data, $id);

      $tgl_mulai = strftime( "%d %B %Y", strtotime($data_periode_magang->tgl_magang_mulai));
      $tgl_selesai = strftime( "%d %B %Y", strtotime($data_periode_magang->tgl_magang_selesai));

      return view('humas/magang-siswa/periode-magang/edit-periode-magang',compact('auth_data','data_magang','data_semester','data_periode_magang','tgl_mulai','tgl_selesai'));

  }

  public function actionPeriodeMagang(Request $request, $mode, $id = null){
    $input = (object) $request->input();

    $validator = Validator::make($request->all(), [
        'id_magang' => 'required',
        'id_semester' => 'required',
        'nm_periode_magang' => 'required',
        'besar_biaya' => 'required',
        'tgl_magang_mulai' => 'required',
        'tgl_magang_selesai' => 'required',
        'is_aktif' => 'required',
        'nomor_sk_periode_magang'=>'required'
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

            $periodeMagang                          = new PeriodeMagang;
            $periodeMagang->id_periode_magang       = $id;
            $periodeMagang->id_magang               = $input->id_magang;
            $periodeMagang->id_semester             = $input->id_semester;
            $periodeMagang->nm_periode_magang       = $input->nm_periode_magang;
            $periodeMagang->besar_biaya             = $input->besar_biaya;
            $periodeMagang->tgl_magang_mulai         = date_format(date_create($input->tgl_magang_mulai),"Y-m-d");
            $periodeMagang->tgl_magang_selesai       = date_format(date_create($input->tgl_magang_selesai),"Y-m-d");
            $periodeMagang->is_aktif                = $input->is_aktif;
            $periodeMagang->nomor_sk_periode_magang = $input->nomor_sk_periode_magang;
            $periodeMagang->created_by              = $input->auth_data->pengguna->id_pengguna;
            $periodeMagang->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'magang-siswa/periode-magang',
                'message' => 'Save Periode Magang successfully'
            ];
        }
        elseif($mode == 'edit'){
            // make object to find id
            $periodeMagang                          = PeriodeMagang::find($id);
            $periodeMagang->id_magang               = $input->id_magang;
            $periodeMagang->id_semester             = $input->id_semester;
            $periodeMagang->nm_periode_magang       = $input->nm_periode_magang;
            $periodeMagang->besar_biaya             = $input->besar_biaya;
            $periodeMagang->tgl_magang_mulai        = date_format(date_create($input->tgl_magang_mulai),"Y-m-d");
            $periodeMagang->tgl_magang_selesai      = date_format(date_create($input->tgl_magang_selesai),"Y-m-d");
            $periodeMagang->is_aktif                = $input->is_aktif;
            $periodeMagang->nomor_sk_periode_magang = $input->nomor_sk_periode_magang;
            $periodeMagang->updated_by              = $input->auth_data->pengguna->id_pengguna;
            $periodeMagang->updated_at              = $now;
            $periodeMagang->save();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'magang-siswa/periode-magang',
                'message' => 'Update Periode Magang successfully'
            ];
        }
        elseif($mode == 'delete'){
            if($pengajuanMagang = PengajuanSiswaMagang::where('id_periode_magang',$id)->first()) {
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Failed To Delete Periode Magang'
                ];
            }
            else {
                // make object to find id
                $periodeMagang              = PeriodeMagang::find($id);
                $periodeMagang->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $periodeMagang->save();

                $periodeMagang->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Periode Magang successfully'
                ];
            }
        }
      }
  }

  public function datatablesPeriodeMagang(Request $request){
      $input = (object) $request->input();
      $auth_data = $input->auth_data;
      $list_data = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);

      return Datatables::of($list_data)
              ->addColumn('semester', function($item){
                  return $item->tahun_ajaran." ".$item->nm_semester;
              })
              ->addColumn('nomor_sk_periode_magang	', function($item){
                  return $item->nomor_sk_periode_magang	;
              })
              ->addColumn('besar_biaya', function($item){
                  return number_format($item->besar_biaya);
              })
              ->addColumn('tgl_mulai', function($item){
                  return strftime( "%d %B %Y", strtotime($item->tgl_magang_mulai));
              })
              ->addColumn('tgl_selesai', function($item){
                  return strftime( "%d %B %Y", strtotime($item->tgl_magang_selesai));
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
                      'id' => $item->id_periode_magang
                  );
                  return $data;
              })
              ->make(true);
  }

}
