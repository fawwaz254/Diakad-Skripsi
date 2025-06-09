<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use App\Models\Guru;
use App\Models\GuruKpi;
use App\Models\GuruPiket;
use App\Models\JurnalPimpinan;
use App\Models\PembinaEkskulSet;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\WaliKelas;
use App\Models\WaliMurid;
use Illuminate\Support\Facades\URL;

// use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
// use Session;

class TokenStaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->segment(1) === 'pulse') {
            return  $next($request);
        }
        if (Auth::check()) {
            $pengguna = Auth::user();
            if(!empty($pengguna->terkunci_hingga) && now()->lt(Carbon::parse($pengguna->terkunci_hingga))){
                Auth::logout();
            }
            
            if (Session::has('auth_data')) {
                $auth_data = Session::get('auth_data');
            } else {

                $sekolah_data = $pengguna->sekolah;
                $google_id = Setting::where('key_setting', 'is_google_analytic')->first()->value;

                $roles_pengguna = $pengguna->role_pengguna;
                $role_aktif = $roles_pengguna->where('is_aktif', 1)->first()->role;
                $moduls = Modul::with('menus')->where(['id_role' => $role_aktif->id_role, 'akses' => 1])->orderBy('urutan', 'asc')->get();
                $kpi = Modul::where('id_role', 2)->where('nm_modul', 'Guru KPI')->pluck('id_modul')->first();

                $tambahan_modul = [];
                $id_pengguna = $pengguna->id_pengguna;
                if ($role_aktif->id_role == 2) {
                    $guru = Guru::where('id_pengguna', $id_pengguna)->first();
                    $jurpin = Modul::where('id_role', 2)->where('nm_modul', 'Jurnal Pimpinan')->pluck('id_modul')->first();
                    if ($guru) {
                        if ($this->isGuruPiket($id_pengguna)) {
                            $tambahan_modul[] = 35;
                        }

                        if ($this->isGuruKpi($id_pengguna)) {
                            $tambahan_modul[] = $kpi;
                        }

                        if ($this->isWaliKelas($guru->id_guru)) {
                            $tambahan_modul[] = 36;
                        }

                        if ($this->isGuruEkskul($guru->id_guru)) {
                            $tambahan_modul[] = 37;
                        }

                        if ($this->isJurnalPimpinan($id_pengguna)) {
                            $tambahan_modul[] = $jurpin;
                        }
                    }
                }

                if ($role_aktif->id_role == 15) {
                    if ($this->isGuruPiket($id_pengguna)) {
                        $tambahan_modul[] = 35;
                    }
                    if ($this->isGuruKpi($id_pengguna)) {
                        $tambahan_modul[] = $kpi;
                    }
                }

                if (!empty($tambahan_modul)) {
                    $additionalModule = Modul::whereIn('id_modul', $tambahan_modul)->with('menus')->orderBy('urutan', 'asc')->get();
                    $moduls = collect($moduls->merge($additionalModule)->all());
                }

                // IF Wali Murid
                $nm_anak_murid = null;
                if ($role_aktif->id_role == 4) {
                    if ($wali_murid = WaliMurid::where('id_pengguna', $pengguna->id_pengguna)->first()) {
                        if ($siswa = Siswa::with('pengguna')->where('id_wali_murid', $wali_murid->id_wali_murid)->where('is_aktif_wali_murid', 1)->first()) {
                            $nm_anak_murid = $siswa->pengguna->nm_pengguna;
                        }
                    }
                }

                $auth_data = (object) array(
                    'pengguna' => $pengguna,
                    'sekolah_data' => $sekolah_data,
                    'role_aktif' => $role_aktif,
                    'moduls' => $moduls,
                    'nm_anak_murid' => $nm_anak_murid,
                    'google_analytic_id' => $google_id
                );

                Session::put('auth_data', $auth_data);
            }

            if (request()->segment(1) != $auth_data->role_aktif->path) {
                //barcode
                if ($auth_data->role_aktif->path == 'guru' && Session::get('backUrl')) {
                    return redirect(Session::get('backUrl'));
                }

                return redirect($auth_data->role_aktif->path);
            }

            return $next($request);
        } else {
            //barcode
            if (!empty(request()->segment(3)) && request()->segment(3) == 'presensi-barcode') {
                Session::put('backUrl', request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3));
            }

            return redirect('/');
        }
    }

    private function isGuru($id)
    {
        return Guru::where('id_pengguna', $id)->exists();
    }

    private function isGuruPiket($id)
    {
        return GuruPiket::where('id_pengguna', $id)->where('is_aktif', 1)->exists();
    }

    private function isGuruKpi($id)
    {
        return GuruKpi::where('id_pengguna', $id)->where('is_aktif', 1)->exists();
    }

    private function isWaliKelas($id)
    {
        return WaliKelas::where('id_guru', $id)->where('is_aktif', 1)->exists();
    }

    private function isGuruEkskul($id)
    {
        return PembinaEkskulSet::where('id_guru', $id)->where('is_aktif', 1)->exists();
    }

    private function isJurnalPimpinan($id)
    {
        return JurnalPimpinan::where('id_pengguna', $id)->where('is_aktif', 1)->exists();
    }
}
