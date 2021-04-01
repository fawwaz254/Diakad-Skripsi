<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Pengguna;
use App\Models\Siswa;
use App\Models\WaliMurid;

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

        $actor_id = !empty($request->input('actor_id'))? $request->input('actor_id') : null;

        if(!empty($actor_id)){
            $pengguna = Pengguna::where(['id_pengguna' => $id_pengguna, 'api_key' => $api_key, 'status_join_table' => $actor_id])->first();
        }else{
            $pengguna = Pengguna::where(['id_pengguna' => $id_pengguna, 'api_key' => $api_key])->first();
        }

        if($pengguna){
            $actor = null;
            if(!empty($actor_id) && $actor_id == 3 && $pengguna->isSiswa){
                $actor = Siswa::where('id_pengguna', $pengguna->id_pengguna)->first();
            }

            if(!empty($actor_id) && $actor_id == 4 && $pengguna->isWaliMurid){
                $actor = WaliMurid::where('id_pengguna', $pengguna->id_pengguna)->first();
            }

            $auth_data = (object) array(
                'pengguna' => $pengguna,
                'sekolah_data' => $pengguna->sekolah,
                'actor' => $actor
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