<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,'.$user->user_id.',user_id',
            'nomor_telepon' => 'nullable|string|max:30',
            'foto_profil' => 'nullable|image|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 30 karakter.',
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
}
