<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Presence;
use App\Models\ActivityLog;
use App\Services\EmployeeService;
use App\Http\Requests\SubmitPresenceRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresenceController extends Controller
{
    public function index()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $time = $now->toTimeString();

        $events = Event::where(function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    $q->whereNull('date_end')
                      ->where('date', $today);
                })->orWhere(function ($q) use ($today) {
                    $q->whereNotNull('date_end')
                      ->where('date', '<=', $today)
                      ->where('date_end', '>=', $today);
                });
            })
            ->where('time_start', '<=', $time)
            ->where('time_end', '>=', $time)
            ->latest()
            ->get();

        return view('welcome', compact('events'));
    }

    public function showForm($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();
        $isBypassed = $this->isUserBypassed($event);

        if (!$isBypassed) {
            if (!$this->isEventTimeValid($event)) {
                return view('presence.gate', [
                    'event' => $event,
                    'error' => 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!'
                ]);
            }

            if ($event->access_type === 'privat' && !session("event_gate_passed_{$event->id}")) {
                return redirect()->route('presence.gate', $event->uuid);
            }
        }

        return view('presence.form', compact('event', 'isBypassed'));
    }

    public function showGate($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();
        $isBypassed = $this->isUserBypassed($event);

        if (!$isBypassed) {
            if (!$this->isEventTimeValid($event)) {
                return view('presence.gate', [
                    'event' => $event,
                    'error' => 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!'
                ]);
            }
        }

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
        $attemptsKey = 'gate_pass_attempts|' . $event->id . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->with('warning', "Kata sandi yang Anda masukkan salah. Akses Anda dibekukan sementara. Silakan coba lagi dalam {$minutes} menit.")
                         ->with('lockout_seconds', $seconds);
        }

        $isBypassed = $this->isUserBypassed($event);

        if (!$isBypassed) {
            if (!$this->isEventTimeValid($event)) {
                return view('presence.gate', [
                    'event' => $event,
                    'error' => 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!'
                ]);
            }
        }

        $isMatch = false;
        try {
            $decrypted = decrypt($event->password);
            if ($request->password === $decrypted) {
                $isMatch = true;
            }
        } catch (\Exception $e) {
            if (Hash::check($request->password, $event->password) || $request->password === $event->password) {
                $isMatch = true;
            }
        }

        if ($isMatch) {
            RateLimiter::clear($throttleKey);
            Cache::forget($attemptsKey);
            session(["event_gate_passed_{$event->id}" => true]);
            return redirect()->route('presence.form', $event->uuid)->with('success', 'Akses diberikan!');
        }

        $attempts = Cache::get($attemptsKey, 0) + 1;
        Cache::put($attemptsKey, $attempts, now()->addMinutes(60));

        if ($attempts < 3) {
            $remaining = 3 - $attempts;
            return back()->with('warning', "Kata sandi yang Anda masukkan salah. Sisa percobaan Anda: {$remaining} kali.");
        }

        $decaySeconds = match ($attempts) {
            3 => 60,
            4 => 180,
            default => 300,
        };

        RateLimiter::hit($throttleKey, $decaySeconds);

        $seconds = RateLimiter::availableIn($throttleKey);
        $minutes = ceil($seconds / 60);

        return back()->with('warning', "Kata sandi yang Anda masukkan salah. Akses dibekukan sementara selama {$minutes} menit.")
                     ->with('lockout_seconds', $seconds);
    }

    public function submitForm(SubmitPresenceRequest $request, $event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

        $isBypassed = $this->isUserBypassed($event);

        if (!$isBypassed) {
            $now = Carbon::now('Asia/Jakarta');
            $start = Carbon::parse($event->date . ' ' . $event->time_start, 'Asia/Jakarta');
            $endDate = $event->date_end ?? $event->date;
            $end = Carbon::parse($endDate . ' ' . $event->time_end, 'Asia/Jakarta');

            if ($now->lt($start) || $now->gt($end)) {
                abort(403, 'Akses Ditolak: Kegiatan belum dimulai atau sudah berakhir!');
            }

            if ($event->access_type === 'privat' && !session("event_gate_passed_{$event->id}")) {
                abort(403, 'Akses Ditolak: Anda belum memasukkan kata sandi event!');
            }
        }

        $tipePeserta = $request->input('tipe_peserta', $event->audience_type === 'pegawai' ? 'pegawai' : 'umum');

        $isAlreadyPresence = false;
        if ($tipePeserta === 'pegawai' && $request->filled('nip')) {
            $isAlreadyPresence = Presence::where('event_id', $event->id)
                ->where('nip', $request->nip)
                ->exists();
        } else {
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

        $institution = EmployeeService::resolveInstitution($tipePeserta, $request->nip, $request->institution);

        $data_presensi = [];
        if ($event->custom_fields) {
            foreach ($event->custom_fields as $cf) {
                $slug = Str::slug($cf['label'], '_');
                if ($request->filled($slug)) {
                    $data_presensi[$cf['label']] = $request->input($slug);
                }
            }
        }

        $data_presensi['WhatsApp'] = $request->phone;
        $data_presensi['Jenis Kelamin'] = $request->gender;
        $data_presensi['Email'] = $request->email;

        $photoPath = $this->saveBase64Image($request->photo, 'presences/photos', 'jpeg');
        $signaturePath = $this->saveBase64Image($request->signature, 'presences/signatures', 'png');

        $presence = Presence::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'institution' => $institution,
            'phone' => $request->phone,
            'nip' => $tipePeserta === 'pegawai' ? $request->nip : null,
            'photo' => $photoPath ?? $request->photo,
            'signature' => $signaturePath ?? $request->signature,
            'data_presensi' => $data_presensi
        ]);

        $identityLog = $presence->nip ? "NIP: {$presence->nip}" : "Umum";
        ActivityLog::log('submit_presence', "Tamu '{$presence->name}' ({$identityLog}) berhasil mengisi presensi untuk kegiatan '{$event->name}'.");

        // Berikan tanda bahwa sesi browser ini yang berhak melihat halaman sukses
        session()->put("just_submitted_presence_{$presence->uuid}", true);

        return redirect()->route('presence.success', $presence->uuid);
    }

    public function showSuccess($presence_uuid)
    {
        // Verifikasi hak kepemilikan sesi
        if (!session()->has("just_submitted_presence_{$presence_uuid}")) {
            abort(403, 'Akses ditolak: Anda tidak berwenang melihat kartu kehadiran ini.');
        }

        $presence = Presence::with('event')->where('uuid', $presence_uuid)->firstOrFail();
        
        // Izinkan sesi browser ini mengakses berkas foto & TTD milik presensi ini
        session()->put("allowed_presence_media_{$presence->id}", true);
        
        return view('presence.success', compact('presence'));
    }

    public function mockEmployeeApi($nip)
    {
        $employee = EmployeeService::findByNip($nip);

        if ($employee) {
            return response()->json(["success" => true, "data" => $employee]);
        }

        return response()->json(["success" => false, "message" => "Pegawai tidak ditemukan"]);
    }

    private function isUserBypassed(Event $event): bool
    {
        return Auth::check() && (Auth::id() === $event->user_id || Auth::user()->role === 'admin');
    }

    private function isEventTimeValid(Event $event): bool
    {
        $now = Carbon::now('Asia/Jakarta');
        $start = Carbon::parse($event->date . ' ' . $event->time_start, 'Asia/Jakarta');
        $endDate = $event->date_end ?? $event->date;
        $end = Carbon::parse($endDate . ' ' . $event->time_end, 'Asia/Jakarta');

        return $now->gte($start) && $now->lte($end);
    }

    private function saveBase64Image(?string $base64Data, string $folder, string $defaultExt): ?string
    {
        if (empty($base64Data)) return null;

        $ext = $defaultExt;
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $ext = strtolower($type[1]);
        }

        $decoded = base64_decode($base64Data);
        if ($decoded !== false) {
            $filename = (string) Str::uuid() . '.' . $ext;
            $path = $folder . '/' . $filename;
            Storage::put($path, $decoded);
            return $path;
        }

        return null;
    }
}
