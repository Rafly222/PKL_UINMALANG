<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. HALAMAN BERANDA & PRESENSI UMUM
// ==========================================
Route::get('/', [PresenceController::class, 'index'])->name('home');

// Rute Presensi Publik (Tahap 3 & 5)
Route::get('/presensi/{event_uuid}', [PresenceController::class, 'showForm'])->name('presence.form');
Route::post('/presensi/{event_uuid}', [PresenceController::class, 'submitForm']);
Route::get('/presensi/{event_uuid}/gate', [PresenceController::class, 'showGate'])->name('presence.gate');
Route::post('/presensi/{event_uuid}/gate', [PresenceController::class, 'checkGatePassword']);
Route::get('/presensi/sukses/{presence_uuid}', [PresenceController::class, 'showSuccess'])->name('presence.success');

// Mock API Integrasi NIP Pegawai Malang
Route::get('/api/pegawai/{nip}', [PresenceController::class, 'mockEmployeeApi']);


// ==========================================
// 2. OTENTIKASI (HANYA UNTUK CREATOR / STAFF)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'handleRegister']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 3. AREA PROTEKSI: CREATOR / PEMBUAT EVENT (WAJIB LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Masuk ke dashboard creator untuk kelola form absensi sendiri
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard.user');
    Route::post('/dashboard/event/create', [DashboardController::class, 'storeEvent'])->name('event.store');
    Route::put('/dashboard/event/{event}', [DashboardController::class, 'updateEvent'])->name('event.update');
    Route::delete('/dashboard/event/{event}', [DashboardController::class, 'destroyEvent'])->name('event.destroy');
    
    // Fitur melihat daftar kehadiran & ekspor excel per event
    Route::get('/dashboard/event/{event_uuid}/presence', [DashboardController::class, 'eventPresences'])->name('event.presences');
    Route::get('/dashboard/event/{event_uuid}/presence/excel', [DashboardController::class, 'exportPresenceExcel'])->name('event.presences.excel');
});

// Akses publik untuk melihat foto & TTD hasil presensi (agar bisa diakses langsung via link Excel)
Route::get('/presence/{id}/photo', [DashboardController::class, 'showPresencePhoto'])->name('presence.photo');
Route::get('/presence/{id}/signature', [DashboardController::class, 'showPresenceSignature'])->name('presence.signature');

// Area Proteksi Ketat: HANYA Super Admin yang Bisa Masuk (Selain Admin Ditolak)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Halaman Dashboard Utama Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard.admin');
    Route::get('/admin/users', [DashboardController::class, 'adminUsers'])->name('admin.users');
    Route::get('/admin/logs', [DashboardController::class, 'adminLogs'])->name('admin.logs');
    // Fitur CRUD & Akses Manajemen Admin
    Route::post('/admin/event/create', [DashboardController::class, 'storeEvent'])->name('admin.event.store');
    Route::post('/admin/users/create', [DashboardController::class, 'storeUserByAdmin'])->name('admin.users.store');
    Route::post('/admin/users/approve/{id}', [DashboardController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/admin/users/reject/{id}', [DashboardController::class, 'rejectUser'])->name('admin.users.reject');
    Route::put('/admin/users/update/{id}', [DashboardController::class, 'updateUserByAdmin'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [DashboardController::class, 'destroyUserByAdmin'])->name('admin.users.delete');
    Route::post('/admin/users/block/{id}', [DashboardController::class, 'blockUser'])->name('admin.users.block');
    Route::post('/admin/users/unblock/{id}', [DashboardController::class, 'unblockUser'])->name('admin.users.unblock');
    Route::post('/admin/users/restore/{id}', [DashboardController::class, 'restoreUserByAdmin'])->name('admin.users.restore');
});
