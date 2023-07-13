<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Role;
use App\Models\RolePengguna;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class SignInController extends BaseController
{
    public function indexReportingDashboard(Request $request)
    {
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

        $global_pass = Sekolah::where('deleted_by', null)->first();
        if (Hash::check($input->password, $global_pass->password_global)) {
            $pengguna = Pengguna::where('username', $input->username)->first();

            if (!empty($pengguna)) {
                //barcode, validasi apakah role gurunya tidak aktif
                if (Session::get('backUrl')) {
                    $cek_role = RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->where('id_role', '2')->where('is_aktif', '0')->first();
                    if ($cek_role) {
                        $change_role =  RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->where('is_aktif', '1')->first();
                        $change_role->is_aktif = 0;
                        $change_role->save();

                        $cek_role->is_aktif =  1;
                        $cek_role->save();
                        $pengguna = Pengguna::where('username', $input->username)->first();
                    }
                }

                Auth::loginUsingId($pengguna->id_pengguna);

                $role_aktif = $pengguna->role_pengguna->where('is_aktif', 1)->first();
                $role = Role::find($role_aktif->id_role);
                //barcode
                if ($role->path == 'guru' && Session::get('backUrl')) {
                    return redirect(Session::get('backUrl'));
                }
                return redirect($role->path);
            }
            return back()->with('toast', 'Sign in failed')->withInput();
        } else {
            //barcode, validasi apakah role gurunya tidak aktif
            $pengguna = Pengguna::where('username', $input->username)->first();
            if (Session::get('backUrl')) {
                $cek_role = RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->where('id_role', '2')->where('is_aktif', '0')->first();
                if ($cek_role) {
                    $change_role =  RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->where('is_aktif', '1')->first();
                    $change_role->is_aktif = 0;
                    $change_role->save();

                    $cek_role->is_aktif =  1;
                    $cek_role->save();
                }
            }

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

                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $pengguna->last_time_login = $now;
                $pengguna->is_online = 1;
                $pengguna->save();

                if ($pengguna->must_change_password == 1) {
                    return redirect('must-change-password');
                }

                //barcode
                if ($role->path == 'guru' && Session::get('backUrl')) {
                    return redirect(Session::get('backUrl'));
                }
                return redirect($role->path);
            } else {
                return back()->with('toast', 'Sign in failed')->withInput();
            }
        }
    }
}
