<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SubmitPresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $event = $this->route('event_uuid') ? \App\Models\Event::where('uuid', $this->route('event_uuid'))->first() : null;
        $fields = $event ? ($event->fields ?? []) : [];

        $rules = [
            'name' => 'required|string',
            'tipe_peserta' => 'required|in:pegawai,umum',
            'phone' => in_array('sc-phone', $fields) ? 'required|string|max:30' : 'nullable|string|max:30',
            'email' => in_array('sc-email', $fields) ? 'required|email|max:255' : 'nullable|email|max:255',
            'institution' => in_array('sc-institution', $fields) ? 'required|string|max:255' : 'nullable|string|max:255',
            'photo' => in_array('sc-photo', $fields) ? 'required|string' : 'nullable|string',
            'signature' => in_array('sc-signature', $fields) ? 'required|string' : 'nullable|string',
        ];

        if ($this->input('tipe_peserta') === 'pegawai') {
            $rules['nip'] = 'required|size:18';
        } else {
            $rules['nip'] = 'nullable|size:18';
        }

        if ($event && $event->custom_fields) {
            foreach ($event->custom_fields as $cf) {
                $slug = Str::slug($cf['label'], '_');
                $isKhususPegawai = (stripos($cf['label'], 'khusus pegawai') !== false);
                $isKhususTamu = (stripos($cf['label'], 'khusus tamu') !== false || stripos($cf['label'], 'khusus masyarakat') !== false || stripos($cf['label'], 'khusus umum') !== false);
                
                if ($event->audience_type === 'semua') {
                    if ($isKhususPegawai && $this->input('tipe_peserta') === 'umum') {
                        $rules[$slug] = 'nullable';
                    } elseif ($isKhususTamu && $this->input('tipe_peserta') === 'pegawai') {
                        $rules[$slug] = 'nullable';
                    } else {
                        $rules[$slug] = 'required';
                    }
                } else {
                    $rules[$slug] = 'required';
                }
            }
        }

        return $rules;
    }
}
