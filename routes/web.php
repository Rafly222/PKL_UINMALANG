<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. HALAMAN BERANDA & PRESENSI UMUM
// ==========================================
Route::get('/', [PresenceController::class, 'index'])->name('home');

// Rute Presensi Publik
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
    Route::get('/dashboard', [DashboardController::class, 'userIndex'])->name('dashboard.user');
    Route::post('/dashboard/event/create', [EventController::class, 'store'])->name('event.store');
    Route::put('/dashboard/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/dashboard/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    
    Route::get('/dashboard/event/{event_uuid}/presence', [EventController::class, 'presences'])->name('event.presences');
    Route::get('/dashboard/event/{event_uuid}/presence/excel', [EventController::class, 'exportExcel'])->name('event.presences.excel');
});

// Akses publik untuk melihat foto & TTD hasil presensi
Route::get('/presence/{id}/photo', [MediaController::class, 'photo'])->name('presence.photo');
Route::get('/presence/{id}/signature', [MediaController::class, 'signature'])->name('presence.signature');

// Area Proteksi Ketat: HANYA Super Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard.admin');
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/admin/logs', [AdminLogController::class, 'index'])->name('admin.logs');

    Route::post('/admin/event/create', [EventController::class, 'store'])->name('admin.event.store');
    Route::post('/admin/users/create', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::post('/admin/users/approve/{id}', [AdminUserController::class, 'approve'])->name('admin.users.approve');
    Route::post('/admin/users/reject/{id}', [AdminUserController::class, 'reject'])->name('admin.users.reject');
    Route::put('/admin/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.delete');
    Route::post('/admin/users/block/{id}', [AdminUserController::class, 'block'])->name('admin.users.block');
    Route::post('/admin/users/unblock/{id}', [AdminUserController::class, 'unblock'])->name('admin.users.unblock');
    Route::post('/admin/users/restore/{id}', [AdminUserController::class, 'restore'])->name('admin.users.restore');
});
