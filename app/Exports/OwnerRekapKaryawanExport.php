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

class OwnerRekapKaryawanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    /**
     * @param  array<int, array<string, int|float|string>>  $rows
     */
    public function __construct(
        protected array $rows,
        protected string $storeName,
    ) {
        $this->judulSheet = 'REKAP KARYAWAN';
        $this->subtitleSheet = 'Pendapatan & Pengeluaran per Karyawan — ' . $this->storeName;
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [8, 26, 30, 14, 14, 14, 14, 14];
        $this->kolomUangSheet = [6, 7, 8];
    }

    public function collection(): Collection
    {
        $result = [];
        foreach ($this->rows as $r) {
            $result[] = [
                'no' => count($result) + 1,
                'nama' => $r['nama'],
                'email' => $r['email'],
                'role' => $r['role'],
                'pesanan' => $r['pesanan'],
                'pendapatan' => $r['pendapatan'],
                'pengeluaran' => $r['pengeluaran'],
                'bersih' => $r['bersih'],
            ];
        }

        $result[] = [
            'no' => '',
            'nama' => 'Total Semua Karyawan',
            'email' => '',
            'role' => '',
            'pesanan' => array_sum(array_column($this->rows, 'pesanan')),
            'pendapatan' => array_sum(array_column($this->rows, 'pendapatan')),
            'pengeluaran' => array_sum(array_column($this->rows, 'pengeluaran')),
            'bersih' => array_sum(array_column($this->rows, 'bersih')),
        ];

        return collect($result);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['No.', 'Nama', 'Email', 'Role', 'Pesanan', 'Pendapatan', 'Pengeluaran', 'Bersih'];
    }

    /**
     * @param  array{no:int|string, nama:string, email:string, role:string, pesanan:int, pendapatan:float, pengeluaran:float, bersih:float}  $row
     * @return (int|string|float)[]
     */
    public function map($row): array
    {
        return [
            $row['no'],
            $row['nama'],
            $row['email'],
            $row['role'],
            $row['pesanan'],
            $row['pendapatan'],
            $row['pengeluaran'],
            $row['bersih'],
        ];
    }

    public function title(): string
    {
        return 'Rekap Karyawan';
    }
}