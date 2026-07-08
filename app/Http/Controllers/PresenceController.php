<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Presence;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PresenceController extends Controller
{
    public function index()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $time = $now->toTimeString();

        $events = Event::where('date', $today)
            ->where('time_start', '<=', $time)
            ->where('time_end', '>=', $time)
            ->latest()
            ->get();

        return view('welcome', compact('events'));
    }

    public function showForm($event_id)
    {
        $event = Event::findOrFail($event_id);
        
        // Tahap 3: Aturan Bypass Pembuat (User) / Admin
        $isBypassed = false;
        if (Auth::check()) {
            if (Auth::id() === $event->user_id || Auth::user()->role === 'admin') {
                $isBypassed = true;
            }
        }

        if (!$isBypassed) {
            // Verifikasi batasan jam pelaksanaan
            $now = Carbon::now('Asia/Jakarta');
            $eventDate = Carbon::parse($event->date, 'Asia/Jakarta');
            $start = Carbon::parse($event->date . ' ' . $event->time_start, 'Asia/Jakarta');
            $end = Carbon::parse($event->date . ' ' . $event->time_end, 'Asia/Jakarta');

            if (!$now->isSameDay($eventDate) || $now->lt($start) || $now->gt($end)) {
                return view('presence.gate', [
                    'event' => $event,
                    'error' => 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!'
                ]);
            }

            // Jika event bertipe privat, minta password
            if ($event->access_type === 'privat' && !session("event_gate_passed_{$event->id}")) {
                return redirect()->route('presence.gate', $event->id);
            }
        }

        return view('presence.form', compact('event', 'isBypassed'));
    }

    public function showGate($event_id)
    {
        $event = Event::findOrFail($event_id);

        // Tahap 3: Aturan Bypass Pembuat (User) / Admin
        $isBypassed = false;
        if (Auth::check()) {
            if (Auth::id() === $event->user_id || Auth::user()->role === 'admin') {
                $isBypassed = true;
            }
        }

        if (!$isBypassed) {
            // Verifikasi batasan jam pelaksanaan
            $now = Carbon::now('Asia/Jakarta');
            $eventDate = Carbon::parse($event->date, 'Asia/Jakarta');
            $start = Carbon::parse($event->date . ' ' . $event->time_start, 'Asia/Jakarta');
            $end = Carbon::parse($event->date . ' ' . $event->time_end, 'Asia/Jakarta');

            if (!$now->isSameDay($eventDate) || $now->lt($start) || $now->gt($end)) {
                return view('presence.gate', [
                    'event' => $event,
                    'error' => 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!'
                ]);
            }
        }

        // Jika user dibypass atau sudah memasukkan password, langsung ke form
        if ($isBypassed || session("event_gate_passed_{$event->id}")) {
            return redirect()->route('presence.form', $event->id);
        }

        return view('presence.gate', compact('event'));
    }

    public function checkGatePassword(Request $request, $event_id)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $event = Event::findOrFail($event_id);

        // Tahap 3: Aturan Bypass Pembuat (User) / Admin
        $isBypassed = false;
        if (Auth::check()) {
            if (Auth::id() === $event->user_id || Auth::user()->role === 'admin') {
                $isBypassed = true;
            }
        }

        if (!$isBypassed) {
            // Verifikasi batasan jam pelaksanaan sebelum memproses password
            $now = Carbon::now('Asia/Jakarta');
            $eventDate = Carbon::parse($event->date, 'Asia/Jakarta');
            $start = Carbon::parse($event->date . ' ' . $event->time_start, 'Asia/Jakarta');
            $end = Carbon::parse($event->date . ' ' . $event->time_end, 'Asia/Jakarta');

            if (!$now->isSameDay($eventDate) || $now->lt($start) || $now->gt($end)) {
                return view('presence.gate', [
                    'event' => $event,
                    'error' => 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!'
                ]);
            }
        }

        if (Hash::check($request->password, $event->password) || $request->password === $event->password) {
            session(["event_gate_passed_{$event->id}" => true]);
            return redirect()->route('presence.form', $event->id)->with('success', 'Akses diberikan!');
        }

        return back()->with('warning', 'Kata Sandi yang Anda masukkan salah.');
    }

    public function submitForm(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        // Tahap 3: Aturan Bypass Pembuat (User) / Admin
        $isBypassed = false;
        if (Auth::check()) {
            if (Auth::id() === $event->user_id || Auth::user()->role === 'admin') {
                $isBypassed = true;
            }
        }

        if (!$isBypassed) {
            // Verifikasi batasan jam pelaksanaan sebelum memproses submit
            $now = Carbon::now('Asia/Jakarta');
            $eventDate = Carbon::parse($event->date, 'Asia/Jakarta');
            $start = Carbon::parse($event->date . ' ' . $event->time_start, 'Asia/Jakarta');
            $end = Carbon::parse($event->date . ' ' . $event->time_end, 'Asia/Jakarta');

            if (!$now->isSameDay($eventDate) || $now->lt($start) || $now->gt($end)) {
                abort(403, 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!');
            }

            // Jika event bertipe privat, pastikan sudah melewati gate password
            if ($event->access_type === 'privat' && !session("event_gate_passed_{$event->id}")) {
                abort(403, 'Akses Ditolak: Anda belum memasukkan kata sandi event!');
            }
        }

        // Paksa tipe_peserta ke pegawai jika event khusus pegawai
        if ($event->audience_type === 'pegawai') {
            $request->merge(['tipe_peserta' => 'pegawai']);
        } else {
            if (!$request->has('tipe_peserta')) {
                $request->merge(['tipe_peserta' => 'umum']);
            }
        }

        $fields = $event->fields ?? [];

        $rules = [
            'name' => 'required|string',
            'nik' => 'required|size:16',
            'tipe_peserta' => 'required|in:pegawai,umum',
            'phone' => in_array('sc-phone', $fields) ? 'required|string|max:30' : 'nullable|string|max:30',
            'institution' => in_array('sc-institution', $fields) ? 'required|string|max:255' : 'nullable|string|max:255',
            'photo' => in_array('sc-photo', $fields) ? 'required|string' : 'nullable|string',
            'signature' => in_array('sc-signature', $fields) ? 'required|string' : 'nullable|string',
        ];

        if ($request->tipe_peserta === 'pegawai') {
            $rules['nip'] = 'required|size:18';
        } else {
            $rules['nip'] = 'nullable|size:18';
        }

        if ($event->custom_fields) {
            foreach ($event->custom_fields as $cf) {
                $rules[Str::slug($cf['label'], '_')] = 'required';
            }
        }

        $request->validate($rules);

        // Cari data instansi untuk pegawai / umum
        $institution = $request->institution;
        if ($request->tipe_peserta === 'pegawai') {
            $employeeDb = [
                "198503152010121002" => ["name" => "Siti Aminah, M.T", "institution" => "Dinas Komunikasi dan Informatika"],
                "199508242018031005" => ["name" => "Rafly Pratama", "institution" => "Diskominfo - Infrastruktur TIK"],
                "197812052005011001" => ["name" => "Ir. Hermawan Adi, M.M", "institution" => "Pemerintah Kota Malang - Sekdin"]
            ];
            if ($request->has('nip') && array_key_exists($request->nip, $employeeDb)) {
                $institution = $institution ?? $employeeDb[$request->nip]['institution'];
            }
            $institution = $institution ?? 'Pemerintah Kota Malang';
        } else {
            $institution = $institution ?? 'Masyarakat Umum';
        }

        // Simpan custom inputs ke dalam array JSON
        $data_presensi = [];
        if ($event->custom_fields) {
            foreach ($event->custom_fields as $cf) {
                $slug = Str::slug($cf['label'], '_');
                if ($request->has($slug)) {
                    $data_presensi[$cf['label']] = $request->input($slug);
                }
            }
        }

        // Simpan inputan bawaan lainnya
        $data_presensi['WhatsApp'] = $request->phone;
        $data_presensi['Jenis Kelamin'] = $request->gender;
        $data_presensi['Alamat'] = $request->address;

        $presence = Presence::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'nik' => $request->nik,
            'institution' => $institution,
            'phone' => $request->phone,
            'nip' => $request->tipe_peserta === 'pegawai' ? $request->nip : null,
            'photo' => $request->photo, // Data Base64 Image
            'signature' => $request->signature, // Data Base64 PNG
            'data_presensi' => $data_presensi
        ]);

        // Catat Log Aktivitas
        \App\Models\ActivityLog::log('submit_presence', "Tamu '{$presence->name}' (NIK: {$presence->nik}) berhasil mengisi presensi untuk kegiatan '{$event->name}'.");

        return redirect()->route('presence.success', $presence->id);
    }

    public function showSuccess($presence_id)
    {
        $presence = Presence::with('event')->findOrFail($presence_id);
        return view('presence.success', compact('presence'));
    }

    // Mock API Pegawai Pemerintah Kota Malang
    public function mockEmployeeApi($nip)
    {
        $database = [
            "198503152010121002" => ["name" => "Siti Aminah, M.T", "institution" => "Dinas Komunikasi dan Informatika", "phone" => "081255566778"],
            "199508242018031005" => ["name" => "Rafly Pratama", "institution" => "Diskominfo - Infrastruktur TIK", "phone" => "081299998888"],
            "197812052005011001" => ["name" => "Ir. Hermawan Adi, M.M", "institution" => "Pemerintah Kota Malang - Sekdin", "phone" => "081344445555"]
        ];

        if (array_key_exists($nip, $database)) {
            return response()->json(["success" => true, "data" => $database[$nip]]);
        }

        return response()->json(["success" => false, "message" => "Pegawai tidak ditemukan"]);
    }
}
