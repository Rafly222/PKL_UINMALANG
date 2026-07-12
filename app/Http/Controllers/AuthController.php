<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' ? redirect('/admin/dashboard') : redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah NIK atau NIP user terdaftar di database Blacklist
            $isBlacklisted = \App\Models\Blacklist::where('nik', $user->nik)
                ->orWhere(function($query) use ($user) {
                    if ($user->nip) {
                        $query->where('nip', $user->nip);
                    }
                })->exists();

            if ($isBlacklisted) {
                // Catat Log Aktivitas Percobaan Login Ditolak
                \App\Models\ActivityLog::create([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'activity' => 'login_blocked',
                    'description' => "Percobaan masuk diblokir: Kredensial '{$user->name}' terdaftar di database Blacklist.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('warning', 'Akses ditolak: Akun Anda ditangguhkan karena identitas NIK/NIP masuk dalam daftar Blacklist.');
            }

            $request->session()->regenerate();

            // Catat Log Aktivitas Sukses
            \App\Models\ActivityLog::log('login', "Pengguna '{$user->name}' (Role: {$user->role}) berhasil masuk ke dashboard.");

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', "Selamat datang Super Admin, {$user->name}!");
            }
            return redirect('/dashboard')->with('success', "Selamat datang, {$user->name}!");
        }

        return back()->with('warning', 'Kredensial login salah atau tidak terdaftar.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        $request->validate([
            'nik' => 'required|size:16',
            'nip' => 'nullable|size:18',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        // Tahap 2: Logika Validasi Blacklist NIP/NIK
        $isBlacklisted = Blacklist::where('nik', $request->nik)
            ->orWhere(function($query) use ($request) {
                if ($request->nip) {
                    $query->where('nip', $request->nip);
                }
            })->exists();

        if ($isBlacklisted) {
            return back()->with('warning', 'Registrasi Gagal: Akun NIK/NIP Anda telah di-blacklist oleh Super Admin!');
        }

        $user = User::create([
            'nik' => $request->nik,
            'nip' => $request->nip,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        // Catat Log Aktivitas
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'activity' => 'register',
            'description' => "Pendaftaran mandiri akun baru oleh '{$user->name}' (Email: {$user->email}, NIK: {$user->nik}).",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Models\ActivityLog::log('logout', "Pengguna '" . Auth::user()->name . "' keluar dari dashboard.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'Anda telah keluar dari dashboard.');
    }
}