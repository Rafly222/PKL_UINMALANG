<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Presence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceFormController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran kehadiran pengunjung
     */
    public function showForm(Request $request)
    {
        $events = Event::where('status', 'aktif')->orderBy('tanggal_event', 'desc')->get();
        $selectedEventId = $request->input('event_id', $events->first()?->id);
        $selectedEvent = Event::find($selectedEventId);

        return view('visitor.form', compact('events', 'selectedEvent'));
    }

    /**
     * Menyimpan data presensi dengan proteksi duplikat (Solusi 3)
     */
    public function storePresence(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'kategori_peserta' => 'required|in:pegawai,tamu',
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_wa' => 'required|string|max:15',
            'foto_capture' => 'required|string',
            'tanda_tangan' => 'required|string',
        ]);

        if ($request->kategori_peserta === 'pegawai' && !$request->filled('nip')) {
            return redirect()->back()->withErrors(['nip' => 'Kolom NIP wajib diisi untuk kategori pegawai.'])->withInput();
        }

        // 2. Proteksi Backend: Cek Duplikasi (Solusi 3)
        // Kita cek apakah data sudah masuk hari ini untuk event tersebut
        $isDuplicate = Presence::where('event_id', $request->event_id)
            ->whereDate('waktu_absensi', Carbon::today())
            ->where(function ($query) use ($request) {
                if ($request->kategori_peserta === 'pegawai') {
                    // Jika pegawai, cek berdasarkan NIP
                    $query->where('nip', $request->nip);
                } else {
                    // Jika tamu, cek berdasarkan Nama dan Instansi
                    $query->where('nama_lengkap', $request->nama_lengkap)
                          ->where('instansi', $request->instansi);
                }
            })
            ->exists();

        if ($isDuplicate) {
            return redirect()->back()->withErrors(['error' => 'Data Anda sudah tercatat sebagai peserta hari ini.'])->withInput();
        }

        // 3. Simpan Data jika tidak ada duplikat
        Presence::create([
            'event_id' => $request->event_id,
            'kategori_peserta' => $request->kategori_peserta,
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->kategori_peserta === 'pegawai' ? $request->nip : null,
            'instansi' => $request->instansi,
            'no_wa' => $request->no_wa,
            'foto_capture' => $request->foto_capture,
            'tanda_tangan' => $request->tanda_tangan,
            'waktu_absensi' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('presensi.form', ['event_id' => $request->event_id])
                         ->with('success', 'Presensi Anda berhasil dicatat! Terima kasih.');
    }

    /**
     * Simulasi API Internal Diskominfo
     */
    public function getPegawaiByNip($nip)
    {
        $databasePegawai = [
            '198503152010121002' => [
                'nama' => 'Budi Santoso, S.Kom',
                'instansi' => 'Diskominfo Kota Malang - Bidang Aplikasi Informatika',
                'no_wa' => '081234567890'
            ],
            '199008242015032005' => [
                'nama' => 'Siti Aminah, M.T',
                'instansi' => 'Diskominfo Kota Malang - Bidang Persandian & Statistik',
                'no_wa' => '085799887766'
            ],
            '197812052005011001' => [
                'nama' => 'Ir. Hermawan Adi, M.M',
                'instansi' => 'Diskominfo Kota Malang - Sekretariat Dinas',
                'no_wa' => '082144332211'
            ],
            '200105122024021003' => [
                'nama' => 'Rafly Pratama (Pranata Komputer)',
                'instansi' => 'Diskominfo Kota Malang - Bidang Infrastruktur TIK',
                'no_wa' => '089876543210'
            ]
        ];

        if (array_key_exists($nip, $databasePegawai)) {
            return response()->json(['success' => true, 'data' => $databasePegawai[$nip]]);
        }

        return response()->json(['success' => false, 'message' => 'NIP Pegawai tidak ditemukan.'], 404);
    }
}
