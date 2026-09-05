<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Support\AdminContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoordinasiGudangController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $warehouseIds = Warehouse::whereIn('store_id', $storeIds)->pluck('warehouse_id');

        $pesananDiambil = Order::with(['checkout.user', 'items.productVariant.product'])
            ->whereIn('store_id', $storeIds)
            ->whereIn('status', ['diproses', 'dikemas'])
            ->orderByDesc('order_id')
            ->limit(10)
            ->get();

        $riwayat = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester'])
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->orWhereIn('to_warehouse_id', $warehouseIds)
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
        if (! in_array($order->store_id, AdminContext::assignedStoreIds())) {
            abort(403);
        }

        $storeIds = AdminContext::assignedStoreIds();
        $warehouses = Warehouse::whereIn('store_id', $storeIds)->orderBy('warehouse_id')->limit(2)->get();
        if ($warehouses->count() < 1) {
            return back()->with('error', 'Belum ada data gudang untuk toko Anda.');
        }
        $from = $warehouses->first();
        $to = $warehouses->count() > 1 ? $warehouses->last() : $from;
        if ($from->warehouse_id === $to->warehouse_id && $warehouses->count() === 1) {
            return back()->with('error', 'Butuh minimal 2 gudang untuk transfer. Tambah gudang dulu.');
        }

        $transfer = StockTransfer::create([
            'from_warehouse_id' => $from->warehouse_id,
            'to_warehouse_id' => $to->warehouse_id,
            'requested_by' => Auth::id(),
            'status' => StockTransfer::STATUS_REQUESTED,
            'diminta_pada' => now(),
        ]);

        foreach ($order->items as $item) {
            $transfer->items()->create([
                'product_variant_id' => $item->product_variant_id,
                'jumlah' => $item->quantity,
                'catatan' => 'Pesanan ' . ($order->nomor_order ?? $order->order_id),
            ]);
        }

        return back()->with('success', 'Permintaan pengambilan dikirim ke Gudang.');
    }
}
