<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Models\Sekolah as Sekolah;

use App\Models\Role;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

class SignInController extends BaseController
{
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

        if (Auth::attempt(['username' => $input->username, 'password' => $input->password], true)) {
            $pengguna = Auth::user();
            $role_aktif = $pengguna->role_pengguna->where('is_aktif', 1)->first();
            $role = Role::find($role_aktif->id_role);

            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $pengguna->last_time_login  = $now;
            $pengguna->is_online        = 1;
            $pengguna->save();

            return redirect($role->path);
        } else {
            return back()->with('toast', 'Sign in failed')->withInput();
        }
    }
}
