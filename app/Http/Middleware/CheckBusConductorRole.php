<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBusConductorRole
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna telah login
        if (!Auth::check()) {
            // Jika pengguna belum login, kembalikan tanggapan 403 (Unauthorized)
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        // Periksa apakah pengguna memiliki peran Bus_Conductor
        if (!$request->user()->hasRole('Bus_Conductor')) {
            // Jika tidak, kembalikan tanggapan 403 (Unauthorized)
            return response()->json(['message' => 'Tidak memiliki akses'], 403);
        }

        // Lanjutkan eksekusi jika pengguna telah login dan memiliki peran Bus_Conductor
        return $next($request);
    }
}
