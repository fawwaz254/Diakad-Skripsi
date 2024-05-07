<?php

namespace App\Http\Middleware;

use App\Models\Pengguna;
use App\Models\PenggunaLogin;
use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class TrackingUserLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $pengguna = Auth::user();

            $pengguna_login_terakhir = PenggunaLogin::where('id_pengguna', $pengguna->id_pengguna)
                ->orderBy('login_time', 'desc')
                ->value('login_time');

            if (!$this->isSessionActive($pengguna_login_terakhir)) {
                $log = new PenggunaLogin();
                $log->id_pengguna_login = strtotime(now()) . uniqid();
                $log->id_pengguna = $pengguna->id_pengguna;
                $log->login_time = now();
                $log->save();
            }
        }

        return $next($request);
    }

    private function isSessionActive($pengguna_login_terakhir)
    {
        return $pengguna_login_terakhir > now()->subMinutes(config('session.lifetime'));
    }
}
