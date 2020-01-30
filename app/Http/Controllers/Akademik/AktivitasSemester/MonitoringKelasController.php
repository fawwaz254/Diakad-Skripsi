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
    public function viewMonitoringKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/aktivitas-semester/monitoring-kelas/view-monitoring-kelas', compact('auth_data', 'data_semester'));
    }

    public function actionViewMonitoringKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' =>'required'
        ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'aktivitas-semester/monitoring-kelas/view-semester-monitoring-kelas/'.$input->id_semester
                ];
        }
    }

    public function viewSemesterMonitoringKelas(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/aktivitas-semester/monitoring-kelas/view-semester-monitoring-kelas', compact('auth_data', 'data_semester', 'id'));
    }

    public function viewDaftarSiswa(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = PengambilanMp::where('id_kelas_mp', '=', $id)->first();

        return view('akademik/aktivitas-semester/monitoring-kelas/view-daftar-siswa', compact('auth_data', 'data_semester', 'id'));
    }

    public function datatablesMonitoringKelas(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = JadwalKelasMp::with(
            'jadwal_hari',
            'jadwal_jam_mulai',
            'jadwal_jam_selesai',
            'ruangan',
            'kelas_mp',
            'kelas_mp.kelas',
            'kelas_mp.pengampu_mp_utama.guru.pengguna',
            'kelas_mp.mata_pelajaran'
        )->with(['kelas_mp.pengambilan_mp' => function ($q) {
            $q->where('status_apv_pengambilan_mp', 1);
        }])
            ->whereHas('kelas_mp', function ($q) use ($id) {
                $q->where('id_semester', $id);
            });

        return Datatables::of($list_data)
                ->addColumn('nm_pengampu', function ($item) {
                    if (!empty($item->kelas_mp->pengampu_mp_utama->guru->pengguna->nm_pengguna)) {
                        return $item->kelas_mp->pengampu_mp_utama->guru->pengguna->gelar_depan.' '.
                                $item->kelas_mp->pengampu_mp_utama->guru->pengguna->nm_pengguna.' '.
                                $item->kelas_mp->pengampu_mp_utama->guru->pengguna->gelar_belakang;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('jml_siswa', function ($item) {
                    return $item->kelas_mp->pengambilan_mp->count();
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->kelas_mp->id_kelas_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesDaftarSiswa(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PengambilanMp::with('siswa', 'siswa.pengguna', 'kelas_mp')
            ->where('id_kelas_mp', '=', $id)
            ->where('status_apv_pengambilan_mp', '=', '1');

        return Datatables::of($list_data)
                                ->make(true);
    }
}
