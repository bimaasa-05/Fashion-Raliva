<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $stats = [
            'semua' => Product::count(),
            Product::STATUS_PENDING => Product::where('status', Product::STATUS_PENDING)->count(),
            Product::STATUS_AKTIF => Product::where('status', Product::STATUS_AKTIF)->count(),
            Product::STATUS_DITOLAK => Product::where('status', Product::STATUS_DITOLAK)->count(),
        ];

        $products = Product::query()
            ->with(['store:store_id,nama_toko', 'category:category_id,nama_kategori'])
            ->when(
                in_array($status, [Product::STATUS_PENDING, Product::STATUS_AKTIF, Product::STATUS_DITOLAK, Product::STATUS_NONAKTIF, Product::STATUS_DRAFT], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'ditolak' THEN 1 WHEN 'nonaktif' THEN 2 ELSE 3 END")
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('SuperAdmin.produk.index', [
            'products' => $products,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }
}
