<?php

namespace Tests\Unit;

use App\Support\ProductCostCalculator;
use PHPUnit\Framework\TestCase;

class ProductCostCalculatorTest extends TestCase
{
    public function test_calculates_batch_cost_and_margin(): void
    {
        $result = ProductCostCalculator::calculate(
            [
                ['jumlah_per_unit' => 2, 'biaya_per_unit' => 5000],
                ['jumlah_per_unit' => 1, 'biaya_per_unit' => 3000],
            ],
            2000,
            10,
            25000
        );

        $this->assertSame(13000.0, $result['modal_bahan']);
        $this->assertSame(15000.0, $result['modal_per_unit']);
        $this->assertSame(150000.0, $result['modal_batch']);
        $this->assertSame(10000.0, $result['margin_per_unit']);
        $this->assertSame(40.0, $result['margin_persen']);
    }

    public function test_zero_selling_price_has_no_margin_percentage(): void
    {
        $result = ProductCostCalculator::calculate(
            [['jumlah_per_unit' => 1, 'biaya_per_unit' => 1000]],
            0,
            1,
            0
        );

        $this->assertSame(1000.0, $result['modal_per_unit']);
        $this->assertNull($result['margin_persen']);
    }
}
