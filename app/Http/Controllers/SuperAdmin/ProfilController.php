<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::with('role')->firstWhere('user_id', 1);

        return view('SuperAdmin.profil.index', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::firstWhere('user_id', 1);

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

        $lama = $user->only(['nama_lengkap', 'email', 'nomor_telepon', 'foto_profil']);

        if ($request->hasFile('foto_profil')) {
            // Folder tujuan: public/profil
            $destDir = public_path('profil');
            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            // Hapus foto lama (disimpan di public/profil) jika ada
            if ($user->foto_profil) {
                $oldFile = public_path($user->foto_profil);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $file = $request->file('foto_profil');
            $filename = 'superadmin-' . $user->user_id . '-' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);

            // Simpan path relatif di database: profil/namafile.jpg
            $data['foto_profil'] = 'profil/' . $filename;
        }

        $user->update($data);

        // Refresh session user data
        Auth::login($user);

        ActivityLogger::log(
            'profile.update',
            User::class,
            $user->user_id,
            $lama,
            ['nama_lengkap' => $data['nama_lengkap'], 'email' => $data['email'], 'foto_profil' => $data['foto_profil'] ?? $user->foto_profil],
            sprintf('Memperbarui profil "%s".', $data['nama_lengkap'])
        );

        return back()->with('toast', [
            'message' => 'Profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = User::firstWhere('user_id', 1);

        $data = $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|string|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->with('toast', [
                'message' => 'Password lama salah.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $user->update(['password' => $data['password_baru']]);

        // Refresh session user data
        Auth::login($user);

        ActivityLogger::log(
            'profile.password.update',
            User::class,
            $user->user_id,
            null,
            null,
            sprintf('"%s" mengubah password.', $user->nama_lengkap)
        );

        return back()->with('toast', [
            'message' => 'Password berhasil diubah.',
            'icon' => 'task_alt',
        ]);
    }
}
