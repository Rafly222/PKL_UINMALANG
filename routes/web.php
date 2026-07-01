<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PresenceFormController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Presensi Digital Diskominfo Malang
|--------------------------------------------------------------------------
*/

// ==================== RUTE PENGUNJUNG (PRESENSI) ====================
// Menampilkan formulir presensi pengunjung
Route::get('/', [PresenceFormController::class, 'showForm'])->name('presensi.form');

// Menyimpan data presensi pengunjung ke database
Route::post('/presensi/kirim', [PresenceFormController::class, 'storePresence'])->name('presensi.store');

// Simulasi API Data Pegawai Berdasarkan NIP (Autofill fitur Custom)
Route::get('/api/pegawai/{nip}', [PresenceFormController::class, 'getPegawaiByNip'])->name('api.pegawai');


// ==================== RUTE ADMIN (REKAPITULASI) ====================
// Menampilkan dashboard utama admin rekapitulasi, pencarian, dan statistik
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

// Menyimpan event baru (Fitur Create Event di Dashboard)
Route::post('/admin/event/buat', [AdminDashboardController::class, 'storeEvent'])->name('admin.event.store');

// Mengubah status keaktifan event (Aktif/Nonaktif)
Route::post('/admin/event/{id}/toggle-status', [AdminDashboardController::class, 'toggleEventStatus'])->name('admin.event.toggle');

// Mengekspor data rekapitulasi presensi per-event ke Microsoft Excel
Route::get('/admin/event/{id}/export', [AdminDashboardController::class, 'exportExcel'])->name('admin.event.export');

// Menghapus data presensi (Bagian dari CRUD Rekapan)
Route::delete('/admin/presensi/{id}/hapus', [AdminDashboardController::class, 'destroyPresence'])->name('admin.presensi.destroy');