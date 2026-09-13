<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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

        return view('customer.orders.index', ['orders' => $orders]);
    }
}