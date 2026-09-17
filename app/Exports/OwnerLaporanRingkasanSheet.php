<?php

namespace App\Exports;

use App\Exports\Traits\SheetRaliva;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OwnerLaporanRingkasanSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    public function __construct(
        protected int $storeId,
        protected int $period,
    ) {
        $this->judulSheet = 'LAPORAN TOKO RALIVA';
        $this->subtitleSheet = 'Ringkasan Keuangan & Penjualan — Periode ' . $this->labelPeriode();
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [6, 34, 28];
        $this->kolomUangSheet = [3];
        $this->barisNonUangSheet = [$this->barisHeaderSheet + 2];
    }

    protected function labelPeriode(): string
    {
        return match ($this->period) {
            7 => now()->subDays(6)->translatedFormat('d M Y') . ' – ' . now()->translatedFormat('d M Y'),
            90 => now()->subMonths(2)->translatedFormat('M Y') . ' – ' . now()->translatedFormat('M Y'),
            365 => now()->subMonths(11)->translatedFormat('M Y') . ' – ' . now()->translatedFormat('M Y'),
            default => now()->subDays(29)->translatedFormat('d M Y') . ' – ' . now()->translatedFormat('d M Y'),
        };
    }

    public function collection(): Collection
    {
        $storeId = $this->storeId;

        $pendapatan = (float) \App\Models\Order::where('store_id', $storeId)->where('status', 'selesai')->sum('grand_total');
        $pesananSelesai = \App\Models\Order::where('store_id', $storeId)->where('status', 'selesai')->count();
        $refund = (float) \App\Models\Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')->sum('refunds.jumlah');
        $dicairkan = (float) \App\Models\Withdrawal::where('store_id', $storeId)->where('status', 'selesai')->sum('jumlah');

        return collect([
            ['no' => 1, 'label' => 'Total Pendapatan', 'value' => $pendapatan],
            ['no' => 2, 'label' => 'Pesanan Selesai', 'value' => $pesananSelesai],
            ['no' => 3, 'label' => 'Nilai Refund', 'value' => $refund],
            ['no' => 4, 'label' => 'Dana Dicairkan', 'value' => $dicairkan],
            ['no' => 5, 'label' => 'Saldo Bersih', 'value' => $pendapatan - $refund - $dicairkan],
            ['no' => 6, 'label' => 'Periode', 'value' => $this->labelPeriode()],
        ]);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['No.', 'Metrik', 'Nilai'];
    }

    /**
     * @param  array{no:int, label:string, value:mixed}  $row
     * @return (int|string|float)[]
     */
    public function map($row): array
    {
        return [$row['no'], $row['label'], is_numeric($row['value']) ? (float) $row['value'] : $row['value']];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}