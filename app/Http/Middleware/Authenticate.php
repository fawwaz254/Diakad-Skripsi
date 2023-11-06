<?php

namespace App\Http\Middleware;

use App\Models\Sekolah;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // return route('login');
            $sekolah = Sekolah::orderBy('id_sekolah')->first();
            return view('signin', compact('sekolah'));
        }
    }
}
