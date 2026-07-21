<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Blacklist;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class DashboardController extends Controller
{
    // Dashboard Staff Pembuat Event
    public function userIndex()
    {
       $events = Event::where('user_id', Auth::id())->latest()->get();
        return view('dashboard.user', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i',
            'access_type' => 'required|in:publik,privat',
            'password' => 'required_if:access_type,privat|nullable|string|min:4',
            'audience_type' => 'required|in:umum,pegawai,semua',
            'custom_labels' => 'nullable|array',
            'custom_types' => 'nullable|array',
        ]);

        // Ambil isian checklist semi-custom
        $fields = ['sc-name'];
        foreach (['sc-phone', 'sc-gender', 'sc-institution', 'sc-email', 'sc-photo', 'sc-signature'] as $field) {
            if ($request->boolean($field)) {
                $fields[] = $field;
            }
        }

        if ($request->has('sc-nip') || $request->audience_type === 'pegawai') {
            $fields[] = 'sc-nip';
        }

        $fields = array_values(array_unique($fields));

        // Ambil isian dinamis full-custom
        $custom_fields = [];
        if ($request->has('custom_labels')) {
            foreach ($request->custom_labels as $index => $label) {
                if (!empty($label)) {
                    $custom_fields[] = [
                        'label' => $label,
                        'type' => $request->custom_types[$index] ?? 'text'
                    ];
                }
            }
        }

        $event = Event::create([
            'user_id' => auth::id(),
            'name' => $request->name,
            'date' => $request->date,
            'date_end' => $request->date_end ?? $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'access_type' => $request->access_type,
            'password' => $request->access_type === 'privat' ? Hash::make($request->password) : null,
            'audience_type' => $request->audience_type,
            'fields' => $fields,
            'custom_fields' => $custom_fields
        ]);

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('create_event', "Pengguna mendaftarkan event baru: '{$event->name}' (Kategori: {$event->audience_type}, Akses: {$event->access_type}).");

        return back()->with('success', 'Event baru berhasil didaftarkan & siap digunakan!');
    }

    public function updateEvent(Request $request, Event $event)
    {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i',
            'access_type' => 'required|in:publik,privat',
            'password' => 'nullable|string|min:4',
            'audience_type' => 'required|in:umum,pegawai,semua',
            'custom_labels' => 'nullable|array',
            'custom_types' => 'nullable|array',
        ]);

        if ($request->access_type === 'privat' && !$event->password && !$request->filled('password')) {
            return back()->withErrors(['password' => 'Password wajib diisi jika merubah akses menjadi privat.']);
        }

        // Ambil isian checklist semi-custom
        $fields = ['sc-name'];
        foreach (['sc-phone', 'sc-gender', 'sc-institution', 'sc-email', 'sc-photo', 'sc-signature'] as $field) {
            if ($request->boolean($field)) {
                $fields[] = $field;
            }
        }

        if ($request->has('sc-nip') || $request->audience_type === 'pegawai') {
            $fields[] = 'sc-nip';
        }

        $fields = array_values(array_unique($fields));

        // Ambil isian dinamis full-custom
        $custom_fields = [];
        if ($request->has('custom_labels')) {
            foreach ($request->custom_labels as $index => $label) {
                if (!empty($label)) {
                    $custom_fields[] = [
                        'label' => $label,
                        'type' => $request->custom_types[$index] ?? 'text'
                    ];
                }
            }
        }

        $data = [
            'name' => $request->name,
            'date' => $request->date,
            'date_end' => $request->date_end ?? $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'access_type' => $request->access_type,
            'audience_type' => $request->audience_type,
            'fields' => $fields,
            'custom_fields' => $custom_fields
        ];

        if ($request->access_type === 'privat') {
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
        } else {
            $data['password'] = null;
        }

        $event->update($data);

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('update_event', "Pengguna memperbarui event: '{$event->name}' (ID: {$event->id}).");

        return back()->with('success', 'Event berhasil diperbarui!');
    }

    // Dashboard Super Admin
    public function adminIndex()
    {
        $events = Event::with('creator')->latest()->get();
        $totalUsers = User::where('status', 'approved')->count();
        $totalBlacklists = Blacklist::count();
        $totalLogs = \App\Models\ActivityLog::count();
        $pendingUsersCount = User::where('status', 'pending')->count();
        return view('dashboard.admin', compact('events', 'totalUsers', 'totalBlacklists', 'totalLogs', 'pendingUsersCount'));
    }

    public function adminBlacklist()
    {
        return redirect()->route('admin.users');
    }

    public function adminUsers(Request $request)
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

        return view('admin.users', compact(
            'users', 
            'pendingUsers', 
            'blacklistedNips', 
            'filter',
            'countActive',
            'countPending',
            'countBlacklisted',
            'countTrashed'
        ));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);

        \App\Models\ActivityLog::log('approve_user', "Admin menyetujui pendaftaran akun: '{$user->name}' (Email: {$user->email}).");

        return back()->with('success', "Akun '{$user->name}' berhasil disetujui!");
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $email = $user->email;
        $user->delete();

        \App\Models\ActivityLog::log('reject_user', "Admin menolak pendaftaran akun: '{$name}' (Email: {$email}).");

        return back()->with('info', "Pendaftaran akun '{$name}' ditolak dan data dihapus.");
    }

    public function adminLogs(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $activityFilter = $request->query('activity_filter', 'all');

        $baseQuery = \App\Models\ActivityLog::query();

        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $baseQuery->where('created_at', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $baseQuery->where('created_at', '<=', $endDate . ' 23:59:59');
        }

        $countTotalEvents = \App\Models\Event::count();
        $countLoginSuccess = (clone $baseQuery)->where('activity', 'login')->count();
        $countLoginFailed = (clone $baseQuery)->where('activity', 'login_failed')->count();
        $countBlocked = (clone $baseQuery)->whereIn('activity', ['blacklist_add', 'login_blocked'])->count();
        $countLogout = (clone $baseQuery)->where('activity', 'logout')->count();
        $countUniqueIps = (clone $baseQuery)->whereNotNull('ip_address')->where('ip_address', '!=', '')->distinct('ip_address')->count('ip_address');

        $logQuery = clone $baseQuery;
        if ($activityFilter === 'login') {
            $logQuery->where('activity', 'login');
        } elseif ($activityFilter === 'login_failed') {
            $logQuery->where('activity', 'login_failed');
        } elseif ($activityFilter === 'blocked') {
            $logQuery->whereIn('activity', ['blacklist_add', 'login_blocked']);
        } elseif ($activityFilter === 'logout') {
            $logQuery->where('activity', 'logout');
        }

        $systemLogs = $logQuery->latest()->take(500)->get();

        return view('admin.logs', compact(
            'systemLogs',
            'countTotalEvents',
            'countLoginSuccess',
            'countLoginFailed',
            'countBlocked',
            'countLogout',
            'countUniqueIps',
            'startDate',
            'endDate',
            'activityFilter'
        ));
    }

    public function storeUserByAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
            'nip' => 'nullable|size:18|unique:users,nip'
        ], [
            'nip.unique' => 'NIP tersebut sudah terdaftar pada akun pengguna lain.',
            'nip.size' => 'NIP harus berisi tepat 18 digit.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar.'
        ]);

        // Cek blacklist NIP
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

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('create_user', "Admin mendaftarkan akun baru: '{$newUser->name}' (Role: {$newUser->role}).");

        return back()->with('success', 'Akun pengguna baru sukses ditambahkan!');
    }

    public function destroyUserByAdmin($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;

        \App\Models\ActivityLog::log('delete_user', "Admin menghapus akun pengguna: '{$name}'.");

        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        if (!$user->nip) {
            return back()->with('warning', 'Pengguna ini tidak memiliki NIP. Harap isi NIP terlebih dahulu melalui menu Edit Akun.');
        }

        Blacklist::firstOrCreate(['nip' => $user->nip]);

        \App\Models\ActivityLog::log('blacklist_add', "Admin mem-blacklist akun pengguna: '{$user->name}' (NIP: {$user->nip}).");

        return back()->with('success', "Akun '{$user->name}' berhasil diblokir!");
    }

    public function unblockUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->nip) {
            Blacklist::where('nip', $user->nip)->delete();
        }

        \App\Models\ActivityLog::log('blacklist_remove', "Admin memulihkan akses akun pengguna: '{$user->name}' (NIP: {$user->nip}).");

        return back()->with('info', "Akses akun '{$user->name}' berhasil dipulihkan.");
    }

    public function restoreUserByAdmin($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        \App\Models\ActivityLog::log('restore_user', "Admin memulihkan akun terhapus: '{$user->name}' (Email: {$user->email}).");

        return back()->with('success', "Akun '{$user->name}' berhasil dipulihkan!");
    }

    public function updateUserByAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:user,admin',
            'nip' => 'nullable|size:18|unique:users,nip,' . $user->id
        ], [
            'nip.unique' => 'NIP tersebut sudah terdaftar pada akun pengguna lain.',
            'nip.size' => 'NIP harus berisi tepat 18 digit.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar.'
        ]);

        // Cek blacklist jika NIP diubah
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

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('update_user', "Admin memperbarui data akun pengguna: '{$user->name}' (Role: {$user->role}).");

        return back()->with('success', 'Akun pengguna berhasil diperbarui!');
    }

    public function removeBlacklist($id)
    {
        $blacklist = Blacklist::findOrFail($id);
        
        // Catat Log Aktivitas
        $blNip = $blacklist->nip ?? '-';
        \App\Models\ActivityLog::log('blacklist_remove', "Admin memulihkan identitas dari Blacklist (NIP: {$blNip}).");

        $blacklist->delete();
        return back()->with('info', 'Blokir identitas berhasil dipulihkan.');
    }

    public function addManualBlacklist(Request $request)
    {
        $request->validate([
            'nip' => 'required|size:18'
        ]);

        if (Blacklist::where('nip', $request->nip)->exists()) {
            return back()->with('warning', 'NIP tersebut sudah terdaftar di Blacklist.');
        }

        Blacklist::create([
            'nip' => $request->nip
        ]);

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('blacklist_add', "Admin mem-blacklist identitas secara manual (NIP: {$request->nip}).");

        return back()->with('success', 'Identitas manual berhasil ditambahkan ke Blacklist.');
    }

    public function destroyEvent(Event $event)
    {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('delete_event', "Pengguna menghapus event: '{$event->name}' (ID: {$event->id}).");

        $event->delete();

        return back()->with('success', 'Event berhasil dihapus.');
    }

    // Melihat daftar kehadiran per event
    public function eventPresences($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

        // Otorisasi: Pembuat event atau Admin
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $presences = $event->presences()->latest()->get();

        return view('dashboard.presences', compact('event', 'presences'));
    }

    // Ekspor rekapan presensi ke excel per event
    public function exportPresenceExcel($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

        // Otorisasi: Pembuat event atau Admin
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $presences = $event->presences()->latest()->get();

        $filename = "Rekap_Presensi_" . \Illuminate\Support\Str::slug($event->name, '_') . "_" . date('Y-m-d') . ".xls";

        return response()->stream(function () use ($event, $presences) {
            echo view('exports.presence_excel', compact('event', 'presences'))->render();
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // Menampilkan foto presensi dalam bentuk gambar biner (Akses Publik via Link Excel)
    public function showPresencePhoto($id)
    {
        $presence = Presence::findOrFail($id);

        if (!$presence->photo) {
            abort(404);
        }

        // Kompatibilitas mundur: Cek jika data berupa string base64 panjang
        if (str_starts_with($presence->photo, 'data:image') || !str_contains($presence->photo, '/')) {
            $data = explode(',', $presence->photo);
            $image = base64_decode($data[1] ?? $data[0]);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        // Ambil dari file storage disk local (storage/app/private/)
        if (\Illuminate\Support\Facades\Storage::exists($presence->photo)) {
            $image = \Illuminate\Support\Facades\Storage::get($presence->photo);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        // Fallback ke storage/app/ (untuk data test sebelum dikembalikan ke default laravel)
        $fallbackPath = storage_path('app/' . $presence->photo);
        if (file_exists($fallbackPath)) {
            $image = file_get_contents($fallbackPath);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        abort(404);
    }

    // Menampilkan tanda tangan presensi dalam bentuk gambar biner (Akses Publik via Link Excel)
    public function showPresenceSignature($id)
    {
        $presence = Presence::findOrFail($id);

        if (!$presence->signature) {
            abort(404);
        }

        // Kompatibilitas mundur: Cek jika data berupa string base64 panjang
        if (str_starts_with($presence->signature, 'data:image') || !str_contains($presence->signature, '/')) {
            $data = explode(',', $presence->signature);
            $image = base64_decode($data[1] ?? $data[0]);
            return response($image)->header('Content-Type', 'image/png');
        }

        // Ambil dari file storage disk local (storage/app/private/)
        if (\Illuminate\Support\Facades\Storage::exists($presence->signature)) {
            $image = \Illuminate\Support\Facades\Storage::get($presence->signature);
            return response($image)->header('Content-Type', 'image/png');
        }

        // Fallback ke storage/app/
        $fallbackPath = storage_path('app/' . $presence->signature);
        if (file_exists($fallbackPath)) {
            $image = file_get_contents($fallbackPath);
            return response($image)->header('Content-Type', 'image/png');
        }

        abort(404);
    }
}
