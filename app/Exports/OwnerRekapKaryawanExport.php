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
     * @param  array<int, array<string, int|float|string|null>>  $rows
     * @param  array<string, int|float|string|null>  $totals
     */
    public function __construct(
        protected array $rows,
        protected string $storeName,
        protected string $role = 'semua',
        protected array $totals = [],
    ) {
        $judul = match ($this->role) {
            'owner' => 'REKAP KARYAWAN — OWNER',
            'admin' => 'REKAP KARYAWAN — ADMIN',
            'produksi' => 'REKAP KARYAWAN — PRODUKSI',
            default => 'REKAP KARYAWAN — GUDANG',
        };
        $this->judulSheet = $judul;
        $this->subtitleSheet = 'Peran & KPI per Karyawan — ' . $this->storeName;
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = $this->role === 'admin' ? [8, 26, 30, 14, 14, 14, 14, 14] : [8, 26, 30, 14, 14, 14, 14];
        $this->kolomUangSheet = match ($this->role) {
            'admin' => [5, 6],
            'owner' => [4, 5, 6],
            default => [],
        };
    }

    public function collection(): Collection
    {
        $result = [];
        foreach ($this->rows as $r) {
            $result[] = array_merge(['no' => count($result) + 1], $this->petakanBaris($r));
        }

        $result[] = array_merge(['no' => ''], $this->petakanTotal());

        return collect($result);
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return match ($this->role) {
            'owner' => ['No.', 'Nama', 'Email', 'ROI (%)', 'Pendapatan', 'Investasi', 'Bersih'],
            'admin' => ['No.', 'Nama', 'Email', 'CR (%)', 'AOV', 'LTV', 'Rating', 'Pesanan'],
            'produksi' => ['No.', 'Nama', 'Email', 'Ditugaskan', 'Rata2 Unit', 'Rata2 Durasi (jam)', 'Berhasil (%)'],
            default => ['No.', 'Nama', 'Email', 'Transfer', 'Rata2 Putaran (jam)', 'Akurasi (%)', 'Rusak (qty)'],
        };
    }

    /**
     * @param  array<string, int|float|string|null>  $row
     * @return (int|string|float|null)[]
     */
    public function map($row): array
    {
        return [
            $row['no'],
            $row['nama'],
            $row['email'],
            $row['kolom1'],
            $row['kolom2'],
            $row['kolom3'],
            $row['kolom4'],
            $row['kolom5'] ?? null,
        ];
    }

    public function title(): string
    {
        return 'Rekap Karyawan';
    }

    /**
     * @param  array<string, int|float|string|null>  $r
     * @return array<string, int|float|string|null>
     */
    private function petakanBaris(array $r): array
    {
        return match ($this->role) {
            'owner' => [
                'nama' => $r['nama'],
                'email' => $r['email'],
                'kolom1' => $r['roi'],
                'kolom2' => $r['pendapatan'],
                'kolom3' => $r['investasi'],
                'kolom4' => $r['bersih'],
            ],
            'admin' => [
                'nama' => $r['nama'],
                'email' => $r['email'],
                'kolom1' => $r['cr'],
                'kolom2' => $r['aov'],
                'kolom3' => $r['ltv'],
                'kolom4' => $r['rating'],
                'kolom5' => $r['pesanan'],
            ],
            'produksi' => [
                'nama' => $r['nama'],
                'email' => $r['email'],
                'kolom1' => $r['ditugaskan'],
                'kolom2' => $r['rata_unit_diminta'],
                'kolom3' => $r['rata_durasi_jam'],
                'kolom4' => $r['sukses_persen'],
            ],
            default => [
                'nama' => $r['nama'],
                'email' => $r['email'],
                'kolom1' => $r['transfer_diminta'],
                'kolom2' => $r['rata_putaran_jam'],
                'kolom3' => $r['akurasi_persen'],
                'kolom4' => $r['kerusakan_qty'],
            ],
        };
    }

    /**
     * @return array<string, int|float|string|null>
     */
    private function petakanTotal(): array
    {
        $t = $this->totals;

        return match ($this->role) {
            'owner' => [
                'nama' => 'Total',
                'email' => '',
                'kolom1' => $t['roi'] ?? null,
                'kolom2' => $t['pendapatan'] ?? 0,
                'kolom3' => $t['investasi'] ?? 0,
                'kolom4' => $t['bersih'] ?? 0,
            ],
            'admin' => [
                'nama' => 'Total',
                'email' => '',
                'kolom1' => $t['cr'] ?? null,
                'kolom2' => $t['aov'] ?? null,
                'kolom3' => $t['ltv'] ?? null,
                'kolom4' => $t['rating'] ?? null,
                'kolom5' => $t['pesanan'] ?? 0,
            ],
            'produksi' => [
                'nama' => 'Total',
                'email' => '',
                'kolom1' => $t['ditugaskan'] ?? 0,
                'kolom2' => null,
                'kolom3' => null,
                'kolom4' => $t['sukses_persen'] ?? null,
            ],
            default => [
                'nama' => 'Total',
                'email' => '',
                'kolom1' => $t['transfer_diminta'] ?? 0,
                'kolom2' => null,
                'kolom3' => $t['akurasi_persen'] ?? null,
                'kolom4' => $t['kerusakan_qty'] ?? 0,
            ],
        };
    }
}
