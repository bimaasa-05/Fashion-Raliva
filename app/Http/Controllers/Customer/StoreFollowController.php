<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Store;
use App\Models\StoreFollow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreFollowController extends Controller
{
    /**
     * Halaman daftar toko yang diikuti customer (login).
     */
    public function index()
    {
        $stores = Auth::user()->followedStores()
            ->withCount(['products', 'followers'])
            ->orderByDesc('store_follows.created_at')
            ->get();

        return view('customer.followed-stores', compact('stores'));
    }

    /**
     * Toggle ikuti/batal ikuti toko (AJAX-friendly).
     */
    public function toggle(Request $request, int $id): JsonResponse
    {
        $store = Store::where('store_id', $id)
            ->where('status', Store::STATUS_AKTIF)
            ->first();

        if (! $store) {
            return response()->json(['status' => 'error', 'message' => __('Toko tidak ditemukan.')], 404);
        }

        $userId = Auth::id();
        $existing = StoreFollow::where('user_id', $userId)
            ->where('store_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            $followed = false;
            $message = __('Berhenti mengikuti toko.');
        } else {
            StoreFollow::create(['user_id' => $userId, 'store_id' => $id]);
            $followed = true;
            $message = __('Mengikuti toko.');

            // Kabar ke owner: hanya sekali per pelanggan per toko,
            // supaya spam follow/batal-follow tidak membanjiri notifikasi owner.
            // Kunci: url notif (berisi id toko) ATAU pesan identik (notif era kode lama).
            if ($store->owner_id) {
                $notifUrl = route('owner.dashboard', ['toko' => $store->store_id]);
                $pesan = sprintf('%s mulai mengikuti toko %s.', Auth::user()->nama_lengkap, $store->nama_toko);

                $alreadyNotified = Notification::where('user_id', $store->owner_id)
                    ->where('aktor_id', $userId)
                    ->where('judul', 'Pengikut Baru')
                    ->where(function ($query) use ($notifUrl, $pesan) {
                        $query->where('url', $notifUrl)
                            ->orWhere('pesan', $pesan);
                    })
                    ->exists();

                if (! $alreadyNotified) {
                    Notification::create([
                        'user_id' => $store->owner_id,
                        'aktor_id' => $userId,
                        'tipe' => Notification::TIPE_SISTEM,
                        'judul' => 'Pengikut Baru',
                        'pesan' => $pesan,
                        'url' => $notifUrl,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => $followed ? 'added' : 'removed',
            'message' => $message,
            'followed' => $followed,
            'followers_count' => $store->followers()->count(),
        ]);
    }
}
