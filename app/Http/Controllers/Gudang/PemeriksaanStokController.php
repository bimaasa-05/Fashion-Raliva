<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\WarehouseStock;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanStokController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $items = collect();
        if ($warehouse) {
            $q = $request->query('q');
            $items = WarehouseStock::with(['productVariant.product.category'])
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%'.$q.'%')))
                ->orderBy('jumlah_stok')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.pemeriksaan.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'products' => $warehouse ? $this->getProductsForWarehouse($warehouse) : collect(),
            'filters' => ['q' => $request->query('q')],
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermission('warehouse.stock_adjust')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.stock_adjust) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,product_variant_id',
            'stok_fisik' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ], [
            'product_variant_id.required' => 'Produk wajib dipilih.',
            'product_variant_id.exists' => 'Produk tidak valid.',
            'stok_fisik.required' => 'Jumlah stok fisik wajib diisi.',
            'stok_fisik.integer' => 'Stok fisik harus berupa angka.',
            'stok_fisik.min' => 'Stok fisik minimal 0.',
        ]);

        $stok = WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        $stokSistem = $stok->jumlah_stok ?? 0;
        $selisih = $data['stok_fisik'] - $stokSistem;

        DB::transaction(function () use ($warehouse, $data, $stokSistem, $selisih, $stok) {
            StockOpname::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $data['product_variant_id'],
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $data['stok_fisik'],
                'selisih' => $selisih,
                'catatan' => $data['catatan'],
                'dibuat_oleh' => auth()->id(),
            ]);

            if ($selisih != 0 && $stok) {
                $stok->update(['jumlah_stok' => $data['stok_fisik']]);

                StockMovement::create([
                    'warehouse_id' => $warehouse->warehouse_id,
                    'product_variant_id' => $data['product_variant_id'],
                    'tipe_pergerakan' => StockMovement::TIPE_PENYESUAIAN,
                    'jumlah' => abs($selisih),
                    'sumber_tipe' => StockMovement::SUMBER_MANUAL,
                    'alasan' => sprintf('Penyesuaian stok opname: %d → %d (selisih %s%d)', $stokSistem, $data['stok_fisik'], $selisih > 0 ? '+' : '', $selisih),
                    'dibuat_oleh' => auth()->id(),
                ]);
            }
        });

        ActivityLogger::log(
            'stock.opname',
            WarehouseStock::class,
            $warehouse->warehouse_id,
            null,
            ['product_variant_id' => $data['product_variant_id'], 'stok_sistem' => $stokSistem, 'stok_fisik' => $data['stok_fisik'], 'selisih' => $selisih],
            sprintf('Pemeriksaan stok: sistem %d, fisik %d, selisih %s%d.', $stokSistem, $data['stok_fisik'], $selisih >= 0 ? '+' : '', $selisih)
        );

        return back()->with('toast', ['message' => 'Pemeriksaan stok berhasil disimpan.', 'icon' => 'task_alt']);
    }

    private function getProductsForWarehouse($warehouse)
    {
        return WarehouseStock::with(['productVariant.product'])
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->orderBy('product_variant_id')
            ->get();
    }
}
