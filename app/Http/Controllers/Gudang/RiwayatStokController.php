<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class RiwayatStokController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $items = collect();
        $tipeList = [
            StockMovement::TIPE_MASUK => 'Barang Masuk',
            StockMovement::TIPE_KELUAR => 'Barang Keluar',
            StockMovement::TIPE_MUTASI_MASUK => 'Mutasi Masuk',
            StockMovement::TIPE_MUTASI_KELUAR => 'Mutasi Keluar',
            StockMovement::TIPE_PENYESUAIAN => 'Pemeriksaan/Penyesuaian',
        ];

        if ($warehouse) {
            $q = $request->query('q');
            $tipe = $request->query('tipe');
            $items = StockMovement::with(['productVariant.product', 'creator'])
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->when($tipe, fn ($query) => $query->where('tipe_pergerakan', $tipe))
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%' . $q . '%')))
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString();
        }

        return view('Gudang.riwayat-stok.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'tipeList' => $tipeList,
            'filters' => ['q' => $request->query('q'), 'tipe' => $request->query('tipe')],
        ]);
    }
}
