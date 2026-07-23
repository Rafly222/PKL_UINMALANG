<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|min:6',
            'role' => 'required|in:user,admin',
            'nip' => 'nullable|size:18|unique:users,nip,' . $userId
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
