<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('Admin.profil.index', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->user_id . ',user_id',
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($user->foto_profil && !str_starts_with($user->foto_profil, 'http')) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $path = $request->file('foto_profil')->store('avatars', 'public');
        $user->update(['foto_profil' => $path]);

        return back()->with('success', 'Foto profil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'password_lama' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($data['password_lama'], $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
