<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibSiswa;

class KpiController extends Controller
{
    public function viewKpi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $data_siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $data_anak_murid_aktif->id_pengguna);

        $data_semester_aktif = Semester::where('is_aktif_semester', 1)->first();

        return view('wali-murid/akademik/kpi/view-kpi', compact('auth_data', 'data_siswa', 'data_semester_aktif'));
    }
}
