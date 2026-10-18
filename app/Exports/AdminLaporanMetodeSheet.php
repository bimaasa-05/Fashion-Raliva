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
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AdminLaporanMetodeSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva {
        registerEvents as registerEventsRaliva;
    }

    public function __construct(
        protected AdminLaporanService $laporan,
    ) {
        $this->judulSheet = 'METODE PEMBAYARAN';
        $this->subtitleSheet = $this->laporan->labelTokoPeriode();
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [6, 32, 16, 24];
        $this->kolomUangSheet = [4];
    }

    public function registerEvents(): array
    {
        $gayaBawaan = $this->registerEventsRaliva()[AfterSheet::class] ?? null;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($gayaBawaan) {
                if ($gayaBawaan) {
                    $gayaBawaan($event);
                }

                // Kolom Transaksi = jumlah transaksi (bukan uang): format angka biasa, rata kanan.
                $sheet = $event->sheet->getDelegate();
                $barisTerakhir = $sheet->getHighestRow();
                for ($baris = $this->barisHeaderSheet + 1; $baris <= $barisTerakhir; $baris++) {
                    $sheet->getStyle("C{$baris}")->getNumberFormat()->setFormatCode('0');
                    $sheet->getStyle("C{$baris}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
            },
        ];
    }

    public function collection(): Collection
    {
        $rows = [];
        $totalTransaksi = 0;
        $total = 0.0;

        foreach ($this->laporan->perMetode() as $metode) {
            $totalTransaksi += (int) $metode->jumlah_transaksi;
            $total += (float) $metode->total;

            $rows[] = [
                'no' => count($rows) + 1,
                'metode' => $metode->nama_metode,
                'transaksi' => (int) $metode->jumlah_transaksi,
                'total' => (float) $metode->total,
            ];
        }

        $rows[] = [
            'no' => '',
            'metode' => __('Total'),
            'transaksi' => $totalTransaksi,
            'total' => $total,
        ];

        return collect($rows);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [__('No.'), __('Metode'), __('Transaksi'), __('Total')];
    }

    /**
     * @param  array{no:int|string, metode:string, transaksi:int, total:float}  $row
     * @return (int|string|float)[]
     */
    public function map($row): array
    {
        return [$row['no'], $row['metode'], $row['transaksi'], $row['total']];
    }

    public function title(): string
    {
        return __('Metode');
    }
}
