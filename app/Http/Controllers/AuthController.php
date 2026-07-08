<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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
            return redirect()->back()->withErrors(['blacklist' => 'Akun 
Anda telah di-blacklist oleh Super Admin. Pendaftaran ditolak!'])->withInput();
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
        return redirect()->route('user.dashboard')->with('success', 'Akun 
berhasil dibuat!');
    }
}
