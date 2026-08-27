<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 30 karakter.',
        ]);

        $lama = $user->only(['nama_lengkap', 'email', 'nomor_telepon']);

        $user->update($data);

        ActivityLogger::log(
            'profile.update',
            User::class,
            $user->user_id,
            $lama,
            ['nama_lengkap' => $data['nama_lengkap'], 'email' => $data['email']],
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
