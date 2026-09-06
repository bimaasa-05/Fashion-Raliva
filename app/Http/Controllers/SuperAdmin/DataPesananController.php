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
        $orders = Order::query()
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap,email', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $orders->transform(function (Order $order) {
            $order->jumlah_produk = $order->items->sum('quantity');
            $order->waktu_relatif = $order->created_at
                ? Carbon::parse($order->created_at)->locale('id')->diffForHumans()
                : '-';

            return $order;
        });

        return view('SuperAdmin.data-pesanan.index', [
            'orders' => $orders,
        ]);
    }
}
