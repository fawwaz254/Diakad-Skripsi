<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Libraries\WinpayPHP\Winpay;
use App\Models\Role;
use App\Models\RolePengguna;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class AuthGlobalController extends BaseController
{
    public function indexDashboard(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('dashboard', compact('auth_data'));
    }

    public function indexMustChangePassword(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('must-change-password', compact('auth_data'));
    }

    public function indexProfile(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $roles = Role::orderBy('nm_role', 'asc')->get();
        return view('profile', compact('auth_data', 'roles'));
    }

    public function indexPassword(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('password', compact('auth_data'));
    }

    public function indexSearch(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $search = $input->q;

        return view('search-result', compact('auth_data', 'search'));
    }

    public function actionChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'new_confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first()
            ];
        }
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $input = (object) $request->input();
        $pengguna = $input->auth_data->pengguna;
        if ($input->new_password == $input->new_confirm_password) {
            if (Auth::once(['username' => $pengguna->username, 'password' => $input->old_password])) {
                $pengguna->password             = Hash::make($input->new_password);
                $pengguna->last_time_password   = $now;
                $pengguna->is_online = 0;
                $pengguna->save();
                
                Session::flush();
                Auth::logout();
                return [
                    'status' => 201, // SUCCESS AND REDIRECT
                    'link' => url('/'),
                    'message' => 'Change password successfully'
                ];
            } else {
                return [
                    'status' => 300, // FAILED
                    'message' => 'Your old password is incorrect'
                ];
            }
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Re-type your new password again'
            ];
        }
    }

    public function actionMustChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'new_confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first()
            ];
        }
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $input = (object) $request->input();
        $pengguna = Auth::user();
        if ($input->new_password == $input->new_confirm_password) {
            $pengguna->password             = Hash::make($input->new_password);
            $pengguna->last_time_password   = $now;
            $pengguna->must_change_password   = 0;
            $pengguna->save();
            
            return [
                'status' => 201, // SUCCESS AND REDIRECT
                'link' => url('/'),
                'message' => 'Change password successfully'
            ];
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Re-type your new password again'
            ];
        }
    }

    public function actionSaveProfile(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required'
        ]);

        $pengguna = $input->auth_data->pengguna;
        $pengguna->nm_pengguna = $input->name;
        $pengguna->save();

        $roles_pengguna = $input->auth_data->roles_pengguna;

        if ($role_pengguna_selected = RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->where('id_role', $input->role)->first()) {
            foreach (RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->get() as $role_pengguna) {
                if ($role_pengguna->id_role == $role_pengguna_selected->id_role) {
                    $role_pengguna->is_aktif = 1;
                    $role_pengguna->save();
                } else {
                    $role_pengguna->is_aktif = 0;
                    $role_pengguna->save();
                }
            }
            
            $pengguna = $input->auth_data->pengguna;
            $pengguna->is_online = 0;
            $pengguna->save();

            Session::flush();
            Auth::logout();
            return [
                'status' => 201, // SUCCESS AND REDIRECT
                'link' => url('/'),
                'message' => 'Save Profile successfully'
            ];
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Your data is incorrect'
            ];
        }
    }

    public function actionSignOut(Request $request)
    {
        $input = (object) $request->input();

        $pengguna = $input->auth_data->pengguna;
        $pengguna->is_online = 0;
        $pengguna->save();

        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
