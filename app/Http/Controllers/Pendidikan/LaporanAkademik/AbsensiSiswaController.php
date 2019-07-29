<?php

namespace App\Http\Controllers\Pendidikan\LaporanAkademik;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;

use App\Models\Kota as Kota;
use App\Models\Provinsi as Provinsi;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\Admisi as Admisi;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Semester as Semester;
use App\Models\Jurusan as Jurusan;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;

use Auth;
use DB;
use Session;
use Validator;


class AbsensiSiswaController extends BaseController
{
    public function viewAbsensiSiswa(Request $request, $id_semester = null, $id_jurusan = null, $id_kelas = null, $tgl_mulai = null, $tgl_selesai = null){
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

		$data_kelas = LibKelas::fetchDataKelas($auth_data);      

	    $jurusan = Jurusan::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();

		return view('pendidikan/laporan-akademik/absensi-siswa/view-absensi-siswa',compact('auth_data', 'data_semester', 'data_kelas', 'id_semester', 'id_kelas','jurusan', 'id_jurusan', 'tgl_selesai', 'tgl_mulai'));
  	}

  	public function actionViewAbsensiSiswa(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;
      $validator = Validator::make($request->all(), [
          'tgl_mulai' 	=> 'required',
          'tgl_selesai' => 'required'
         
      ]);
      // dd($input);
      $tgl_mulai 	= strftime( "%Y-%m-%d", strtotime($input->tgl_mulai));
      $tgl_selesai 	= strftime( "%Y-%m-%d", strtotime($input->tgl_selesai));

      if($validator->fails()) {
          return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
      }
      else {
          return [
                	'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'laporan-akademik/absensi-siswa/absensi-kelas/'.$input->id_semester.'/'.$input->id_jurusan.'/'.$input->id_kelas.'/'.$tgl_mulai.'/'.$tgl_selesai
                ];
     	}
  	}
}
