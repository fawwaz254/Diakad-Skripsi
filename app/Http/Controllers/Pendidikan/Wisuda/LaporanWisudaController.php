<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PengajuanWisuda as PengajuanWisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;

use App\Models\Admisi as Admisi;
use App\Models\Kelas;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\StatusPengguna as StatusPengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibWisuda;

use Auth;
use DB;
use Session;
use Validator;

class LaporanWisudaController extends BaseController{

    public function viewLaporanWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/wisuda/laporan-wisuda/view-laporan-wisuda',compact('auth_data'));

    }

}