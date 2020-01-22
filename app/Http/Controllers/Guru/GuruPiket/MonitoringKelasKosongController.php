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

class MonitoringKelasKosongController extends BaseController
{
    public function viewMonitoringKelasKosong(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/guru-piket/monitoring-kelas-kosong/view-monitoring-kelas-kosong', compact('auth_data'));
    }

    public function datatablesMonitoringKelasKosong(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $tgl = $now->toDateString();
        $hari = $now->dayOfWeekIso;
        $jam = $now->hour;
        $menit = $now->minute;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = DB::select('SELECT jkm.id_jadwal_kelas_mp, mp.nm_mata_pelajaran, k.tingkat, k.nm_kelas, r.nm_ruangan, pmp.id_presensi_mp, p.nm_pengguna, p.gelar_depan, p.gelar_belakang
                                    FROM jadwal_kelas_mp jkm
                                    JOIN ruangan r ON r.id_ruangan = jkm.id_ruangan AND r.deleted_at IS NULL
                                    JOIN kelas_mp kmp ON kmp.id_kelas_mp = jkm.id_kelas_mp AND kmp.deleted_at IS NULL
                                    JOIN kelas k ON k.id_kelas = kmp.id_kelas AND k.deleted_at IS NULL
                                    JOIN mata_pelajaran mp ON mp.id_mata_pelajaran = kmp.id_mata_pelajaran AND mp.deleted_at IS NULL
                                    JOIN jadwal_jam jj ON jj.id_jadwal_jam = jkm.id_jadwal_jam AND jj.deleted_at IS NULL
                                    JOIN jadwal_jam jjs ON jjs.id_jadwal_jam = jkm.id_jadwal_jam_selesai AND jjs.deleted_at IS NULL
                                    LEFT JOIN pengampu_mp pm ON pm.id_kelas_mp = kmp.id_kelas_mp AND pm.pjmp_pengampu_mp = 1 AND pm.deleted_at IS NULL
                                    JOIN guru g ON g.id_guru = pm.id_guru AND g.deleted_at IS NULL
                                    JOIN pengguna p ON p.id_pengguna = g.id_pengguna AND p.deleted_at IS NULL
                                    LEFT JOIN presensi_mp pmp ON pmp.id_kelas_mp = kmp.id_kelas_mp 
                                        AND DATE(pmp.tgl_entry) = DATE(NOW()) 
                                        AND WEEKDAY(pmp.tgl_entry) = '.$hari.'-1
                                        AND pmp.deleted_at IS NULL
                                    WHERE jkm.id_jadwal_hari = '.$hari.' 
                                    AND jkm.deleted_at IS NULL
                                    AND kmp.id_semester = "'.$semester_aktif->id_semester.'"
                                    AND TIME("'.$now.'") BETWEEN TIME(CONCAT(jj.jam_mulai, ":", jj.menit_mulai)) and TIME(CONCAT(jjs.jam_selesai, ":", jjs.menit_selesai))
                                    ORDER BY k.tingkat, k.nm_kelas');
                                    
        return Datatables::of($list_data)
                ->addColumn('nm_pengguna', function ($item) {
                    if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    } elseif (! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;
                    } elseif (! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna;
                    }
                })
                ->addColumn('status', function ($item) {
                    if (!empty($item->id_presensi_mp)) {
                        return 'Sudah absensi kelas';
                    } else {
                        return 'Kelas kosong';
                    }
                })
                ->make(true);
    }
}
