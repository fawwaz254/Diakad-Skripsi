<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Guru as Guru;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class InventarisKelasController extends BaseController
{
    public function viewInventarisKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        return view('guru/wali-kelas/inventaris-kelas/view-inventaris-kelas', compact('auth_data', 'wali_kelas'));
    }

    public function datatablesInventarisKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $data_ruangan_kelas = LibKelas::fetchDataRuanganKelas($auth_data, $wali_kelas->id_kelas, $semester_aktif->id_semester);

        if ($data_ruangan_kelas) {
            $list_data = LibDataSarpras::fetchDataInventarisRuangan($auth_data, $data_ruangan_kelas->id_ruangan);
        } else {
            $list_data = array();
        }

        return Datatables::of($list_data)
                ->make(true);
    }
}
