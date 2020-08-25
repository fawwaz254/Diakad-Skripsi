<?php

namespace App\Http\Controllers\Guru\GuruPiket;

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

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view(
            'guru/guru-piket/rekap-kesehatan/view-rekap-kesehatan',
            compact('auth_data', 'data_kelas')
        );
    }

    public function viewDetailRekapKesehatan(Request $request, $id_kelas = '-'){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

    	return view('guru/guru-piket/rekap-kesehatan/view-detail-rekap-kesehatan',compact('auth_data', 'data_kelas'));
    }
}
