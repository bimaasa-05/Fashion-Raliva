<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Halaman pencarian produk/toko/kategori dari data DB.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Product::query()
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($s) => $s->where('status', Store::STATUS_AKTIF))
            ->with([
                'store:store_id,nama_toko,logo',
                'category:category_id,nama_kategori,parent_id',
                'category.parent:category_id,nama_kategori',
                'images' => fn ($img) => $img->orderBy('urutan'),
                'variants' => fn ($v) => $v->where('status', 'aktif'),
            ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama_produk', 'like', "%{$q}%")
                    ->orWhereHas('store', fn ($s) => $s->where('nama_toko', 'like', "%{$q}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('nama_kategori', 'like', "%{$q}%"))
                    ->orWhereHas('category.parent', fn ($c) => $c->where('nama_kategori', 'like', "%{$q}%"));
            });
        }

        $products = $query->latest()->take(12)->get();
        $totalProducts = $products->count();

        $popularTags = Product::query()
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($s) => $s->where('status', Store::STATUS_AKTIF))
            ->with(['category:category_id,nama_kategori,parent_id', 'category.parent:category_id,nama_kategori'])
            ->limit(200)
            ->get()
            ->map(fn ($p) => $p->category?->parent?->nama_kategori ?? $p->category?->nama_kategori)
            ->filter()
            ->unique()
            ->take(6)
            ->values()
            ->all();

        $wishlistedIds = $this->wishlistedIds();

        return view('customer.search.index', compact('products', 'totalProducts', 'q', 'popularTags', 'wishlistedIds'));
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