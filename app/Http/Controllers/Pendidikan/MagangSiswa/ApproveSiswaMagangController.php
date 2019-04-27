<?php

namespace App\Http\Controllers\Pendidikan\MagangSiswa;

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

use Auth;
use DB;
use Session;
use Validator;


class ApproveSiswaMagangController extends BaseController
{
    public function viewApproveSiswaMagang(Request $request){
        # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
      $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);

    	return view('pendidikan/magang-siswa/approve-siswa-magang/view-approve-siswa-magang',compact('auth_data','data_periode_magang','data_rekanan_magang'));

    }
    public function actionViewDetailApproveSiswaMagang(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            /*'id_semester' => 'required'*/
            'id_rekanan_magang' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            if(! empty($input->nis_nama_siswa)) {
                return [
                            'status' => 204, // SUCCESS AND LOAD CONTENT
                            'path' => 'magang-siswa/approve-siswa-magang/view-detail/'.$input->id_periode_magang.'/'.$input->id_rekanan_magang.'/'.$input->nis_nama_siswa
                        ];
            }
            else {
                return [
                            'status' => 204, // SUCCESS AND LOAD CONTENT
                            'path' => 'magang-siswa/approve-siswa-magang/view-detail/'.$input->id_periode_magang.'/'.$input->id_rekanan_magang.'/0'
                        ];
            }   
        }
    }
    public function viewDetailApproveSiswaMagang(Request $request, $id_periode_magang,$id_rekanan_magang, $nis_nama_siswa){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data,$id_periode_magang);
      	$data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data,$id_rekanan_magang);


        return view('pendidikan/magang-siswa/approve-siswa-magang/view-detail-approve-siswa-magang',compact('auth_data','id_periode_magang','data_periode_magang', 'nis_nama_siswa','id_rekanan_magang','data_rekanan_magang'));

    }
    public function datatablesApproveSiswaMagang(Request $request, $id_periode_magang, $id_rekanan_magang,$nis_nama_siswa){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibMagangSiswa::fetchDataApproveSiswaMagang($auth_data, $id_periode_magang, $id_rekanan_magang,$nis_nama_siswa);

        return Datatables::of($list_data)
                ->addColumn('nm_periode_magang', function($item){
                    if(! empty($item->id_pengambilan_magang) && empty($item->id_periode_magang)) {
                        return "Diajukan Di Periode Lain";
                    }
                    else {
                        return $item->nm_periode_magang;
                    }
                })
                ->addColumn('semester', function($item){
                    if(! empty($item->id_pengambilan_magang) && empty($item->id_periode_magang)) {
                        return "Diajukan Di Periode Lain";
                    }
                    else {
                        return $item->tahun_ajaran." ".$item->nm_semester;
                    }
                })
                ->addColumn('nm_rekanan_magang', function($item){
                    if(! empty($item->id_rekanan_magang) && empty($item->id_rekanan_magang)) {
                        return "Diajukan Pada Rekanan Lain";
                    }
                    else {
                        return $item->nm_rekanan_magang;
                    }
                })
                ->addColumn('status_apv_pengambilan_magang', function($item){
                    if($item->status_apv_pengambilan_magang== 0) {
                        return "Belum di Approve";
                    }
                    else {
                        return "Sudah di Approve";
                    }
                })
                ->addColumn('status_magang', function($item){
                    if($item->status_magang == 0) {
                        return "Dalam Proses Magang";
                    }
                    elseif($item->status_magang == 1) {
                        return "Sudah Selesai Magang";
                    }
                    else{
                    	return "Magang dibatalkan";
                    }
                })
                 ->addColumn('keterangan_batal', function($item){
                    if(! empty($item->keterangan_batal) && empty($item->keterangan_batal)) {
                        return "-";
                    }
                    else {
                        return $item->keterangan_batal;
                    }
                })
                ->addColumn('action', function($item){
                    if(! empty($item->id_pengambilan_magang)) {
                        $data = array(
                            'id' => $item->id_pengambilan_magang,
                            'id_rekanan_magang' => $item->id_rekanan_magang,
                            'id_siswa' => $item->id_siswa,
                            'id_periode_magang' => $item->id_periode_magang,
                            'status_magang' => $item->status_magang,
                            'status_apv_pengambilan_magang' => $item->status_apv_pengambilan_magang
                        );
                    }
                    else {
                        $data = array(
                            'id' => null,
                            'id_siswa' => $item->id_siswa,
                            'id_rekanan_magang' => $item->id_rekanan_magang,
                            'id_periode_magang' => $item->id_periode_magang,
                            'status_magang' => $item->status_magang,
                            'status_apv_pengambilan_magang' => $item->status_apv_pengambilan_magang
                            
                        );
                    }
                    
                    return $data;
                })
                ->make(true);
    }

}