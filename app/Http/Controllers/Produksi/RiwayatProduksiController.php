<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreStaff;
use Illuminate\Http\Request;

class RiwayatProduksiController extends Controller
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

        $status = $request->query('status', 'semua');
        if (! in_array($status, ['semua', 'siap_kirim', 'dikirim', 'selesai'], true)) {
            $status = 'semua';
        }

        $cari = trim((string) $request->query('cari'));

        $query = Order::whereIn('store_id', $storeIds)
            ->whereIn('status', [Order::STATUS_SIAP_KIRIM, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->with(['items.productVariant.product', 'qualityChecks', 'checkout', 'store', 'bahanList', 'shipments']);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_order', 'like', "%{$cari}%")
                    ->orWhereHas('checkout', function ($cq) use ($cari) {
                        $cq->where('nama_penerima', 'like', "%{$cari}%")
                            ->orWhere('nomor_telepon', 'like', "%{$cari}%");
                    });
            });
        }

        $orders = $query
            ->orderByRaw("CASE status WHEN 'siap_kirim' THEN 0 WHEN 'dikirim' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'siap_kirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SIAP_KIRIM)->count(),
            'dikirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_DIKIRIM)->count(),
            'selesai' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SELESAI)->count(),
            'unit_berhasil' => (int) Order::whereIn('store_id', $storeIds)
                ->whereIn('status', [Order::STATUS_SIAP_KIRIM, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
                ->sum('jumlah_berhasil'),
        ];

        return view('Produksi.riwayat-produksi.index', compact('orders', 'stats', 'status', 'cari'));
    }
}