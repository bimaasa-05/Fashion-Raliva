<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Halaman "Pesanan": riwayat seluruh pesanan milik customer.
     */
    public function index(Request $request)
    {
        $orders = Auth::user()->orders()
            ->with([
                'store',
                'items.productVariant.product.images',
                'shipments.courier',
                'shipments.shippingService',
                'checkout.payment',
            ])
            ->orderByDesc('orders.created_at')
            ->get();

        // Alasan pembatalan untuk kartu yang dibatalkan (1 query, tanpa migration).
        $cancelReasons = [];
        $cancelledIds = $orders->where('status', Order::STATUS_DIBATALKAN)->map->order_id->values()->all();
        if ($cancelledIds) {
            $cancelReasons = ActivityLog::where('aksi', 'admin.order.cancel')
                ->where('target_tipe', Order::class)
                ->whereIn('target_id', $cancelledIds)
                ->orderByDesc('activity_log_id')
                ->get(['target_id', 'nilai_baru'])
                ->groupBy('target_id')
                ->map(fn ($g) => $g->first()->nilai_baru['alasan'] ?? null)
                ->filter()
                ->all();
        }

        return view('customer.orders.index', ['orders' => $orders, 'cancelReasons' => $cancelReasons]);
    }
}