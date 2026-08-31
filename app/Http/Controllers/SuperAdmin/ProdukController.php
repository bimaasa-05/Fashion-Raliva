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
            ->with(['store:store_id,nama_toko', 'category:category_id,nama_kategori'])
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
        ];

        return view('SuperAdmin.produk.index', [
            'products' => $products,
            'stats' => $stats,
        ]);
    }
}
