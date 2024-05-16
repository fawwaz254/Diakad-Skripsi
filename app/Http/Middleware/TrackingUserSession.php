<?php

namespace App\Http\Middleware;

use App\Models\Pengguna;
use App\Models\Logs;
use App\Models\LogSesiPengguna;
use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class TrackingUserSession
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

            $sesi_terakhir_pengguna = LogSesiPengguna::where('id_pengguna', $pengguna->id_pengguna)
                ->orderBy('login_time', 'desc')
                ->value('login_time');

            if (!$this->isSessionActive($sesi_terakhir_pengguna)) {
                $log = new LogSesiPengguna();
                $log->id_log_sesi_pengguna = strtotime(now()) . uniqid();
                $log->id_pengguna = $pengguna->id_pengguna;
                $log->login_time = now();
                $log->save();
            }
        }

        return $next($request);
    }

    private function isSessionActive($sesi_terakhir_pengguna)
    {
        return $sesi_terakhir_pengguna > now()->subMinutes(config('session.lifetime'));
    }
}
