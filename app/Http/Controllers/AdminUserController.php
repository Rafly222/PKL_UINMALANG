<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blacklist;
use App\Models\ActivityLog;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status_filter', 'all');
        $blacklistedNips = Blacklist::pluck('nip')->filter()->toArray();

        $query = User::where('id', '!=', Auth::id());

        if ($filter === 'approved') {
            $query->where('status', 'approved')->whereNotIn('nip', $blacklistedNips);
        } elseif ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'blacklisted') {
            $query->whereIn('nip', $blacklistedNips);
        } elseif ($filter === 'trashed') {
            $query->onlyTrashed();
        }

        $users = $query->latest()->get();
        $pendingUsers = User::where('status', 'pending')->latest()->get();

        $countActive = User::where('status', 'approved')->whereNotIn('nip', $blacklistedNips)->count();
        $countPending = User::where('status', 'pending')->count();
        $countBlacklisted = Blacklist::count();
        $countTrashed = User::onlyTrashed()->count();

        $trashedUsers = User::onlyTrashed()->latest()->get();

        return view('admin.users', compact(
            'users', 
            'pendingUsers', 
            'trashedUsers',
            'blacklistedNips', 
            'filter',
            'countActive',
            'countPending',
            'countBlacklisted',
            'countTrashed'
        ));
    }

    public function store(StoreUserRequest $request)
    {
        $isBlacklisted = false;
        if ($request->filled('nip')) {
            $isBlacklisted = Blacklist::where('nip', $request->nip)->exists();
        }

        if ($isBlacklisted) {
            return back()->with('warning', 'Identitas terdaftar di database Blacklist!');
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nip' => $request->nip
        ]);

        ActivityLog::log('create_user', "Admin mendaftarkan akun baru: '{$newUser->name}' (Role: {$newUser->role}).");

        return back()->with('success', 'Akun pengguna baru sukses ditambahkan!');
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->filled('nip') && $request->nip !== $user->nip) {
            $isBlacklisted = Blacklist::where('nip', $request->nip)->exists();
            if ($isBlacklisted) {
                return back()->with('warning', 'NIP baru tersebut terdaftar di database Blacklist!');
            }
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nip' => $request->nip
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        ActivityLog::log('update_user', "Admin memperbarui data akun pengguna: '{$user->name}' (Role: {$user->role}).");

        return back()->with('success', 'Akun pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;

        ActivityLog::log('delete_user', "Admin menghapus akun pengguna: '{$name}'.");

        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);

        ActivityLog::log('approve_user', "Admin menyetujui pendaftaran akun: '{$user->name}' (Email: {$user->email}).");

        return back()->with('success', "Akun '{$user->name}' berhasil disetujui!");
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $email = $user->email;
        $user->delete();

        ActivityLog::log('reject_user', "Admin menolak pendaftaran akun: '{$name}' (Email: {$email}).");

        return back()->with('info', "Pendaftaran akun '{$name}' ditolak dan data dihapus.");
    }

    public function block($id)
    {
        $user = User::findOrFail($id);
        if (!$user->nip) {
            return back()->with('warning', 'Pengguna ini tidak memiliki NIP. Harap isi NIP terlebih dahulu melalui menu Edit Akun.');
        }

        Blacklist::firstOrCreate(['nip' => $user->nip]);

        ActivityLog::log('blacklist_add', "Admin mem-blacklist akun pengguna: '{$user->name}' (NIP: {$user->nip}).");

        return back()->with('success', "Akun '{$user->name}' berhasil diblokir!");
    }

    public function unblock($id)
    {
        $user = User::findOrFail($id);
        if ($user->nip) {
            Blacklist::where('nip', $user->nip)->delete();
        }

        ActivityLog::log('blacklist_remove', "Admin memulihkan akses akun pengguna: '{$user->name}' (NIP: {$user->nip}).");

        return back()->with('info', "Akses akun '{$user->name}' berhasil dipulihkan.");
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        ActivityLog::log('restore_user', "Admin memulihkan akun terhapus: '{$user->name}' (Email: {$user->email}).");

        return back()->with('success', "Akun '{$user->name}' berhasil dipulihkan!");
    }
}
