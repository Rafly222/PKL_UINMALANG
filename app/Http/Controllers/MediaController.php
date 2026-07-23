<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Tampilkan Foto Wajah Peserta Secara Aman.
     */
    public function photo($id)
    {
        $presence = Presence::findOrFail($id);

        // Otorisasi Hak Akses
        $this->authorizeAccess($presence);

        if (!$presence->photo) {
            abort(404);
        }

        if (str_starts_with($presence->photo, 'data:image') || !str_contains($presence->photo, '/')) {
            $data = explode(',', $presence->photo);
            $image = base64_decode($data[1] ?? $data[0]);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        if (Storage::exists($presence->photo)) {
            $image = Storage::get($presence->photo);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        $fallbackPath = storage_path('app/' . $presence->photo);
        if (file_exists($fallbackPath)) {
            $image = file_get_contents($fallbackPath);
            return response($image)->header('Content-Type', 'image/jpeg');
        }

        abort(404);
    }

    /**
     * Tampilkan Tanda Tangan Peserta Secara Aman.
     */
    public function signature($id)
    {
        $presence = Presence::findOrFail($id);

        // Otorisasi Hak Akses
        $this->authorizeAccess($presence);

        if (!$presence->signature) {
            abort(404);
        }

        if (str_starts_with($presence->signature, 'data:image') || !str_contains($presence->signature, '/')) {
            $data = explode(',', $presence->signature);
            $image = base64_decode($data[1] ?? $data[0]);
            return response($image)->header('Content-Type', 'image/png');
        }

        if (Storage::exists($presence->signature)) {
            $image = Storage::get($presence->signature);
            return response($image)->header('Content-Type', 'image/png');
        }

        $fallbackPath = storage_path('app/' . $presence->signature);
        if (file_exists($fallbackPath)) {
            $image = file_get_contents($fallbackPath);
            return response($image)->header('Content-Type', 'image/png');
        }

        abort(404);
    }

    /**
     * Metode Pembatasan Otorisasi Akses Media
     */
    private function authorizeAccess(Presence $presence)
    {
        // Pastikan pengguna telah terautentikasi (login)
        if (!Auth::check()) {
            abort(401, 'Silakan login terlebih dahulu untuk mengakses media ini.');
        }

        $user = Auth::user();

        // 1. Super Admin (role: admin) selalu diizinkan
        if ($user->role === 'admin') {
            return;
        }

        // 2. Pembuat Event diizinkan melihat semua absensi di event miliknya
        if ($user->id === $presence->event->user_id) {
            return;
        }

        // 3. Pemilik Absensi sendiri (staf yang bersangkutan) diizinkan melihat filenya sendiri
        if (!empty($presence->nip) && $user->nip === $presence->nip) {
            return;
        }

        // Jika tidak memenuhi semua kriteria di atas, lempar error 403 Forbidden
        abort(403, 'Akses ditolak: Anda tidak berwenang mengakses berkas media ini.');
    }
}
