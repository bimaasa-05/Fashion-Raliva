<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Halaman My Reviews: daftar review yang sudah ditulis + item yang bisa di-review.
     */
    public function index()
    {
        $user = Auth::user();

        $reviews = $user->reviews()
            ->with(['product.images', 'orderItem.productVariant.product.images', 'store'])
            ->orderByDesc('created_at')
            ->get();

        $toReviewItems = OrderItem::query()
            ->whereHas('order.checkout', fn ($q) => $q->where('user_id', $user->user_id))
            ->whereHas('order', fn ($q) => $q->where('status', Order::STATUS_SELESAI))
            ->doesntHave('review')
            ->with(['order.store', 'productVariant.product.images'])
            ->latest('order_item_id')
            ->get();

        return view('customer.reviews.index', compact('reviews', 'toReviewItems'));
    }

    /**
     * Form menulis review untuk satu order item.
     */
    public function create(Request $request)
    {
        $orderItem = $this->resolveEligibleOrderItem((int) $request->query('order_item'));

        if (! $orderItem) {
            return redirect()->route('customer.reviews')->with('toast', [
                'message' => 'Produk yang Anda pilih tidak bisa direview.',
                'icon' => 'info',
            ]);
        }

        return view('customer.reviews.create', compact('orderItem'));
    }

    /**
     * Simpan review baru.
     */
    public function store(Request $request)
    {
        $data = $this->validateReview($request);

        $orderItem = $this->resolveEligibleOrderItem((int) $data['order_item_id']);

        if (! $orderItem) {
            return back()->withErrors(['order_item_id' => 'Produk tidak bisa direview.'])->withInput();
        }

        Review::create([
            'user_id' => Auth::id(),
            'order_item_id' => $orderItem->order_item_id,
            'product_id' => $orderItem->productVariant?->product_id,
            'store_id' => $orderItem->order?->store_id,
            'rating' => (int) $data['rating'],
            'ulasan' => $data['ulasan'],
            'status' => Review::STATUS_DIMODERASI,
        ]);

        return redirect()->route('customer.reviews')->with('toast', [
            'message' => 'Review berhasil dikirim dan sedang menunggu moderasi.',
            'icon' => 'task_alt',
        ]);
    }

    /**
     * Form edit review milik user.
     */
    public function edit(Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        $review->load(['product.images', 'orderItem.productVariant.product.images', 'store']);

        return view('customer.reviews.edit', compact('review'));
    }

    /**
     * Perbarui review.
     */
    public function update(Request $request, Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        $data = $this->validateReview($request);

        $review->update([
            'rating' => (int) $data['rating'],
            'ulasan' => $data['ulasan'],
        ]);

        return redirect()->route('customer.reviews')->with('toast', [
            'message' => 'Review berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    /**
     * Hapus review milik user.
     */
    public function destroy(Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        $review->delete();

        return redirect()->route('customer.reviews')->with('toast', [
            'message' => 'Review berhasil dihapus.',
            'icon' => 'task_alt',
        ]);
    }

    /**
     * Validasi input rating & ulasan.
     */
    protected function validateReview(Request $request): array
    {
        return $request->validate([
            'order_item_id' => 'required|integer|exists:order_items,order_item_id',
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'required|string|min:20|max:2000',
        ], [
            'order_item_id.required' => 'Produk wajib dipilih.',
            'order_item_id.exists' => 'Produk tidak valid.',
            'rating.required' => 'Rating wajib diisi.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'ulasan.required' => 'Ulasan wajib diisi.',
            'ulasan.min' => 'Ulasan minimal 20 karakter.',
            'ulasan.max' => 'Ulasan maksimal 2000 karakter.',
        ]);
    }

    /**
     * Ambil order item milik user yang berhak di-review.
     */
    protected function resolveEligibleOrderItem(int $orderItemId): ?OrderItem
    {
        $user = Auth::user();

        return OrderItem::query()
            ->where('order_item_id', $orderItemId)
            ->whereHas('order.checkout', fn ($q) => $q->where('user_id', $user->user_id))
            ->whereHas('order', fn ($q) => $q->where('status', Order::STATUS_SELESAI))
            ->with(['order.store', 'productVariant.product.images'])
            ->first();
    }
}