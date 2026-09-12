<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ProductionOrder;
use App\Models\ProductionResult;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StoreStaff;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanProduksiController extends Controller
{
    private const TRANSITIONS = [
        'requested' => ['diproses', 'dibatalkan'],
        'diproses' => ['menunggu_qc', 'dibatalkan'],
        'menunggu_qc' => ['selesai', 'dibatalkan'],
        'selesai' => [],
        'dibatalkan' => [],
    ];

    public function index()
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->pluck('store_id')
            ->all();

        $base = ProductionOrder::with(['items.productVariant.product', 'targetWarehouse', 'requester'])
            ->when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->orderByDesc('dimulai_pada');

        $orders = (clone $base)->paginate(15);

        $stats = [
            'requested' => (clone $base)->where('status', ProductionOrder::STATUS_REQUESTED)->count(),
            'diproses' => (clone $base)->where('status', ProductionOrder::STATUS_DIPROSES)->count(),
            'menunggu_qc' => (clone $base)->where('status', ProductionOrder::STATUS_MENUNGGU_QC)->count(),
            'selesai' => (clone $base)->where('status', ProductionOrder::STATUS_SELESAI)->count(),
        ];

        return view('Produksi.permintaan-produksi.index', compact('orders', 'stats'));
    }

    public function updateStatus(Request $request, ProductionOrder $productionOrder)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->pluck('store_id')
            ->all();

        if ($storeIds && ! in_array($productionOrder->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Permintaan ini di luar toko yang ditugaskan.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'status' => ['required', 'in:diproses,menunggu_qc,selesai,dibatalkan'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $allowed = self::TRANSITIONS[$productionOrder->status] ?? [];

        if (! in_array($data['status'], $allowed, true)) {
            return back()->with('toast', ['message' => 'Transisi status tidak valid dari status saat ini.', 'icon' => 'gpp_maybe']);
        }

        if ($data['status'] === ProductionOrder::STATUS_SELESAI && ! $productionOrder->target_warehouse_id) {
            return back()->with('toast', ['message' => 'Gudang tujuan belum ditentukan, tidak dapat menyelesaikan.', 'icon' => 'gpp_maybe']);
        }

        $lama = $productionOrder->only(['status']);

        try {
            DB::transaction(function () use ($productionOrder, $data) {
                $locked = ProductionOrder::with('items')
                    ->where('production_order_id', $productionOrder->production_order_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $allowed = self::TRANSITIONS[$locked->status] ?? [];

                if (! in_array($data['status'], $allowed, true)) {
                    throw new \RuntimeException('Status permintaan sudah berubah.');
                }

                $locked->update([
                    'status' => $data['status'],
                    'assigned_to' => $locked->assigned_to ?? auth()->id(),
                    'catatan' => $data['catatan'] ?? $locked->catatan,
                    'selesai_pada' => $data['status'] === ProductionOrder::STATUS_SELESAI ? now() : $locked->selesai_pada,
                ]);

                if ($data['status'] === ProductionOrder::STATUS_SELESAI && $locked->target_warehouse_id) {
                    $result = ProductionResult::create([
                        'production_order_id' => $locked->production_order_id,
                        'jumlah_diproduksi' => $locked->items->sum('jumlah_diminta'),
                        'jumlah_gagal' => 0,
                        'catatan' => $data['catatan'] ?? null,
                    ]);

                    foreach ($locked->items as $item) {
                        WarehouseStock::updateOrCreate(
                            ['warehouse_id' => $locked->target_warehouse_id, 'product_variant_id' => $item->product_variant_id],
                            ['jumlah_stok' => DB::raw('jumlah_stok + '.$item->jumlah_diminta)]
                        );

                        StockMovement::create([
                            'warehouse_id' => $locked->target_warehouse_id,
                            'product_variant_id' => $item->product_variant_id,
                            'tipe_pergerakan' => StockMovement::TIPE_MASUK,
                            'jumlah' => $item->jumlah_diminta,
                            'sumber_tipe' => StockMovement::SUMBER_PRODUCTION_RESULT,
                            'sumber_id' => $result->production_result_id,
                            'alasan' => 'Hasil produksi '.$locked->nomor_produksi,
                            'dibuat_oleh' => auth()->id(),
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('produksi.status.update', ProductionOrder::class, $productionOrder->production_order_id, $lama, ['status' => $data['status']], sprintf('Status produksi %s menjadi %s.', $productionOrder->nomor_produksi, $data['status']));
        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM, 'Status Produksi Diperbarui', sprintf('Produksi %s kini %s.', $productionOrder->nomor_produksi, $data['status']), auth()->id(), route('admin.permintaan-produksi'));
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Status Diperbarui', sprintf('Produksi %s kini %s.', $productionOrder->nomor_produksi, $data['status']), route('produksi.permintaan-produksi'));

        return back()->with('toast', ['message' => 'Status permintaan diperbarui.', 'icon' => 'task_alt']);
    }
}
