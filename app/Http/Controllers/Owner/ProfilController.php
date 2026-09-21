<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Notification;
use App\Support\ProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role?->nama_role ?? 'Owner';
        $ownedStores = $user ? $user->ownedStores()->get() : collect();

        return view('Owner.profil.index', compact('user', 'roleName', 'ownedStores'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if ($request->boolean('remove_photo')) {
            ProfilePhoto::delete($user->foto_profil);
            $data['foto_profil'] = null;
        } elseif ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = ProfilePhoto::replace(
                $request->file('foto_profil'),
                (int) $user->user_id,
                'owner',
                $user->foto_profil
            );
        }

        $user->update($data);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Profil Diperbarui', 'Data profil Anda berhasil diperbarui.', route('owner.profil'));

        return redirect()->route('owner.profil')->with('toast', [
            'message' => 'Profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi saat ini salah.'])->withInput();
        }

        $user->update([
            'password' => $data['password_baru'],
        ]);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kata Sandi Diperbarui', 'Kata sandi akun Anda berhasil diperbarui.', route('owner.profil'));

        return redirect()->route('owner.profil')->with('toast', [
            'message' => 'Kata sandi berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = ProfilePhoto::replace(
                $request->file('foto_profil'),
                (int) $user->user_id,
                'owner',
                $user->foto_profil
            );
            $user->update(['foto_profil' => $path]);

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Foto Profil Diperbarui', 'Foto profil Anda berhasil diperbarui.', route('owner.profil'));
        }

        return back()->with('toast', [
            'message' => 'Foto profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}