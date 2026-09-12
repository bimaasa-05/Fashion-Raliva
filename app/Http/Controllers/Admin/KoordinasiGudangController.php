<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use App\Support\OwnerContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KoordinasiGudangController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $warehouseIds = Warehouse::whereIn('store_id', $storeIds)->pluck('warehouse_id');

        $pesananDiambil = Order::with(['checkout.user', 'items.productVariant.product'])
            ->whereIn('store_id', $storeIds)
            ->whereIn('status', ['diproses', 'dikemas'])
            ->orderByDesc('order_id')
            ->limit(10)
            ->get();

        $riwayat = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester'])
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->orWhereIn('to_warehouse_id', $warehouseIds)
            ->orderByDesc('stock_transfer_id')
            ->limit(10)
            ->get();

        $menungguPersetujuan = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester', 'items.productVariant.product'])
            ->where('status', StockTransfer::STATUS_REQUESTED)
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->orderByDesc('stock_transfer_id')
            ->limit(10)
            ->get();

        return view('Admin.koordinasi-gudang.index', compact('pesananDiambil', 'riwayat', 'menungguPersetujuan'));
    }

    public function kirim(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
        ]);

        $order = Order::with('items.productVariant')->findOrFail($data['order_id']);
        if (! in_array($order->store_id, AdminContext::assignedStoreIds())) {
            abort(403);
        }

        $storeIds = AdminContext::assignedStoreIds();
        $warehouses = Warehouse::whereIn('store_id', $storeIds)->orderBy('warehouse_id')->limit(2)->get();
        if ($warehouses->count() < 1) {
            return back()->with('error', 'Belum ada data gudang untuk toko Anda.');
        }
        $from = $warehouses->first();
        $to = $warehouses->count() > 1 ? $warehouses->last() : $from;
        if ($from->warehouse_id === $to->warehouse_id && $warehouses->count() === 1) {
            return back()->with('error', 'Butuh minimal 2 gudang untuk transfer. Tambah gudang dulu.');
        }

        $transfer = StockTransfer::create([
            'from_warehouse_id' => $from->warehouse_id,
            'to_warehouse_id' => $to->warehouse_id,
            'requested_by' => Auth::id(),
            'status' => StockTransfer::STATUS_REQUESTED,
            'diminta_pada' => now(),
        ]);

        foreach ($order->items as $item) {
            $transfer->items()->create([
                'product_variant_id' => $item->product_variant_id,
                'jumlah' => $item->quantity,
                'catatan' => 'Pesanan ' . ($order->nomor_order ?? $order->order_id),
            ]);
        }

        NotificationService::sendToRole(
            Role::GUDANG,
            Notification::TIPE_SISTEM,
            'Permintaan Pengambilan Stok',
            sprintf('Pengambilan stok untuk pesanan %s dikirim ke Gudang.', $order->nomor_order ?? ('#' . $order->order_id)),
            Auth::id(),
            route('gudang.dashboard')
        );
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Permintaan Pengambilan Dikirim', sprintf('Permintaan pengambilan stok pesanan %s dikirim ke Gudang.', $order->nomor_order ?? ('#' . $order->order_id)), route('admin.koordinasi-gudang'));

        return back()->with('success', 'Permintaan pengambilan dikirim ke Gudang.');
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

                if (! $this->canApproveTransfer($transfer)) {
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

                if (! $this->canApproveTransfer($transfer)) {
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

    private function canApproveTransfer(StockTransfer $transfer): bool
    {
        $storeId = $transfer->fromWarehouse?->store_id;

        if (! $storeId) {
            return false;
        }

        if (Auth::user()->role?->nama_role === Role::ADMIN) {
            return AdminContext::canAccessStore($storeId);
        }

        if (Auth::user()->role?->nama_role === Role::OWNER) {
            return OwnerContext::canAccessStore($storeId);
        }

        return false;
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
