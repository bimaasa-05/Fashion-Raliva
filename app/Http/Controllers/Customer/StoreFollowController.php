<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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

        if (!$store) {
            return response()->json(['status' => 'error', 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $userId = Auth::id();
        $existing = StoreFollow::where('user_id', $userId)
            ->where('store_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            $followed = false;
            $message = 'Berhenti mengikuti toko.';
        } else {
            StoreFollow::create(['user_id' => $userId, 'store_id' => $id]);
            $followed = true;
            $message = 'Mengikuti toko.';
        }

        return response()->json([
            'status' => $followed ? 'added' : 'removed',
            'message' => $message,
            'followed' => $followed,
            'followers_count' => $store->followers()->count(),
        ]);
    }
}
