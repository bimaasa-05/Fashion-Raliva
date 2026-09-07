<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,'.$user->user_id.',user_id',
            'nomor_telepon' => 'nullable|string|max:30',
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'tanggal_lahir' => 'nullable|date|before:today',
            'foto_profil' => 'nullable|image|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 30 karakter.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->boolean('remove_photo')) {
            if ($user->foto_profil) {
                $oldFile = public_path($user->foto_profil);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $data['foto_profil'] = null;
        } elseif ($request->hasFile('foto_profil')) {
            $destDir = public_path('profil');
            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            if ($user->foto_profil) {
                $oldFile = public_path($user->foto_profil);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $file = $request->file('foto_profil');
            $filename = 'customer-'.$user->user_id.'-'.Str::random(20).'.'.$file->getClientOriginalExtension();
            $file->move($destDir, $filename);

            $data['foto_profil'] = 'profil/'.$filename;
        }

        $user->update($data);

        Auth::login($user);

        return redirect()->route('customer.account')->with('toast', [
            'message' => 'Profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi minimal 8 karakter.',
            'new_password.regex' => 'Kata sandi harus mengandung minimal 1 huruf kapital dan 1 angka.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ], [
            'new_password' => 'kata sandi baru',
            'new_password_confirmation' => 'konfirmasi kata sandi',
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini salah.',
            ])->withInput();
        }

        $user->password = $data['new_password'];
        $user->save();

        Auth::login($user);

        return redirect()->route('customer.account')->with('toast', [
            'message' => 'Kata sandi berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}
