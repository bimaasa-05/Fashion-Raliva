<?php

namespace App\Exports;

use App\Services\AdminLaporanService;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AdminLaporanExport implements WithMultipleSheets
{
    public function __construct(
        protected AdminLaporanService $laporan,
    ) {
    }

    /**
     * @return array<int, object>
     */
    public function sheets(): array
    {
        return [
            new AdminLaporanRingkasanSheet($this->laporan),
            new AdminLaporanHarianSheet($this->laporan),
            new AdminLaporanMetodeSheet($this->laporan),
            new AdminLaporanPesananSheet($this->laporan),
        ];
    }
}
