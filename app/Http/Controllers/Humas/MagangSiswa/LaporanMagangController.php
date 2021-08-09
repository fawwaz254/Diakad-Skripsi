<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengambilanMagang as PengajuanSiswaMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\KomponenMagang;
use App\Models\PeraturanNilai as PeraturanNilai;
use App\Models\NilaiMagang as NilaiMagang;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;


class LaporanMagangController extends BaseController
{

  public function viewLaporanMagang(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);

      return view('humas/magang-siswa/laporan-magang/view-laporan-magang',compact('auth_data','data_periode_magang'));
  }

  public function datatablesLaporanMagang(Request $request){

    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    $id_periode_magang = $input->id_periode_magang;

    $data = PengajuanSiswaMagang::with('periode','rekanan')
                                 ->select('id_rekanan_magang','id_periode_magang')
                                 ->groupBy('id_rekanan_magang','id_periode_magang')
                                 ->when($id_periode_magang,function($q) use($id_periode_magang){
                                      $q->where('pengambilan_magang.id_periode_magang',$id_periode_magang);
                                 })    
                                ->get();

    return Datatables::of($data)
                     ->editColumn('nm_periode_magang',function($item){
                        return $item->periode->nm_periode_magang;
                     })
                     ->editColumn('nm_rekanan_magang',function($item){
                        return $item->rekanan->nm_rekanan_magang;
                     })
                     ->addColumn('action', function($item){
                        $data = array(
                            'id' => $item->id_pengambilan_magang,
                            'id_rekanan_magang' => $item->id_rekanan_magang,
                            'id_periode_magang' => $item->id_periode_magang
                        );
                  
                        return $data;
                    })
                    ->make(true);


  }

  public function printLaporanMagang(Request $request,$id_rekanan_magang,$id_periode_magang){

      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $data = PengajuanSiswaMagang::where(['id_rekanan_magang'=>$id_rekanan_magang,'id_periode_magang'=>$id_periode_magang])->first();
      $list_komponen = KomponenMagang::where('id_periode_magang',$id_periode_magang)->get();

      $list_siswa = PengajuanSiswaMagang::join('siswa','siswa.id_siswa','=','pengambilan_magang.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('rekanan_magang','rekanan_magang.id_rekanan_magang','=','pengambilan_magang.id_rekanan_magang')
                        ->join('periode_magang','periode_magang.id_periode_magang','=','pengambilan_magang.id_periode_magang')
                        ->join('semester','semester.id_semester','=','periode_magang.id_semester')
                        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->where('pengambilan_magang.id_periode_magang','=',$id_periode_magang)
                        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->where('status_magang',1)
                        ->get();

      $list_nilai = NilaiMagang::select('nilai_magang.id_pengambilan_magang','nilai_magang.id_komponen_magang','nilai_magang.besar_nilai_magang','komponen_magang.urutan_komponen_magang')->join('komponen_magang','nilai_magang.id_komponen_magang','=','komponen_magang.id_komponen_magang')
          ->where('komponen_magang.id_periode_magang','=',$id_periode_magang)
          ->get();

      $nilai_magang_siswa = [];
      if($list_siswa){
        $nilai = $list_nilai->toArray();
        foreach ($nilai as $komponen => $nilaiMagang) {
            foreach($nilaiMagang as $nama_komponen => $besarNilai){
                $nilai_magang_siswa[$nilaiMagang['id_pengambilan_magang'].$nilaiMagang['id_komponen_magang']] = $nilaiMagang['besar_nilai_magang'];    
            }

        }
      }

      return view('humas/magang-siswa/laporan-magang/print-laporan-magang',compact('data','list_komponen','list_siswa','nilai_magang_siswa'));

  }

}