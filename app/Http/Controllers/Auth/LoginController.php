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
        // Check if the user is already authenticated
        if (Auth::check()) {
            // If the user is authenticated, redirect them based on their role
            $user = Auth::user();

            if ($user->hasRole('Root')) {
                return redirect()->route('upts.index');
            } elseif ($user->hasRole('Upt')) {
                return redirect()->route('dashboard_upt');
            } elseif ($user->hasRole('Admin')) {
                return redirect()->route('dashboard_admin');
            } elseif ($user->hasRole('PO')) {
                return redirect()->route('dashboard_po');
            }
        }

        // If the user is not authenticated, show the login form
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
            } elseif ($user->hasRole('Upt')) {
                // Jika peran pengguna adalah 'upt', tampilkan full dashboard
                return redirect()->route('dashboard_upt');
            } elseif ($user->hasRole(['Admin'])) {
                // Jika peran pengguna adalah 'upt', tampilkan full dashboard
                return redirect()->route('dashboard_admin');
            } elseif ($user->hasRole(['PO'])) {
                // Jika peran pengguna adalah 'upt', tampilkan full dashboard
                return redirect()->route('dashboard_po');
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