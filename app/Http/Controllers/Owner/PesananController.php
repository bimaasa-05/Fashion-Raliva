<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    protected const STATUS_MAP = [
        'baru' => ['pending_payment', 'dibayar'],
        'diproses' => ['diproses'],
        'dikirim' => ['dikirim'],
        'selesai' => ['selesai'],
        'dibatalkan' => ['dibatalkan'],
    ];

    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $status = $request->input('status');
        $period = $request->input('period');

        $query = Order::with(['checkout.user', 'checkout.payment', 'items.productVariant.product'])
            ->where('store_id', $storeId);

        if ($status && isset(self::STATUS_MAP[$status])) {
            $query->whereIn('status', self::STATUS_MAP[$status]);
        }

        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'semua' => Order::where('store_id', $storeId)->count(),
            'baru' => Order::where('store_id', $storeId)->whereIn('status', self::STATUS_MAP['baru'])->count(),
            'diproses' => Order::where('store_id', $storeId)->where('status', 'diproses')->count(),
            'dikirim' => Order::where('store_id', $storeId)->where('status', 'dikirim')->count(),
            'selesai' => Order::where('store_id', $storeId)->where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('store_id', $storeId)->where('status', 'dibatalkan')->count(),
        ];

        return view('Owner.pesanan.index', compact('orders', 'counts', 'status', 'period'));
    }

    public function forward(Request $request, Order $order)
    {
        if ($order->store_id !== OwnerContext::firstStoreId()) {
            abort(403);
        }

        // Teruskan pesanan ke Admin Produksi untuk diproses.
        if (! in_array($order->status, ['baru', 'dibayar'])) {
            return back()->with('error', 'Pesanan sudah diproses atau tidak dapat diteruskan.');
        }

        $order->update(['status' => 'diproses']);

        return back()->with('success', 'Pesanan ' . $order->nomor_order . ' diteruskan ke Admin Produksi.');
    }
}
