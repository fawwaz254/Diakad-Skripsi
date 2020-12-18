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
                $role_aktif = $roles_pengguna->where('is_aktif', 1)->first();
                $moduls = Modul::with('menus')->where(['id_role' => $role_aktif->id_role, 'akses' => 1])->orderBy('urutan', 'asc')->get();

                if (request()->segment(1) != 'pendidikan' && $role_aktif->id_role == 1) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'guru' && $role_aktif->id_role == 2) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'siswa' && $role_aktif->id_role == 3) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'wali-murid' && $role_aktif->id_role == 4) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'bimbingan-konseling' && $role_aktif->id_role == 5) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'kesiswaan' && $role_aktif->id_role == 6) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'akademik' && $role_aktif->id_role == 7) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'sumber-daya' && $role_aktif->id_role == 8) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'keuangan' && $role_aktif->id_role == 9) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'sarana-prasarana' && $role_aktif->id_role == 10) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'ppdb' && $role_aktif->id_role == 11) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'alumni' && $role_aktif->id_role == 12) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'pelatih-ekskul' && $role_aktif->id_role == 13) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'sekretariat' && $role_aktif->id_role == 14) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'tendik' && $role_aktif->id_role == 15) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'administrator' && $role_aktif->id_role == 16) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                if (request()->segment(1) != 'humas' && $role_aktif->id_role == 19) {
                    $role = Role::find($role_aktif->id_role);
                    return redirect($role->path);
                }

                $tambahan_modul = [];
                if ($role_aktif->id_role == 2) {
                    $id_pengguna = $pengguna->id_pengguna;
                    $guru = Guru::where('id_pengguna', $id)->first();

                    if ($guru) {

                        if ($this->isGuruPiket($id_pengguna)) {
                            $tambahan_modul[] = 35;
                        }

                        if ($this->isGuruPiket($guru->id_guru)) {
                            $tambahan_modul[] = 36;
                        }

                        if ($this->isGuruEkskul($guru->id_guru)) {
                            $tambahan_modul[] = 37;
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
                    'roles_pengguna' => $roles_pengguna,
                    'moduls' => $moduls,
                    'nm_anak_murid' => $nm_anak_murid
                );

                Session::put('auth_data', $auth_data);
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
}
