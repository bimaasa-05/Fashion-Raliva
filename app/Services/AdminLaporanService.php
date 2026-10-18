<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Store;
use App\Models\StoreExpense;
use App\Support\AdminContext;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminLaporanService
{
    /** Batas maksimum rentang laporan (inklusif), selaras filter tanggal lama. */
    public const MAKS_HARI = 93;

    /** @var string[] */
    protected const PALET = ['#C9A24D', '#E9CE8A', '#795905', '#4ade80', '#3b82f6', '#a855f7'];

    protected ?Collection $perMetodeCache = null;

    protected ?array $harianCache = null;

    /**
     * @param  int[]  $storeIds
     */
    public function __construct(
        protected array $storeIds,
        protected Carbon $dari,
        protected Carbon $sampai,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        [$dari, $sampai] = static::rentangDariRequest($request);

        return new self(AdminContext::assignedStoreIds(), $dari, $sampai);
    }

    /**
     * Normalisasi rentang tanggal dari query string: default 30 hari terakhir,
     * tukar bila terbalik, dan batasi maksimum MAKS_HARI.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function rentangDariRequest(Request $request): array
    {
        $dari = $request->date('dari')?->startOfDay() ?? now()->subDays(29)->startOfDay();
        $sampai = $request->date('sampai')?->endOfDay() ?? now()->endOfDay();

        if ($sampai->lt($dari)) {
            [$dari, $sampai] = [$sampai->copy()->startOfDay(), $dari->copy()->endOfDay()];
        }

        if ($dari->diffInDays($sampai) > static::MAKS_HARI) {
            $dari = $sampai->copy()->subDays(static::MAKS_HARI - 1)->startOfDay();
        }

        return [$dari, $sampai];
    }

    /**
     * @return int[]
     */
    public function storeIds(): array
    {
        return $this->storeIds;
    }

    public function punyaToko(): bool
    {
        return $this->storeIds !== [];
    }

    public function dari(): Carbon
    {
        return $this->dari;
    }

    public function sampai(): Carbon
    {
        return $this->sampai;
    }

    public function labelPeriode(): string
    {
        return $this->dari->translatedFormat('d M Y').' – '.$this->sampai->translatedFormat('d M Y');
    }

    public function labelToko(): string
    {
        if (! $this->punyaToko()) {
            return '-';
        }

        return Store::query()
            ->whereIn('store_id', $this->storeIds)
            ->orderBy('nama_toko')
            ->pluck('nama_toko')
            ->implode(', ');
    }

    /**
     * Preset rentang yang sedang aktif (7/30/90), null bila rentang kustom.
     */
    public function presetAktif(): ?int
    {
        if (! $this->sampai->isToday()) {
            return null;
        }

        foreach ([7, 30, 90] as $hari) {
            if ($this->dari->isSameDay(now()->subDays($hari - 1))) {
                return $hari;
            }
        }

        return null;
    }

    /**
     * Ringkasan operasional pada rentang terpilih.
     *
     * @return array{pendapatan: float, pesanan: int, refund: float, expense: float, pengeluaran: float, bersih: float}
     */
    public function ringkasan(): array
    {
        if (! $this->punyaToko()) {
            return [
                'pendapatan' => 0.0,
                'pesanan' => 0,
                'refund' => 0.0,
                'expense' => 0.0,
                'pengeluaran' => 0.0,
                'bersih' => 0.0,
            ];
        }

        // pendapatan = order berstatus pendapatan (dibayar→selesai; TIDAK termasuk refund/dibatalkan),
        // selaras dengan Order::STATUS_PENDAPATAN & omzet dashboard operasional.
        $pendapatan = (float) Order::query()
            ->whereIn('store_id', $this->storeIds)
            ->whereIn('status', Order::STATUS_PENDAPATAN)
            ->whereBetween('created_at', [$this->dari, $this->sampai])
            ->sum('grand_total');

        $pesanan = Order::query()
            ->whereIn('store_id', $this->storeIds)
            ->whereIn('status', Order::STATUS_PENDAPATAN)
            ->whereBetween('created_at', [$this->dari, $this->sampai])
            ->count();

        // pengeluaran = refund disetujui/selesai + pengeluaran toko pada periode yang sama.
        $refund = (float) Refund::query()
            ->join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->whereIn('orders.store_id', $this->storeIds)
            ->whereIn('refunds.status', [Refund::STATUS_DISETUJUI, Refund::STATUS_SELESAI])
            ->whereBetween('refunds.diajukan_pada', [$this->dari, $this->sampai])
            ->sum('refunds.jumlah');

        $expense = (float) StoreExpense::query()
            ->whereIn('store_id', $this->storeIds)
            ->whereBetween('tanggal', [$this->dari->toDateString(), $this->sampai->toDateString()])
            ->sum('nominal');

        $pengeluaran = $refund + $expense;

        return [
            'pendapatan' => $pendapatan,
            'pesanan' => $pesanan,
            'refund' => $refund,
            'expense' => $expense,
            'pengeluaran' => $pengeluaran,
            'bersih' => $pendapatan - $pengeluaran,
        ];
    }

    /**
     * Rekap harian sepanjang rentang terpilih.
     *
     * @return array<int, array{tanggal: Carbon, label: string, pesanan: int, pendapatan: float, refund: float, expense: float, bersih: float}>
     */
    public function harian(): array
    {
        return $this->harianCache ??= $this->queryHarian();
    }

    /**
     * Data grafik tren omzet (label tanggal + nilai rupiah penuh).
     *
     * @return array<int, array{label: string, value: float}>
     */
    public function omzetBars(): array
    {
        return array_map(
            fn (array $baris) => ['label' => $baris['label'], 'value' => $baris['pendapatan']],
            $this->harian(),
        );
    }

    /**
     * Statistik ringkas grafik: total, rata-rata per hari, dan hari terbaik.
     *
     * @return array{total: float, rata: float, maks: float, tanggal_maks: ?string}
     */
    public function ringkasanHarian(): array
    {
        $rows = $this->harian();
        $total = (float) array_sum(array_column($rows, 'pendapatan'));

        $maks = 0.0;
        $tanggalMaks = null;
        foreach ($rows as $row) {
            if ($row['pendapatan'] > $maks) {
                $maks = $row['pendapatan'];
                $tanggalMaks = $row['tanggal'];
            }
        }

        return [
            'total' => $total,
            'rata' => $total / max(count($rows), 1),
            'maks' => $maks,
            'tanggal_maks' => $tanggalMaks?->translatedFormat('d M'),
        ];
    }

    /**
     * Rekap pembayaran terverifikasi per metode pada rentang terpilih.
     *
     * @return Collection<int, object>
     */
    public function perMetode(): Collection
    {
        return $this->perMetodeCache ??= $this->queryPerMetode();
    }

    /**
     * @return array<int, array{value: int, color: string, label: string}>
     */
    public function distribusiMetode(): array
    {
        return $this->perMetode()
            ->map(fn (object $metode, int $idx) => [
                'value' => (int) $metode->jumlah_transaksi,
                'color' => self::PALET[$idx % count(self::PALET)],
                'label' => $metode->nama_metode,
            ])
            ->values()
            ->all();
    }

    public function metodeTerbanyak(): ?object
    {
        return $this->perMetode()->sortByDesc('jumlah_transaksi')->first();
    }

    /**
     * Pesanan selesai (income pasti) pada rentang terpilih.
     *
     * @return Collection<int, Order>
     */
    public function pesananSelesai(int $limit = 10): Collection
    {
        if (! $this->punyaToko()) {
            return collect();
        }

        $query = Order::query()
            ->whereIn('store_id', $this->storeIds)
            ->where('status', Order::STATUS_SELESAI)
            ->whereBetween('created_at', [$this->dari, $this->sampai])
            ->with(['checkout.user:user_id,nama_lengkap', 'checkout.payment.paymentMethod'])
            ->orderByDesc('created_at');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Seluruh pesanan selesai pada rentang terpilih (untuk export).
     *
     * @return Collection<int, Order>
     */
    public function pesananSelesaiSemua(): Collection
    {
        return $this->pesananSelesai(0);
    }

    protected function queryHarian(): array
    {
        if (! $this->punyaToko()) {
            return [];
        }

        $pesanan = Order::query()
            ->whereIn('store_id', $this->storeIds)
            ->whereIn('status', Order::STATUS_PENDAPATAN)
            ->whereBetween('created_at', [$this->dari, $this->sampai])
            ->groupBy('tanggal')
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah, SUM(grand_total) as total')
            ->get()
            ->keyBy('tanggal');

        $refund = Refund::query()
            ->join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->whereIn('orders.store_id', $this->storeIds)
            ->whereIn('refunds.status', [Refund::STATUS_DISETUJUI, Refund::STATUS_SELESAI])
            ->whereBetween('refunds.diajukan_pada', [$this->dari, $this->sampai])
            ->groupBy('tanggal')
            ->selectRaw('DATE(refunds.diajukan_pada) as tanggal, SUM(refunds.jumlah) as total')
            ->get()
            ->keyBy('tanggal');

        $expense = StoreExpense::query()
            ->whereIn('store_id', $this->storeIds)
            ->whereBetween('tanggal', [$this->dari->toDateString(), $this->sampai->toDateString()])
            ->groupBy('tanggal')
            ->selectRaw('tanggal, SUM(nominal) as total')
            ->get()
            ->keyBy('tanggal');

        $rows = [];
        foreach (range($this->dari->diffInDays($this->sampai), 0) as $selisih) {
            $hari = $this->sampai->copy()->subDays($selisih);
            $kunci = $hari->toDateString();

            $pendapatan = (float) ($pesanan[$kunci]->total ?? 0);
            $nilaiRefund = (float) ($refund[$kunci]->total ?? 0);
            $nilaiExpense = (float) ($expense[$kunci]->total ?? 0);

            $rows[] = [
                'tanggal' => $hari->copy(),
                'label' => $hari->format('d/m'),
                'pesanan' => (int) ($pesanan[$kunci]->jumlah ?? 0),
                'pendapatan' => $pendapatan,
                'refund' => $nilaiRefund,
                'expense' => $nilaiExpense,
                'bersih' => $pendapatan - $nilaiRefund - $nilaiExpense,
            ];
        }

        return $rows;
    }

    /**
     * @return Collection<int, object>
     */
    protected function queryPerMetode(): Collection
    {
        if (! $this->punyaToko()) {
            return collect();
        }

        return Payment::query()
            ->where('status', Payment::STATUS_TERVERIFIKASI)
            ->whereHas('checkout.orders', fn ($q) => $q->whereIn('store_id', $this->storeIds))
            ->whereBetween('created_at', [$this->dari, $this->sampai])
            ->with('paymentMethod')
            ->get()
            ->groupBy('payment_method_id')
            ->map(function (Collection $group) {
                $metode = $group->first()->paymentMethod;

                return (object) [
                    'nama_metode' => $metode?->nama_metode ?? '-',
                    'jumlah_transaksi' => $group->count(),
                    'total' => (float) $group->sum('jumlah'),
                ];
            })
            ->sortByDesc('total')
            ->values();
    }
}
