<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Semester;
use Auth;
use DB;
use Session;
use Validator;

class RaporController extends BaseController
{
    public function viewSisipan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $data_siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $data_anak_murid_aktif->id_pengguna);

        $data_semester_aktif = Semester::where('is_aktif_semester', 1)->first();

        return view('wali-murid/akademik/rapor/view-sisipan', compact('auth_data', 'data_siswa', 'data_semester_aktif'));
    }
}
