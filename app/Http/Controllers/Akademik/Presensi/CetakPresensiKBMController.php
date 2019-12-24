<?php

namespace App\Http\Controllers\Akademik\Presensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum as Kurikulum;
use App\Models\MataPelajaran as MataPelajaran;
use App\Models\Semester as Semester;
use App\Models\KelasMp as KelasMp;
use App\Models\Guru as Guru;
use App\Models\Kelas as Kelas;
use App\Models\PengampuMp as PengampuMp;
use App\Models\JadwalHari as JadwalHari;
use App\Models\Ruangan as Ruangan;
use App\Models\JadwalJam as JadwalJam;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use App\Models\PengambilanMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use PDF;
use Session;
use Validator;

class CetakPresensiKBMController extends BaseController
{
    //
    public function viewCetakPresensiKBM(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/presensi/cetak-presensi-kbm/view-cetak-presensi-kbm', compact('auth_data', 'data_semester'));
    }

    public function actionViewCetakPresensiKBM(Request $request)
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
                'path' => 'presensi/cetak-presensi-kbm/view-semester-cetak-presensi-kbm/'.$input->id_semester
            ];
        }
    }

    public function viewSemesterCetakPresensiKBM(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = Semester::where('id_semester', '=', $id)->first();

        $kelas_mp = LibAkademik::FetchDataUsulanMataAjar($auth_data, $semester->id_semester);
       
        return view('akademik/presensi/cetak-presensi-kbm/view-semester-cetak-presensi-kbm', compact('auth_data', 'semester', 'id', 'kelas_mp'));
    }

    public function datatablesCetakPresensiKBM(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = JadwalKelasMp::select('jadwal_kelas_mp.id_jadwal_kelas_mp', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"), 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'ruangan.kapasitas_ruangan')
            ->join('kelas_mp', function ($q) {
                $q->on('kelas_mp.id_kelas_mp', '=', 'jadwal_kelas_mp.id_kelas_mp')
                    ->whereNull('kelas_mp.deleted_at');
            })
            ->join('jadwal_hari', function ($q) {
                $q->on('jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
                    ->whereNull('jadwal_hari.deleted_at');
            })
            ->join('jadwal_jam', function ($q) {
                $q->on('jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
                    ->whereNull('jadwal_jam.deleted_at');
            })
            ->join('kelas', function ($q) {
                $q->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                    ->whereNull('kelas.deleted_at');
            })
            ->join('mata_pelajaran', function ($q) {
                $q->on('mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                    ->whereNull('mata_pelajaran.deleted_at');
            })
            ->leftJoin('ruangan', function ($q) {
                $q->on('ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
                    ->whereNull('ruangan.deleted_at');
            })
            ->where('kelas_mp.id_semester', '=', $id)
            ->orderBy('kelas.nm_kelas', 'ASC')
            ->orderBy('mata_pelajaran.nm_mata_pelajaran', 'ASC')
            ->orderBy('jadwal_kelas_mp.id_jadwal_hari', 'ASC')
            ->get();

        return Datatables::of($list_data)
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_jadwal_kelas_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function printCetakPresensiKBM(Request $request, $id_jadwal_kelas_mp)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, null, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        $jadwal_kelas_mp = JadwalKelasMp::find($id_jadwal_kelas_mp);

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $jadwal_kelas_mp->id_kelas_mp);

        $pdf = PDF::loadView('akademik/presensi/cetak-presensi-kbm/download-cetak-presensi-kbm', compact('data_siswa', 'auth_data', 'semester_aktif', 'data_kelas'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
