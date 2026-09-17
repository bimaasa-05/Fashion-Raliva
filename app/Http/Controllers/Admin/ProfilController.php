<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Notification;
use App\Models\StoreStaff;
use App\Support\ProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleName = $user->role?->nama_role ?? 'Admin Toko';
        $assignedStores = $user ? StoreStaff::query()
            ->with('store')
            ->where('user_id', $user->user_id)
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->orderBy('tanggal_penugasan')
            ->get() : collect();

        return view('Admin.profil.index', compact('user', 'roleName', 'assignedStores'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if ($request->boolean('remove_photo')) {
            ProfilePhoto::delete($user->foto_profil);
            $data['foto_profil'] = null;
        }

        $user->update($data);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Profil Diperbarui', 'Data profil Anda berhasil diperbarui.', route('admin.profil'));

        return redirect()->route('admin.profil')->with('toast', [
            'message' => 'Profil berhasil diperbarui.',
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
                'admin',
                $user->foto_profil
            );
            $user->update(['foto_profil' => $path]);

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Foto Profil Diperbarui', 'Foto profil Anda berhasil diperbarui.', route('admin.profil'));
        }

        return redirect()->route('admin.profil')->with('toast', [
            'message' => 'Foto profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if (! Hash::check($data['password_lama'], $user->password)) {
            return redirect()->route('admin.profil')->withErrors(['password_lama' => 'Kata sandi saat ini salah.']);
        }

        $user->update([
            'password' => $data['password_baru'],
        ]);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kata Sandi Diperbarui', 'Kata sandi akun Anda berhasil diperbarui.', route('admin.profil'));

        return redirect()->route('admin.profil')->with('toast', [
            'message' => 'Kata sandi berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}