<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\MaterialMovement;
use App\Models\Notification;
use App\Models\ProductionMaterial;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderMaterial;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataProduksiController extends Controller
{
    private const TRANSITIONS = [
        ProductionOrder::STATUS_REQUESTED => [ProductionOrder::STATUS_DIPROSES, ProductionOrder::STATUS_DIBATALKAN],
        ProductionOrder::STATUS_DIPROSES => [ProductionOrder::STATUS_MENUNGGU_QC, ProductionOrder::STATUS_DIBATALKAN],
        ProductionOrder::STATUS_MENUNGGU_QC => [ProductionOrder::STATUS_DIBATALKAN],
        ProductionOrder::STATUS_SELESAI => [],
        ProductionOrder::STATUS_DIBATALKAN => [],
    ];

    private function storeIds(): array
    {
        return StoreStaff::where('user_id', auth()->id())
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->pluck('store_id')
            ->all();
    }

    public function index(Request $request)
    {
        $storeIds = $this->storeIds();

        $tab = in_array($request->query('tab'), ['proses', 'riwayat', 'semua'], true) ? $request->query('tab') : 'proses';
        $cari = trim((string) $request->query('cari'));

        $base = ProductionOrder::with(['items.productVariant.product', 'targetWarehouse', 'requester', 'materials.material', 'qualityChecks'])
            ->when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->orderByDesc('dimulai_pada');

        $stats = [
            'requested' => (clone $base)->where('status', ProductionOrder::STATUS_REQUESTED)->count(),
            'diproses' => (clone $base)->where('status', ProductionOrder::STATUS_DIPROSES)->count(),
            'menunggu_qc' => (clone $base)->where('status', ProductionOrder::STATUS_MENUNGGU_QC)->count(),
            'selesai' => (clone $base)->where('status', ProductionOrder::STATUS_SELESAI)->count(),
            'dibatalkan' => (clone $base)->where('status', ProductionOrder::STATUS_DIBATALKAN)->count(),
        ];

        if ($tab === 'proses') {
            $base = $base->whereIn('status', [ProductionOrder::STATUS_REQUESTED, ProductionOrder::STATUS_DIPROSES, ProductionOrder::STATUS_MENUNGGU_QC]);
        } elseif ($tab === 'riwayat') {
            $base = $base->whereIn('status', [ProductionOrder::STATUS_SELESAI, ProductionOrder::STATUS_DIBATALKAN]);
        }

        if ($cari !== '') {
            $base = $base->where(function ($q) use ($cari) {
                $q->where('nomor_produksi', 'like', "%{$cari}%")
                    ->orWhere('catatan', 'like', "%{$cari}%")
                    ->orWhereHas('items.productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', "%{$cari}%"));
            });
        }

        $orders = $base->paginate(12)->withQueryString();

        $materials = ProductionMaterial::whereIn('store_id', $storeIds)->orderBy('nama_bahan')->get();

        return view('Produksi.data-produksi.index', compact('orders', 'stats', 'tab', 'cari', 'materials'));
    }

    public function updateStatus(Request $request, ProductionOrder $productionOrder)
    {
        $storeIds = $this->storeIds();

        if ($storeIds && ! in_array($productionOrder->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Produksi ini di luar toko yang ditugaskan.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'status' => ['required', 'in:diproses,menunggu_qc,dibatalkan'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $allowed = self::TRANSITIONS[$productionOrder->status] ?? [];

        if (! in_array($data['status'], $allowed, true)) {
            return back()->with('toast', ['message' => 'Transisi status tidak valid dari status saat ini.', 'icon' => 'gpp_maybe']);
        }

        $lama = $productionOrder->only(['status']);

        try {
            DB::transaction(function () use ($productionOrder, $data) {
                $locked = ProductionOrder::with('items', 'materials.material')
                    ->where('production_order_id', $productionOrder->production_order_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $allowed = self::TRANSITIONS[$locked->status] ?? [];

                if (! in_array($data['status'], $allowed, true)) {
                    throw new \RuntimeException('Status produksi sudah berubah.');
                }

                $locked->update([
                    'status' => $data['status'],
                    'assigned_to' => $locked->assigned_to ?? auth()->id(),
                    'catatan' => $data['catatan'] ?? $locked->catatan,
                ]);

                if ($data['status'] === ProductionOrder::STATUS_DIBATALKAN) {
                    $this->restoreMaterials($locked);
                }
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('produksi.status.update', ProductionOrder::class, $productionOrder->production_order_id, $lama, ['status' => $data['status']], sprintf('Status produksi %s menjadi %s.', $productionOrder->nomor_produksi, $data['status']));
        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM, 'Status Produksi Diperbarui', sprintf('Produksi %s kini %s.', $productionOrder->nomor_produksi, $data['status']), auth()->id(), route('admin.permintaan-produksi'));
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Status Diperbarui', sprintf('Produksi %s kini %s.', $productionOrder->nomor_produksi, $data['status']), route('produksi.data-produksi'));

        return back()->with('toast', ['message' => 'Status produksi diperbarui.', 'icon' => 'task_alt']);
    }

    public function bahan(Request $request, ProductionOrder $productionOrder)
    {
        $storeIds = $this->storeIds();

        if ($storeIds && ! in_array($productionOrder->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Produksi ini di luar toko yang ditugaskan.', 'icon' => 'gpp_maybe']);
        }

        if (in_array($productionOrder->status, [ProductionOrder::STATUS_SELESAI, ProductionOrder::STATUS_DIBATALKAN], true)) {
            return back()->with('toast', ['message' => 'Bahan hanya dapat dikelola untuk produksi yang masih berjalan.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'bahan' => ['array'],
            'bahan.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $selected = collect($data['bahan'] ?? [])
            ->filter(fn ($v) => is_numeric($v) && $v > 0)
            ->map(fn ($v) => floatval($v));

        if ($selected->isEmpty()) {
            return back()->with('toast', ['message' => 'Pilih minimal satu bahan beserta jumlahnya.', 'icon' => 'gpp_maybe']);
        }

        $materials = ProductionMaterial::whereIn('store_id', $storeIds)
            ->whereIn('production_material_id', $selected->keys())
            ->get()
            ->keyBy('production_material_id');

        if ($materials->count() !== $selected->count()) {
            return back()->with('toast', ['message' => 'Ada bahan yang tidak dikenali.', 'icon' => 'gpp_maybe']);
        }

        try {
            DB::transaction(function () use ($productionOrder, $selected, $materials) {
                $locked = ProductionOrder::with('materials.material')
                    ->where('production_order_id', $productionOrder->production_order_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (in_array($locked->status, [ProductionOrder::STATUS_SELESAI, ProductionOrder::STATUS_DIBATALKAN], true)) {
                    throw new \RuntimeException('Produksi sudah selesai/dibatalkan.');
                }

                $existing = $locked->materials
                    ->mapWithKeys(fn ($row) => [$row->production_material_id => floatval($row->jumlah_pakai)]);

                foreach ($selected as $materialId => $newQty) {
                    $material = $materials->get($materialId);
                    $current = $existing->get($materialId, 0.0);

                    if ($newQty > $current) {
                        $delta = $newQty - $current;
                        if ($material->stok_sekarang < $delta) {
                            throw new \RuntimeException(sprintf('Stok "%s" tersisa %s %s, tidak cukup untuk penambahan %s %s.', $material->nama_bahan, $material->stok_sekarang, $material->satuan, $delta, $material->satuan));
                        }
                        $this->applyMovement($material, MaterialMovement::TIPE_KELUAR, $delta, $locked, 'Pemakaian bahan untuk '.$locked->nomor_produksi);
                    } elseif ($newQty < $current) {
                        $this->applyMovement($material, MaterialMovement::TIPE_MASUK, $current - $newQty, $locked, 'Pengembalian bahan (penyesuaian) '.$locked->nomor_produksi);
                    }
                }

                $locked->materials()->delete();

                foreach ($selected as $materialId => $newQty) {
                    ProductionOrderMaterial::create([
                        'production_order_id' => $locked->production_order_id,
                        'production_material_id' => $materialId,
                        'jumlah_pakai' => $newQty,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('produksi.materials.assign', ProductionOrder::class, $productionOrder->production_order_id, [], $selected->toArray(), 'Mengatur bahan produksi '.$productionOrder->nomor_produksi);

        return back()->with('toast', ['message' => 'Bahan produksi diperbarui.', 'icon' => 'task_alt']);
    }

    private function restoreMaterials(ProductionOrder $locked): void
    {
        foreach ($locked->materials as $row) {
            $row->material?->increment('stok_sekarang', floatval($row->jumlah_pakai));

            MaterialMovement::create([
                'production_material_id' => $row->production_material_id,
                'tipe' => MaterialMovement::TIPE_MASUK,
                'jumlah' => floatval($row->jumlah_pakai),
                'saldo_akhir' => $row->material?->fresh()->stok_sekarang ?? 0,
                'production_order_id' => $locked->production_order_id,
                'alasan' => 'Bahan dikembalikan — '.$locked->nomor_produksi.' dibatalkan',
                'dibuat_oleh' => auth()->id(),
            ]);
        }
    }

    private function applyMovement(ProductionMaterial $material, string $tipe, float $jumlah, ProductionOrder $locked, string $alasan): void
    {
        $material->fresh()->increment('stok_sekarang', $tipe === MaterialMovement::TIPE_MASUK ? $jumlah : -$jumlah);

        MaterialMovement::create([
            'production_material_id' => $material->production_material_id,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'saldo_akhir' => $material->fresh()->stok_sekarang,
            'production_order_id' => $locked->production_order_id,
            'alasan' => $alasan,
            'dibuat_oleh' => auth()->id(),
        ]);
    }
}