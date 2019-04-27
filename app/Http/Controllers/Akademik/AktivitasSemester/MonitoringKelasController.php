<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum as Kurikulum;
use App\Models\MataPelajaran as MataPelajaran;
use App\Models\KelasMp as KelasMp;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use App\Models\PengambilanMp as PengambilanMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class MonitoringKelasController extends BaseController
{
    public function viewMonitoringKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        // dd($data_semester);

        return view('akademik/aktivitas-semester/monitoring-kelas/view-monitoring-kelas',compact('auth_data','data_semester'));

    }

    public function actionViewMonitoringKelas(Request $request){
      # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$validator = Validator::make($request->all(), [
			'id_semester' =>'required'
		]);

		if($validator->fails()) {
			return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
      	}
      	else {
	      	return [
	                'status' => 204, // SUCCESS AND LOAD CONTENT
	                'path' => 'aktivitas-semester/monitoring-kelas/view-semester-monitoring-kelas/'.$input->id_semester
	            ];
        }
    }

    public function viewSemesterMonitoringKelas(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);   

        return view('akademik/aktivitas-semester/monitoring-kelas/view-semester-monitoring-kelas',compact('auth_data','data_semester','id'));

    }

    public function viewDaftarSiswa(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = PengambilanMp::where('id_kelas_mp','=',$id)->first(); 

        return view('akademik/aktivitas-semester/monitoring-kelas/view-daftar-siswa',compact('auth_data','data_semester','id'));

    }

    public function datatablesMonitoringKelas(Request $request, $id){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = JadwalKelasMp::select('mata_pelajaran.nm_mata_pelajaran','mata_pelajaran.kd_mata_pelajaran','kelas.nm_kelas','jadwal_hari.nm_jadwal_hari','jadwal_jam.nm_jadwal_jam',DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"),'kelas_mp.id_kelas_mp','mata_pelajaran.kredit_semester','ruangan.kapasitas_ruangan')
			->join('kelas_mp','kelas_mp.id_kelas_mp','=','jadwal_kelas_mp.id_kelas_mp')
			->join('jadwal_hari','jadwal_hari.id_jadwal_hari','=','jadwal_kelas_mp.id_jadwal_hari')
			->join('jadwal_jam','jadwal_jam.id_jadwal_jam','=','jadwal_kelas_mp.id_jadwal_jam')
			->join('kelas','kelas.id_kelas','=','kelas_mp.id_kelas')
			->join('mata_pelajaran','mata_pelajaran.id_mata_pelajaran','=','kelas_mp.id_mata_pelajaran')
			->leftJoin('ruangan','ruangan.id_ruangan','=','jadwal_kelas_mp.id_ruangan')
			->where('kelas_mp.id_semester','=',$id)
            ->orderBy('kelas.nm_kelas','ASC')
            ->orderBy('mata_pelajaran.nm_mata_pelajaran','ASC')
            ->orderBy('jadwal_kelas_mp.id_jadwal_hari','ASC')
            ->get();

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kelas_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesDaftarSiswa(Request $request, $id){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PengambilanMp::join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
        	->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
        	->join('kelas_mp','kelas_mp.id_kelas_mp','=','pengambilan_mp.id_kelas_mp')
        	->where('pengambilan_mp.id_kelas_mp','=',$id)
        	->where('pengambilan_mp.status_apv_pengambilan_mp','=','1')
            ->orderBy('siswa.nis_siswa','ASC')
        	->get();

        return Datatables::of($list_data)->make(true);
    }
}