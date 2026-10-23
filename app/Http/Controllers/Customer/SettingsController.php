<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Soft delete: akun hilang dari sistem, riwayat transaksi tetap utuh.
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // route('home') tidak ada (hanya customer.home) → pakai login
        // yang menampilkan session('success') setelah akun dihapus.
        return redirect()->route('login')
            ->with('success', __('Akun Anda berhasil dihapus.'));
    }
}
