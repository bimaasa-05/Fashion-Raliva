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

class AdminLaporanPesananSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    public function __construct(
        protected AdminLaporanService $laporan,
    ) {
        $this->judulSheet = 'PESANAN SELESAI';
        $this->subtitleSheet = $this->laporan->labelTokoPeriode();
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [6, 24, 14, 28, 20, 18];
        $this->kolomUangSheet = [6];
    }

    public function collection(): Collection
    {
        $rows = [];
        $total = 0.0;

        foreach ($this->laporan->pesananSelesaiSemua() as $pesanan) {
            $total += (float) $pesanan->grand_total;

            $rows[] = [
                'no' => count($rows) + 1,
                'nomor' => $pesanan->nomor_order ?? ('#'.$pesanan->order_id),
                'tanggal' => $pesanan->created_at?->translatedFormat('d M Y') ?? '-',
                'customer' => $pesanan->checkout?->user?->nama_lengkap ?? '-',
                'metode' => $pesanan->checkout?->payment?->paymentMethod?->nama_metode ?? '-',
                'total' => (float) $pesanan->grand_total,
            ];
        }

        if ($rows === []) {
            return collect([
                ['no' => 1, 'nomor' => __('Belum ada pesanan selesai pada periode ini.'), 'tanggal' => '', 'customer' => '', 'metode' => '', 'total' => 0.0],
            ]);
        }

        $rows[] = [
            'no' => '',
            'nomor' => __('Total'),
            'tanggal' => '',
            'customer' => '',
            'metode' => '',
            'total' => $total,
        ];

        return collect($rows);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [__('No.'), __('Nomor Order'), __('Tanggal'), __('Customer'), __('Metode Bayar'), __('Total')];
    }

    /**
     * @param  array{no:int|string, nomor:string, tanggal:string, customer:string, metode:string, total:float}  $row
     * @return (int|string|float)[]
     */
    public function map($row): array
    {
        return [$row['no'], $row['nomor'], $row['tanggal'], $row['customer'], $row['metode'], $row['total']];
    }

    public function title(): string
    {
        return __('Pesanan');
    }
}
