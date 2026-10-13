<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'nama_klinik' => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:30',
            'provinsi'    => 'nullable|string|max:10',
            'kota'        => 'nullable|string|max:10',
            'device_name' => 'nullable|string|max:100',
        ];
    }

    /**
     * Custom message for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'name.max'           => 'Nama lengkap maksimal 255 karakter.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Alamat email sudah terdaftar dalam sistem.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
