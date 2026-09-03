<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $dikirim = Order::where('store_id', $storeId)->where('status', Order::STATUS_DIKIRIM)->count();
        $terkirimHariIni = Order::where('store_id', $storeId)->where('status', Order::STATUS_SELESAI)
            ->whereDate('updated_at', now()->toDateString())->count();
        $menungguKurir = Order::where('store_id', $storeId)->where('status', Order::STATUS_DIPROSES)->count();

        $shipments = Order::with('checkout', 'shipments')
            ->where('store_id', $storeId)
            ->whereIn('status', [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->orderByDesc('updated_at')
            ->paginate(15);

        $summary = [
            'dikirim' => $dikirim,
            'terkirim' => $terkirimHariIni,
            'menunggu' => $menungguKurir,
        ];

        return view('Owner.pengiriman.index', compact('shipments', 'summary'));
    }
}
