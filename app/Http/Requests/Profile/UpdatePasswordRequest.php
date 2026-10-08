<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'password_lama' => __('kata sandi saat ini'),
            'password_baru' => __('kata sandi baru'),
            'password_baru_confirmation' => __('konfirmasi kata sandi baru'),
        ];
    }

    public function messages(): array
    {
        return [
            'password_lama.required' => __('Kata sandi saat ini wajib diisi.'),
            'password_baru.required' => __('Kata sandi baru wajib diisi.'),
            'password_baru.min' => __('Kata sandi baru minimal 8 karakter.'),
            'password_baru.regex' => __('Kata sandi baru harus mengandung minimal 1 huruf kapital dan 1 angka.'),
            'password_baru.confirmed' => __('Konfirmasi kata sandi baru tidak cocok.'),
        ];
    }
}