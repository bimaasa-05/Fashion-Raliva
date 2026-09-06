<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->user_id . ',user_id'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && ! str_starts_with($user->foto_profil, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
                $oldPublic = public_path($user->foto_profil);
                if (is_file($oldPublic)) @unlink($oldPublic);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('profil', 'public');
        } else {
            unset($data['foto_profil']);
        }

        $user->update($data);

        return redirect()->route('owner.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'password_lama' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi saat ini salah.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('owner.profil')->with('success', 'Kata sandi berhasil diperbarui.');
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

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
