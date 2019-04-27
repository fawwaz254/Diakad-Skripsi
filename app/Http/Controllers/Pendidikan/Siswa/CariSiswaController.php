<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;

use App\Models\Kota as Kota;
use App\Models\Provinsi as Provinsi;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\Admisi as Admisi;
use App\Models\Siswa as Siswa;


use Auth;
use DB;
use Session;
use Validator;

class CariSiswaController extends BaseController
{
     public function viewCariSiswa(Request $request, $nis_nama_siswa = null){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

    	return view('pendidikan/siswa/cari-siswa/view-cari-siswa',compact('auth_data','nis_nama_siswa'));
  	}

  	public function actionViewCariSiswa(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $validator = Validator::make($request->all(), [
          'nis_nama_siswa' =>'required'
      ]);

      if($validator->fails()) {
          return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
      }
      else {
          return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'siswa/cari-siswa/view-detail/'.$input->nis_nama_siswa
                ];
      }
  }

  public function viewDetailCariSiswa(Request $request, $nis_nama_siswa){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchCariSiswaDetail($auth_data, $nis_nama_siswa);
  
        return view('pendidikan/siswa/cari-siswa/view-cari-siswa',compact('auth_data','nis_nama_siswa'));
  }

    public function datatablesCariSiswa(Request $request, $nis_nama_siswa){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::select('siswa.nis_siswa','siswa.nisn_siswa','pengguna.nm_pengguna','kelas.nm_kelas','status_pengguna.nm_status_pengguna','jalur.nm_jalur')
          ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
          ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
          ->join('status_pengguna','pengguna.id_status_pengguna','=','status_pengguna.id_status_pengguna')
          ->join('jalur_siswa', function ($join) {
                            $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                        })
          ->join('jalur','jalur_siswa.id_jalur','=','jalur.id_jalur')
          ->where(function ($query) use ($nis_nama_siswa) {
                    $query->where('siswa.nis_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('pengguna.nm_pengguna', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('siswa.nisn_siswa', 'like', '%'.$nis_nama_siswa.'%');
             })
          ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
          ->get();
        return Datatables::of($siswa)
                ->addColumn('action', function($item) use($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->nis_siswa,
                        'id_asli' => $nis_nama_siswa
                    );
                    return $data;
                })
                ->make(true);
    }
    public function viewDetailSiswaCariSiswa(Request $request, $nis_siswa, $nis_nama_siswa_asli){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_siswa);
        
        if($siswa->jenis_kelamin == "1"){
          $jenis_kelamin = "Laki-Laki";
        }elseif($siswa->jenis_kelamin == "2"){
          $jenis_kelamin = "Perempuan";
        }else{
          $jenis_kelamin = " ";
        }

        if($siswa->id_kota_lahir == null){
          $kota_lahir = " ";
        }else{
          $kota_lahir = Kota::select('nm_kota')->where('id_kota','=',$siswa->id_kota_lahir)->first();
          $kota_lahir = $kota_lahir->nm_kota;
        }
        
        if($siswa->alamat_jalan == null){
          $alamat_jalan_siswa = '';
        }else{
          $alamat_jalan_siswa = "Jalan ".$siswa->alamat_jalan;
        }

        if($siswa->alamat_dusun == null){
          $alamat_dusun_siswa = '';
        }else{
          $alamat_dusun_siswa = "Dusun ".$siswa->alamat_dusun;
        }

        if($siswa->alamat_kelurahan == null){
          $alamat_kelurahan_siswa = '';
        }else{
          $alamat_kelurahan_siswa = "Kelurahan ".$siswa->alamat_kelurahan;
        }

        if($siswa->alamat_rt == null){
          $alamat_rt_siswa = '';
        }else{
          $alamat_rt_siswa = "RT.".$siswa->alamat_rt;
        }

        if($siswa->alamat_rw == null){
          $alamat_rw_siswa = '';
        }else{
          $alamat_rw_siswa = "RW.".$siswa->alamat_rw;
        }

        if($siswa->alamat_kecamatan == null){
          $alamat_kecamatan_siswa = '';
        }else{
          $alamat_kecamatan_siswa = "Kecamatan ".$siswa->alamat_kecamatan;
        }

        if($siswa->alamat_kodepos == null){
          $alamat_kodepos_siswa = '';
        }else{
          $alamat_kodepos_siswa = "Kodepos ".$siswa->alamat_kodepos;
        }

        if($siswa->alamat_jalan_ortu == null){
          $alamat_jalan_ortu = '';
        }else{
          $alamat_jalan_ortu = "Jalan ".$siswa->alamat_ortu;
        }

        if($siswa->alamat_dusun_ortu == null){
          $alamat_dusun_ortu = '';
        }else{
          $alamat_dusun_ortu = "Dusun ".$siswa->alamat_dusun_ortu;
        }

        if($siswa->alamat_kelurahan_ortu == null){
          $alamat_kelurahan_ortu = '';
        }else{
          $alamat_kelurahan_ortu = "Kelurahan ".$siswa->alamat_kelurahan_ortu;
        }

        if($siswa->almat_rt_ortu == null){
          $alamat_rt_ortu = '';
        }else{
          $alamat_rt_ortu = "RT.".$siswa->almat_rt_ortu;
        }

        if($siswa->alamat_rw_ortu == null){
          $alamat_rw_ortu = '';
        }else{
          $alamat_rw_ortu = "RW.".$siswa->alamat_rw_ortu;
        }

        if($siswa->alamat_kecamatan_ortu == null){
          $alamat_kecamatan_ortu = '';
        }else{
          $alamat_kecamatan_ortu = "Kecamatan ".$siswa->alamat_kecamatan_ortu;
        }

        if($siswa->alamat_kodepos_ortu == null){
          $alamat_kodepos_ortu = '';
        }else{
          $alamat_kodepos_ortu = "Kodepos ".$siswa->alamat_kodepos_ortu;
        }

        if($siswa->alamat_kota_ortu == null){
          $alamat_kota_ortu = "";
        }else{
          $alamat_kota_ortu = Kota::where('id_kota','=',$siswa->alamat_kota_ortu)->first();
          $alamat_kota_ortu = $alamat_kota_ortu->nm_kota;
        }

        if($siswa->alamat_provinsi_ortu == null){
          $alamat_provinsi_ortu = "";
        }else{
          $alamat_provinsi_ortu = Provinsi::where('id_provinsi','=',$siswa->alamat_provinsi_ortu)->first();
          $alamat_provinsi_ortu = $alamat_provinsi_ortu->nm_provinsi;
        }

        $pengambilan = PengambilanMp::select('pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','kelas_mp.nm_kelas_mp','mata_pelajaran.nm_mata_pelajaran','mata_pelajaran.kd_mata_pelajaran','mata_pelajaran.nilai_kkm','mata_pelajaran.kredit_semester','semester.nm_semester','semester.tahun_ajaran')
            ->join('siswa','pengambilan_mp.id_siswa','=','siswa.id_siswa')
            ->join('kelas_mp','kelas_mp.id_kelas_mp','=','pengambilan_mp.id_kelas_mp')
            ->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
            ->join('semester','semester.id_semester','=','pengambilan_mp.id_semester')
            ->where('siswa.nis_siswa','=',$nis_siswa)
            ->where('pengambilan_mp.status_apv_pengambilan_mp','=','1')
            ->get();

          $grup_semester_kelas = $pengambilan->groupBy('tahun_ajaran')->transform(function($item, $k) {
              return $item->groupBy('nm_semester');
          });

          
        $aktivitas = LibSiswa::aktivitasAdmisi($auth_data,$nis_siswa);

        return view('pendidikan/siswa/cari-siswa/view-detail-siswa-cari-siswa',compact('auth_data','nis_siswa','nis_nama_siswa_asli','siswa','jenis_kelamin','kota_lahir','alamat_jalan_siswa','alamat_dusun_siswa','alamat_kelurahan_siswa','alamat_rt_siswa','alamat_rw_siswa','alamat_kecamatan_siswa','alamat_kodepos_siswa','alamat_jalan_ortu','alamat_dusun_ortu','alamat_kelurahan_ortu','alamat_rt_ortu','alamat_rw_ortu','alamat_kecamatan_ortu','alamat_kodepos_ortu','alamat_kota_ortu','alamat_provinsi_ortu','aktivitas','grup_semester_kelas'));
    }
}
