<?php

namespace App\Http\Controllers\Guru\GuruPiket;

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

class MonitoringKelasKosongController extends BaseController{

    public function viewMonitoringKelasKosong(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('guru/guru-piket/monitoring-kelas-kosong/view-monitoring-kelas-kosong',compact('auth_data'));

    }

    public function datatablesMonitoringKelasKosong(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now();
        $tgl = $now->toDateString();
        $hari = $now->dayOfWeekIso;
        $jam = $now->hour;
        $menit = $now->minute;

        $list_data = DB::select('SELECT jkm.id_jadwal_kelas_mp, mp.nm_mata_pelajaran, k.nm_kelas, r.nm_ruangan, pmp.id_presensi_mp
                                    FROM jadwal_kelas_mp jkm
                                    JOIN ruangan r ON r.id_ruangan = jkm.id_ruangan
                                    JOIN kelas_mp kmp ON kmp.id_kelas_mp = jkm.id_kelas_mp
                                    JOIN kelas k ON k.id_kelas = kmp.id_kelas
                                    JOIN mata_pelajaran mp ON mp.id_mata_pelajaran = kmp.id_mata_pelajaran
                                    JOIN jadwal_jam jj ON jj.id_jadwal_jam = jkm.id_jadwal_jam
                                    JOIN jadwal_jam jjs ON jjs.id_jadwal_jam = jkm.id_jadwal_jam_selesai
                                    LEFT JOIN presensi_mp pmp ON pmp.id_kelas_mp = kmp.id_kelas_mp 
                                        AND DATE(pmp.tgl_entry) = DATE(NOW()) 
                                        AND WEEKDAY(pmp.tgl_entry) = '.$hari.'-1
                                    WHERE jkm.id_jadwal_hari = '.$hari.' 
                                    AND TIME("'.$now.'") BETWEEN TIME(CONCAT(jj.jam_mulai, ":", jj.menit_mulai)) and TIME(CONCAT(jjs.jam_selesai, ":", jjs.menit_selesai))');
                                    
        return Datatables::of($list_data)
                ->addColumn('status', function($item){
                    if(!empty($item->id_presensi_mp)){
                        return 'Sudah absensi kelas';
                    }else{
                        return 'Kelas kosong';
                    }
                })
                ->make(true);
    }

}