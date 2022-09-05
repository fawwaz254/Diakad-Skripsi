<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use App\Models\Guru;
use App\Models\GuruPiket;
use App\Models\JurnalPimpinan;
use App\Models\PembinaEkskulSet;
use App\Models\Siswa;
use App\Models\WaliKelas;
use App\Models\WaliMurid;

use Auth;
use Session;

class TokenStaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Session::has('auth_data')) {
                $auth_data = Session::get('auth_data');
            } else {
                $pengguna = Auth::user();
                $sekolah_data = $pengguna->sekolah;

                $roles_pengguna = $pengguna->role_pengguna;
                $role_aktif = $roles_pengguna->where('is_aktif', 1)->first()->role;
                $moduls = Modul::with('menus')->where(['id_role' => $role_aktif->id_role, 'akses' => 1])->orderBy('urutan', 'asc')->get();

                $tambahan_modul = [];
                $id_pengguna = $pengguna->id_pengguna;
                if ($role_aktif->id_role == 2) {
                    $guru = Guru::where('id_pengguna', $id_pengguna)->first();

                    if ($guru) {
                        if ($this->isGuruPiket($id_pengguna)) {
                            $tambahan_modul[] = 35;
                        }

                        if ($this->isWaliKelas($guru->id_guru)) {
                            $tambahan_modul[] = 36;
                        }

                        if ($this->isGuruEkskul($guru->id_guru)) {
                            $tambahan_modul[] = 37;
                        }

                        if ($this->isJurnalPimpinan($id_pengguna)) {
                            $tambahan_modul[] = 126;
                        }
                    }
                }

                if ($role_aktif->id_role == 15) {
                    if ($this->isGuruPiket($id_pengguna)) {
                        $tambahan_modul[] = 35;
                    }
                }

                if (!empty($tambahan_modul)) {
                    $additionalModule = Modul::whereIn('id_modul', $tambahan_modul)->with('menus')->orderBy('urutan', 'asc')->get();
                    $moduls = collect($moduls->merge($additionalModule)->all());
                }

                // IF Wali Murid
                $nm_anak_murid = null;
                if($role_aktif->id_role == 4){
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
                    'nm_anak_murid' => $nm_anak_murid
                );

                Session::put('auth_data', $auth_data);
            }

            if (request()->segment(1) != $auth_data->role_aktif->path) {
                return redirect($auth_data->role_aktif->path);
            }
            
            $request->request->add(['auth_data' => $auth_data]);
            return $next($request);
        } else {
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
