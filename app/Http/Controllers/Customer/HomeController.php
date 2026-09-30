<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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
            ->take(16)
            ->get();

        $stores = Store::withCount('products')
            ->where('status', Store::STATUS_AKTIF)
            ->orderByDesc('products_count')
            ->orderBy('store_id')
            ->take(4)
            ->get();

        $adProducts = AdSlot::activeProducts(10);
        // Kategori dibutuhkan untuk atribut data-category pada kartu Sponsored
        // (filter kategori beranda). activeProducts() tidak eager-load kategori,
        // jadi dimuat di sini dengan pola yang sama seperti ShopController.
        EloquentCollection::make($adProducts)->loadMissing([
            'category:category_id,nama_kategori,parent_id',
            'category.parent:category_id,nama_kategori',
        ]);

        // Opsi filter kategori: seluruh produk aktif, bukan hanya newest, agar
        // kategori tanpa produk terbaru tetap punya pill. Label kartu memakai
        // parent bila ada (lihat index.blade.php), jadi pill harus sama.
        $leafCatIds = Product::query()
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->whereNotNull('category_id')
            ->distinct()
            ->pluck('category_id');

        $leafCats = Category::whereIn('category_id', $leafCatIds)
            ->get(['category_id', 'parent_id']);

        $effectiveCatIds = $leafCats->pluck('parent_id')->filter()
            ->merge($leafCats->whereNull('parent_id')->pluck('category_id'))
            ->unique();

        $homeCats = Category::whereIn('category_id', $effectiveCatIds)
            ->orderBy('category_id')
            ->pluck('nama_kategori')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $wishlistedIds = $this->wishlistedIds();
        $cartCount = CartController::countForUser(Auth::id());

        return view('customer.home.index', compact('products', 'stores', 'adProducts', 'homeCats', 'wishlistedIds', 'cartCount'));
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