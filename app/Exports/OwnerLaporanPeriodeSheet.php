<?php

namespace App\Exports;

use App\Exports\Traits\SheetRaliva;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OwnerLaporanPeriodeSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    public function __construct(
        protected int $storeId,
        protected int $period,
    ) {
        $this->judulSheet = 'LAPORAN PERIODE';
        $this->subtitleSheet = 'Rekap pendapatan, refund, dan pencairan setiap periode';
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [20, 12, 18, 18, 18, 18];
        $this->kolomUangSheet = [3, 4, 5, 6];
    }

    public function collection(): Collection
    {
        $storeId = $this->storeId;

        $bucket = function ($s, $e, $label) use ($storeId) {
            return [
                'periode' => $label,
                'pesanan' => Order::where('store_id', $storeId)->whereBetween('created_at', [$s, $e])->count(),
                'pendapatan' => (float) Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->whereBetween('created_at', [$s, $e])->sum('grand_total'),
                'refund' => (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                    ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')
                    ->whereBetween('refunds.diajukan_pada', [$s, $e])->sum('refunds.jumlah'),
                'pencairan' => (float) Withdrawal::where('store_id', $storeId)->where('status', 'selesai')->whereBetween('diajukan_pada', [$s, $e])->sum('jumlah'),
            ];
        };

        $rows = [];
        if ($this->period <= 7) {
            for ($i = $this->period - 1; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $rows[] = $bucket($day->copy()->startOfDay(), $day->copy()->endOfDay(), $day->translatedFormat('d M Y'));
            }
        } elseif ($this->period <= 30) {
            for ($i = 3; $i >= 0; $i--) {
                $end = now()->subDays($i * 7);
                $s = $end->copy()->subDays(6)->startOfDay();
                $rows[] = $bucket($s, $end->copy()->endOfDay(), $s->translatedFormat('d') . ' — ' . $end->translatedFormat('d M'));
            }
        } else {
            $months = $this->period <= 90 ? 3 : 12;
            for ($i = $months - 1; $i >= 0; $i--) {
                $m = now()->subMonths($i);
                $rows[] = $bucket($m->copy()->startOfMonth(), $m->copy()->endOfMonth(), $m->translatedFormat('M Y'));
            }
        }

        $totals = [
            'pesanan' => array_sum(array_column($rows, 'pesanan')),
            'pendapatan' => array_sum(array_column($rows, 'pendapatan')),
            'refund' => array_sum(array_column($rows, 'refund')),
            'pencairan' => array_sum(array_column($rows, 'pencairan')),
        ];

        $result = [];
        foreach ($rows as $r) {
            $result[] = [
                'no' => count($result) + 1,
                'periode' => $r['periode'],
                'pesanan' => $r['pesanan'],
                'pendapatan' => $r['pendapatan'],
                'refund' => $r['refund'],
                'pencairan' => $r['pencairan'],
                'saldo' => $r['pendapatan'] - $r['refund'] - $r['pencairan'],
            ];
        }

        $result[] = [
            'no' => '',
            'periode' => 'Total',
            'pesanan' => $totals['pesanan'],
            'pendapatan' => $totals['pendapatan'],
            'refund' => $totals['refund'],
            'pencairan' => $totals['pencairan'],
            'saldo' => $totals['pendapatan'] - $totals['refund'] - $totals['pencairan'],
        ];

        return collect($result);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['Periode', 'Pesanan', 'Pendapatan', 'Refund', 'Pencairan', 'Saldo Akhir'];
    }

    /**
     * @param  array{no:int|string, periode:string, pesanan:int, pendapatan:float, refund:float, pencairan:float, saldo:float}  $row
     * @return (int|string|float)[]
     */
    public function map($row): array
    {
        return [
            $row['periode'],
            $row['pesanan'],
            $row['pendapatan'],
            $row['refund'],
            $row['pencairan'],
            $row['saldo'],
        ];
    }

    public function title(): string
    {
        return 'Periode';
    }
}