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
        // Hanya tampilkan event yang berstatus aktif agar tidak salah absen
        $events = Event::where('status', 'aktif')->orderBy('tanggal_event', 'desc')->get();
        
        // Cek jika ada parameter input event_id yang dipilih di awal
        $selectedEventId = $request->input('event_id', $events->first()?->id);
        $selectedEvent = Event::find($selectedEventId);

        return view('visitor.form', compact('events', 'selectedEvent'));
    }

    /**
     * Menyimpan data presensi pengunjung lengkap dengan foto capture & tanda tangan digital
     */
    public function storePresence(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'kategori_peserta' => 'required|in:pegawai,tamu',
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_wa' => 'required|string|max:15',
            'foto_capture' => 'required|string', // String data URI base64 gambar
            'tanda_tangan' => 'required|string', // String data URI base64 tanda tangan
        ]);

        // Opsional: Validasi NIP jika kategori pegawai dipilih
        if ($request->kategori_peserta === 'pegawai' && !$request->filled('nip')) {
            return redirect()->back()->withErrors(['nip' => 'Kolom NIP wajib diisi untuk kategori pegawai.'])->withInput();
        }

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
     * Simulasi API Internal Diskominfo untuk mengambil data pegawai berdasarkan NIP.
     * Fitur autofill otomatis (Opsi Custom) ketika NIP diinputkan.
     */
    public function getPegawaiByNip($nip)
    {
        // Kumpulan data mock pegawai Diskominfo Malang untuk simulasi presentasi PKL
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
            return response()->json([
                'success' => true,
                'data' => $databasePegawai[$nip]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'NIP Pegawai tidak ditemukan pada database Diskominfo.'
        ], 404);
    }
}