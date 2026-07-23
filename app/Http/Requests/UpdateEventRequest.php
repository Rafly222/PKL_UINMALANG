<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'date' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i',
            'access_type' => 'required|in:publik,privat',
            'password' => 'nullable|string|min:4',
            'audience_type' => 'required|in:umum,pegawai,semua',
            'custom_labels' => 'nullable|array',
            'custom_types' => 'nullable|array',
        ];
    }
}
