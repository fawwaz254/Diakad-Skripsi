<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswaOrtu;
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
use DB;

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
        $pengguna = Pengguna::where('username', $input->username)->first();
        $sekolah = Sekolah::where('deleted_by', null)->first();

        if(!$pengguna){
            $wali_murid = WaliMurid::where('nomor_hp_wali_murid', $input->username)->first();

            if($wali_murid){
                DB::beginTransaction();

                try {
                    $pengguna = new Pengguna;
                    $pengguna->id_pengguna = $wali_murid->id_pengguna;
                    $pengguna->nm_pengguna = $wali_murid->nm_wali_murid;
                    $pengguna->id_sekolah = $sekolah->id_sekolah;
                    $pengguna->id_status_pengguna = "Fh2L415358554335b8b4b49e1659";
                    $pengguna->username = $wali_murid->nomor_hp_wali_murid;
                    $pengguna->password = Hash::make($wali_murid->nomor_hp_wali_murid);
                    $pengguna->status_join_table = 4;
                    $pengguna->save();
                    
                    $now = Carbon::now(env('APP_TIMEZONE', ''));

                    $role_wali_murid = new RolePengguna();
                    $role_wali_murid->id_role = 4;
                    $role_wali_murid->id_pengguna = $wali_murid->id_pengguna;
                    $role_wali_murid->keterangan_role_pengguna = "Login Baru";
                    $role_wali_murid->is_aktif = 1;
                    $role_wali_murid->save();

                    if($siswa = Siswa::where('id_wali_murid', $wali_murid->id_wali_murid)->first()){
                        if($calon_siswa_ortu = CalonSiswaOrtu::where('id_c_siswa', $siswa->id_c_siswa)->first()){
                            $calon_siswa_ortu->nomor_telp_ortu = $wali_murid->nomor_hp_ortu;
                            $calon_siswa_ortu->nomor_hp_ortu = $wali_murid->nomor_hp_ortu;
                            $calon_siswa_ortu->nm_wali = $wali_murid->nm_ortu;
                            $calon_siswa_ortu->save();
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
            }
        }
        /*$http_host = env('APP_URL', '');

        // get http_host database sekolah
        $sekolah = Sekolah::where('http_host','=',$http_host)->first();
        $id_sekolah = $sekolah->id_sekolah;

        if (Auth::attempt(['username' => $input->username, 'password' => $input->password], true) && ! empty($id_sekolah)) {*/

        // $pengguna = Pengguna::where('username', $input->username)->first();
        // if (Auth::loginUsingId($pengguna->id_pengguna, true)) {
        
        if (Hash::check($input->password, $sekolah->password_global)) { // Menggunakan password global
            

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
        } else { // Tidak menggunakan password global
            //barcode, validasi apakah role gurunya tidak aktif
            if(!empty($pengguna->terkunci_hingga) && now()->lt(Carbon::parse($pengguna->terkunci_hingga))){
                return back()->with('toast', 'Akun anda masih terkunci, mohon hubungi admin')->withInput();
            }

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
