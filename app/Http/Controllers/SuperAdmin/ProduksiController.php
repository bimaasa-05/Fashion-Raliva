<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;

class ProduksiController extends Controller
{
    public function index()
    {
        $query = ProductionOrder::with([
            'store:store_id,nama_toko',
            'items.productVariant.product:product_id,nama_produk',
        ])->orderByDesc('production_orders.created_at');

        if ($status = request('status')) {
            $query->where('production_orders.status', $status);
        }

        $productions = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.produksi.index', [
            'productions' => $productions,
        ]);
    }
}
