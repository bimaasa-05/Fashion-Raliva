<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Tampilkan halaman cart (chart) milik customer yang sedang login.
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();

        $items = $cart->items()
            ->with([
                'productVariant' => fn ($q) => $q->with([
                    'product' => fn ($p) => $p->with([
                        'store:store_id,nama_toko,logo',
                        'images' => fn ($img) => $img->orderBy('urutan'),
                    ]),
                ]),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $count = $items->sum('quantity');
        $subtotal = $items->sum(fn ($i) => $i->quantity * $i->harga_snapshot);
        $shipping = 18000;
        $total = $subtotal + $shipping;

        return view('customer.chart.index', compact('items', 'count', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Tambah varian produk ke cart (AJAX-friendly).
     * Body: { product_variant_id: integer }
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_variant_id' => 'required|integer|exists:product_variants,product_variant_id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Varian produk tidak ditemukan.'], 422);
        }

        $variant = ProductVariant::where('product_variant_id', $validated['product_variant_id'])
            ->where('status', ProductVariant::STATUS_AKTIF)
            ->with('product')
            ->first();

        if (!$variant || !$variant->product || $variant->product->status !== Product::STATUS_AKTIF) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak tersedia.'], 422);
        }

        $cart = $this->getOrCreateCart();

        $existing = $cart->items()
            ->where('product_variant_id', $variant->product_variant_id)
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->product_variant_id,
                'quantity' => 1,
                'harga_snapshot' => $variant->harga,
            ]);
        }

        return response()->json([
            'status' => 'added',
            'message' => 'Ditambahkan ke keranjang.',
            'count' => $this->cartTotalQty(),
        ]);
    }

    /**
     * Ubah quantity item cart (AJAX-friendly).
     * Body: { quantity: integer >= 1 }
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:99',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Jumlah tidak valid.'], 422);
        }

        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak diizinkan.'], 403);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        $totals = $this->cartTotals();

        return response()->json([
            'status' => 'updated',
            'message' => 'Keranjang diperbarui.',
            'count' => $totals['count'],
            'subtotal' => $totals['subtotal'],
            'total' => $totals['total'],
            'item_total' => $cartItem->quantity * $cartItem->harga_snapshot,
        ]);
    }

    /**
     * Hapus satu item dari cart (AJAX-friendly).
     */
    public function destroy(CartItem $cartItem): JsonResponse
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak diizinkan.'], 403);
        }

        $cartItem->delete();

        $totals = $this->cartTotals();

        return response()->json([
            'status' => 'removed',
            'message' => 'Dihapus dari keranjang.',
            'count' => $totals['count'],
            'subtotal' => $totals['subtotal'],
            'total' => $totals['total'],
        ]);
    }

    /**
     * Ambil cart milik user, buat bila belum ada.
     */
    protected function getOrCreateCart(): Cart
    {
        $userId = Auth::id();

        return Cart::firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId, 'status' => Cart::STATUS_AKTIF]
        );
    }

    /**
     * Total quantity seluruh item cart user.
     */
    protected function cartTotalQty(): int
    {
        return $this->getOrCreateCart()->items()->sum('quantity');
    }

    /**
     * Ringkasan cart (count qty, subtotal, total).
     */
    protected function cartTotals(): array
    {
        $cart = $this->getOrCreateCart();
        $items = $cart->items()->get();
        $count = $items->sum('quantity');
        $subtotal = $items->sum(fn ($i) => $i->quantity * $i->harga_snapshot);
        $shipping = 18000;

        return [
            'count' => $count,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    /**
     * Jumlah item (quantity) untuk badge header — bisa dipakai controller lain.
     */
    public static function countForUser(?int $userId): int
    {
        if (!$userId) {
            return 0;
        }

        return Cart::where('user_id', $userId)->withSum('items', 'quantity')->first()?->items_sum_quantity ?? 0;
    }
}
