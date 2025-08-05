<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Models\Jurusan;
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
        $auth_data = auth_data();

        $count_siswa = Siswa::with('pengguna')->whereHas('pengguna.status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->whereNotNull('id_kelas')->count();

        $jenis_kelamin = Siswa::select('jenis_kelamin', DB::raw('count(*) as user_count'))
            ->join('calon_siswa_baru', function ($join) {
                $join->on('calon_siswa_baru.id_c_siswa', 'siswa.id_c_siswa');
                $join->whereNull('calon_siswa_baru.deleted_at');
            })
            ->with('pengguna')->whereHas('pengguna.status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
            })
            ->whereNotNull('id_kelas')
            ->groupBy('jenis_kelamin')
            ->get();

        $role_aktif = $auth_data->role_aktif;
        $data_tingkat = Kelas::select('tingkat')->distinct()->orderBy('tingkat', 'asc')->get();
        $data_jurusan = Jurusan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();


        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();
        return view('kesiswaan/welcome', compact('auth_data', 'data_tingkat', 'role_dashboard', 'count_siswa',  'jenis_kelamin', 'data_jurusan'));
    }
}
