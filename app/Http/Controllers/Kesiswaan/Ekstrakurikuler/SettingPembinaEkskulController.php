<?php

namespace App\Http\Controllers\Kesiswaan\Ekstrakurikuler;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul as Ekskul;
use App\Models\PembinaEkskulSet as PembinaEkskulSet;
use App\Models\Guru as Guru;
use App\Models\Kelas as Kelas;
use App\Models\EkskulWajib as EkskulWajib;

use Auth;
use DB;
use Session;
use Validator;

class SettingPembinaEkskulController extends BaseController
{
    public function viewSettingPembinaEkskul(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('kesiswaan/ekstrakurikuler/setting-pembina-ekskul/view-setting-pembina-ekskul',compact('auth_data'));
    }

    public function assignSettingPembinaEkskul(Request $request,$id){
          # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

          // mengambil waktu sekarang
      $now    = Carbon::now(env('APP_TIMEZONE', ''));
      $pembina = Guru::select('pengguna.nm_pengguna','ekskul.id_ekskul','guru.id_guru','ekskul.nm_ekskul','guru.nip_guru')
        ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
        ->leftJoin('pembina_ekskul_set','pembina_ekskul_set.id_guru','=','guru.id_guru')
        ->leftJoin('ekskul','ekskul.id_ekskul','=','pembina_ekskul_set.id_ekskul')
        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
        ->first();
      $data_guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

      $ekskul = Ekskul::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

          // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

      return view('kesiswaan/ekstrakurikuler/setting-pembina-ekskul/assign-setting-pembina-ekskul',compact('auth_data','pembina','ekskul','data_guru','id'));

    }

    public function datatablesSettingPembinaEkskul(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PembinaEkskulSet::select('pengguna.nm_pengguna','pengguna.gelar_depan','pengguna.gelar_belakang','pembina_ekskul_set.is_aktif','ekskul.nm_ekskul','guru.id_guru','guru.nip_guru','pembina_ekskul_set.id_pembina_ekskul_set')
        ->join('guru','guru.id_guru','=','pembina_ekskul_set.id_guru')
        ->join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
        ->join('ekskul','ekskul.id_ekskul','=','pembina_ekskul_set.id_ekskul')
        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
       			->addColumn('is_aktif', function($item){
                  if($item->is_aktif == "0"){
                      return "Tidak Aktif";
                  }
                  else{
                      return "Aktif";
                  }
              })
            ->addColumn('nm_pengguna', function($item){
                    if( ! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    }
                    elseif( ! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;   
                    }
                    elseif( ! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;   
                    }
                    else {
                        return $item->nm_pengguna; 
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_guru,
                        'id_pembina' => $item->id_pembina_ekskul_set
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionSettingPembinaEkskul(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'id_ekskul'     => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
           if($mode == 'assign'){
                if(PembinaEkskulSet::where('id_guru','=',$input->id_guru)->where('id_ekskul','=',$input->id_ekskul)->first()){
                $pembina = PembinaEkskulSet::where('id_guru','=',$input->id_guru)->where('id_ekskul','=',$input->id_ekskul)->first();
                $pembina->id_guru               = $input->id_guru;
                $pembina->id_ekskul             = $input->id_ekskul;
                $pembina->is_aktif              = $input->is_aktif;
                $pembina->updated_at            = $now;
                $pembina->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $pembina->save();
              }else{
                $pembina = new PembinaEkskulSet;
                $pembina->id_pembina_ekskul_set = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pembina->id_guru               = $input->id_guru;
                $pembina->id_ekskul             = $input->id_ekskul;
                $pembina->is_aktif              = $input->is_aktif;
                $pembina->created_by            = $input->auth_data->pengguna->id_pengguna;
                $pembina->created_at            = $now;
                $pembina->save();
              }
              return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/setting-pembina-ekskul',
                    'message' => 'Save Data pembina  Ekskul Successfully'
                ];      
          }elseif($mode == 'delete'){
            $ekskul         = PembinaEkskulSet::find($id);
                    $ekskul->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $ekskul->save();

                    $ekskul->delete();

                // $detail        = PelatihEkskul::where('id_pelatih_ekskul','=',$id)->first();
                // $pengguna        = Pengguna::find($detail->id_pengguna);
                // $pengguna->deleted_by  =  $input->auth_data->pengguna->id_pengguna;
                // $pengguna->save();

                // $pengguna->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Unassign Pembina Ekskul Successfully'
                    ];
          }
        }
    }
}
