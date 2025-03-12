<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Guru;
use App\Models\PembayaranBiaya;
use App\Models\RolePengguna;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Staff;
use App\Models\RoleDashboard;
use App\Models\TagihanBiaya;
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
        $auth_data = auth_data();
        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();
        return view('administrator/welcome', compact('auth_data', 'role_dashboard' , 'semester_aktif'));
    }
    public function viewBiodata(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
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

    public function deleteTagihanYangSama(Request $request){
        set_time_limit(-1);
        $grouptagihanbiaya = TagihanBiaya::where('updated_by' , 'khairil-changetagihan30/07')->pluck('id_tagihan_biaya')->toArray();
        // dd($grouptagihanbiaya);
        $pembayaran = PembayaranBiaya::whereIn('id_tagihan_biaya',$grouptagihanbiaya)->get();
        dd($pembayaran);
    }
}
