<?php

namespace App\Exports;

use App\Exports\Traits\SheetRaliva;
use App\Services\AdminLaporanService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AdminLaporanRingkasanSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    public function __construct(
        protected AdminLaporanService $laporan,
    ) {
        $this->judulSheet = 'LAPORAN OPERASIONAL '.mb_strtoupper(brand());
        $this->subtitleSheet = $this->laporan->labelTokoPeriode();
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [6, 34, 28];
        $this->kolomUangSheet = [3];
        // Baris non-uang: 5 = Pesanan Berpendapatan.
        $this->barisNonUangSheet = [5];
    }

    public function collection(): Collection
    {
        $ringkasan = $this->laporan->ringkasan();

        return collect([
            ['no' => 1, 'label' => __('Total Pendapatan'), 'value' => $ringkasan['pendapatan']],
            ['no' => 2, 'label' => __('Pesanan Berpendapatan'), 'value' => $ringkasan['pesanan']],
            ['no' => 3, 'label' => __('Nilai Refund'), 'value' => $ringkasan['refund']],
            ['no' => 4, 'label' => __('Pengeluaran Toko'), 'value' => $ringkasan['expense']],
            ['no' => 5, 'label' => __('Total Pengeluaran'), 'value' => $ringkasan['pengeluaran']],
            ['no' => 6, 'label' => __('Total Bersih'), 'value' => $ringkasan['bersih']],
        ]);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [__('No.'), __('Metrik'), __('Nilai')];
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
        return __('Ringkasan');
    }
}
