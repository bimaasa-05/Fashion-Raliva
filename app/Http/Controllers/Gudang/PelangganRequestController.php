<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganRequestController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        // Customer request terkait toko gudang ini: pesanan dengan status
        // menunggu pemenuhan dari gudang (cek stok/bahan). Untuk V1 ditampilkan
        // sebagai daftar pesanan toko yang butuh pengecekan stok.
        $requests = collect();

        if ($warehouse) {
            $requests = \App\Models\Order::with(['store', 'items.productVariant.product'])
                ->where('store_id', $warehouse->store_id)
                ->whereIn('status', ['dibayar', 'diproses'])
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.pelanggan-request.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'requests' => $requests,
        ]);
    }
}
