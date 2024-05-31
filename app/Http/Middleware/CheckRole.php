<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        // Periksa apakah pengguna telah login
        if (!Auth::check()) {
            // Jika pengguna belum login, arahkan ke halaman login
            return redirect()->route('login');
        }

        // Periksa apakah pengguna memiliki peran yang diperlukan
        if (!$request->user()->hasRole($role)) {
            // Jika tidak, kembalikan tanggapan 403 (Unauthorized)
            abort(403, 'Unauthorized action.');
        }

        // Lanjutkan eksekusi jika pengguna telah login dan memiliki peran yang diperlukan
        return $next($request);
    }
}
