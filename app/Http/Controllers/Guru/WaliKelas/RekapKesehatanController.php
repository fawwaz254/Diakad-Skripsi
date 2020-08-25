<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Siswa;
use App\Models\WaliMurid;
use App\Models\Bulan;
use App\Models\Guru;
use App\Models\PresensiHarian;
use App\Models\PresensiHarianSiswa;
use App\Models\PengisianKegiatanHarian;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\LibGlobal;

use Auth;
use DB;
use Session;
use Validator;

class RekapKesehatanController extends BaseController
{

    public function viewRekapKesehatan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $wali_kelas->id_kelas);

    	return view('guru/guru-piket/rekap-kesehatan/view-detail-rekap-kesehatan',compact('auth_data', 'data_kelas'));
    }
}
