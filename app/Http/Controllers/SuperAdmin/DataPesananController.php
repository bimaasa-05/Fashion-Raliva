<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DataPesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $validStatus = [
            Order::STATUS_PENDING_PAYMENT,
            Order::STATUS_DIBAYAR,
            Order::STATUS_DIPROSES,
            Order::STATUS_DIKIRIM,
            Order::STATUS_SELESAI,
            Order::STATUS_DIBATALKAN,
            Order::STATUS_REFUND,
        ];

        $rawStatus = match ($status) {
            'menunggu' => Order::STATUS_PENDING_PAYMENT,
            'dibayar' => Order::STATUS_DIBAYAR,
            'diproses' => Order::STATUS_DIPROSES,
            'dikirim' => Order::STATUS_DIKIRIM,
            'selesai' => Order::STATUS_SELESAI,
            'dibatalkan' => Order::STATUS_DIBATALKAN,
            'refund' => Order::STATUS_REFUND,
            default => null,
        };

        $orders = Order::query()
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap,email', 'items'])
            ->when($rawStatus, fn ($q) => $q->where('status', $rawStatus))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $orders->getCollection()->transform(function (Order $order) {
            $order->jumlah_produk = $order->items->sum('quantity');
            $order->waktu_relatif = $order->created_at
                ? Carbon::parse($order->created_at)->locale('id')->diffForHumans()
                : '-';

            return $order;
        });

        return view('SuperAdmin.data-pesanan.index', [
            'orders' => $orders,
            'activeStatus' => $status,
        ]);
    }
}
