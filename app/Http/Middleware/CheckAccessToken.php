<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckAccessToken
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->header('access_token');
        if (!$accessToken || strlen($accessToken) !== 40) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Periksa apakah access_token ada di database (misal disimpan di tabel `users` dengan kolom `access_token`)
        $user = DB::table('users')->where('access_token', $accessToken)->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Periksa apakah pengguna memiliki peran 'Passenger'
        if (!DB::table('model_has_roles')->where('model_id', $user->id)->where('role_id', 6)->exists()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $request->user = $user;
        return $next($request);
    }
}
