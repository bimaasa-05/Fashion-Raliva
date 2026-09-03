<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
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

        return view('Admin.permintaan-produksi.index', compact('stats', 'history'));
    }
}
