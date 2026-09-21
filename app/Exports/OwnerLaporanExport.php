<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OwnerLaporanExport implements WithMultipleSheets
{
    public function __construct(
        protected int $storeId,
        protected int $period = 30,
    ) {
    }

    /**
     * @return array<int, object>
     */
    public function sheets(): array
    {
        return [
            new OwnerLaporanRingkasanSheet($this->storeId, $this->period),
            new OwnerLaporanPeriodeSheet($this->storeId, $this->period),
            new OwnerLaporanTopProdukSheet($this->storeId),
        ];
    }
}