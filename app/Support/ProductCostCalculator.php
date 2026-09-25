<?php

namespace App\Support;

class ProductCostCalculator
{
    /**
     * @param array<int, array{jumlah_per_unit: float, biaya_per_unit: float}> $materials
     * @param array<int, array{nominal: float}|float> $operational
     * @return array{modal_bahan: float, biaya_operasional: float, modal_per_unit: float, modal_batch: float, margin_per_unit: float, margin_persen: float|null}
     */
    public static function calculate(array $materials, float $overheadPerUnit, int $target, float $sellingPrice, array $operational = []): array
    {
        $materialCost = 0.0;
        foreach ($materials as $material) {
            $materialCost += ((float) ($material['jumlah_per_unit'] ?? 0)) * ((float) ($material['biaya_per_unit'] ?? 0));
        }

        $operationalCost = $overheadPerUnit;
        foreach ($operational as $item) {
            $operationalCost += (float) (is_array($item) ? ($item['nominal'] ?? 0) : $item);
        }

        $unitCost = $materialCost + $operationalCost;
        $batchCost = $unitCost * $target;
        $margin = $sellingPrice - $unitCost;

        return [
            'modal_bahan' => round($materialCost, 2),
            'biaya_operasional' => round($operationalCost, 2),
            'modal_per_unit' => round($unitCost, 2),
            'modal_batch' => round($batchCost, 2),
            'margin_per_unit' => round($margin, 2),
            'margin_persen' => $sellingPrice > 0 ? round(($margin / $sellingPrice) * 100, 2) : null,
        ];
    }
}
