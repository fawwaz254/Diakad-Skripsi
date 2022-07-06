<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Process\Process;

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

        return view('dashboard');
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

        $roles = RolePengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)->join('role', 'role.id_role', '=', 'role_pengguna.id_role')->orderBy('nm_role')->get();
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

    public function actionByPassChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_agree' => 'required|in:1',
        ], [
            'is_agree.required' => 'Silahkan klik centang pernyataan potensi menggunakan PASSWORD DEFAULT'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first()
            ];
        }
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $input = (object) $request->input();
        $pengguna = $input->auth_data->pengguna;

        $pengguna->must_change_password = 0;
        $pengguna->last_time_password   = $now;
        $pengguna->save();
        
        return [
            'status' => 201, // SUCCESS AND REDIRECT
            'link' => url('/'),
            'message' => 'Use default password successfully'
        ];
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
            if ($input->new_password == $pengguna->username) {
                return [
                    'status' => 300, // FAILED
                    'message' => 'Mohon tidak menggunakan password lama Anda'
                ];
            }
            $pengguna->password             = Hash::make($input->new_password);
            $pengguna->last_time_password   = $now;
            $pengguna->must_change_password   = 0;
            $pengguna->save();
            
            return [
                'status' => 201, // SUCCESS AND REDIRECT
                'link' => url('/'),
                'message' => 'Sukses mengubah password'
            ];
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Ketik kembali password baru Anda'
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

    public function actionMerge(Request $request){
        $input = (object) $request->input();

        if(!empty($input->b)){
            $name_branch = $input->b;

            $cmd = [];
            $cmd[] = 'git config --global user.email "riordhn@gmail.com"';
            $cmd[] = 'git config --global user.name "Rio Ramadhan D"';
            $cmd[] = 'git fetch';
            $cmd[] = 'git merge origin '. $name_branch . ' -m "Merge branch '.$name_branch.' into master"';
            $cmd[] = 'git push origin master';
            $cmd[] = 'git checkout latest-release';
            $cmd[] = 'git merge master -m "Merge branch master into latest-release"';
            $cmd[] = 'git push origin latest-release';
            $cmd[] = 'git checkout master';
            
            $process = new Process(implode(' && ', $cmd));
            $process->setTimeout(360);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }

            return 'true';
        }

        return 'false';
    }
}
