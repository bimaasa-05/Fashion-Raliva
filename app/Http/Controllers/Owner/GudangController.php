<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\OwnerContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $warehouses = Warehouse::with('staff')
            ->where('store_id', $storeId)
            ->get();

        $summary = [
            'total' => $warehouses->count(),
            'unit' => 0,
            'menipis' => 0,
            'kapasitas' => $warehouses->avg('kapasitas') ?? 0,
        ];

        $menungguPersetujuan = $storeId
            ? StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester', 'items.productVariant.product'])
                ->where('status', StockTransfer::STATUS_REQUESTED)
                ->whereHas('fromWarehouse', fn ($query) => $query->where('store_id', $storeId))
                ->orderByDesc('created_at')
                ->orderByDesc('stock_transfer_id')
                ->limit(10)
                ->get()
            : collect();

        return view('Owner.gudang.index', compact('warehouses', 'summary', 'menungguPersetujuan'));
    }

    public function setujui(Request $request, StockTransfer $stockTransfer): RedirectResponse
    {
        try {
            DB::transaction(function () use ($stockTransfer) {
                $transfer = StockTransfer::with('items')
                    ->where('stock_transfer_id', $stockTransfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->first();

                if (! $transfer) {
                    throw new \RuntimeException('Pemindahan tidak ditemukan.');
                }

                $storeId = $transfer->fromWarehouse?->store_id;
                if (! $storeId || ! OwnerContext::canAccessStore($storeId)) {
                    throw new \RuntimeException('Anda tidak berhak menyetujui pemindahan ini.');
                }

                if (! $transfer->canTransitionTo(StockTransfer::STATUS_APPROVED)) {
                    throw new \RuntimeException('Pemindahan sudah tidak dapat disetujui.');
                }

                foreach ($transfer->items as $item) {
                    $asal = WarehouseStock::where('warehouse_id', $transfer->from_warehouse_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $asal || $asal->jumlah_stok < $item->jumlah) {
                        throw new \RuntimeException('Stok di gudang asal tidak mencukupi untuk pemindahan ini.');
                    }

                    $affected = WarehouseStock::where('warehouse_stock_id', $asal->warehouse_stock_id)
                        ->where('jumlah_stok', '>=', $item->jumlah)
                        ->decrement('jumlah_stok', $item->jumlah);

                    if ($affected === 0) {
                        throw new \RuntimeException('Stok di gudang asal tidak mencukupi untuk pemindahan ini.');
                    }

                    StockMovement::create([
                        'warehouse_id' => $transfer->from_warehouse_id,
                        'product_variant_id' => $item->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_MUTASI_KELUAR,
                        'jumlah' => $item->jumlah,
                        'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                        'sumber_id' => $transfer->stock_transfer_id,
                        'alasan' => 'Pemindahan stok keluar',
                        'dibuat_oleh' => Auth::id(),
                    ]);
                }

                $affected = StockTransfer::where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->update([
                        'status' => StockTransfer::STATUS_APPROVED,
                        'approved_by' => Auth::id(),
                    ]);

                if ($affected === 0) {
                    throw new \RuntimeException('Gagal menyetujui pemindahan.');
                }
            }, 5);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui pemindahan.');
        }

        $this->notifyPersetujuan($stockTransfer, 'disetujui', 'Pemindahan #TRF-'.$stockTransfer->stock_transfer_id.' disetujui. Stok gudang asal telah dikurangi.');

        return back()->with('success', 'Pemindahan disetujui. Stok gudang asal telah dikurangi.');
    }

    public function tolak(Request $request, StockTransfer $stockTransfer): RedirectResponse
    {
        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:500',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        try {
            DB::transaction(function () use ($stockTransfer, $data) {
                $transfer = StockTransfer::where('stock_transfer_id', $stockTransfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->first();

                if (! $transfer) {
                    throw new \RuntimeException('Pemindahan tidak ditemukan.');
                }

                $storeId = $transfer->fromWarehouse?->store_id;
                if (! $storeId || ! OwnerContext::canAccessStore($storeId)) {
                    throw new \RuntimeException('Anda tidak berhak menolak pemindahan ini.');
                }

                if (! $transfer->canTransitionTo(StockTransfer::STATUS_CANCELLED)) {
                    throw new \RuntimeException('Pemindahan sudah tidak dapat dibatalkan.');
                }

                $affected = StockTransfer::where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->update([
                        'status' => StockTransfer::STATUS_CANCELLED,
                        'alasan_penolakan' => $data['alasan'],
                        'dibatalkan_pada' => now(),
                    ]);

                if ($affected === 0) {
                    throw new \RuntimeException('Gagal menolak pemindahan.');
                }
            }, 5);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menolak pemindahan.');
        }

        $this->notifyPersetujuan($stockTransfer, 'ditolak', 'Pemindahan #TRF-'.$stockTransfer->stock_transfer_id.' ditolak. Alasan: '.$data['alasan']);

        return back()->with('success', 'Pemindahan ditolak.');
    }

    private function notifyPersetujuan(StockTransfer $transfer, string $aksi, string $pesan): void
    {
        if ($transfer->requested_by) {
            Notification::create([
                'user_id' => $transfer->requested_by,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Pemindahan Stok '.ucfirst($aksi),
                'pesan' => $pesan,
                'url' => route('gudang.pemindahan'),
            ]);
        }

        NotificationService::sendToRole(
            Role::GUDANG,
            Notification::TIPE_SISTEM,
            'Pemindahan Stok '.ucfirst($aksi),
            $pesan,
            Auth::id(),
            route('gudang.pemindahan')
        );
    }
}
