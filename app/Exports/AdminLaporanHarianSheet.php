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

class AdminLaporanHarianSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    public function __construct(
        protected AdminLaporanService $laporan,
    ) {
        $this->judulSheet = 'RINCIAN HARIAN';
        $this->subtitleSheet = __('Rekap pendapatan, refund, dan pengeluaran per hari — :ph1', ['ph1' => $this->laporan->labelPeriode()]);
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [20, 12, 18, 18, 18, 18];
        $this->kolomUangSheet = [3, 4, 5, 6];
    }

    public function collection(): Collection
    {
        $rows = [];
        foreach ($this->laporan->harian() as $harian) {
            $rows[] = [
                'tanggal' => $harian['tanggal']->translatedFormat('d M Y'),
                'pesanan' => $harian['pesanan'],
                'pendapatan' => $harian['pendapatan'],
                'refund' => $harian['refund'],
                'expense' => $harian['expense'],
                'bersih' => $harian['bersih'],
            ];
        }

        $ringkasan = $this->laporan->ringkasan();
        $rows[] = [
            'tanggal' => __('Total'),
            'pesanan' => $ringkasan['pesanan'],
            'pendapatan' => $ringkasan['pendapatan'],
            'refund' => $ringkasan['refund'],
            'expense' => $ringkasan['expense'],
            'bersih' => $ringkasan['bersih'],
        ];

        return collect($rows);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [__('Tanggal'), __('Pesanan'), __('Pendapatan'), __('Refund'), __('Pengeluaran'), __('Bersih')];
    }

    /**
     * @param  array{tanggal:string, pesanan:int, pendapatan:float, refund:float, expense:float, bersih:float}  $row
     * @return (int|string|float)[]
     */
    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['pesanan'],
            $row['pendapatan'],
            $row['refund'],
            $row['expense'],
            $row['bersih'],
        ];
    }

    public function title(): string
    {
        return __('Harian');
    }
}
