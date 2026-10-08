<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regresi P0-5: receive menciptakan baris stok baru bila varian
 * belum terdaftar di gudang tujuan (bukan error/garbage).
 */
class PemindahanReceiveTest extends TestCase
{
    use DatabaseTransactions;

    public function test_receive_creates_missing_stock_row(): void
    {
        [$gudang, $transfer, $variant] = $this->transferSiap();

        $this->assertNull(WarehouseStock::where('warehouse_id', $transfer->to_warehouse_id)
            ->where('product_variant_id', $variant)
            ->first());

        $this->actingAs($gudang)
            ->post(route('gudang.pemindahan.receive', $transfer->stock_transfer_id))
            ->assertSessionHasNoErrors();

        $row = WarehouseStock::where('warehouse_id', $transfer->to_warehouse_id)
            ->where('product_variant_id', $variant)
            ->firstOrFail();
        $this->assertSame(7, (int) $row->jumlah_stok);
        $this->assertSame(StockTransfer::STATUS_RECEIVED, $transfer->fresh()->status);
    }

    /**
     * @return array{User, StockTransfer, int}
     */
    private function transferSiap(): array
    {
        $prefix = 'recv-';
        $owner = User::create([
            'role_id' => Role::where('nama_role', Role::OWNER)->value('role_id'),
            'nama_lengkap' => 'Owner Tr ' . $prefix,
            'email' => 'owner-tr-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Tr ' . $prefix,
            'alamat' => 'Jl. Tr No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $gudang = User::create([
            'role_id' => Role::where('nama_role', Role::GUDANG)->value('role_id'),
            'nama_lengkap' => 'Gudang Tr ' . $prefix,
            'email' => 'gudang-tr-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        StoreStaff::create(['store_id' => $store->store_id, 'user_id' => $gudang->user_id, 'status' => 'aktif']);

        $asal = Warehouse::create(['store_id' => $store->store_id, 'nama_gudang' => 'WH Asal ' . $prefix, 'alamat' => 'Jl. A', 'status' => Warehouse::STATUS_AKTIF]);
        $tujuan = Warehouse::create(['store_id' => $store->store_id, 'nama_gudang' => 'WH Tujuan ' . $prefix, 'alamat' => 'Jl. B', 'status' => Warehouse::STATUS_AKTIF]);

        // User ditugaskan ke gudang TUJUAN (penerima).
        \App\Models\WarehouseStaff::create([
            'warehouse_id' => $tujuan->warehouse_id,
            'user_id' => $gudang->user_id,
            'tanggal_penugasan' => now(),
            'status' => 'aktif',
        ]);

        $product = Product::create([
            'store_id' => $store->store_id,
            'nama_produk' => 'Produk Tr ' . $prefix,
            'harga_dasar' => 50000,
            'status' => 'aktif',
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->product_id,
            'sku' => 'TR-' . uniqid(),
            'ukuran' => 'M',
            'harga' => 50000,
            'status' => 'aktif',
        ])->product_variant_id;

        WarehouseStock::create([
            'warehouse_id' => $asal->warehouse_id,
            'product_variant_id' => $variant,
            'jumlah_stok' => 20,
        ]);

        $transfer = StockTransfer::create([
            'from_warehouse_id' => $asal->warehouse_id,
            'to_warehouse_id' => $tujuan->warehouse_id,
            'requested_by' => $gudang->user_id,
            'status' => StockTransfer::STATUS_APPROVED,
            'diminta_pada' => now(),
        ]);
        StockTransferItem::create([
            'stock_transfer_id' => $transfer->stock_transfer_id,
            'product_variant_id' => $variant,
            'jumlah' => 7,
        ]);

        return [$gudang, $transfer->fresh(), $variant];
    }
}
