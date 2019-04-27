<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

use App\Models\Pengguna;

class MobileMiddleware
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
        $api_key = $request->input('api_key');
        $id_pengguna = $request->input('user_id');

        if($pengguna = Pengguna::where(['id_pengguna' => $id_pengguna, 'api_key' => $api_key])->first()){
            // $actor = ['Pegawai', 'Guru', 'Siswa', 'Wali Murid', 'Pelatih Ekskul'];
            // if(request()->segment(1) != 'guru' && $pengguna->status_join_to_text() == $actor[0]){
            //     $role = Role::find($role_aktif->id_role);
            //     return redirect($role->path);
            // }
            $auth_data = (object) array(
                'pengguna' => $pengguna,
                'sekolah_data' => $pengguna->sekolah
            );
            $request->request->add(['auth_data' => $auth_data]);
            
            return $next($request);
        }else{
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' 	=> 'Authentification failed'
            ]);
        }
    }
}