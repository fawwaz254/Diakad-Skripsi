<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
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
        if(Auth::check()){
            $pengguna = Auth::user();

            $sekolah_data = $pengguna->sekolah;

            $roles_pengguna = $pengguna->role_pengguna;
            $role_aktif = $roles_pengguna->where('is_aktif', 1)->first();
            $moduls = Modul::where(['id_role' => $role_aktif->id_role, 'akses' => 1])->orderBy('urutan', 'asc')->get();
            $menus = Menu::whereIn('id_modul', $moduls->pluck('id_modul'))->where('akses', 1)->orderBy('urutan', 'asc')->get();

            if(request()->segment(1) != 'pendidikan' && $role_aktif->id_role == 1){
                // return redirect('pendidikan/'.request()->segment(2));
                // Auth::logout();
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'guru' && $role_aktif->id_role == 2){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'siswa' && $role_aktif->id_role == 3){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'wali-murid' && $role_aktif->id_role == 4){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'bimbingan-konseling' && $role_aktif->id_role == 5){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'kesiswaan' && $role_aktif->id_role == 6){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'akademik' && $role_aktif->id_role == 7){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'sumber-daya' && $role_aktif->id_role == 8){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'keuangan' && $role_aktif->id_role == 9){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'sarana-prasarana' && $role_aktif->id_role == 10){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'ppdb' && $role_aktif->id_role == 11){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'alumni' && $role_aktif->id_role == 12){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'pelatih-ekskul' && $role_aktif->id_role == 13){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'sekretariat' && $role_aktif->id_role == 14){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'tendik' && $role_aktif->id_role == 15){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
            }

            if(request()->segment(1) != 'administrator' && $role_aktif->id_role == 16){
                $role = Role::find($role_aktif->id_role);
                return redirect($role->path);
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
        }else{
            return redirect('/');
        }
    }
}