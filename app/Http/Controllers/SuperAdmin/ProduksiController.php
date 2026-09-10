<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;

class ProduksiController extends Controller
{
    public function index()
    {
        $query = ProductionOrder::with([
            'store:store_id,nama_toko',
            'items.productVariant.product:product_id,nama_produk',
        ])->orderByDesc('production_orders.created_at');

        $stats = [
            'semua' => ProductionOrder::count(),
            'requested' => ProductionOrder::where('status', 'requested')->count(),
            'diproses' => ProductionOrder::where('status', 'diproses')->count(),
            'menunggu_qc' => ProductionOrder::where('status', 'menunggu_qc')->count(),
            'selesai' => ProductionOrder::where('status', 'selesai')->count(),
            'dibatalkan' => ProductionOrder::where('status', 'dibatalkan')->count(),
        ];

        $productions = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.produksi.index', [
            'productions' => $productions,
            'stats' => $stats,
        ]);
    }

    public function detailJson(ProductionOrder $productionOrder)
    {
        $productionOrder->load([
            'store:store_id,nama_toko',
            'targetWarehouse:warehouse_id,nama_gudang',
            'requester:user_id,nama_lengkap',
            'assignee:user_id,nama_lengkap',
            'items.productVariant.product:product_id,nama_produk',
        ]);

        $items = $productionOrder->items
            ->map(fn ($i) => [
                'nama' => $i->productVariant?->product?->nama_produk ?? '-',
                'sku' => $i->productVariant?->sku ?? '-',
                'varian' => trim(($i->productVariant?->warna ?? '') . ' ' . ($i->productVariant?->ukuran ?? '')),
                'jumlah' => (int) $i->jumlah_diminta,
            ])
            ->values()
            ->all();

        return response()->json([
            'order' => [
                'nomor' => $productionOrder->nomor_produksi,
                'toko' => $productionOrder->store?->nama_toko ?? '-',
                'gudang' => $productionOrder->targetWarehouse?->nama_gudang ?? '-',
                'prioritas' => $productionOrder->prioritas,
                'status' => $productionOrder->status,
                'pemohon' => $productionOrder->requester?->nama_lengkap ?? '-',
                'pelaksana' => $productionOrder->assignee?->nama_lengkap ?? '-',
                'catatan' => $productionOrder->catatan ?? '-',
                'dimulai' => $productionOrder->dimulai_pada ? $productionOrder->dimulai_pada->translatedFormat('d M Y') : '-',
                'selesai' => $productionOrder->selesai_pada ? $productionOrder->selesai_pada->translatedFormat('d M Y') : '-',
                'dibuat' => $productionOrder->created_at ? $productionOrder->created_at->translatedFormat('d M Y • H.i') : '-',
            ],
            'items' => $items,
        ]);
    }
}
