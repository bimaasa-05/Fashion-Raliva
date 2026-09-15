<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreStaff;
use Illuminate\Http\Request;

class ProdukSelesaiController extends Controller
{
    public function index()
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_SIAP_KIRIM)
            ->with(['items.productVariant.product', 'qualityChecks' => function ($q) {
                $q->whereNotNull('order_id')->latest();
            }, 'checkout', 'store'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'siap_kirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SIAP_KIRIM)->count(),
            'dikirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_DIKIRIM)->count(),
        ];

        return view('Produksi.produk-selesai.index', compact('orders', 'stats'));
    }
}
