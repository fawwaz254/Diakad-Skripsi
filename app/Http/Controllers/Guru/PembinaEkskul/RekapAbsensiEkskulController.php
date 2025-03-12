<?php

namespace App\Http\Controllers\Guru\PembinaEkskul;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul;
use App\Models\PelatihEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\PresensiEkskulPeserta;
use App\Models\PengambilanEkskul;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Models\Guru;
use App\Models\PembinaEkskulSet;
use App\Models\Pengguna;
use Auth;
use DB;
use Session;
use Validator;

class RekapAbsensiEkskulController extends BaseController
{
    protected $modul_url = 'pembina-ekskul';
    protected $menu_url = 'rekap-absensi-ekskul';

    public function viewRekapAbsensiEkskul(Request $request, $id_semester = null, $id_ekskul = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if (!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

        $pembina_ekskul = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $data_ekskul = PembinaEkskulSet::with('ekskul')->where('id_guru', $pembina_ekskul->id_guru)->get();

        return view(
            'pelatih-ekskul/absensi-ekskul/rekap-absensi-ekskul/view-rekap-absensi-ekskul',
            compact('auth_data', 'data_ekskul', 'id_ekskul', 'selected_semester', 'data_semester')
        );
    }

    public function viewDetailRekapAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
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
            'pelatih-ekskul/absensi-ekskul/rekap-absensi-ekskul/view-detail-rekap-absensi-ekskul',
            compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_siswa', 'data_presensi', 'id_semester', 'id_ekskul')
        );
    }

    public function printRekapAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_ekskul = Ekskul::find($id_ekskul);
        $data_pelatih = PelatihEkskulSet::with('pelatih_ekskul.pengguna')
            ->where('id_ekskul', $id_ekskul)
            ->where('is_aktif', 1)
            ->get()
            ->map(function ($m) {
                $pelatih = $m->pelatih_ekskul;
                return $pelatih ? $pelatih->pengguna->nm_pengguna . (($pelatih->pengguna->gelar_belakang != null) ? ', ' . $pelatih->pengguna->gelar_belakang : null) : null;
            })->toArray();

        $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

        $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
            ->where('id_ekskul', $id_ekskul)
            ->where('id_semester', $id_semester)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        return view(
            'pelatih-ekskul/absensi-ekskul/rekap-absensi-ekskul/print-rekap-absensi-ekskul',
            compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_pelatih', 'data_siswa', 'data_presensi', 'id_semester', 'id_ekskul')
        );
    }

    public function printRekapAbsensiKehadiranEkskul(Request $request, $id_semester, $id_ekskul, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_ekskul = Ekskul::find($id_ekskul);
        $data_pelatih = PelatihEkskulSet::with('pelatih_ekskul.pengguna')
            ->where('id_ekskul', $id_ekskul)
            ->where('is_aktif', 1)
            ->get()
            ->map(function ($m) {
                $pelatih = $m->pelatih_ekskul;
                return $pelatih ? $pelatih->pengguna->nm_pengguna . (($pelatih->pengguna->gelar_belakang != null) ? ', ' . $pelatih->pengguna->gelar_belakang : null) : null;
            })->toArray();

        $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->where('id_siswa', $id_siswa)->first();

        $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
            ->where('id_ekskul', $id_ekskul)
            ->where('id_semester', $id_semester)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        $hadir = 0;
        $izin = 0;
        $alpha = 0;
        $sakit = 0;

        foreach ($data_presensi as  $presensi_ekskul) {
            $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_siswa'] = $presensi_ekskul->presensi_ekskul_peserta->count();
            $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_hadir'] = $presensi_ekskul->presensi_ekskul_peserta->where('kehadiran', 1)->count();

            if ($presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $data_siswa->id_siswa)) {
                if ($presensi_ekskul_peserta->kehadiran == 1) {
                    $hadir = $hadir + 1;
                } elseif ($presensi_ekskul_peserta->kehadiran == 2) {
                    $sakit = $sakit + 1;
                } elseif ($presensi_ekskul_peserta->kehadiran == 3) {
                    $izin = $izin + 1;
                } elseif ($presensi_ekskul_peserta->kehadiran == 4) {
                    $alpha = $alpha + 1;
                }
            }
        }

        return view(
            'pelatih-ekskul/absensi-ekskul/rekap-absensi-ekskul/print-detail-rekap-absensi-ekskul',
            compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_pelatih', 'data_siswa', 'data_presensi', 'hadir', 'izin', 'alpha', 'sakit')
        );
    }
}
