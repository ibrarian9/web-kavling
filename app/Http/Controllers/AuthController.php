<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            \App\Services\ActivityLogger::log('AUTH_LOGIN', 'Pengguna berhasil login ke dalam sistem.');
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang dimasukkan tidak cocok dengan data kami.',
        ]);
    }

    public function switchRole(Request $request, string $role)
    {
        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            \App\Services\ActivityLogger::log('AUTH_SWITCH_ROLE', 'Beralih peran menjadi: ' . strtoupper($role));
            return redirect()->route('dashboard')->with('success', 'Berhasil beralih peran sebagai: ' . strtoupper($role));
        }

        return redirect()->back()->with('error', 'User dengan peran tersebut tidak ditemukan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
