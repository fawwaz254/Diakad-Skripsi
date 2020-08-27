<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Siswa;
use App\Models\WaliMurid;
use App\Models\Pengguna;
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

    public function viewRekapKesehatanSiswa(Request $request, $id_pengguna = '-', $date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pengguna = Pengguna::find($id_pengguna);

    	return view('guru/guru-piket/rekap-kesehatan/view-detail-rekap-kesehatan',compact('auth_data', 'pengguna', 'date'));
    }

    public function viewRekapFormKesehatan(Request $request, $id_bulan = null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if(empty($id_bulan)){
            $id_bulan = $now->month;
        }
        $tahun = $now->year;
        
        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $data_kelas = LibKelas::fetchDataKelas($auth_data, $wali_kelas->id_kelas);        
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas, null, 'all');

        $data_pengisian = PengisianKegiatanHarian::whereBetween('created_at', [$start_month, $end_month])->whereIn('id_pengguna_pengisi', $data_siswa->pluck('id_pengguna'))->get();

        return view('guru/guru-piket/rekap-kesehatan/view-rekap-kesehatan-siswa',compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_kelas', 'data_siswa', 'data_pengisian'));
    }
}
