<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoordinasiGudangController extends Controller
{
    public function index()
    {
        $pesananDiambil = Order::with(['checkout.user', 'items.productVariant.product'])
            ->whereIn('status', ['diproses', 'dikemas'])
            ->orderByDesc('order_id')
            ->limit(10)
            ->get();

        $riwayat = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester'])
            ->orderByDesc('stock_transfer_id')
            ->limit(10)
            ->get();

        return view('Admin.koordinasi-gudang.index', compact('pesananDiambil', 'riwayat'));
    }

    public function kirim(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
        ]);

        $order = Order::with('items.productVariant')->findOrFail($data['order_id']);
        $gudang = Warehouse::first();

        if (!$gudang) {
            return back()->with('error', 'Belum ada data gudang.');
        }

        $transfer = StockTransfer::create([
            'from_warehouse_id' => $gudang->warehouse_id,
            'to_warehouse_id' => $gudang->warehouse_id,
            'requested_by' => Auth::id(),
            'status' => StockTransfer::STATUS_REQUESTED,
            'diminta_pada' => now(),
        ]);

        foreach ($order->items as $item) {
            $transfer->items()->create([
                'product_variant_id' => $item->product_variant_id,
                'jumlah' => $item->quantity,
                'catatan' => 'Pesanan ' . ($order->kode_pesanan ?? $order->order_id),
            ]);
        }

        return back()->with('success', 'Permintaan pengambilan dikirim ke Gudang.');
    }
}
