<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Katalog produk: semua produk aktif dari toko aktif (milik role Owner).
     */
    public function index()
    {
        $products = Product::query()
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->with([
                'store:store_id,nama_toko,logo',
                'category:category_id,nama_kategori',
                'images' => fn ($q) => $q->orderBy('urutan'),
                'variants' => fn ($q) => $q->where('status', 'aktif'),
            ])
            ->latest()
            ->get();

        $totalProducts = $products->count();

        $wishlistedIds = $this->wishlistedIds();
        $cartCount = \App\Http\Controllers\Customer\CartController::countForUser(Auth::id());

        return view('customer.shop.index', compact('products', 'totalProducts', 'wishlistedIds', 'cartCount'));
    }

    /**
     * Detail satu produk aktif dari toko aktif.
     */
    public function produkDetail(int $id)
    {
        $product = Product::query()
            ->where('product_id', $id)
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->with([
                'store:store_id,nama_toko,logo,deskripsi',
                'category:category_id,nama_kategori',
                'images' => fn ($q) => $q->orderBy('urutan'),
                'variants' => fn ($q) => $q->where('status', 'aktif')->orderBy('product_variant_id'),
            ])
            ->firstOrFail();

        $reviews = Review::query()
            ->where('product_id', $product->product_id)
            ->where('status', Review::STATUS_AKTIF)
            ->with('user:user_id,nama_lengkap,foto_profil')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();
        $reviews = $reviews->take(3);

        $relatedProducts = Product::query()
            ->where('product_id', '!=', $product->product_id)
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->where(fn ($q) => $q->where('category_id', $product->category_id)->orWhere('store_id', $product->store_id))
            ->with([
                'store:store_id,nama_toko',
                'images' => fn ($q) => $q->orderBy('urutan'),
                'variants' => fn ($q) => $q->where('status', 'aktif'),
            ])
            ->latest()
            ->take(6)
            ->get();

        $wishlistedIds = $this->wishlistedIds();
        $cartCount = \App\Http\Controllers\Customer\CartController::countForUser(Auth::id());

        return view('customer.shop.produk-detail', compact('product', 'reviews', 'averageRating', 'reviewCount', 'relatedProducts', 'wishlistedIds', 'cartCount'));
    }

    /**
     * Halaman selengkapnya ulasan (riviews) untuk satu produk aktif.
     */
    public function produkRiviews(int $id)
    {
        $product = Product::query()
            ->where('product_id', $id)
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->with([
                'store:store_id,nama_toko,logo',
                'category:category_id,nama_kategori',
                'images' => fn ($q) => $q->orderBy('urutan'),
            ])
            ->firstOrFail();

        $reviews = Review::query()
            ->where('product_id', $product->product_id)
            ->where('status', Review::STATUS_AKTIF)
            ->with('user:user_id,nama_lengkap,foto_profil')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        return view('customer.shop.produk-riviews', compact('product', 'reviews', 'averageRating', 'reviewCount'));
    }

    /**
     * Halaman toko (landing = produk-produk toko) milik role Owner.
     */
    public function store(int $id)
    {
        $store = $this->activeStore($id);

        $products = $store->products()
            ->where('status', Product::STATUS_AKTIF)
            ->with([
                'category:category_id,nama_kategori',
                'images' => fn ($q) => $q->orderBy('urutan'),
                'variants' => fn ($q) => $q->where('status', 'aktif'),
            ])
            ->latest()
            ->get();

        $reviews = $store->reviews()->where('status', Review::STATUS_AKTIF)->get();
        $reviewCount = $reviews->count();
        $averageRating = $reviews->avg('rating');

        $totalProducts = $products->count();

        $wishlistedIds = $this->wishlistedIds();

        return view('customer.store.produk', compact('store', 'products', 'reviewCount', 'averageRating', 'totalProducts', 'wishlistedIds'));
    }

    /**
     * Halaman riviews (ulasan toko).
     */
    public function storeRiviews(int $id)
    {
        $store = $this->activeStore($id);

        $reviews = $store->reviews()
            ->where('status', Review::STATUS_AKTIF)
            ->with('user:user_id,nama_lengkap,foto_profil')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        return view('customer.store.riviews', compact('store', 'reviews', 'averageRating', 'reviewCount'));
    }

    /**
     * Halaman about (informasi toko).
     */
    public function storeAbout(int $id)
    {
        $store = $this->activeStore($id);

        $reviews = $store->reviews()->where('status', Review::STATUS_AKTIF)->get();
        $reviewCount = $reviews->count();
        $averageRating = $reviews->avg('rating');

        return view('customer.store.about', compact('store', 'reviewCount', 'averageRating'));
    }

    /**
     * Ambil toko aktif; 404 bila bukan toko aktif.
     */
    protected function activeStore(int $id): Store
    {
        return Store::where('store_id', $id)
            ->where('status', Store::STATUS_AKTIF)
            ->firstOrFail();
    }

    /**
     * Daftar product_id milik user yang sudah di-wishlist.
     */
    protected function wishlistedIds(): array
    {
        if (!Auth::check()) {
            return [];
        }

        return Auth::user()->wishlist?->items()->pluck('product_id')->all() ?? [];
    }
}
