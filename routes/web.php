<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PresenceFormController;
use App\Http\Controllers\AuthController; // Pastikan AuthController ini sudah dibuat sesuai panduan canvas

// --- 1. AUTHENTICATION (LOGIN, REGISTER, LOGOUT) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- 2. PERMANENT EVENT-BASED DIRECT LINKS (TAMU) ---
// Rute presensi dikelompokkan berdasarkan ID event agar privat tanpa dropdown
Route::prefix('presensi/{id}')->group(function () {
    Route::get('/', [PresenceFormController::class, 'accessForm'])->name('presensi.access');
    
    // Gerbang password jika event bersifat privat
    Route::get('/gate', [PresenceFormController::class, 'showGate'])->name('presensi.gate');
    Route::post('/gate', [PresenceFormController::class, 'verifyGate'])->name('presensi.gate.verify');
    
    Route::post('/submit', [PresenceFormController::class, 'submitPresence'])->name('presensi.submit');
    Route::get('/success', [PresenceFormController::class, 'showSuccess'])->name('presensi.success');
});

// Simulasi API Data Pegawai Berdasarkan NIP (Autofill fitur Custom)
Route::get('/api/pegawai/{nip}', [PresenceFormController::class, 'getPegawaiByNip'])->name('api.pegawai');

// --- 3. DASHBOARDS WITH MIDDLEWARE (CREATOR & ADMIN) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard untuk Creator Event (Role: User biasa)
    Route::get('/dashboard', [AdminDashboardController::class, 'userDashboard'])->name('user.dashboard');
    Route::post('/event/create', [AdminDashboardController::class, 'storeEvent'])->name('event.store');

    // Dashboard Khusus Super Admin (Sistem Manajemen & Admin Utama)
    Route::middleware(['role:super_admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // CRUD User & Sistem Blacklist
        Route::post('/users/create', [AdminDashboardController::class, 'createUser'])->name('admin.users.create');
        Route::delete('/users/{id}/delete-blacklist', [AdminDashboardController::class, 'deleteAndBlacklistUser'])->name('admin.users.delete');
        
        // Manajemen Blacklist Manual
        Route::post('/blacklist/manual', [AdminDashboardController::class, 'addManualBlacklist'])->name('admin.blacklist.add');
        Route::delete('/blacklist/{id}/remove', [AdminDashboardController::class, 'removeBlacklist'])->name('admin.blacklist.remove');
        
        // Fitur Tambahan Rekap/Log Lama yang Masih Terpakasi
        Route::post('/event/{id}/toggle-status', [AdminDashboardController::class, 'toggleEventStatus'])->name('admin.event.toggle');
        Route::get('/event/{id}/export', [AdminDashboardController::class, 'exportExcel'])->name('admin.event.export');
        Route::delete('/presensi/{id}/hapus', [AdminDashboardController::class, 'destroyPresence'])->name('admin.presensi.destroy');
    });
});