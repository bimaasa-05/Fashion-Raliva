<?php

namespace App\Exports;

use App\Exports\Traits\SheetRaliva;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OwnerLaporanTopProdukSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents, WithCustomStartCell
{
    use SheetRaliva;

    public function __construct(
        protected int $storeId,
    ) {
        $this->judulSheet = 'PRODUK TERLARIS';
        $this->subtitleSheet = '5 produk dengan penjualan tertinggi (pesanan selesai)';
        $this->barisHeaderSheet = 3;
        $this->lebarKolomSheet = [10, 36, 20];
        $this->kolomUangSheet = [];
    }

    public function collection(): Collection
    {
        $storeId = $this->storeId;

        $top = OrderItem::query()
            ->select('product_variants.product_id', DB::raw('SUM(order_items.quantity) as terjual'))
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->where('orders.store_id', $storeId)
            ->where('orders.status', 'selesai')
            ->groupBy('product_variants.product_id')
            ->orderByDesc('terjual')
            ->limit(5)
            ->with('productVariant.product')
            ->get()
            ->map(function ($oi, $i) {
                return [
                    'no' => $i + 1,
                    'nama' => $oi->productVariant?->product?->nama_produk ?? '-',
                    'terjual' => (int) $oi->terjual,
                ];
            });

        return $top->isEmpty()
            ? collect([['no' => 1, 'nama' => 'Belum ada data produk terjual.', 'terjual' => 0]])
            : $top;
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return ['No.', 'Nama Produk', 'Terjual (pcs)'];
    }

    /**
     * @param  array{no:int, nama:string, terjual:int}  $row
     * @return (int|string)[]
     */
    public function map($row): array
    {
        return [$row['no'], $row['nama'], $row['terjual']];
    }

    public function title(): string
    {
        return 'Top Produk';
    }
}