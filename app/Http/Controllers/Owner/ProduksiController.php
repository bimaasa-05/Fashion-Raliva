<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
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

        $berjalan = 0; $selesaiBulan = 0; $unit = 0; $menunggu = 0;
        foreach ($orders as $o) {
            $st = $o->status;
            $qty = $o->items->sum('jumlah_diminta') ?: 0;
            if ($st === 'selesai') {
                $selesaiBulan += ($o->selesai_pada && $o->selesai_pada->isCurrentMonth()) ? 1 : 0;
                $unit += $qty;
            } elseif ($st === 'menunggu' || $st === 'pending') {
                $menunggu++;
            } else {
                $berjalan++;
            }
        }

        $summary = [
            'berjalan' => $berjalan,
            'selesai' => $selesaiBulan,
            'unit' => $unit,
            'menunggu' => $menunggu,
        ];

        return view('Owner.produksi.index', compact('orders', 'summary'));
    }
}
