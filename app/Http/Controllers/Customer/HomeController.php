<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Halaman beranda customer: produk baru & toko unggulan dari data DB.
     */
    public function index()
    {
        $products = Product::query()
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->with([
                'store:store_id,nama_toko,logo',
                'category:category_id,nama_kategori,parent_id',
                'category.parent:category_id,nama_kategori',
                'images' => fn ($q) => $q->orderBy('urutan'),
                'variants' => fn ($q) => $q->where('status', 'aktif'),
            ])
            ->latest()
            ->take(8)
            ->get();

        $stores = Store::withCount('products')
            ->where('status', Store::STATUS_AKTIF)
            ->orderByDesc('products_count')
            ->orderBy('store_id')
            ->take(4)
            ->get();

        $wishlistedIds = $this->wishlistedIds();
        $cartCount = CartController::countForUser(Auth::id());

        return view('customer.home.index', compact('products', 'stores', 'wishlistedIds', 'cartCount'));
    }

    /**
     * Daftar product_id milik user yang sudah di-wishlist.
     */
    protected function wishlistedIds(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return Auth::user()->wishlist?->items()->pluck('product_id')->all() ?? [];
    }
}