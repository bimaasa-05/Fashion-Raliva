<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreStaff;
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

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->user_id . ',user_id'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

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
            if ($user->foto_profil && ! str_starts_with($user->foto_profil, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
                $oldPublic = public_path($user->foto_profil);
                if (is_file($oldPublic)) @unlink($oldPublic);
            }
            $path = $request->file('foto_profil')->store('profil', 'public');
            $user->update(['foto_profil' => $path]);
        }

        return redirect()->route('admin.profil')->with('toast', [
            'message' => 'Foto profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'password_lama' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['password_lama'], $user->password)) {
            return redirect()->route('admin.profil')->withErrors(['password_lama' => 'Kata sandi saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.profil')->with('toast', [
            'message' => 'Kata sandi berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}