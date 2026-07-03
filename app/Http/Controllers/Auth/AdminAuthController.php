<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
{
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }

    return view('admin.login');
}

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended('/admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password tidak cocok.'
    ]);
}

    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/admin/login');
}
}
// class AdminAuthController extends Controller
// {
//     public function showLogin() {
//         return view('admin.login');
//     }

//     // Fungsi login hanya boleh ada SATU
//     public function login(Request $request) {
//     $credentials = $request->validate([
//         'email' => ['required', 'email'],
//         'password' => ['required'],
//     ]);

//     if (Auth::attempt($credentials)) {
//         $request->session()->regenerate();
//         // Jika sampai di sini, artinya login BERHASIL
//         return redirect()->intended('/admin/dashboard');
//     }

//     // Jika sampai di sini, artinya login GAGAL
//     return back()->withErrors(['email' => 'Email atau password tidak cocok.']);
//     }

//     public function logout(Request $request) {
//         Auth::logout();
//         $request->session()->invalidate();
//         return redirect('/admin/login');
//     }
// }