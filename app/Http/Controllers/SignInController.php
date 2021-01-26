<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Models\Sekolah;
use App\Models\WaliMurid;

use App\Models\Role;
use App\Models\Pengguna;
use App\Models\Siswa;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

class SignInController extends BaseController
{
    public function indexReportingDashboard(Request $request){
        return view('reporting-dashboard/index');
    }

    public function indexSignin(Request $request)
    {
        if (Auth::check()) {
            $pengguna = Auth::user();
            $role_aktif = $pengguna->role_pengguna->where('is_aktif', 1)->first();
            $role = Role::find($role_aktif->id_role);
            return redirect($role->path);
        } else {
            $sekolah = Sekolah::orderBy('id_sekolah')->first();
            return view('signin', compact('sekolah'));
        }
    }

    public function actionSignIn(Request $request)
    {
        $input = (object) $request->input();

        /*$http_host = env('APP_URL', '');

        // get http_host database sekolah
        $sekolah = Sekolah::where('http_host','=',$http_host)->first();
        $id_sekolah = $sekolah->id_sekolah;

        if (Auth::attempt(['username' => $input->username, 'password' => $input->password], true) && ! empty($id_sekolah)) {*/

        // $pengguna = Pengguna::where('username', $input->username)->first();
        // if (Auth::loginUsingId($pengguna->id_pengguna, true)) {
        if (Auth::attempt(['username' => $input->username, 'password' => $input->password], true)) {
            $pengguna = Auth::user();
            $role_aktif = $pengguna->role_pengguna->where('is_aktif', 1)->first();
            $role = Role::find($role_aktif->id_role);

            if ($role_aktif->id_role == 4) {
                if ($wali_murid = WaliMurid::where('id_pengguna', $pengguna->id_pengguna)->first()) {
                    if (!Siswa::where('id_wali_murid', $wali_murid->id_wali_murid)->first()) {
                        Auth::logout();
                        return back()->with('toast', 'Akun Anda belum disetting menjadi wali murid')->withInput();
                    }
                }
            }

            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $pengguna->last_time_login  = $now;
            $pengguna->is_online        = 1;
            $pengguna->save();

            if($pengguna->must_change_password == 1){
                return redirect('must-change-password');
            }

            return redirect($role->path);
        } else {
            return back()->with('toast', 'Sign in failed')->withInput();
        }
    }
}
