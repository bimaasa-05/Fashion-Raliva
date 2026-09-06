<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;

class ProduksiController extends Controller
{
    public function index()
    {
        $productions = ProductionOrder::with([
            'store:store_id,nama_toko',
            'items.productVariant.product:product_id,nama_produk',
        ])->orderByDesc('production_orders.created_at')->get();

        $stats = [
            'semua' => $productions->count(),
            'requested' => $productions->where('status', 'requested')->count(),
            'diproses' => $productions->where('status', 'diproses')->count(),
            'menunggu_qc' => $productions->where('status', 'menunggu_qc')->count(),
            'selesai' => $productions->where('status', 'selesai')->count(),
            'dibatalkan' => $productions->where('status', 'dibatalkan')->count(),
        ];

        return view('SuperAdmin.produksi.index', [
            'productions' => $productions,
            'stats' => $stats,
        ]);
    }
}
