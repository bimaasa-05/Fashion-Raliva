<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = (int) ($this->user()?->getKey());

        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($userId, 'user_id')],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => __('Nama lengkap wajib diisi.'),
            'nama_lengkap.string' => __('Nama lengkap tidak valid.'),
            'nama_lengkap.max' => __('Nama lengkap maksimal 150 karakter.'),
            'email.required' => __('Email wajib diisi.'),
            'email.email' => __('Format email tidak valid.'),
            'email.max' => __('Email maksimal 150 karakter.'),
            'email.unique' => __('Email sudah digunakan.'),
            'nomor_telepon.string' => __('Nomor telepon tidak valid.'),
            'nomor_telepon.max' => __('Nomor telepon maksimal 30 karakter.'),
            'gender.in' => __('Jenis kelamin tidak valid.'),
            'tanggal_lahir.date' => __('Format tanggal lahir tidak valid.'),
            'tanggal_lahir.before' => __('Tanggal lahir tidak boleh di masa depan.'),
            'foto_profil.image' => __('File harus berupa gambar.'),
            'foto_profil.max' => __('Ukuran foto maksimal 2MB.'),
        ];
    }
}