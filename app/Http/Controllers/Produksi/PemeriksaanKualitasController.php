<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ProductionOrder;
use App\Models\ProductionResult;
use App\Models\QualityCheck;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StoreStaff;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanKualitasController extends Controller
{
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

        $cari = trim((string) $request->query('cari'));
        $status = in_array($request->query('status'), ['lulus', 'gagal', 'sebagian'], true) ? $request->query('status') : 'semua';

        $checkBase = QualityCheck::with(['productionOrder.items.productVariant.product', 'checker'])
            ->whereHas('productionOrder', fn ($q) => $q->when($storeIds, fn ($sq) => $sq->whereIn('store_id', $storeIds)));

        $stats = [
            'diperiksa' => (clone $checkBase)->where('diperiksa_pada', '>=', now()->startOfMonth())->sum(DB::raw('jumlah_lulus + jumlah_gagal')),
            'layak' => (clone $checkBase)->where('diperiksa_pada', '>=', now()->startOfMonth())->sum('jumlah_lulus'),
            'gagal' => (clone $checkBase)->where('diperiksa_pada', '>=', now()->startOfMonth())->sum('jumlah_gagal'),
        ];

        $checkBase->when($cari !== '', fn ($q) => $q->whereHas('productionOrder', fn ($sq) => $sq->where('nomor_produksi', 'like', "%{$cari}%")));
        $checkBase->when($status !== 'semua', fn ($q) => $q->where('status', $status));

        $checks = $checkBase->orderByDesc('diperiksa_pada')->paginate(12)->withQueryString();

        $antrian = ProductionOrder::with(['items.productVariant.product', 'qualityChecks', 'targetWarehouse'])
            ->when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->where('status', ProductionOrder::STATUS_MENUNGGU_QC)
            ->orderByDesc('dimulai_pada')
            ->get()
            ->map(function (ProductionOrder $order) {
                $diminta = $order->items->sum('jumlah_diminta');
                $layak = $order->qualityChecks->sum('jumlah_lulus');

                $order->setAttribute('target_diminta', $diminta);
                $order->setAttribute('layak_akumulasi', $layak);
                $order->setAttribute('sisa_target', max(0, $diminta - $layak));

                return $order;
            });

        return view('Produksi.pemeriksaan-kualitas.index', compact('checks', 'antrian', 'stats', 'cari', 'status'));
    }

    public function store(Request $request)
    {
        $storeIds = $this->storeIds();

        $data = $request->validate([
            'production_order_id' => ['required', 'exists:production_orders,production_order_id'],
            'jumlah_lulus' => ['required', 'integer', 'min:0'],
            'jumlah_gagal' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [], [
            'production_order_id' => 'produksi',
            'jumlah_lulus' => 'jumlah layak',
            'jumlah_gagal' => 'jumlah gagal',
        ]);

        if ((int) $data['jumlah_lulus'] + (int) $data['jumlah_gagal'] < 1) {
            return back()->with('toast', ['message' => 'Total unit yang diperiksa minimal 1.', 'icon' => 'gpp_maybe']);
        }

        $order = ProductionOrder::with(['items.productVariant.product', 'qualityChecks', 'targetWarehouse'])
            ->when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->find($data['production_order_id']);

        if (! $order || $order->status !== ProductionOrder::STATUS_MENUNGGU_QC) {
            return back()->with('toast', ['message' => 'Produksi tidak ditemukan atau tidak sedang menunggu QC.', 'icon' => 'gpp_maybe']);
        }

        $diminta = $order->items->sum('jumlah_diminta');
        $layakSebelum = $order->qualityChecks->sum('jumlah_lulus');
        $sisa = $diminta - $layakSebelum;

        $lulus = (int) $data['jumlah_lulus'];
        $gagal = (int) $data['jumlah_gagal'];

        if ($lulus + $gagal > $sisa) {
            return back()->with('toast', ['message' => sprintf('Unit yang diperiksa (%d) melebihi sisa target (%d).', $lulus + $gagal, $sisa), 'icon' => 'gpp_maybe']);
        }

        $lama = ['status' => $order->status];

        try {
            DB::transaction(function () use ($order, $lulus, $gagal, $data, $diminta, $layakSebelum) {
                $locked = ProductionOrder::with(['items.productVariant.product', 'qualityChecks', 'targetWarehouse'])
                    ->where('production_order_id', $order->production_order_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($locked->status !== ProductionOrder::STATUS_MENUNGGU_QC) {
                    throw new \RuntimeException('Status produksi sudah berubah.');
                }

                $prevLayak = $locked->qualityChecks->sum('jumlah_lulus');

                if ($lulus + $gagal > $diminta - $prevLayak) {
                    throw new \RuntimeException('Unit yang diperiksa melebihi sisa target.');
                }

                $statusQc = $gagal === 0
                    ? QualityCheck::STATUS_LULUS
                    : ($lulus === 0 ? QualityCheck::STATUS_GAGAL : QualityCheck::STATUS_SEBAGIAN);

                $check = QualityCheck::create([
                    'production_order_id' => $locked->production_order_id,
                    'checked_by' => auth()->id(),
                    'jumlah_lulus' => $lulus,
                    'jumlah_gagal' => $gagal,
                    'status' => $statusQc,
                    'catatan' => $data['catatan'] ?? null,
                    'diperiksa_pada' => now(),
                ]);

                $result = ProductionResult::create([
                    'production_order_id' => $locked->production_order_id,
                    'jumlah_diproduksi' => $lulus + $gagal,
                    'jumlah_gagal' => $gagal,
                    'catatan' => $data['catatan'] ?? null,
                ]);

                if ($lulus > 0 && $locked->target_warehouse_id) {
                    $this->releaseToWarehouse($locked, $lulus, $diminta, $result);
                }

                $totalLayak = $prevLayak + $lulus;

                $locked->update([
                    'status' => $totalLayak >= $diminta ? ProductionOrder::STATUS_SELESAI : ProductionOrder::STATUS_DIPROSES,
                    'selesai_pada' => $totalLayak >= $diminta ? now() : $locked->selesai_pada,
                    'catatan' => trim(sprintf('%s%s', $locked->catatan ?? '', $data['catatan'] ? PHP_EOL.'QC: '.$data['catatan'] : '')),
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        $selesai = ($order->qualityChecks->sum('jumlah_lulus') + $lulus) >= $diminta;

        ActivityLogger::log('produksi.qc.store', QualityCheck::class, $order->production_order_id, $lama, ['lulus' => $lulus, 'gagal' => $gagal], sprintf('QC %s: %d layak, %d gagal.', $order->nomor_produksi, $lulus, $gagal));

        NotificationService::sendToRole(
            Role::ADMIN,
            Notification::TIPE_SISTEM,
            $selesai ? 'Produksi Selesai' : 'Produksi Ulang',
            $selesai
                ? sprintf('Produksi %s selesai (%d layak masuk gudang).', $order->nomor_produksi, $lulus)
                : sprintf('Produksi %s perlu produksi ulang (hasil QC: %d layak, %d gagal; sisa %d).', $order->nomor_produksi, $lulus, $gagal, $diminta - $order->qualityChecks->sum('jumlah_lulus') - $lulus),
            auth()->id(),
            route('admin.permintaan-produksi')
        );
        Notification::fireSelf(
            Notification::TIPE_SISTEM,
            $selesai ? 'Produksi Selesai' : 'Produksi Ulang Diperlukan',
            $selesai
                ? sprintf('Produksi %s selesai, %d unit layak masuk gudang.', $order->nomor_produksi, $lulus)
                : sprintf('Produksi %s kembali diproses: %d layak, %d gagal. Sisa %d unit.', $order->nomor_produksi, $lulus, $gagal, $diminta - $order->qualityChecks->sum('jumlah_lulus') - $lulus),
            route('produksi.data-produksi')
        );

        return back()->with('toast', [
            'message' => $selesai ? 'QC tercatat — produksi selesai.' : 'QC tercatat — produksi ulang untuk sisa target.',
            'icon' => $selesai ? 'task_alt' : 'refresh',
        ]);
    }

    private function releaseToWarehouse(ProductionOrder $locked, int $lulus, int $diminta, ProductionResult $result): void
    {
        $items = $locked->items;
        $parts = [];
        foreach ($items as $item) {
            $parts[] = [
                'item' => $item,
                'base' => intdiv($item->jumlah_diminta * $lulus, max(1, $diminta)),
                'fraction' => fmod($item->jumlah_diminta * $lulus / max(1, $diminta), 1),
            ];
        }

        $sisaBagian = $lulus - collect($parts)->sum('base');
        usort($parts, fn ($a, $b) => $b['fraction'] <=> $a['fraction']);

        foreach ($parts as $i => $part) {
            $bagian = $part['base'] + ($i < $sisaBagian ? 1 : 0);

            if ($bagian < 1) {
                continue;
            }

            WarehouseStock::updateOrCreate(
                ['warehouse_id' => $locked->target_warehouse_id, 'product_variant_id' => $part['item']->product_variant_id],
                ['jumlah_stok' => DB::raw('jumlah_stok + '.$bagian)]
            );

            StockMovement::create([
                'warehouse_id' => $locked->target_warehouse_id,
                'product_variant_id' => $part['item']->product_variant_id,
                'tipe_pergerakan' => StockMovement::TIPE_MASUK,
                'jumlah' => $bagian,
                'sumber_tipe' => StockMovement::SUMBER_PRODUCTION_RESULT,
                'sumber_id' => $result->production_result_id,
                'alasan' => 'Hasil QC layak '.$locked->nomor_produksi,
                'dibuat_oleh' => auth()->id(),
            ]);
        }
    }
}