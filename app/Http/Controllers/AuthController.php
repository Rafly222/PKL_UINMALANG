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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => config('services.recaptcha.secret_key') ? ['required', new \App\Rules\Recaptcha] : ['nullable']
        ], [
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib diisi.'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah akun user sudah disetujui oleh admin
            if ($user->status !== 'approved') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($user->status === 'pending') {
                    return back()->with('warning', 'Pendaftaran akun Anda masih menunggu persetujuan (approval) dari Super Admin. Silakan hubungi admin via WhatsApp/Telepon ke nomor 085756654123 untuk konfirmasi persetujuan akun Anda.');
                } else {
                    return back()->with('warning', 'Pendaftaran akun Anda telah ditolak oleh Admin.');
                }
            }

            // Cek apakah NIP user terdaftar di database Blacklist
            $isBlacklisted = false;
            if ($user->nip) {
                $isBlacklisted = \App\Models\Blacklist::where('nip', $user->nip)->exists();
            }

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

        \App\Models\ActivityLog::create([
            'user_id' => null,
            'user_name' => $request->email ?? 'Tamu (Guest)',
            'activity' => 'login_failed',
            'description' => "Percobaan login gagal untuk email: '{$request->email}'.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('warning', 'Kredensial login salah atau tidak terdaftar.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|size:18|unique:users,nip',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => config('services.recaptcha.secret_key') ? ['required', new \App\Rules\Recaptcha] : ['nullable']
        ], [
            'nip.unique' => 'NIP tersebut sudah terdaftar pada akun lain.',
            'nip.size' => 'NIP harus berisi tepat 18 digit.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib diisi.'
        ]);

        // Tahap 2: Logika Validasi Blacklist NIP
        $isBlacklisted = false;
        if ($request->filled('nip')) {
            $isBlacklisted = Blacklist::where('nip', $request->nip)->exists();
        }

        if ($isBlacklisted) {
            return back()->with('warning', 'Registrasi Gagal: Akun NIK/NIP Anda telah di-blacklist oleh Super Admin!');
        }

        $user = User::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'pending'
        ]);

        // Catat Log Aktivitas
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'activity' => 'register',
            'description' => "Pendaftaran mandiri akun baru oleh '{$user->name}' (Email: {$user->email}).",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect('/login')->with('info', 'Pendaftaran mandiri berhasil! Akun Anda sedang menunggu persetujuan (approval) dari Super Admin sebelum dapat digunakan. Silakan hubungi admin via WhatsApp/Telepon ke nomor 085756654123 untuk konfirmasi persetujuan akun Anda.');
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