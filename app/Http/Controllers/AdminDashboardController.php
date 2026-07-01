<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Presence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin beserta rekapitulasi, filter, dan statistik visual.
     */
    public function index(Request $request)
    {
        // Mengambil semua event untuk filter dropdown dan form pembuat event
        $events = Event::orderBy('tanggal_event', 'desc')->get();

        // Tentukan event ID yang sedang dipilih (default ke event terbaru jika belum ada filter)
        $selectedEventId = $request->input('event_id', $events->first()?->id);

        // Ambil data detail event terpilih
        $selectedEvent = Event::find($selectedEventId);

        // Query dasar untuk mengambil kehadiran pada event terpilih
        $query = Presence::where('event_id', $selectedEventId);

        // Fitur Pencarian Dinamis (Nama, NIP, Instansi, atau Alamat)
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
        $totalPegawai = Presence::where('event_id', $selectedEventId)->where('kategori_peserta', 'pegawai')->count();
        $totalTamu = Presence::where('event_id', $selectedEventId)->where('kategori_peserta', 'tamu')->count();

        // Menghitung persentase pencapaian target kehadiran peserta
        $target = $selectedEvent?->target_peserta ?? 100;
        $rasioKehadiran = ($target > 0) ? round(($totalHadir / $target) * 100, 1) : 0;

        // Simulasi kalkulasi tren kedatangan berdasarkan jam absensi untuk data Chart.js
        $hourlyTrend = [
            '07:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 7')->count(),
            '08:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 8')->count(),
            '09:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 9')->count(),
            '10:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) = 10')->count(),
            '11:00' => Presence::where('event_id', $selectedEventId)->whereRaw('HOUR(waktu_absensi) >= 11')->count(),
        ];

        return view('admin.dashboard', compact(
            'events',
            'selectedEventId',
            'selectedEvent',
            'presences',
            'totalHadir',
            'totalPegawai',
            'totalTamu',
            'rasioKehadiran',
            'hourlyTrend'
        ));
    }

    /**
     * Membuat Event Acara Baru (Fitur: Create Event)
     */
    public function storeEvent(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_event' => 'required|date',
            'target_peserta' => 'required|integer|min:1',
        ]);

        Event::create([
            'nama_event' => $request->nama_event,
            'tanggal_event' => $request->tanggal_event,
            'target_peserta' => $request->target_peserta,
            'status' => 'aktif', // default langsung aktif saat dibuat
        ]);

        return redirect()->back()->with('success', 'Event baru berhasil dibuat dan siap digunakan!');
    }

    /**
     * Mengubah Status Aktif/Nonaktif Event
     */
    public function toggleEventStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->status = $event->status === 'aktif' ? 'nonaktif' : 'aktif';
        $event->save();

        return redirect()->back()->with('success', 'Status keaktifan event berhasil diperbarui.');
    }

    /**
     * Menghapus Data Absensi (Bagian CRUD Rekapan)
     */
    public function destroyPresence($id)
    {
        $presence = Presence::findOrFail($id);
        $presence->delete();

        return redirect()->back()->with('success', 'Data presensi peserta berhasil dihapus dari database.');
    }
}