<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['store:store_id,nama_toko', 'category:category_id,nama_kategori', 'images' => fn ($q) => $q->orderBy('urutan'), 'variants', 'adSlot'])
            ->orderByRaw("(SELECT COUNT(*) FROM ad_slots AS s WHERE s.product_id = products.product_id AND s.status = 'aktif' AND s.tanggal_mulai IS NOT NULL AND s.tanggal_selesai IS NOT NULL AND s.tanggal_mulai <= '" . now()->toDateString() . "' AND s.tanggal_selesai >= '" . now()->toDateString() . "') DESC")
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'ditolak' THEN 1 WHEN 'nonaktif' THEN 2 ELSE 3 END")
            ->orderByDesc('updated_at')
            ->get();

        $stats = [
            'semua' => $products->count(),
            'pending' => $products->where('status', Product::STATUS_PENDING)->count(),
            'aktif' => $products->where('status', Product::STATUS_AKTIF)->count(),
            'ditolak' => $products->where('status', Product::STATUS_DITOLAK)->count(),
            'nonaktif' => $products->where('status', Product::STATUS_NONAKTIF)->count(),
            'draft' => $products->where('status', Product::STATUS_DRAFT)->count(),
            'arsip' => $products->where('status', Product::STATUS_ARSIP)->count(),
            'iklan' => $products->filter(fn ($p) => $p->relationLoaded('adSlot') && $p->adSlot !== null)->count(),
        ];

        return view('SuperAdmin.produk.index', [
            'products' => $products,
            'stats' => $stats,
        ]);
    }
}
