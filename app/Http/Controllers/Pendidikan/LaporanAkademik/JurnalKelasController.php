<?php

namespace App\Http\Controllers\Pendidikan\LaporanAkademik;

use App\Models\KelasMp;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru as Guru;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class JurnalKelasController extends BaseController
{

    protected $modul_url = 'laporan-akademik';
    protected $menu_url = 'jurnal-kelas';

    public function viewJurnalKelas(Request $request, $id_kelas = null, $id_semester = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $selected_kelas = null;
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        if (!empty($id_kelas)) {
            $selected_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        }

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if (!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

        return view('pendidikan/laporan-akademik/jurnal-kelas/view-jurnal-kelas', compact('auth_data', 'selected_semester', 'data_semester', 'selected_kelas', 'data_kelas'));

    }

    public function datatablesJurnalKelas(Request $request, $id_kelas, $id_semester)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibGuru::fetchDataJadwalKBMByKelas($auth_data, $id_semester, $id_kelas);

        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->kelas_mp->mata_pelajaran->kd_mata_pelajaran . " - " . $item->kelas_mp->mata_pelajaran->nm_mata_pelajaran;
            })
            ->addColumn('jadwal_hari', function ($item) {
                return $item->jadwal_hari->nm_jadwal_hari;
            })
            ->addColumn('ruangan', function ($item) {
                return $item->ruangan->nm_ruangan;
            })
            ->addColumn('jadwal_jam', function ($item) {
                return $item->jadwal_jam_mulai->jam_mulai . ":" . $item->jadwal_jam_mulai->menit_mulai . " - " . $item->jadwal_jam_mulai->jam_selesai . ":" . $item->jadwal_jam_mulai->menit_selesai;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_kelas_mp' => $item->id_kelas_mp,
                );
                return $data;
            })
            ->make(true);
    }

    public function printPdfJurnalKelas(Request $request, $id_kelas_mp)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas_mp = KelasMp::with('mata_pelajaran', 'kelas')->where('id_kelas_mp', $id_kelas_mp)->first();
        $presensi_mp = PresensiMp::with(['kelas_mp', 'jadwal_kelas_mp.jadwal_hari'])->where('id_kelas_mp', $id_kelas_mp)->get();

        $data_pertemuan = [];

        for ($i = 1; $i <= 25; $i++) {
            $presensiMp = $presensi_mp->firstWhere('pertemuan_ke', $i);
            $data_pertemuan[] = [
                'pertemuan_ke' => $i,
                'presensi' => $presensiMp,
                'hari' => $presensiMp ? $presensiMp->jadwal_kelas_mp->jadwal_hari->nm_jadwal_hari : '-',
            ];
        }

        return view('pendidikan/laporan-akademik/jurnal-kelas/print-jurnal-kelas', [
            'auth_data' => $auth_data,
            'kelas_mp' => $kelas_mp,
            'data_pertemuan' => $data_pertemuan
        ]);
    }

}
