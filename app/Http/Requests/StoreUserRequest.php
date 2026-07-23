<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
            'nip' => 'nullable|size:18|unique:users,nip'
        ];
    }

    public function messages(): array
    {
        return [
            'nip.unique' => 'NIP tersebut sudah terdaftar pada akun pengguna lain.',
            'nip.size' => 'NIP harus berisi tepat 18 digit.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar.'
        ];
    }
}
