<?php

namespace App\Http\Controllers\Guru;

use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\RoleDashboard;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Staff;
use Auth;
use DB;
use Session;

class WelcomeController extends BaseController
{
    public function indexWelcome(Request $request)
    {
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

        $input = (object) $request->input();
        $auth_data = auth_data();

        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();
        return view('guru/welcome', compact('auth_data', 'role_dashboard', 'start_monkes', 'end_monkes')); //folder akademik/nama file welcome.blade
    }

    // public function viewBiodata(Request $request)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = auth_data();
    //     if ($auth_data->pengguna->status_join_table == '1') { //tendik
    //         $pengguna = Staff::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna')->first();
    //         return view('view-biodata', compact('auth_data', 'pengguna'));
    //     } elseif ($auth_data->pengguna->status_join_table == '2') { //guru
    //         $pengguna = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna')->first();
    //         return view('view-biodata', compact('auth_data', 'pengguna'));
    //     } elseif ($auth_data->pengguna->status_join_table == '3') { //siswa
    //         $pengguna = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna')->first();
    //         return view('view-biodata', compact('auth_data', 'pengguna'));
    //     } else { }

    // }
}
