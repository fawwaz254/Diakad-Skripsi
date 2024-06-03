<?php

namespace App\Http\Middleware;

use Closure;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingUserActivity
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
        if (auth()->check()) {
            if (url()->current() !== url('/') && url()->current() !== url('/') . '/' . $request->segment(1)) {
                DB::table('log_aktivitas_pengguna')->insert([
                    'id_log_aktivitas_pengguna' => Uuid::uuid4()->toString(),
                    'id_pengguna' => auth()->user()->id_pengguna,
                    'route' => url()->current(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
