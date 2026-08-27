<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route(EnsureRole::homeRouteFor(Auth::user()->role?->nama_role));
        }

        return view('customer.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:customer,owner'],
            'terms' => ['accepted'],
        ]);

        $roleName = $data['role'] === 'owner' ? Role::OWNER : Role::CUSTOMER;
        $roleId = Role::where('nama_role', $roleName)->value('role_id');

        $user = User::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $roleId,
            'email_verified_at' => now(),
            'status' => User::STATUS_AKTIF,
        ]);

        Auth::login($user);

        if ($roleName === Role::OWNER) {
            return redirect()->route('owner.pengajuan-toko')
                ->with('success', 'Pendaftaran berhasil. Ajukan toko Anda untuk verifikasi.');
        }

        return redirect()->route('customer.home');
    }
}
