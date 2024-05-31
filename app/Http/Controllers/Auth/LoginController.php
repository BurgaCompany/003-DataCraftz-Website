<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class LoginController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Jika pengguna sudah terotentikasi, arahkan mereka ke dashboard
            if (Auth::user()->hasRole('Root')) {
                return redirect()->route('upts.index');
            } elseif (Auth::user()->hasAnyRole(['Upt', 'Admin', 'PO'])) {
                return redirect()->route('dashboard');
            }
        }

        // Jika belum terotentikasi, tampilkan halaman login
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::user()->id);

            // Simpan peran pengguna dalam sesi
            session(['user_role' => $user->role]);

            if ($user->hasRole('Root')) {
                // Jika peran pengguna adalah 'root', tampilkan halaman read upt
                return redirect()->route('upts.index');
            } elseif ($user->hasAnyRole(['Upt', 'Admin', 'PO'])) {
                // Jika peran pengguna adalah 'upt', tampilkan full dashboard
                return redirect()->route('dashboard');
            }
        }


        return redirect()->back()->withInput($request->only('email'))->with('error', 'Email dan Password Salah !');
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
