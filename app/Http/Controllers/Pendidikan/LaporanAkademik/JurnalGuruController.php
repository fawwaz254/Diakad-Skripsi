<?php

namespace App\Http\Controllers\Pendidikan\LaporanAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru as Guru;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class JurnalGuruController extends BaseController{

    public function viewJurnalGuru(Request $request,$id_guru = null ,$id_semester = null){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if (!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

        $selected_guru = null;

        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->get();

    	return view('pendidikan/laporan-akademik/jurnal-guru/view-jurnal-guru',compact('auth_data','selected_guru','selected_semester','data_semester','data_guru'));

    }

}