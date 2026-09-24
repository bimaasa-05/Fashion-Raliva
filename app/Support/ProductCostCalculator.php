<?php

namespace App\Support;

class ProductCostCalculator
{
    /**
     * @param array<int, array{jumlah_per_unit: float, biaya_per_unit: float}> $materials
     * @return array{modal_bahan: float, modal_per_unit: float, modal_batch: float, margin_per_unit: float, margin_persen: float|null}
     */
    public static function calculate(array $materials, float $overheadPerUnit, int $target, float $sellingPrice): array
    {
        $materialCost = 0.0;
        foreach ($materials as $material) {
            $materialCost += ((float) ($material['jumlah_per_unit'] ?? 0)) * ((float) ($material['biaya_per_unit'] ?? 0));
        }

        $unitCost = $materialCost + $overheadPerUnit;
        $batchCost = $unitCost * $target;
        $margin = $sellingPrice - $unitCost;

        return [
            'modal_bahan' => round($materialCost, 2),
            'modal_per_unit' => round($unitCost, 2),
            'modal_batch' => round($batchCost, 2),
            'margin_per_unit' => round($margin, 2),
            'margin_persen' => $sellingPrice > 0 ? round(($margin / $sellingPrice) * 100, 2) : null,
        ];
    }
}
