<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Support\ProfilePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
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
                'customer',
                $user->foto_profil
            );
        }

        $user->update($data);

        Auth::login($user);

        return redirect()->route('customer.account')->with('toast', [
            'message' => 'Profil berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Kata sandi saat ini salah.',
            ])->withInput();
        }

        $user->password = $data['password_baru'];
        $user->save();

        Auth::login($user);

        return redirect()->route('customer.account')->with('toast', [
            'message' => 'Kata sandi berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}