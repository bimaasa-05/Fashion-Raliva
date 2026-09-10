<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use Illuminate\Support\Carbon;
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
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'warehouse_stocks.supplier_id')
            ->select(
                'warehouse_stocks.*',
                'products.nama_produk',
                'product_variants.sku',
                'product_variants.warna',
                'product_variants.ukuran',
                'stores.nama_toko',
                'warehouses.nama_gudang',
                DB::raw('COALESCE(suppliers.nama_supplier, (SELECT s.nama_supplier FROM stock_movements sm JOIN suppliers s ON s.supplier_id = sm.sumber_id WHERE sm.sumber_tipe = "supplier" AND sm.warehouse_id = warehouse_stocks.warehouse_id AND sm.product_variant_id = warehouse_stocks.product_variant_id ORDER BY sm.created_at DESC LIMIT 1)) as nama_supplier'),
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

    public function detailJson(WarehouseStock $warehouseStock)
    {
        $warehouseStock->load([
            'warehouse.store:store_id,nama_toko',
            'productVariant.product:product_id,nama_produk',
            'supplier',
        ]);

        $hist = DB::table('stock_movements as sm')
            ->join('suppliers as s', 's.supplier_id', '=', 'sm.sumber_id')
            ->select(
                's.nama_supplier',
                's.kontak',
                's.email',
                's.alamat',
                's.kota',
                's.jenis',
                's.catatan',
                's.status',
                'sm.created_at as masuk_pada'
            )
            ->where('sm.sumber_tipe', 'supplier')
            ->where('sm.warehouse_id', $warehouseStock->warehouse_id)
            ->where('sm.product_variant_id', $warehouseStock->product_variant_id)
            ->orderByDesc('sm.created_at')
            ->first();

        $supplier = null;
        if ($warehouseStock->supplier) {
            $s = $warehouseStock->supplier;
            $supplier = [
                'nama' => $s->nama_supplier,
                'kontak' => $s->kontak ?? '-',
                'email' => $s->email ?? '-',
                'alamat' => $s->alamat ?? '-',
                'kota' => $s->kota ?? '-',
                'jenis' => $s->jenis ?? '-',
                'status' => $s->status ?? 'nonaktif',
                'catatan' => $s->catatan ?? '-',
                'masuk_pada' => $hist?->masuk_pada ? Carbon::parse($hist->masuk_pada)->translatedFormat('d M Y • H.i') : '-',
            ];
        } elseif ($hist) {
            $supplier = [
                'nama' => $hist->nama_supplier,
                'kontak' => $hist->kontak ?? '-',
                'email' => $hist->email ?? '-',
                'alamat' => $hist->alamat ?? '-',
                'kota' => $hist->kota ?? '-',
                'jenis' => $hist->jenis ?? '-',
                'status' => $hist->status ?? 'nonaktif',
                'catatan' => $hist->catatan ?? '-',
                'masuk_pada' => $hist->masuk_pada ? Carbon::parse($hist->masuk_pada)->translatedFormat('d M Y • H.i') : '-',
            ];
        }

        $jumlah = (int) $warehouseStock->jumlah_stok;
        $minimum = (int) $warehouseStock->stok_minimum;
        $statusStok = $jumlah === 0 ? 'habis' : ($jumlah <= $minimum ? 'menipis' : 'aman');

        return response()->json([
            'stock' => [
                'nama' => $warehouseStock->productVariant?->product?->nama_produk ?? '-',
                'sku' => $warehouseStock->productVariant?->sku ?? '-',
                'varian' => trim(($warehouseStock->productVariant?->warna ?? '') . ' ' . ($warehouseStock->productVariant?->ukuran ?? '')),
                'jumlah' => $jumlah,
                'reservasi' => (int) $warehouseStock->jumlah_direservasi,
                'minimum' => $minimum,
                'status' => $statusStok,
            ],
            'warehouse' => [
                'nama' => $warehouseStock->warehouse?->nama_gudang ?? '-',
                'toko' => $warehouseStock->warehouse?->store?->nama_toko ?? '-',
            ],
            'supplier' => $supplier,
        ]);
    }
}
