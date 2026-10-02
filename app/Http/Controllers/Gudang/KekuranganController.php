<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\ShortfallStockAllocator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KekuranganController extends Controller
{
    public function index()
    {
        $storeIds = $this->storeIds();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('kekurangan_gudang', '>', 0)
            ->with(['items.productVariant.product', 'checkout', 'store'])
            ->orderByDesc('tanggal_qc')
            ->orderByDesc('order_id')
            ->paginate(15);

        $totalKekurangan = (int) Order::whereIn('store_id', $storeIds)
            ->where('kekurangan_gudang', '>', 0)
            ->sum('kekurangan_gudang');

        return view('Gudang.kekurangan.index', [
            'orders' => $orders,
            'totalKekurangan' => $totalKekurangan,
        ]);
    }

    public function siapkan(Request $request, Order $order)
    {
        $storeIds = $this->storeIds();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => __('Pesanan di luar scope toko Anda.'), 'icon' => 'gpp_maybe']);
        }

        $kurang = (int) $order->kekurangan_gudang;
        if ($kurang <= 0) {
            return back()->with('toast', ['message' => __('Pesanan ini sudah tidak memiliki kekurangan.'), 'icon' => 'info']);
        }

        try {
            $gudangSumber = null;
            DB::transaction(function () use ($order, $kurang, $storeIds, &$gudangSumber) {
                // Mode manual: tolak penuh bila ada varian yang kurang.
                $hasil = ShortfallStockAllocator::allocate($order, $kurang, $storeIds, false);
                $gudangSumber = $hasil['gudang'];

                $totalQty = (int) $order->items()->sum('quantity');
                $order->update([
                    'kekurangan_gudang' => 0,
                    'jumlah_berhasil' => min($totalQty, (int) ($order->jumlah_berhasil ?? 0) + $kurang),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        $lama = ['kekurangan_gudang' => $kurang];
        ActivityLogger::log('gudang.kekurangan.siapkan', Order::class, $order->order_id, $lama,
            ['kekurangan_gudang' => 0, 'gudang_sumber' => $gudangSumber],
            sprintf('Kekurangan %d pcs pesanan %s disiapkan dari stok gudang%s.', $kurang, $order->nomor_order, $gudangSumber ? ' ('.$gudangSumber.')' : ''));

        NotificationService::sendToRoleInStores(Role::PRODUKSI, [$order->store_id], Notification::TIPE_SISTEM,
            'Kekurangan Disiapkan dari Gudang',
            sprintf('Pesanan %s — %d pcs kekurangan sudah disiapkan Gudang%s. Silakan dipacking ulang.',
                $order->nomor_order, $kurang, $gudangSumber ? ' ('.$gudangSumber.')' : ''),
            ActivityLogger::resolveActorId(),
            route('produksi.pemeriksaan-kualitas'));

        NotificationService::sendToRoleInStores(Role::ADMIN, [$order->store_id], Notification::TIPE_SISTEM,
            'Kekurangan Disiapkan dari Gudang',
            sprintf('Pesanan %s — %d pcs kekurangan sudah disiapkan Gudang%s.', $order->nomor_order, $kurang, $gudangSumber ? ' ('.$gudangSumber.')' : ''),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC]));

        return back()->with('toast', [
            'message' => __('Kekurangan :ph1 pcs untuk pesanan :ph2 disiapkan dari gudang', ['ph1' => $kurang, 'ph2' => $order->nomor_order]) . ($gudangSumber ? ' (' . $gudangSumber . ')' : '') . '.',
            'icon' => 'task_alt',
        ]);
    }

    private function storeIds(): array
    {
        return StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();
    }
}
