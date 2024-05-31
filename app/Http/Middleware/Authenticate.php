<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;


class Authenticate extends Middleware
{
    // Metode handle sesuai dengan kontrak Illuminate\Auth\Middleware\Authenticate
    public function handle($request, Closure $next, ...$guards)
    {

        // Jika pengguna tidak terotentikasi, arahkan ke halaman login
        if (!$request->expectsJson()) {
            return route('login'); // Ubah ini dengan URL yang sesuai
        }

        return $next($request);
    }
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
