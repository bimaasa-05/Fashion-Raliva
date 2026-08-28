<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Commission;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Store;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI ringkasan platform (hak Super Admin = global)
        $totalPengguna = User::count();
        $totalToko = Store::count();
        $totalPesanan = Order::count();
        $totalProduk = Product::count();

        $nilaiTransaksi = (float) Order::whereIn('status', [
            Order::STATUS_DIBAYAR,
            Order::STATUS_DIPROSES,
            Order::STATUS_DIKIRIM,
            Order::STATUS_SELESAI,
        ])->sum('grand_total');

        $komisiRaliva = (float) Commission::sum('jumlah_komisi');

        // Tugas yang perlu perhatian
        $tokoMenunggu = Store::where('status', Store::STATUS_PENDING)->count();
        $produkDitandai = Product::where('status', Product::STATUS_PENDING)->count();
        $refundMenunggu = Refund::where('status', Refund::STATUS_REQUESTED)->count();

        // Komposisi toko berdasarkan status
        $tokoAktif = Store::where('status', Store::STATUS_AKTIF)->count();
        $tokoMenungguStatus = Store::where('status', Store::STATUS_PENDING)->count();
        $tokoNonaktif = Store::where('status', Store::STATUS_NONAKTIF)->count();
        $tokoDitolak = Store::where('status', Store::STATUS_DITOLAK)->count();

        // Top Toko (omzet) — reuse logika peringkat
        $topToko = $this->topToko();
        $topKategori = $this->topKategori();
        $topPelanggan = $this->topPelanggan();

        // Aktivitas terbaru dari tabel activity_log
        $aktivitas = ActivityLog::with('user:user_id,nama_lengkap')
            ->orderByDesc('activity_log_id')
            ->limit(6)
            ->get()
            ->map(function (ActivityLog $log) {
                return [
                    'deskripsi' => $log->deskripsi,
                    'waktu' => $log->created_at
                        ? Carbon::parse($log->created_at)->locale('id')->diffForHumans()
                        : '-',
                ];
            });

        // Data grafik: pesanan per 6 bulan terakhir (dibagi per bulan)
        $chart = Order::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan, COUNT(*) as jumlah, SUM(grand_total) as omzet')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartLabels = [];
        $chartPesanan = [];
        $chartTransaksi = [];
        foreach ($chart as $row) {
            $chartLabels[] = Carbon::createFromFormat('Y-m', $row->bulan)->locale('id')->translatedFormat('M');
            $chartPesanan[] = (int) $row->jumlah;
            $chartTransaksi[] = (float) $row->omzet;
        }

        $chartPesananBars = [];
        foreach ($chartLabels as $i => $label) {
            $chartPesananBars[] = ['label' => $label, 'value' => $chartPesanan[$i] ?? 0];
        }

        // Range data chart real-time (7/30/90 hari) untuk JS
        $rangeData = [
            '7' => $this->chartRange(7),
            '30' => $this->chartRange(30),
            '90' => $this->chartRange(90),
        ];

        return view('SuperAdmin.dashboard', [
            'kpi' => [
                'pengguna' => $totalPengguna,
                'toko' => $totalToko,
                'pesanan' => $totalPesanan,
                'produk' => $totalProduk,
                'nilai_transaksi' => $nilaiTransaksi,
                'komisi' => $komisiRaliva,
            ],
            'perhatian' => [
                'toko' => $tokoMenunggu,
                'produk' => $produkDitandai,
                'refund' => $refundMenunggu,
            ],
            'komposisiToko' => [
                'aktif' => $tokoAktif,
                'menunggu' => $tokoMenungguStatus,
                'nonaktif' => $tokoNonaktif,
                'ditolak' => $tokoDitolak,
            ],
            'komposisiTokoDonut' => [
                ['value' => (int) $tokoAktif, 'color' => '#C9A24D', 'label' => 'Aktif'],
                ['value' => (int) $tokoMenungguStatus, 'color' => '#E9CE8A', 'label' => 'Menunggu'],
                ['value' => (int) $tokoNonaktif + (int) $tokoDitolak, 'color' => '#BA1A26', 'label' => 'Nonaktif/Ditolak'],
            ],
            'topToko' => $topToko,
            'topKategori' => $topKategori,
            'topPelanggan' => $topPelanggan,
            'aktivitas' => $aktivitas,
            'chartLabels' => $chartLabels,
            'chartPesanan' => $chartPesanan,
            'chartPesananBars' => $chartPesananBars,
            'chartTransaksi' => $chartTransaksi,
            'rangeData' => $rangeData,
        ]);
    }

    private function chartRange(int $days): array
    {
        if ($days <= 7) {
            $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $rows = Order::query()
                ->selectRaw('DAYOFWEEK(created_at) as hari, COUNT(*) as jumlah, SUM(grand_total) as omzet')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('hari')
                ->get();
            $pesanan = array_fill(0, 7, 0);
            $transaksi = array_fill(0, 7, 0);
            foreach ($rows as $r) {
                $idx = ((int) $r->hari + 5) % 7; // MySQL DAYOFWEEK: 1=Minggu -> index Senin=0
                $pesanan[$idx] = (int) $r->jumlah;
                $transaksi[$idx] = (float) $r->omzet;
            }
        } else {
            $group = $days <= 30 ? 'Minggu' : 'Bulan';
            $rows = Order::query()
                ->selectRaw('FLOOR(DATEDIFF(created_at, ?) / ?) as grp, COUNT(*) as jumlah, SUM(grand_total) as omzet', [now()->subDays($days)->toDateString(), $days <= 30 ? 7 : 30])
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('grp')
                ->orderBy('grp')
                ->get();
            $labels = [];
            $pesanan = [];
            $transaksi = [];
            foreach ($rows as $r) {
                $labels[] = $group . ' ' . (((int) $r->grp) + 1);
                $pesanan[] = (int) $r->jumlah;
                $transaksi[] = (float) $r->omzet;
            }
        }

        return [
            'labels' => $labels,
            'transaksi' => $transaksi,
            'pesanan' => $pesanan,
        ];
    }

    private function topToko(): array
    {
        $rows = Order::query()
            ->selectRaw('stores.store_id, stores.nama_toko')
            ->selectRaw('SUM(orders.grand_total) as total_omzet')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->join('stores', 'stores.store_id', '=', 'orders.store_id')
            ->whereIn('orders.status', [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->groupBy('stores.store_id', 'stores.nama_toko')
            ->orderByDesc('total_omzet')
            ->limit(5)
            ->get();

        $max = (float) $rows->max('total_omzet') ?: 1;

        return $rows->map(function ($row, $i) use ($max) {
            return [
                'name' => $row->nama_toko,
                'meta' => $row->jumlah_pesanan . ' pesanan',
                'display' => 'Rp ' . number_format((float) $row->total_omzet, 0, ',', '.'),
                'pct' => max(4, (int) round(((float) $row->total_omzet / $max) * 100)),
            ];
        })->all();
    }

    private function topKategori(): array
    {
        $rows = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.product_id', '=', 'product_variants.product_id')
            ->join('categories', 'categories.category_id', '=', 'products.category_id')
            ->whereIn('orders.status', [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->groupBy('categories.category_id', 'categories.nama_kategori')
            ->selectRaw('categories.nama_kategori')
            ->selectRaw('SUM(order_items.quantity) as total_terjual')
            ->selectRaw('SUM(order_items.total) as total_omzet')
            ->orderByDesc('total_omzet')
            ->limit(5)
            ->get();

        $max = (float) $rows->max('total_omzet') ?: 1;

        return $rows->map(function ($row) use ($max) {
            return [
                'name' => $row->nama_kategori,
                'meta' => number_format((float) $row->total_terjual, 0, ',', '.') . ' terjual',
                'display' => 'Rp ' . number_format((float) $row->total_omzet, 0, ',', '.'),
                'pct' => max(4, (int) round(((float) $row->total_omzet / $max) * 100)),
            ];
        })->all();
    }

    private function topPelanggan(): array
    {
        $rows = DB::table('orders')
            ->join('checkouts', 'checkouts.checkout_id', '=', 'orders.checkout_id')
            ->join('users', 'users.user_id', '=', 'checkouts.user_id')
            ->whereIn('orders.status', [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->groupBy('users.user_id', 'users.nama_lengkap')
            ->selectRaw('users.nama_lengkap')
            ->selectRaw('SUM(orders.grand_total) as total_belanja')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->selectRaw('MIN(orders.created_at) as pesanan_pertama')
            ->orderByDesc('total_belanja')
            ->limit(5)
            ->get();

        $max = (float) $rows->max('total_belanja') ?: 1;

        return $rows->map(function ($row) use ($max) {
            return [
                'name' => $row->nama_lengkap,
                'meta' => $row->jumlah_pesanan . ' pesanan',
                'display' => 'Rp ' . number_format((float) $row->total_belanja, 0, ',', '.'),
                'pct' => max(4, (int) round(((float) $row->total_belanja / $max) * 100)),
            ];
        })->all();
    }
}
