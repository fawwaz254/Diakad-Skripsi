<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswaOrtu;
use App\Models\Pengguna;
use App\Models\Role;
use App\Models\RolePengguna;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Session;

use Symfony\Component\Process\Process;
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

    public function indexMustAddBiodata(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa =  Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('pengguna', 'wali_murid')->first();
        return view('must-update-biodata', compact('auth_data', 'siswa'));
    }

    public function actionMustAddBiodata(Request $request, $nis_siswa)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'email_pengguna' => 'required',
            'nm_ortu' => 'required',
            'nomor_hp_ortu'    => 'required|min:10|max:14',
        ]);


        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first(),
            ];
        }

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $siswa = Siswa::where('nis_siswa', '=', $nis_siswa)->first();
        $wali_murid = WaliMurid::where('id_wali_murid', $siswa->id_wali_murid ?? null)->first();
        if ($wali_murid != null) { } else {
            // if siswa doesnt have wali murid
            $now1 = Carbon::now(env('APP_TIMEZONE', ''));
            $wali_murid = new WaliMurid;
            $wali_murid->id_wali_murid = $input->auth_data->sekolah_data->prefix . strtotime($now1) . uniqid();
            $wali_murid->id_pengguna = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $wali_murid->nm_wali_murid = $input->nm_ortu;
            $wali_murid->is_aktif = 1;
            $wali_murid->nomor_hp_wali_murid = $input->nomor_hp_ortu;
            $wali_murid->updated_at = $now;
            $wali_murid->save();

            $pengguna = new Pengguna;
            $pengguna->id_pengguna = $wali_murid->id_pengguna;
            $pengguna->nm_pengguna = $input->nm_ortu;
            $pengguna->id_sekolah = $input->auth_data->sekolah_data->id_sekolah;
            $pengguna->id_status_pengguna = "Fh2L415358554335b8b4b49e1659";
            $pengguna->username = $input->nomor_hp_ortu;
            $pengguna->password = Hash::make($input->nomor_hp_ortu);
            $pengguna->status_join_table = 4;
            $pengguna->save();
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $role_wali_murid = RolePengguna::where('id_pengguna', $wali_murid->id_pengguna)->where('role', 4)->first();
            if(!$role_wali_murid){
                $role_wali_murid = new RolePengguna;
                $role_wali_murid->id_role = 4;
                $role_wali_murid->id_pengguna = $wali_murid->id_pengguna;
                $role_wali_murid->keterangan_role_pengguna = "Input Wali Murid";
                $role_wali_murid->is_aktif = 1;
                $role_wali_murid->save();
            }

            $siswa1 = Siswa::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first();
            $siswa1->id_wali_murid = $wali_murid->id_wali_murid;
            $siswa1->save();

            $calon_siswa_ortu = CalonSiswaOrtu::where('id_c_siswa', $siswa1->id_c_siswa)->first();
            $calon_siswa_ortu->nomor_telp_ortu = $input->nomor_hp_ortu;
            $calon_siswa_ortu->nomor_hp_ortu = $input->nomor_hp_ortu;
            $calon_siswa_ortu->nm_wali = $input->nm_ortu;
            $calon_siswa_ortu->save();

            $pengguna1 = Pengguna::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first();
            $pengguna1->email_pengguna = $input->email_pengguna;
            $pengguna1->save();
        };
        return [
            'status' => 201,
            'link' =>  url('/'),
            'message' => 'Update Data Siswa Berhasil'
        ];
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
            'is_agree.required' => 'Silahkan klik centang pernyataan potensi menggunakan PASSWORD DEFAULT',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first(),
            ];
        }
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $input = (object) $request->input();
        $pengguna = $input->auth_data->pengguna;

        $pengguna->must_change_password = 0;
        $pengguna->last_time_password = $now;
        $pengguna->save();

        return [
            'status' => 201, // SUCCESS AND REDIRECT
            'link' => url('/'),
            'message' => 'Use Default Password Successfully',
        ];
    }

    public function actionChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'new_confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first(),
            ];
        }
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $input = (object) $request->input();
        $pengguna = $input->auth_data->pengguna;
        if ($input->new_password == $input->new_confirm_password) {
            if (Auth::once(['username' => $pengguna->username, 'password' => $input->old_password])) {
                $pengguna->password = Hash::make($input->new_password);
                $pengguna->last_time_password = $now;
                $pengguna->is_online = 0;
                $pengguna->save();

                Session::flush();
                Auth::logout();
                return [
                    'status' => 201, // SUCCESS AND REDIRECT
                    'link' => url('/'),
                    'message' => 'Change Password Successfully',
                ];
            } else {
                return [
                    'status' => 300, // FAILED
                    'message' => 'Your old password is incorrect',
                ];
            }
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Re-type your new password again',
            ];
        }
    }

    public function actionMustChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'new_confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // Failed
                'message' => $validator->errors()->first(),
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
                    'message' => 'Mohon tidak menggunakan password lama Anda',
                ];
            }
            $pengguna->password = Hash::make($input->new_password);
            $pengguna->last_time_password = $now;
            $pengguna->must_change_password = 0;
            $pengguna->save();

            return [
                'status' => 201, // SUCCESS AND REDIRECT
                'link' => url('/'),
                'message' => 'Sukses mengubah password',
            ];
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Ketik kembali password baru Anda',
            ];
        }
    }

    public function actionSaveProfile(Request $request)
    {
        $input = (object) $request->input();

        $pengguna = $input->auth_data->pengguna;
        $pengguna->nm_pengguna = $input->name;
        $pengguna->save();

        if ($role_pengguna_selected = RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->where('id_role', $input->role)->first()) {
            foreach (RolePengguna::where('id_pengguna', $pengguna->id_pengguna)->get() as $role_pengguna) {
                if ($role_pengguna->id_role == $role_pengguna_selected->id_role) {
                    $role = Role::where('id_role', $role_pengguna_selected->id_role)->first();
                    $role_pengguna->is_aktif = 1;
                    $role_pengguna->save();
                } else {
                    $role_pengguna->is_aktif = 0;
                    $role_pengguna->save();
                }
            }
            Session::flush();

            Auth::loginUsingId($pengguna->id_pengguna);

            return [
                'status' => 201, // SUCCESS AND REDIRECT
                'link' => url($role->path),
                'message' => 'Save Profile Successfully',
            ];
        } else {
            return [
                'status' => 300, // FAILED
                'message' => 'Your data is incorrect',
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

    public function actionLocked(Request $request)
    {
        $input = (object) $request->input();

        $pengguna = $input->auth_data->pengguna;
        $pengguna->is_online = 0;
        $pengguna->terkunci_hingga = now()->addHours(24);
        $pengguna->save();

        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
