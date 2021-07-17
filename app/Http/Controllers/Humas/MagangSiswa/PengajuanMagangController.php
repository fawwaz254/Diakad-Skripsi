<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengambilanMagang as PengajuanSiswaMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;


class PengajuanMagangController extends BaseController
{

  public function viewPengajuanMagang(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
      $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);

      return view('humas/magang-siswa/pengajuan-magang/view-pengajuan-magang',compact('auth_data','data_periode_magang','data_rekanan_magang'));
  }

  public function datatablesPengajuanMagang(Request $request){

    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    $id_periode_magang = $input->id_periode_magang;

    $data = PengajuanSiswaMagang::select('nm_periode_magang','nm_rekanan_magang','nis_siswa','pengguna.nm_pengguna', 'kelas.nm_kelas','semester.tahun_ajaran','semester.nm_semester','pengambilan_magang.status_apv_pengambilan_magang','status_magang','siswa.id_siswa','id_pengambilan_magang')
                        ->join('siswa','siswa.id_siswa','=','pengambilan_magang.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                        ->join('rekanan_magang','rekanan_magang.id_rekanan_magang','=','pengambilan_magang.id_rekanan_magang')
                        ->join('periode_magang','periode_magang.id_periode_magang','=','pengambilan_magang.id_periode_magang')
                        ->join('semester','semester.id_semester','=','periode_magang.id_semester')
                        ->when($id_periode_magang,function($q) use($id_periode_magang){
                            $q->where('pengambilan_magang.id_periode_magang',$id_periode_magang);
                        })
                        ->where('pengambilan_magang.id_rekanan_magang',$input->id_rekanan_magang)
                        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->get();

    return Datatables::of($data)
                ->addColumn('semester', function($item){     
                        return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('status_apv_pengambilan_magang', function($item){
                    if($item->status_apv_pengambilan_magang== 0) {
                        return "Belum di Approve";
                    }
                    elseif($item->status_apv_pengambilan_magang==1) {
                        return "Sudah di Approve";
                    }
                    elseif($item->status_apv_pengambilan_magang==2) {
                        return "Waiting Approval";
                    }
                    elseif($item->status_apv_pengambilan_magang==3) {
                        return "Tidak di Approve";
                    }
                })
                ->addColumn('status_magang', function($item){
                    if($item->status_magang == 0) {
                        return "";
                    }
                    elseif($item->status_magang == 1) {
                        return "Sudah Selesai Magang";
                    }
                    else{
                      return "Pemagang Dibatalkan";
                    }
                })
  
                ->addColumn('action', function($item){
                        $data = array(
                            'id' => $item->id_pengambilan_magang,
                            'status_apv_pengambilan_magang' => $item->status_apv_pengambilan_magang,
                            'id_siswa' => $item->id_siswa
                        );
                  
                        return $data;
                })
                ->make(true);

  }

  public function addPengajuanMagang(Request $request,$id_rekanan_magang,$id_periode_magang){

      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data,$id_rekanan_magang);
      $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data,$id_periode_magang);

      return view('humas/magang-siswa/pengajuan-magang/add-pengajuan-magang',compact('auth_data','data_periode_magang','data_rekanan_magang'));

  }

  public function datatablesListSiswa(Request $request,$id_rekanan_magang,$id_periode_magang){

    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    $data_siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang','pengambilan_magang.id_rekanan_magang','pengambilan_magang.id_periode_magang','pengambilan_magang.status_apv_pengambilan_magang','pengambilan_magang.status_magang','siswa.id_siswa', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas')
                        ->leftJoin('pengambilan_magang', function ($join) use($id_rekanan_magang,$id_periode_magang) {
                                $join->on('pengambilan_magang.id_siswa','=','siswa.id_siswa')
                                     ->where('pengambilan_magang.status_magang', '<>', 10)
                                     ->whereNull('pengambilan_magang.deleted_at')
                                     ->where('pengambilan_magang.id_rekanan_magang','=',$id_rekanan_magang)
                                     ->where('pengambilan_magang.id_periode_magang','=',$id_periode_magang);                     
                            })
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                        ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('status_pengguna.aktif_status_pengguna','=',1)
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('siswa.nis_siswa', 'asc');

    return Datatables::of($data_siswa)
                      ->addColumn('status_apv_pengambilan_magang', function($item){
                          if($item->status_apv_pengambilan_magang){
                            if($item->status_apv_pengambilan_magang== 0) {
                              return "Belum di Approve";
                            }
                            elseif($item->status_apv_pengambilan_magang==1) {
                                return "Sudah di Approve";
                            }
                            elseif($item->status_apv_pengambilan_magang==2) {
                                return "Waiting Approval";
                            }
                            elseif($item->status_apv_pengambilan_magang==3) {
                                return "Tidak di Approve";
                            }  
                          }

                          else{
                            return '';
                          }
                          
                      })    
                      ->addColumn('action', function($item){
                        $data = array(
                            'id' => $item->id_siswa,
                            'id_pengambilan_magang' => $item->id_pengambilan_magang,
                            'status_apv_pengambilan_magang' => $item->status_apv_pengambilan_magang
                        );
                  
                        return $data;
                      })
                      ->make(true);

  }

  public function actionPengajuanMagang(Request $request){

    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    $now = Carbon::now(env('APP_TIMEZONE', ''));

    $id_pengambilan_magang = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

    if(!isset($input->id_pengambilan_magang)){
       $siswa  = Siswa::find($input->id_siswa);
    }

    if($input->mode == 'pengajuan'){

      $data                        = new PengajuanSiswaMagang;
      $data->id_pengambilan_magang = $id_pengambilan_magang;
      $data->id_siswa              = $input->id_siswa;
      $data->id_kelas              = $siswa->id_kelas;
      $data->id_periode_magang     = $input->id_periode_magang;
      $data->id_rekanan_magang     = $input->id_rekanan_magang;
      $data->status_apv_pengambilan_magang         = $input->status_apv_pengambilan_magang;
      $data->status_magang         = 0;
      $data->created_by            = $input->auth_data->pengguna->id_pengguna;
      $data->save();

      $message = 'Pengajuan Siswa Magang Successfully';
    }

    elseif($input->mode == 'pengubahan-status-magang'){
      $data = PengajuanSiswaMagang::find($input->id_pengambilan_magang);
      $data->status_magang = $input->status_magang;
      $data->updated_by            = $input->auth_data->pengguna->id_pengguna;
      $data->save();

      $message = 'Perngubahan Status Magang Successfully';


    }

    elseif($input->mode == 'tidak-diapprove'){

      if(isset($input->id_pengambilan_magang)){

        $data = PengajuanSiswaMagang::find($input->id_pengambilan_magang);

      }

      else{

        $data = PengajuanSiswaMagang::where([
          'id_siswa' => $siswa->id_siswa,
          'id_rekanan_magang' => $input->id_rekanan_magang,
          'id_periode_magang' => $input->id_periode_magang
        ])->first();

      }

      $data->status_apv_pengambilan_magang = $input->status_apv_pengambilan_magang;
      $data->keterangan_approval = $input->keterangan;
      $data->updated_by            = $input->auth_data->pengguna->id_pengguna;
      $data->save();

      $message = 'Perngubahan Status Tidak Diapprove Successfully';

    }

    elseif($input->mode == 'hapus-data'){

      if(isset($input->id_pengambilan_magang)){

        $data = PengajuanSiswaMagang::find($input->id_pengambilan_magang);

      }

      else{

        $data = PengajuanSiswaMagang::where([
          'id_siswa' => $siswa->id_siswa,
          'id_rekanan_magang' => $input->id_rekanan_magang,
          'id_periode_magang' => $input->id_periode_magang
        ])->first();

      }

      $data->deleted_by            = $input->auth_data->pengguna->id_pengguna;
      $data->save();
      $data->delete();

      $message = 'Penghapusan Data Successfully';
    }

    return [
        'status' => 205, // SUCCESS AND LOAD TABLE
        'message' => $message
    ];

  }

}