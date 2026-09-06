<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WishlistController extends Controller
{
    /**
     * Tampilkan halaman wishlist milik customer yang sedang login.
     */
    public function index()
    {
        $wishlist = $this->getOrCreateWishlist();

        $items = $wishlist->items()
            ->with([
                'product' => fn ($q) => $q->with([
                    'store:store_id,nama_toko,logo',
                    'images' => fn ($img) => $img->orderBy('urutan'),
                    'variants' => fn ($var) => $var->where('status', 'aktif'),
                ]),
            ])
            ->latest('created_at')
            ->get();

        return view('customer.wishlist.index', compact('items'));
    }

    /**
     * Toggle produk masuk/keluar wishlist (AJAX-friendly).
     * Body: { product_id: integer }
     */
    public function toggle(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer|exists:products,product_id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan.'], 422);
        }

        $productId = (int) $validated['product_id'];
        $wishlist = $this->getOrCreateWishlist();

        $existing = $wishlist->items()
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Dihapus dari wishlist.',
                'count' => $wishlist->items()->count(),
                'wishlisted' => false,
            ]);
        }

        $wishlist->items()->create(['product_id' => $productId]);

        return response()->json([
            'status' => 'added',
            'message' => 'Ditambahkan ke wishlist.',
            'count' => $wishlist->items()->count(),
            'wishlisted' => true,
        ]);
    }

    /**
     * Hapus satu produk dari wishlist.
     */
    public function destroy(int $productId): JsonResponse
    {
        $wishlist = $this->getOrCreateWishlist();

        $deleted = $wishlist->items()
            ->where('product_id', $productId)
            ->delete();

        if (!$deleted) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ada di wishlist.'], 404);
        }

        return response()->json([
            'status' => 'removed',
            'message' => 'Dihapus dari wishlist.',
            'count' => $wishlist->items()->count(),
        ]);
    }

    /**
     * Ambil wishlist milik user, buat bila belum ada.
     */
    protected function getOrCreateWishlist(): Wishlist
    {
        $userId = Auth::id();

        return Wishlist::firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );
    }
}
