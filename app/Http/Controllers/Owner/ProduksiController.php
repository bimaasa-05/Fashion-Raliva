<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $orders = ProductionOrder::with(['items.productVariant.product', 'targetWarehouse'])
            ->where('store_id', $storeId)
            ->orderByDesc('created_at')
            ->paginate(12);

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $base = ProductionOrder::where('store_id', $storeId);

        $summary = [
            'berjalan' => (clone $base)
                ->whereIn('status', [
                    ProductionOrder::STATUS_DIPROSES,
                    ProductionOrder::STATUS_MENUNGGU_QC,
                ])
                ->count(),

            'selesai' => (clone $base)
                ->where('status', ProductionOrder::STATUS_SELESAI)
                ->whereBetween('selesai_pada', [$startOfMonth, $endOfMonth])
                ->count(),

            'unit' => (int) ProductionOrderItem::whereHas(
                'productionOrder',
                function ($query) use ($storeId, $startOfMonth, $endOfMonth) {
                    $query->where('store_id', $storeId)
                        ->where('status', ProductionOrder::STATUS_SELESAI)
                        ->whereBetween('selesai_pada', [$startOfMonth, $endOfMonth]);
                }
            )->sum('jumlah_diminta'),

            'menunggu' => (clone $base)
                ->where('status', ProductionOrder::STATUS_REQUESTED)
                ->count(),
        ];

        return view('Owner.produksi.index', compact('orders', 'summary'));
    }
}
