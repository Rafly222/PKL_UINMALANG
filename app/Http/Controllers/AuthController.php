<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 🟢 1. Menampilkan Halaman Login (INI YANG TADI HILANG)
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'super_admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }

    // 🟢 2. Proses Log In (INI JUGA TADI HILANG)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return Auth::user()->role === 'super_admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 🟢 3. Menampilkan Halaman Registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // 🔵 4. Proses Registrasi (Kode asli milikmu yang sudah rapi)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'nik' => 'required|string|size:16|unique:users',
            'nip' => 'nullable|string|size:18|unique:users',
        ]);

        // PROTEKSI BLACKLIST: Periksa apakah NIP/NIK terdaftar di blacklist 
        $isBlacklisted = Blacklist::where('nik', $request->nik)->orWhere(function ($query) use ($request) {
            if ($request->nip) {
                $query->where('nip', $request->nip);
            }
        })->exists();

        if ($isBlacklisted) {
            return redirect()->back()->withErrors([
                'blacklist' => 'Akun Anda telah di-blacklist oleh Super Admin. Pendaftaran ditolak!'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'nip' => $request->nip,
            'role' => 'user' // Default sebagai event creator staff 
        ]);

        Auth::login($user);
        
        return redirect()->route('user.dashboard')->with('success', 'Akun berhasil dibuat!');
    }

    // 🟢 5. Proses Log Out
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}