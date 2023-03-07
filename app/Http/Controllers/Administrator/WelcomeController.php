<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Guru;
use App\Models\RolePengguna;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Staff;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController
{
    public function indexWelcome(Request $request)
    {
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('administrator/welcome', compact('auth_data', 'semester_aktif'));
    }
    public function viewBiodata(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $role_pengguna = RolePengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('role.modul.menus')
            ->get();
        if ($auth_data->pengguna->status_join_table == '1') { //tendik
            $pengguna = Staff::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna')->first();
        } elseif ($auth_data->pengguna->status_join_table == '2') { //guru
            $pengguna = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna')->first();
        } else { //siswa
            $pengguna = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna')->first();
        }

        return view('view-biodata', compact('auth_data', 'pengguna', 'role_pengguna'));
    }
}
