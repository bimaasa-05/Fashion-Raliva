<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Notification;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\ProfilePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::with('role')->findOrFail(Auth::id());

        return view('SuperAdmin.profil.index', [
            'user' => $user,
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        $data = $request->validated();
        $lama = $user->only(['nama_lengkap', 'email', 'nomor_telepon', 'foto_profil', 'gender', 'tanggal_lahir']);

        if ($request->boolean('remove_photo')) {
            ProfilePhoto::delete($user->foto_profil);
            $data['foto_profil'] = null;
        } elseif ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = ProfilePhoto::replace(
                $request->file('foto_profil'),
                (int) $user->user_id,
                'superadmin',
                $user->foto_profil
            );
        }

        $user->update($data);

        ActivityLogger::log(
            'profile.update',
            User::class,
            $user->user_id,
            $lama,
            ['nama_lengkap' => $data['nama_lengkap'], 'email' => $data['email'], 'foto_profil' => $data['foto_profil'] ?? $user->foto_profil],
            sprintf('Memperbarui profil "%s".', $data['nama_lengkap'])
        );

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Profil Diperbarui', 'Profil Anda berhasil diperbarui.', route('superadmin.profil'));

        return back()->with('toast', [
            'message' => 'Profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        $data = $request->validated();

        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi saat ini salah.'])->withInput();
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

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kata Sandi Diperbarui', 'Kata sandi akun Anda berhasil diperbarui.', route('superadmin.profil'));

        return back()->with('toast', [
            'message' => 'Password berhasil diubah.',
            'icon' => 'task_alt',
        ]);
    }
}