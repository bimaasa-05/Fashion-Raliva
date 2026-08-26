<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\Store;
use App\Support\AdminContext;
use Illuminate\Support\Facades\DB;

class DashboardOperasionalController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $checkoutIds = Order::whereIn('store_id', $storeIds)->select('checkout_id');

        $stats = [
            'pesanan_baru' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_DIBAYAR)->count(),
            'menunggu_verifikasi' => Payment::whereIn('checkout_id', $checkoutIds)->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'siap_dikirim' => Order::whereIn('store_id', $storeIds)
                ->where('status', Order::STATUS_DIPROSES)
                ->whereDoesntHave('shipments')
                ->count(),
            'sedang_dikirim' => Shipment::whereHas('order', fn ($query) => $query->whereIn('store_id', $storeIds))
                ->where('status', Shipment::STATUS_DIKIRIM)
                ->count(),
            'komplain_terbuka' => Complaint::whereIn('store_id', $storeIds)
                ->whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES])
                ->count(),
        ];

        $pesananTerbaru = Order::query()
            ->whereIn('store_id', $storeIds)
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $komplainTerbaru = Complaint::query()
            ->whereIn('store_id', $storeIds)
            ->with(['user:user_id,nama_lengkap', 'store:store_id,nama_toko'])
            ->orderByDesc('dibuat_pada')
            ->limit(5)
            ->get();

        return view('Admin.dashboard-operasional.index', [
            'stores' => Store::whereIn('store_id', $storeIds)->get(['store_id', 'nama_toko']),
            'stats' => $stats,
            'pesananTerbaru' => $pesananTerbaru,
            'komplainTerbaru' => $komplainTerbaru,
            'omzetMingguan' => $this->omzetMingguan($storeIds),
            'distribusiStatus' => $this->distribusiStatus($storeIds),
            'produkTerlaris' => $this->produkTerlaris($storeIds),
        ]);
    }

    private function omzetMingguan(array $storeIds): array
    {
        $valid = [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI];

        $rows = Order::query()
            ->whereIn('store_id', $storeIds)
            ->whereIn('status', $valid)
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('tanggal')
            ->selectRaw('DATE(created_at) as tanggal, SUM(grand_total) as total')
            ->pluck('total', 'tanggal');

        $hasil = [];
        $total = 0;

        foreach (range(6, 0) as $i) {
            $hari = now()->subDays($i);
            $kunci = $hari->toDateString();
            $nilai = (float) ($rows[$kunci] ?? 0);
            $total += $nilai;
            $hasil[] = ['label' => $hari->translatedFormat('D'), 'value' => round($nilai / 1000000, 1)];
        }

        return ['bars' => $hasil, 'total' => $total];
    }

    private function distribusiStatus(array $storeIds): array
    {
        $warna = [
            Order::STATUS_DIBAYAR => '#C9A24D',
            Order::STATUS_DIPROSES => '#E9CE8A',
            Order::STATUS_DIKIRIM => '#795905',
            Order::STATUS_SELESAI => '#4ade80',
            Order::STATUS_DIBATALKAN => '#BA1A26',
        ];

        $label = [
            Order::STATUS_DIBAYAR => 'Baru',
            Order::STATUS_DIPROSES => 'Diproses',
            Order::STATUS_DIKIRIM => 'Dikirim',
            Order::STATUS_SELESAI => 'Selesai',
            Order::STATUS_DIBATALKAN => 'Dibatalkan',
        ];

        $counts = Order::whereIn('store_id', $storeIds)
            ->whereIn('status', array_keys($warna))
            ->groupBy('status')
            ->selectRaw('status, COUNT(*) as jumlah')
            ->pluck('jumlah', 'status');

        $donut = [];

        foreach ($warna as $status => $color) {
            if (($counts[$status] ?? 0) > 0) {
                $donut[] = ['value' => (int) $counts[$status], 'color' => $color, 'label' => $label[$status]];
            }
        }

        return $donut;
    }

    private function produkTerlaris(array $storeIds): array
    {
        $rows = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->whereIn('orders.store_id', $storeIds)
            ->whereIn('orders.status', [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->groupBy('order_items.nama_produk_snapshot')
            ->selectRaw('order_items.nama_produk_snapshot as nama, SUM(order_items.quantity) as terjual')
            ->orderByDesc('terjual')
            ->limit(4)
            ->get();

        $maks = (float) $rows->max('terjual');

        return $rows->map(fn ($row) => [
            'name' => $row->nama,
            'meta' => 'Terjual periode ini',
            'display' => number_format((float) $row->terjual, 0, ',', '.').' pcs',
            'pct' => $maks > 0 ? max(8, (int) round($row->terjual / $maks * 100)) : 0,
        ])->all();
    }
}
