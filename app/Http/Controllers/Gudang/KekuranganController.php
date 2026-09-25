<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

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
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        $kurang = (int) $order->kekurangan_gudang;
        if ($kurang <= 0) {
            return back()->with('toast', ['message' => 'Pesanan ini sudah tidak memiliki kekurangan.', 'icon' => 'info']);
        }

        $lama = $order->only(['kekurangan_gudang']);
        $order->update(['kekurangan_gudang' => 0]);

        ActivityLogger::log('gudang.kekurangan.siapkan', Order::class, $order->order_id, $lama,
            ['kekurangan_gudang' => 0],
            sprintf('Kekurangan %d pcs pesanan %s disiapkan dari stok gudang.', $kurang, $order->nomor_order));

        NotificationService::sendToRoleInStores(Role::PRODUKSI, [$order->store_id], Notification::TIPE_SISTEM,
            'Kekurangan Disiapkan dari Gudang',
            sprintf('Pesanan %s — %d pcs kekurangan sudah disiapkan Gudang. Silakan dipacking ulang.',
                $order->nomor_order, $kurang),
            ActivityLogger::resolveActorId(),
            route('produksi.pemeriksaan-kualitas'));

        NotificationService::sendToRoleInStores(Role::ADMIN, [$order->store_id], Notification::TIPE_SISTEM,
            'Kekurangan Disiapkan dari Gudang',
            sprintf('Pesanan %s — %d pcs kekurangan sudah disiapkan Gudang.', $order->nomor_order, $kurang),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC]));

        return back()->with('toast', [
            'message' => "Kekurangan {$kurang} pcs untuk pesanan {$order->nomor_order} disiapkan dari gudang.",
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
