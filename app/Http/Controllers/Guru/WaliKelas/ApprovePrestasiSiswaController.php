<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\KegiatanSiswa;
use App\Models\PrestasiSiswa;
use App\Models\TingkatPrestasiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class ApprovePrestasiSiswaController extends BaseController{

    public function viewApprovePrestasiSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('guru/wali-kelas/approve-prestasi-siswa/view-approve-prestasi-siswa',compact('auth_data'));

    }
}