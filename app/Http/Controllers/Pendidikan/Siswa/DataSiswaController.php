<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Jurusan as Jurusan;
use App\Models\Jalur as Jalur;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Kelas as Kelas;
use Illuminate\Support\Collection;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class DataSiswaController extends BaseController
{
    public function viewDataSiswa(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

	    $jurusan = Jurusan::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();
	    $jalur = Jalur::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();
	    $status_pengguna = StatusPengguna::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
            ->where('status_join_table','=',3)
            ->orderBy('nm_status_pengguna')
	    	->get();
	    $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')
                                  ->distinct()
                                  ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                  ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                  ->orderBy('thn_masuk_siswa', 'ASC')->get();

    	return view('pendidikan/siswa/data-siswa/view-data-siswa',compact('auth_data','jurusan','jalur','status_pengguna','thn_masuk_siswa'));
  	}
  	
  	public function getKelas($id_jurusan)
    {
        $kelas = Kelas::where('id_jurusan','=',$id_jurusan)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return response()->json($kelas);
    }
    
    public function actionViewDataSiswa(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;
      $validator = Validator::make($request->all(), [
         
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
                'path' => 'siswa/data-siswa/view-detail-data-siswa/'.$input->id_jurusan.'/'.$input->id_kelas.'/'.$input->thn_masuk_siswa.'/'.$input->id_jalur.'/'.$input->id_status_pengguna. '/'. $input->filter_by
            ];
     	}
  	}

    public function viewDetailDataSiswa(Request $request, $id_jurusan,$id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna, $filter_by){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);

        $filter_siswa = new Collection();
        $collection = collect($siswa->all());
        $collection->filter(function($item) use ($filter_by, $filter_siswa) {
            $hurufPertama = substr($item->nm_pengguna, 0, 1);

            if($hurufPertama == $filter_by) {
            $filter_siswa->push($item);
            }
        });

        $jurusan = Jurusan::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();
        $kelas = Kelas::where('id_jurusan','=',$id_jurusan)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $jalur = Jalur::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();
        $status_pengguna = StatusPengguna::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
          ->where('status_join_table','=',3)
          ->get();
        $thn_masuk_siswa_list = Siswa::select('thn_masuk_siswa')
                                  ->distinct()
                                  ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                  ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                  ->orderBy('thn_masuk_siswa', 'ASC')->get();
        
        if ($filter_by == '0') {
            return view('pendidikan/siswa/data-siswa/view-detail-data-siswa',compact('auth_data','id_jurusan','id_kelas','thn_masuk_siswa','id_jalur','id_status_pengguna','jurusan','kelas','jalur','status_pengguna','thn_masuk_siswa_list','siswa'));
        } else {
            return view('pendidikan/siswa/data-siswa/view-detail-data-siswa',compact('auth_data','id_jurusan','id_kelas','thn_masuk_siswa','id_jalur','id_status_pengguna','jurusan','kelas','jalur','status_pengguna','thn_masuk_siswa_list','filter_siswa'));
        }
    }
    public function datatablesDataSiswa(Request $request, $id_jurusan,$id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);

        $filter_siswa = new Collection();
        $collection = collect($siswa->all());
        $collection->filter(function($item) use ($input, $filter_siswa) {
            $hurufPertama = substr($item->nm_pengguna, 0, 1);

            if($hurufPertama == $input->filter_by) {
            $filter_siswa->push($item);
            }
        });

        if ($input->filter_by == '0') {
            return Datatables::of($siswa)
            ->addColumn('nomor_pendaftaran', function($item){
                return $item->kode_voucher;
            })
            ->addColumn('thn_masuk_siswa', function($item){
                return $item->thn_masuk_siswa;
            })
            ->addColumn('alamat', function($item){
                return $item->alamat_jalan." Dusun ".$item->alamat_dusun." Kelurahan ".$item->alamat_kelurahan." RT".$item->alamat_rt." RW".$item->alamat_rw." Kecamatan ".$item->alamat_kec." Kodepos: ".$item->alamat_kodepos." Kota ".$item->nm_kota." Provinsi ".$item->nm_provinsi;
            })
            ->make(true);
        } else {
            return Datatables::of($filter_siswa)
            ->addColumn('nomor_pendaftaran', function($item){
                return $item->kode_voucher;
            })
            ->addColumn('thn_masuk_siswa', function($item){
                return $item->thn_masuk_siswa;
            })
            ->addColumn('alamat', function($item){
                return $item->alamat_jalan." Dusun ".$item->alamat_dusun." Kelurahan ".$item->alamat_kelurahan." RT".$item->alamat_rt." RW".$item->alamat_rw." Kecamatan ".$item->alamat_kec." Kodepos: ".$item->alamat_kodepos." Kota ".$item->nm_kota." Provinsi ".$item->nm_provinsi;
            })
            ->make(true);
        }
    }
}
