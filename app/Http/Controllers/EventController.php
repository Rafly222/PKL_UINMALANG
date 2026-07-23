<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ActivityLog;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function store(StoreEventRequest $request)
    {
        $fields = $this->extractFields($request);
        $customFields = $this->extractCustomFields($request);

        $event = Event::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'date' => $request->date,
            'date_end' => $request->date_end ?? $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'access_type' => $request->access_type,
            'password' => $request->access_type === 'privat' ? encrypt($request->password) : null,
            'audience_type' => $request->audience_type,
            'fields' => $fields,
            'custom_fields' => $customFields
        ]);

        ActivityLog::log('create_event', "Pengguna mendaftarkan event baru: '{$event->name}' (Kategori: {$event->audience_type}, Akses: {$event->access_type}).");

        return back()->with('success', 'Event baru berhasil didaftarkan & siap digunakan!');
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->access_type === 'privat' && !$event->password && !$request->filled('password')) {
            return back()->withErrors(['password' => 'Password wajib diisi jika merubah akses menjadi privat.']);
        }

        $fields = $this->extractFields($request);
        $customFields = $this->extractCustomFields($request);

        $data = [
            'name' => $request->name,
            'date' => $request->date,
            'date_end' => $request->date_end ?? $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'access_type' => $request->access_type,
            'audience_type' => $request->audience_type,
            'fields' => $fields,
            'custom_fields' => $customFields
        ];

        if ($request->access_type === 'privat') {
            if ($request->filled('password') && $request->password !== '********') {
                $data['password'] = encrypt($request->password);
            }
        } else {
            $data['password'] = null;
        }

        $event->update($data);

        ActivityLog::log('update_event', "Pengguna memperbarui event: '{$event->name}' (ID: {$event->id}).");

        return back()->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        ActivityLog::log('delete_event', "Pengguna menghapus event: '{$event->name}' (ID: {$event->id}).");

        $event->delete();

        return back()->with('success', 'Event berhasil dihapus.');
    }

    public function presences($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $presences = $event->presences()->latest()->get();

        return view('dashboard.presences', compact('event', 'presences'));
    }

    public function exportExcel($event_uuid)
    {
        $event = Event::where('uuid', $event_uuid)->firstOrFail();

        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $presences = $event->presences()->latest()->get();

        $filename = "Rekap_Presensi_" . Str::slug($event->name, '_') . "_" . date('Y-m-d') . ".xls";

        return response()->stream(function () use ($event, $presences) {
            echo view('exports.presence_excel', compact('event', 'presences'))->render();
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function extractFields(Request $request): array
    {
        $fields = ['sc-name'];
        foreach (['sc-phone', 'sc-gender', 'sc-institution', 'sc-email', 'sc-photo', 'sc-signature'] as $field) {
            if ($request->boolean($field)) {
                $fields[] = $field;
            }
        }

        if ($request->has('sc-nip') || $request->audience_type === 'pegawai') {
            $fields[] = 'sc-nip';
        }

        return array_values(array_unique($fields));
    }

    private function extractCustomFields(Request $request): array
    {
        $customFields = [];
        if ($request->has('custom_labels')) {
            foreach ($request->custom_labels as $index => $label) {
                if (!empty($label)) {
                    $customFields[] = [
                        'label' => $label,
                        'type' => $request->custom_types[$index] ?? 'text'
                    ];
                }
            }
        }
        return $customFields;
    }
}
