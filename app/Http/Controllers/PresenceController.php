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

    public function showForm($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();
        
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
                return redirect()->route('presence.gate', $event->uuid);
            }
        }

        return view('presence.form', compact('event', 'isBypassed'));
    }

    public function showGate($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

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
            return redirect()->route('presence.form', $event->uuid);
        }

        return view('presence.gate', compact('event'));
    }

    public function checkGatePassword(Request $request, $event_uuid)
    {
        $request->validate([
            'password' => 'required|string',
            'g-recaptcha-response' => config('services.recaptcha.secret_key') ? ['required', new \App\Rules\Recaptcha] : ['nullable']
        ], [
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib diisi.'
        ]);

        $event = Event::where('uuid', $event_uuid)->firstOrFail();

        $throttleKey = 'gate_pass|' . $event->id . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->with('warning', "Terlalu banyak percobaan salah password. Akses Anda dibekukan sementara. Silakan coba lagi dalam {$minutes} menit.")
                         ->with('lockout_seconds', $seconds);
        }

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
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            session(["event_gate_passed_{$event->id}" => true]);
            return redirect()->route('presence.form', $event->uuid)->with('success', 'Akses diberikan!');
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 180); // Freeze for 3 minutes (180 seconds)

        // Jika setelah kegagalan ini sudah mencapai 3 kali salah, langsung aktifkan freeze
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->with('warning', "Terlalu banyak percobaan salah password. Akses Anda dibekukan sementara. Silakan coba lagi dalam {$minutes} menit.")
                         ->with('lockout_seconds', $seconds);
        }

        return back()->with('warning', 'Kata Sandi yang Anda masukkan salah.');
    }

    public function submitForm(Request $request, $event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

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
            'tipe_peserta' => 'required|in:pegawai,umum',
            'phone' => in_array('sc-phone', $fields) ? 'required|string|max:30' : 'nullable|string|max:30',
            'email' => in_array('sc-email', $fields) ? 'required|email|max:255' : 'nullable|email|max:255',
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
                $slug = Str::slug($cf['label'], '_');
                $isKhususPegawai = (stripos($cf['label'], 'khusus pegawai') !== false);
                $isKhususTamu = (stripos($cf['label'], 'khusus tamu') !== false || stripos($cf['label'], 'khusus masyarakat') !== false || stripos($cf['label'], 'khusus umum') !== false);
                
                if ($event->audience_type === 'semua') {
                    if ($isKhususPegawai && $request->input('tipe_peserta') === 'umum') {
                        $rules[$slug] = 'nullable';
                    } elseif ($isKhususTamu && $request->input('tipe_peserta') === 'pegawai') {
                        $rules[$slug] = 'nullable';
                    } else {
                        $rules[$slug] = 'required';
                    }
                } else {
                    $rules[$slug] = 'required';
                }
            }
        }

        $request->validate($rules);

        // Cek apakah NIP atau Nama & Phone sudah melakukan presensi untuk event ini
        $isAlreadyPresence = false;
        if ($request->tipe_peserta === 'pegawai' && $request->filled('nip')) {
            $isAlreadyPresence = Presence::where('event_id', $event->id)
                ->where('nip', $request->nip)
                ->exists();
        } else {
            // Tipe umum
            $query = Presence::where('event_id', $event->id)->where('name', $request->name);
            if ($request->filled('phone')) {
                $query->where('phone', $request->phone);
            }
            if ($request->filled('email')) {
                $query->where('data_presensi->Email', $request->email);
            }
            $isAlreadyPresence = $query->exists();
        }

        if ($isAlreadyPresence) {
            return back()->withInput()->with('warning', 'Anda sudah melakukan presensi pada event ini!');
        }

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
                if ($request->filled($slug)) {
                    $data_presensi[$cf['label']] = $request->input($slug);
                }
            }
        }

        // Simpan inputan bawaan lainnya
        $data_presensi['WhatsApp'] = $request->phone;
        $data_presensi['Jenis Kelamin'] = $request->gender;
        $data_presensi['Email'] = $request->email;

        // Simpan photo dan signature ke storage jika berupa data Base64
        $photoPath = null;
        if ($request->filled('photo')) {
            $photoData = $request->photo;
            if (preg_match('/^data:image\/(\w+);base64,/', $photoData, $type)) {
                $photoData = substr($photoData, strpos($photoData, ',') + 1);
                $ext = strtolower($type[1]);
            } else {
                $ext = 'jpeg';
            }
            $photoData = base64_decode($photoData);
            if ($photoData !== false) {
                $photoName = (string) \Illuminate\Support\Str::uuid() . '.' . $ext;
                $photoPath = 'presences/photos/' . $photoName;
                \Illuminate\Support\Facades\Storage::put($photoPath, $photoData);
            }
        }

        $signaturePath = null;
        if ($request->filled('signature')) {
            $sigData = $request->signature;
            if (preg_match('/^data:image\/(\w+);base64,/', $sigData, $type)) {
                $sigData = substr($sigData, strpos($sigData, ',') + 1);
                $ext = strtolower($type[1]);
            } else {
                $ext = 'png';
            }
            $sigData = base64_decode($sigData);
            if ($sigData !== false) {
                $sigName = (string) \Illuminate\Support\Str::uuid() . '.' . $ext;
                $signaturePath = 'presences/signatures/' . $sigName;
                \Illuminate\Support\Facades\Storage::put($signaturePath, $sigData);
            }
        }

        $presence = Presence::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'institution' => $institution,
            'phone' => $request->phone,
            'nip' => $request->tipe_peserta === 'pegawai' ? $request->nip : null,
            'photo' => $photoPath ?? $request->photo,
            'signature' => $signaturePath ?? $request->signature,
            'data_presensi' => $data_presensi
        ]);

        // Catat Log Aktivitas
        $identityLog = $presence->nip ? "NIP: {$presence->nip}" : "Umum";
        \App\Models\ActivityLog::log('submit_presence', "Tamu '{$presence->name}' ({$identityLog}) berhasil mengisi presensi untuk kegiatan '{$event->name}'.");

        return redirect()->route('presence.success', $presence->uuid);
    }

    public function showSuccess($presence_uuid)
    {
        $presence = Presence::with('event')->where('uuid', $presence_uuid)->firstOrFail();
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
