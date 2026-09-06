<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class ModerasiProdukController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $products = Product::with(['category', 'variants'])
            ->where('store_id', $storeId)
            ->whereIn('status', ['pending', 'ditolak', 'aktif'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'ditolak' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->paginate(12);

        $all = Product::where('store_id', $storeId)
            ->whereIn('status', ['pending', 'ditolak', 'aktif'])->get();
        $summary = [
            'total' => Product::where('store_id', $storeId)->where('status', '!=', 'arsip')->count(),
            'pending' => $all->where('status', 'pending')->count(),
            'aktif' => $all->where('status', 'aktif')->count(),
            'ditolak' => $all->where('status', 'ditolak')->count(),
        ];

        return view('Owner.moderasi-produk.index', compact('products', 'summary'));
    }
}
