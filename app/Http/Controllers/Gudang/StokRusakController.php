<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\StockDamage;
use App\Models\StockMovement;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokRusakController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $items = collect();
        if ($warehouse) {
            $q = $request->query('q');
            $items = StockDamage::with([
                'productVariant.product.category',
                'productVariant.warehouseStocks' => fn ($query) => $query->where('warehouse_id', $warehouse->warehouse_id),
                'creator',
            ])
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%'.$q.'%')))
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.stok-rusak.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'products' => $warehouse ? $this->getProductsForWarehouse($warehouse) : collect(),
            'filters' => ['q' => $request->query('q')],
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermission('warehouse.damage')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.damage) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,product_variant_id',
            'jumlah_rusak' => 'required|integer|min:1',
            'alasan' => 'nullable|string|max:500',
        ], [
            'product_variant_id.required' => 'Produk wajib dipilih.',
            'product_variant_id.exists' => 'Produk tidak valid.',
            'jumlah_rusak.required' => 'Jumlah rusak wajib diisi.',
            'jumlah_rusak.integer' => 'Jumlah rusak harus berupa angka.',
            'jumlah_rusak.min' => 'Jumlah rusak minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($warehouse, $data) {
                $stok = WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)
                    ->where('product_variant_id', $data['product_variant_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $stok || $stok->jumlah_stok < $data['jumlah_rusak']) {
                    throw new \RuntimeException('Stok tidak mencukupi untuk dilaporkan rusak.');
                }

                $affected = WarehouseStock::where('warehouse_stock_id', $stok->warehouse_stock_id)
                    ->where('jumlah_stok', '>=', $data['jumlah_rusak'])
                    ->decrement('jumlah_stok', $data['jumlah_rusak']);

                if ($affected === 0) {
                    throw new \RuntimeException('Stok tidak mencukupi untuk dilaporkan rusak.');
                }

                StockDamage::create([
                    'warehouse_id' => $warehouse->warehouse_id,
                    'product_variant_id' => $data['product_variant_id'],
                    'jumlah_rusak' => $data['jumlah_rusak'],
                    'alasan' => $data['alasan'],
                    'dibuat_oleh' => auth()->id(),
                ]);

                StockMovement::create([
                    'warehouse_id' => $warehouse->warehouse_id,
                    'product_variant_id' => $data['product_variant_id'],
                    'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                    'jumlah' => $data['jumlah_rusak'],
                    'sumber_tipe' => StockMovement::SUMBER_MANUAL,
                    'alasan' => $data['alasan'] ?? 'Stok rusak/dihapus',
                    'dibuat_oleh' => auth()->id(),
                ]);
            }, 5);
        } catch (\RuntimeException $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => 'Gagal melaporkan stok rusak.', 'icon' => 'error']);
        }

        ActivityLogger::log(
            'stock.damage',
            WarehouseStock::class,
            $warehouse->warehouse_id,
            null,
            ['product_variant_id' => $data['product_variant_id'], 'jumlah_rusak' => $data['jumlah_rusak']],
            sprintf('Laporan stok rusak %d unit di gudang "%s".', $data['jumlah_rusak'], $warehouse->nama_gudang)
        );

        NotificationService::sendToRole(
            Role::ADMIN,
            Notification::TIPE_SISTEM,
            'Laporan Stok Rusak',
            sprintf('%d unit stok rusak dilaporkan di gudang "%s".', $data['jumlah_rusak'], $warehouse->nama_gudang),
            auth()->id(),
            route('admin.stok')
        );
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Stok Rusak Dilaporkan', sprintf('%d unit stok rusak dilaporkan di gudang "%s".', $data['jumlah_rusak'], $warehouse->nama_gudang), route('gudang.dashboard'));

        return back()->with('toast', ['message' => 'Stok rusak berhasil dilaporkan.', 'icon' => 'task_alt']);
    }

    private function getProductsForWarehouse($warehouse)
    {
        return WarehouseStock::with(['productVariant.product'])
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->where('jumlah_stok', '>', 0)
            ->orderBy('product_variant_id')
            ->get();
    }
}
