<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;

use App\Models\Kelas as Kelas;
use App\Models\Jurusan as Jurusan;

use Auth;
use DB;
use Session;
use Validator;

class SiswaAktifController extends BaseController
{
    public function viewSiswaAktif(Request $request){
	    # code..
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$jurusan = Jurusan::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
		
		return view('pendidikan/siswa/siswa-aktif/view-siswa-aktif',compact('auth_data','jurusan'));
	}
}
