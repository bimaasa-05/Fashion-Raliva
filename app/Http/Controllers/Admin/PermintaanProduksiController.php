<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Models\Warehouse;
use App\Support\AdminContext;
use App\Support\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PermintaanProduksiController extends Controller
{
    public function index()
    {
        $storeIds = \App\Support\AdminContext::assignedStoreIds();

        $base = ProductionOrder::query()
            ->with(['items.productVariant.product', 'requester'])
            ->whereIn('store_id', $storeIds);

        $stats = [
            'total_ajuan' => (clone $base)->count(),
            'diproses' => (clone $base)->where('status', ProductionOrder::STATUS_DIPROSES)->count(),
            'selesai' => (clone $base)->where('status', ProductionOrder::STATUS_SELESAI)->count(),
            'unit_diminta' => (clone $base)->join('production_order_items', 'production_order_items.production_order_id', '=', 'production_orders.production_order_id')->sum('production_order_items.jumlah_diminta'),
        ];

        $history = (clone $base)->orderByDesc('dimulai_pada')->paginate(20)->withQueryString();

        // Order-driven: pesanan dibayar/diproses yang butuh produksi (untuk dropdown modal)
        $pendingOrders = Order::with(['items.productVariant.product'])
            ->whereIn('store_id', $storeIds)
            ->whereIn('status', [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES])
            ->orderByDesc('created_at')->limit(20)->get();
        $warehouses = Warehouse::whereIn('store_id', $storeIds)->orderBy('nama_gudang')->get();

        return view('Admin.permintaan-produksi.index', compact('stats', 'history', 'pendingOrders', 'warehouses'));
    }

    public function store(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $data = $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,product_variant_id'],
            'jumlah_diminta' => ['required', 'integer', 'min:1'],
            'target_warehouse_id' => ['nullable', 'exists:warehouses,warehouse_id'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'prioritas' => ['nullable', 'in:rendah,normal,tinggi,urgent'],
            'order_id' => ['nullable', 'exists:orders,order_id'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $variant = \App\Models\ProductVariant::with('product')->findOrFail($data['product_variant_id']);
        $storeId = $variant->product?->store_id;
        if (! in_array($storeId, $storeIds)) abort(403);

        if (! empty($data['target_warehouse_id'])) {
            $wh = Warehouse::findOrFail($data['target_warehouse_id']);
            if (! in_array($wh->store_id, $storeIds)) abort(403);
        }

        $po = ProductionOrder::create([
            'store_id' => $storeId,
            'requested_by' => auth()->id(),
            'target_warehouse_id' => $data['target_warehouse_id'] ?? Warehouse::where('store_id', $storeId)->value('warehouse_id'),
            'nomor_produksi' => 'PRD-'.date('ymd').'-'.strtoupper(substr(md5(uniqid()),0,4)),
            'prioritas' => $data['prioritas'] ?? ProductionOrder::PRIORITAS_NORMAL,
            'status' => ProductionOrder::STATUS_REQUESTED,
            'catatan' => trim(($data['catatan'] ?? '').($data['order_id'] ? ' (dari Order #'.$data['order_id'].')' : '').($data['tanggal_mulai'] ? " Target {$data['tanggal_mulai']} s/d {$data['tanggal_selesai']}" : '')),
            'dimulai_pada' => $data['tanggal_mulai'] ?? now(),
            'selesai_pada' => $data['tanggal_selesai'] ?? null,
        ]);
        ProductionOrderItem::create([
            'production_order_id' => $po->production_order_id,
            'product_variant_id' => $variant->product_variant_id,
            'jumlah_diminta' => $data['jumlah_diminta'],
        ]);

        ActivityLogger::log('admin.production.request', ProductionOrder::class, $po->production_order_id, [], $po->toArray(), 'Mengajukan produksi '.$variant->product?->nama_produk);

        NotificationService::sendToRole(
            \App\Models\Role::PRODUKSI,
            Notification::TIPE_SISTEM,
            'Permintaan Produksi Baru',
            sprintf('Permintaan produksi %s untuk produk "%s" (%d unit).', $po->nomor_produksi, $variant->product?->nama_produk ?? '-', $data['jumlah_diminta']),
            auth()->id(),
            route('produksi.dashboard')
        );
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Permintaan Produksi Dikirim', sprintf('Permintaan produksi %s dikirim ke tim Produksi.', $po->nomor_produksi), route('admin.permintaan-produksi'));

        return back()->with('success', 'Permintaan produksi '.$po->nomor_produksi.' dikirim ke tim Produksi.');
    }
}
