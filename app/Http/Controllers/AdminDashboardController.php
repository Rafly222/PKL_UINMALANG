<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Presence;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    // 👥 1. Dashboard untuk Staff / Pegawai Biasa (Event Creator)
    public function userDashboard()
    {
        // Mengambil event yang HANYA dibuat oleh pegawai yang sedang login (Aturan Bypass)
        $events = Event::where('user_id', Auth::id())->latest()->get();
        
        return view('user.dashboard', compact('events'));
    }

    // 👑 2. Dashboard untuk Super Admin (Rekap Total Sistem + Filter Pencarian Dinamis)
    public function adminDashboard(Request $request)
    {
        // Statistik Ringkas Atas
        $totalPegawai = User::where('role', 'user')->count();
        $totalBlacklist = Blacklist::count();

        // Mengambil semua event untuk filter dropdown di dashboard rekap super admin
        $events = Event::latest()->get();

        // Tentukan event ID yang sedang dipilih (default ke event terbaru jika belum ada filter)
        $selectedEventId = $request->input('event_id', $events->first()?->id);

        // Ambil data detail event terpilih
        $selectedEvent = Event::find($selectedEventId);

        // Query dasar untuk mengambil kehadiran pada event terpilih
        $query = Presence::where('event_id', $selectedEventId);

        // Fitur Pencarian Dinamis (Nama, NIP, atau Instansi) peninggalan fitur lama
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        // Tampilkan dengan pagination (10 data per halaman) agar tabel tetap rapi
        $presences = $query->orderBy('waktu_absensi', 'desc')->paginate(10)->withQueryString();

        // Menghitung data agregasi statistik kehadiran per event secara dinamis
        $totalHadir = Presence::where('event_id', $selectedEventId)->count();
        $totalEventPegawai = Presence::where('event_id', $selectedEventId)->where('kategori_peserta', 'pegawai')->count();
        $totalTamu = Presence::where('event_id', $selectedEventId)->where('kategori_peserta', 'tamu')->count();

        // Menghitung persentase pencapaian target kehadiran peserta (Default target 100 jika kosong)
        $target = $selectedEvent?->target_peserta ?? 100;
        $rasioKehadiran = ($target > 0) ? round(($totalHadir / $target) * 100, 1) : 0;

        // Kalkulasi tren kedatangan berdasarkan jam absensi untuk data Chart.js
        $hourlyTrend = [
            '07:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 7')->count(),
            '08:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 8')->count(),
            '09:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 9')->count(),
            '10:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 10')->count(),
            '11:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) >= 11')->count(),
        ];

        $blacklists = Blacklist::latest()->get();

        return view('admin.dashboard', compact(
            'totalPegawai', 'totalBlacklist', 'events', 'selectedEventId', 'selectedEvent',
            'presences', 'totalHadir', 'totalEventPegawai', 'totalTamu', 'rasioKehadiran', 'hourlyTrend', 'blacklists'
        ));
    }

    // 🔒 3. Membuat Tambah Admin Manual via Admin
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'super_admin',
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Admin baru berhasil ditambahkan.');
    }

    // 📝 4. Membuat Event Acara Baru (Fitur Multi-role)
    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'target_audience' => 'required|in:umum,pegawai,semua',
            'access_type' => 'required|in:publik,privat',
            'password' => 'required_if:access_type,privat|nullable|string|min:4',
        ]);

        $formFields = $request->has('fields') ? $request->fields : [];

        Event::create([
            'user_id' => Auth::id(),
            'nama_event' => $request->name,
            'tanggal_event' => $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'target_audience' => $request->target_audience,
            'access_type' => $request->access_type,
            'password' => $request->access_type === 'privat' ? $request->password : null,
            'form_fields' => $formFields,
            'status' => 'aktif',
        ]);

        // Ganti bagian return paling bawah pada fungsi storeEvent menjadi ini:
    return redirect()->route('user.dashboard')->with('success', 'Event baru dengan formulir kustom dinamis berhasil dibuat!');

    }

    // 🔄 5. Mengubah Status Aktif/Nonaktif Event
    public function toggleEventStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->status = $event->status === 'aktif' ? 'nonaktif' : 'aktif';
        $event->save();

        return redirect()->back()->with('success', 'Status keaktifan event berhasil diperbarui.');
    }

    // 🗑️ 6. Menghapus Data Absensi (CRUD Rekapan)
    public function destroyPresence($id)
    {
        $presence = Presence::findOrFail($id);
        $presence->delete();

        return redirect()->back()->with('success', 'Data presensi peserta berhasil dihapus dari database.');
    }

    // 🛡️ 7. Fitur Deteksi Pelanggaran: Blokir Akun dan Masukkan Identitas ke Blacklist (Revisi)
    public function deleteAndBlacklistUser($id)
    {
        $user = User::findOrFail($id);
        
        Blacklist::create([
            'nik' => $user->nik,
            'nip' => $user->nip,
        ]);

        $user->delete();
        return redirect()->back()->with('success', 'Akun user berhasil dihapus dan identitasnya resmi diblokir dari registrasi!');
    }

    // 📊 8. Mengekspor Data Presensi Kehadiran per Event ke Format Excel (.xls) dengan Gambar Wajah & TTD
    public function exportExcel($id)
    {
        $event = Event::findOrFail($id);
        $presences = Presence::where('event_id', $id)->orderBy('waktu_absensi', 'asc')->get();

        $fileName = 'Rekap_Presensi_' . str_replace(' ', '_', $event->name) . '_' . Carbon::now()->format('Ymd_His') . '.xls';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $tempDir = public_path('exports_temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $convertBase64ToUrl = function($base64String, $prefix) use ($tempDir) {
            if (strpos($base64String, 'data:image') !== 0) {
                return $base64String;
            }

            @list($type, $file_data) = explode(';', $base64String);
            @list(, $file_data) = explode(',', $file_data);

            $extension = 'png';
            if (strpos($type, 'image/jpeg') !== false) { $extension = 'jpg'; }

            $imageName = $prefix . '_' . uniqid() . '.' . $extension;
            $filePath = $tempDir . '/' . $imageName;
            file_put_contents($filePath, base64_decode($file_data));

            return url('exports_temp/' . $imageName);
        };

        $callback = function() use ($presences, $event, $convertBase64ToUrl) {
            $file = fopen('php://output', 'w');
            
            fwrite($file, '
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <style>
                    table { border-collapse: collapse; table-layout: fixed; }
                    th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; vertical-align: middle; font-family: sans-serif; font-size: 11px; }
                    th { background-color: #1e3a8a; color: #ffffff; font-weight: bold; }
                    .img-cell { text-align: center; vertical-align: middle; }
                    .nip-wa { mso-number-format:"\@"; }
                </style>
            </head>
            <body>
                <h2 style="font-family: sans-serif; color: #1e3a8a; margin-bottom: 2px;">REKAPITULASI PRESENSI KEHADIRAN</h2>
                <h4 style="font-family: sans-serif; color: #475569; margin-top: 0; margin-bottom: 20px;">
                    Kegiatan: ' . htmlspecialchars($event->name) . ' (' . Carbon::parse($event->date)->translatedFormat('d F Y') . ')
                </h4>
                <table>
                    <thead>
                        <tr style="height: 30px;">
                            <th width="40">No</th>
                            <th width="100">Tanggal</th>
                            <th width="100">Jam Absen</th>
                            <th width="180">Nama Lengkap</th>
                            <th width="100">Kategori</th>
                            <th width="160">NIP (Pegawai)</th>
                            <th width="200">Instansi</th>
                            <th width="120">No WhatsApp</th>
                            <th width="140" style="text-align: center;">Tanda Tangan</th>
                            <th width="120" style="text-align: center;">Foto Wajah</th>
                        </tr>
                    </thead>
                    <tbody>
            ');

            foreach ($presences as $idx => $pres) {
                $tanggal = Carbon::parse($pres->waktu_absensi)->translatedFormat('d-m-Y');
                $jam = Carbon::parse($pres->waktu_absensi)->format('H:i:s');
                $kategori = ucfirst($pres->kategori_peserta);
                $nip = $pres->nip ? $pres->nip : '-';
                
                $ttdUrl = $convertBase64ToUrl($pres->tanda_tangan, 'ttd');
                $fotoUrl = $convertBase64ToUrl($pres->foto_capture, 'foto');

                fwrite($file, '
                        <tr style="height: 80px;">
                            <td>' . ($idx + 1) . '</td>
                            <td>' . htmlspecialchars($tanggal) . '</td>
                            <td>' . htmlspecialchars($jam) . '</td>
                            <td><b>' . htmlspecialchars($pres->nama_lengkap) . '</b></td>
                            <td>' . htmlspecialchars($kategori) . '</td>
                            <td class="nip-wa">' . htmlspecialchars($nip) . '</td>
                            <td>' . htmlspecialchars($pres->instansi) . '</td>
                            <td class="nip-wa">' . htmlspecialchars($pres->no_wa) . '</td>
                            <td class="img-cell"><img src="' . $ttdUrl . '" width="110" height="60"></td>
                            <td class="img-cell"><img src="' . $fotoUrl . '" width="60" height="60"></td>
                        </tr>
                ');
            }

            fwrite($file, '</tbody></table></body></html>');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}