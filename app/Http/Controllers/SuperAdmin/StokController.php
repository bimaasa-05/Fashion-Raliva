<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index()
    {
        $stocks = WarehouseStock::query()
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'warehouse_stocks.product_variant_id')
            ->join('products', 'products.product_id', '=', 'product_variants.product_id')
            ->join('warehouses', 'warehouses.warehouse_id', '=', 'warehouse_stocks.warehouse_id')
            ->join('stores', 'stores.store_id', '=', 'warehouses.store_id')
            ->select(
                'warehouse_stocks.*',
                'products.nama_produk',
                'product_variants.sku',
                'product_variants.warna',
                'product_variants.ukuran',
                'stores.nama_toko',
                'warehouses.nama_gudang',
                DB::raw('CASE WHEN warehouse_stocks.jumlah_stok = 0 THEN "habis" WHEN warehouse_stocks.jumlah_stok <= warehouse_stocks.stok_minimum THEN "menipis" ELSE "aman" END as status_stok')
            )
            ->orderBy('products.nama_produk')
            ->get();

        $stats = [
            'semua' => $stocks->count(),
            'aman' => $stocks->where('status_stok', 'aman')->count(),
            'menipis' => $stocks->where('status_stok', 'menipis')->count(),
            'habis' => $stocks->where('status_stok', 'habis')->count(),
        ];

        return view('SuperAdmin.stok.index', [
            'stocks' => $stocks,
            'stats' => $stats,
        ]);
    }
}
