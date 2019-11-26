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
use App\Models\WaliKelas;

use Auth;

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
            $pengguna = Auth::user();

            $sekolah_data = $pengguna->sekolah;

            $roles_pengguna = $pengguna->role_pengguna;
            $role_aktif = $roles_pengguna->where('is_aktif', 1)->first();
            $moduls = Modul::where(['id_role' => $role_aktif->id_role, 'akses' => 1])->orderBy('urutan', 'asc')->get();
            $menus = Menu::with('modul')->whereIn('id_modul', $moduls->pluck('id_modul'))->where('akses', 1)->orderBy('urutan', 'asc')->get();

            if (request()->segment(1) != 'pendidikan' && $role_aktif->id_role == 1) {
                // return redirect('pendidikan/'.request()->segment(2));
                // Auth::logout();
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

            $tambahan_modul = [];
            if ($role_aktif->id_role == 2) {
                if ($guru = Guru::where('id_pengguna', $pengguna->id_pengguna)->first()) {
                    if ($wali_kelas = WaliKelas::where('id_guru', $guru->id_guru)->first()) {
                        $tambahan_modul[] = 36;
                    }

                    if ($pembina_ekskul_set = PembinaEkskulSet::where('id_guru', $guru->id_guru)->first()) {
                        $tambahan_modul[] = 37;
                    }
                }
            }

            if ($role_aktif->id_role == 15) {
                if ($guru_piket = GuruPiket::where('id_pengguna', $pengguna->id_pengguna)->first()) {
                    $tambahan_modul[] = 35;
                }
            }

            if (!empty($tambahan_modul)) {
                $moduls_2 = Modul::whereIn('id_modul', $tambahan_modul)->get();
                $menus_2 = Menu::whereIn('id_modul', $tambahan_modul)->where('is_aktif', 1)->get();

                $moduls = collect($moduls->merge($moduls_2)->all());
                $menus = collect($menus->merge($menus_2)->all());
            }

            $auth_data = (object) array(
                'pengguna' => $pengguna,
                'sekolah_data' => $sekolah_data,
                'roles_pengguna' => $roles_pengguna,
                'moduls' => $moduls,
                'menus' => $menus
            );
            $request->request->add(['auth_data' => $auth_data]);
            return $next($request);
        } else {
            return redirect('/');
        }
    }
}
