<?php

namespace App\Http\Controllers\Kesiswaan\Ekstrakurikuler;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Bulan;
use App\Models\Guru;
use App\Models\Ekskul;
use App\Models\PelatihEkskul;
use App\Models\PelatihEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\PresensiEkskulPeserta;
use App\Models\PengambilanEkskul;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class MonitoringAbsensiEkskulController extends BaseController
{
    protected $modul_url = 'ekstrakurikuler';
    protected $menu_url = 'monitoring-absensi-ekskul';

    public function viewMonitoringAbsensiEkskul(Request $request, $id_semester = null, $id_ekskul = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if (!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

        $data_ekskul = Ekskul::all();

        return view(
            'kesiswaan/ekstrakurikuler/monitoring-absensi-ekskul/view-monitoring-absensi-ekskul',
            compact('auth_data', 'data_ekskul', 'id_ekskul', 'selected_semester', 'data_semester')
        );
    }

    public function viewDetailMonitoringAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        
        $data_ekskul = Ekskul::find($id_ekskul);
        
        $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

        $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
                                    ->where('id_ekskul', $id_ekskul)
                                    ->where('id_semester', $id_semester)
                                    ->orderBy('pertemuan_ke', 'asc')
                                    ->get();

        return view(
            'kesiswaan/ekstrakurikuler/monitoring-absensi-ekskul/view-detail-monitoring-absensi-ekskul',
            compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_siswa', 'data_presensi', 'id_semester', 'id_ekskul')
        );
    }

    public function printMonitoringAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        
        $data_ekskul = Ekskul::find($id_ekskul);
        $data_pelatih = PelatihEkskulSet::with('pelatih_ekskul.pengguna')
                                        ->where('id_ekskul', $id_ekskul)
                                        ->where('is_aktif', 1)
                                        ->get()
                                        ->map(function($m){
                                            $pelatih = $m->pelatih_ekskul;
                                            return $pelatih ? $pelatih->pengguna->nm_pengguna . 
                                            (($pelatih->pengguna->gelar_belakang != null) ? ', ' . $pelatih->pengguna->gelar_belakang : null) : null;
                                        })->toArray();
        
        $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

        $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
                                    ->where('id_ekskul', $id_ekskul)
                                    ->where('id_semester', $id_semester)
                                    ->orderBy('pertemuan_ke', 'asc')
                                    ->get();

        return view(
            'kesiswaan/ekstrakurikuler/monitoring-absensi-ekskul//print-monitoring-absensi-ekskul',
            compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_pelatih', 'data_siswa', 'data_presensi', 'id_semester', 'id_ekskul')
        );
    }
}
