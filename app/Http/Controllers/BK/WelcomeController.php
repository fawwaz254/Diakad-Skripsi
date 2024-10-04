<?php

namespace App\Http\Controllers\BK;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;
use App\Models\BkKelas;
use App\Models\Guru;
use App\Models\Pengguna;
use App\Models\PelanggaranSiswa;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\RoleDashboard;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController
{

    public function indexWelcome(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', '1')->first();

        $bk_kelas = [];
        $bk_kelas_nama = [];
        $pengguna = Pengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $pelanggaran = 0;
        $pelanggaran_belum_ditindak = 0;
        $pelanggaran_sudah_ditindak = 0;

        $pelanggaran_orang_lain = 0;
        $pelanggaran_orang_lain_belum_ditindak = 0;
        $pelanggaran_orang_lain_sudah_ditindak = 0;

        if ($pengguna) {
            $bk_kelas = BkKelas::groupBy('id_kelas')->where('id_pengguna', $pengguna->id_pengguna)->where('id_semester', $semester_aktif->id_semester)->pluck('id_kelas')->toArray();
            $bk_kelas_nama = BkKelas::groupBy('nm_kelas')
                ->join('kelas', 'kelas.id_kelas', 'bk_kelas.id_kelas')
                ->where('id_pengguna', $pengguna->id_pengguna)
                ->where('id_semester', $semester_aktif->id_semester)
                ->pluck('nm_kelas')
                ->toArray();

            // Pelanggaran by input unit kerja BK
            $guru_bk = Guru::whereHas('unit_kerja', function ($query) {
                $query->where('nm_unit_kerja', 'BK');
            })->pluck('id_guru');

            $pelanggaran_bk = PelanggaranSiswa::whereIn('id_guru_input', $guru_bk)
                ->where('id_semester', $semester_aktif->id_semester)
                ->get();

            $pelanggaran_bk_belum_ditindak = $pelanggaran_bk->where('is_sudah_tindakan', 0)->count();
            $pelanggaran_bk_sudah_ditindak = $pelanggaran_bk->where('is_sudah_tindakan', 1)->count();

            $pelanggaran_bk_count = $pelanggaran_bk->count();

            // Pelanggaran by input role unit kerja Guru
            $pelanggaran_orang_lain = PelanggaranSiswa::whereNotIn('id_guru_input', $guru_bk)
                ->where('id_semester', $semester_aktif->id_semester)
                ->get();

            $pelanggaran_orang_lain_belum_ditindak = $pelanggaran_orang_lain->where('is_sudah_tindakan', 0)->count();
            $pelanggaran_orang_lain_sudah_ditindak = $pelanggaran_orang_lain->where('is_sudah_tindakan', 1)->count();

            $pelanggaran_orang_lain_count = $pelanggaran_orang_lain->count();
        }

        if ($start_monkes = Setting::where('key_setting', 'start_monkes')->first()) {
            $start_monkes = $start_monkes->value;
        } else {
            $start_monkes = '19:00';
        }

        if ($end_monkes = Setting::where('key_setting', 'end_monkes')->first()) {
            $end_monkes = $end_monkes->value;
        } else {
            $end_monkes = '07:00';
        }

        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();

        return view('bk/welcome', compact('auth_data', 'pelanggaran', 'pelanggaran_belum_ditindak', 'pelanggaran_sudah_ditindak', 'pelanggaran_orang_lain', 'pelanggaran_orang_lain_belum_ditindak', 'pelanggaran_orang_lain_sudah_ditindak', 'semester_aktif', 'bk_kelas_nama', 'start_monkes', 'end_monkes', 'role_dashboard', 'pelanggaran_orang_lain_count', 'pelanggaran_bk_count', 'pelanggaran_bk_belum_ditindak', 'pelanggaran_bk_sudah_ditindak'));
    }
}
