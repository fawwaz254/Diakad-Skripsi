<?php

namespace App\Http\Controllers\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kelas;
use App\Models\Siswa;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController{
    public function indexWelcome(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_tingkat = Kelas::select('tingkat')->distinct()->orderBy('tingkat', 'asc')->get();
        $count_siswa = Siswa::whereNotNull('id_kelas')->count();

        $last_siswa = Siswa::select('created_at')->orderBy('created_at', 'desc')->first();

        return view('kesiswaan/welcome', compact('auth_data', 'data_tingkat', 'count_siswa', 'last_siswa'));
    }

}