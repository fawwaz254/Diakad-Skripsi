<?php

namespace App\Http\Controllers\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\RoleDashboard;

use Auth;
use DB;
use Session;
use App\Models\Setting;

class WelcomeController extends BaseController
{

    public function indexWelcome(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_tingkat = Kelas::select('tingkat')->distinct()->orderBy('tingkat', 'asc')->get();
        $count_siswa = Siswa::with('pengguna')->whereHas('pengguna.status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
        })->whereNotNull('id_kelas')->count();

        $last_siswa = Siswa::select('created_at')->orderBy('created_at', 'desc')->first();

        $jenis_kelamin = Siswa::select('jenis_kelamin', DB::raw('count(*) as user_count'))
            ->join('calon_siswa_baru', function ($join) {
                $join->on('calon_siswa_baru.id_c_siswa', 'siswa.id_c_siswa');
                $join->whereNull('calon_siswa_baru.deleted_at');
            })
            ->with('pengguna')->whereHas('pengguna.status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
            })
            ->groupBy('jenis_kelamin')
            ->get();

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
        // dd($count_siswa);
        return view('kesiswaan/welcome', compact('auth_data', 'data_tingkat','role_dashboard', 'count_siswa', 'last_siswa', 'jenis_kelamin', 'start_monkes', 'end_monkes'));
    }
}
