<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();
            $user->ralivaNotifications()->delete();
            $user->wishlist()->delete();
            $user->cart()->delete();
            $user->addresses()->delete();
            $user->reviews()->delete();
            $user->complaints()->delete();
            $user->activityLogs()->delete();
            $user->delete();
            DB::commit();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')
                ->with('status', 'Akun Anda berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Akun tidak dapat dihapus karena masih memiliki pesanan atau aktivitas lainnya.');
        }
    }
}