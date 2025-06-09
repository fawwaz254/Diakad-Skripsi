<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\PelatihEkskul;
use App\Models\Pengguna;
use App\Models\Siswa;
use App\Models\Staff;
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
    public function handle(Request $request, Closure $next)
    {
        $api_key = $request->input('api_key');
        $id_pengguna = $request->input('user_id');

        $pengguna = Pengguna::where(['id_pengguna' => $id_pengguna, 'api_key' => $api_key])->first();

        if ($pengguna) {
            $actor = null;
            if ($pengguna->isPegawai) {
                $actor = Staff::where('id_pengguna', $pengguna->id_pengguna)->first();
            } else if ($pengguna->isPegawai) {
                $actor = Guru::where('id_pengguna', $pengguna->id_pengguna)->first();
            } else if ($pengguna->isSiswa) {
                $actor = Siswa::where('id_pengguna', $pengguna->id_pengguna)->first();
            } else if ($pengguna->isWaliMurid) {
                $actor = WaliMurid::where('id_pengguna', $pengguna->id_pengguna)->first();
            } else if ($pengguna->isPelatihEkskul) {
                $actor = PelatihEkskul::where('id_pengguna', $pengguna->id_pengguna)->first();
            }

            $auth_data = (object) array(
                'pengguna' => $pengguna,
                'sekolah_data' => $pengguna->sekolah,
                'actor' => $actor
            );
            Session::put('auth_data', $auth_data);

            return $next($request);
        } else {
            return response()->json([
                'status_code'     => 300,
                'status_text'     => 'Failed',
                'message'     => 'Authentification failed'
            ]);
        }
    }
}
