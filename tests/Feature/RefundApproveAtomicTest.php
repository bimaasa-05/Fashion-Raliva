<?php

namespace Tests\Feature;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Refund;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regresi P0-3: setujui refund atomik — gagal complete() tidak
 * memacetkan refund di DISETUJUI; retry tetap bisa jalan.
 */
class RefundApproveAtomicTest extends TestCase
{
    use DatabaseTransactions;

    public function test_failed_complete_keeps_requested_and_retry_succeeds(): void
    {
        [$superadmin, $refund, $wallet] = $this->refundSiap(0);
        Storage::fake('public');

        // Saldo toko 0 -> gagal ramah, status tetap REQUESTED (bisa retry).
        $this->actingAs($superadmin)->post(
            route('superadmin.pengembalian-dana.setujui', $refund->refund_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHas('toast');

        $this->assertSame(Refund::STATUS_REQUESTED, $refund->fresh()->status);
        $this->assertEqualsWithDelta(0, (float) $wallet->fresh()->saldo_tersedia, 0.01);

        // Danai wallet -> retry sukses sampai SELESAI.
        $wallet->update(['saldo_tersedia' => 1000000]);
        $this->actingAs($superadmin)->post(
            route('superadmin.pengembalian-dana.setujui', $refund->refund_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHasNoErrors();

        $refund->refresh();
        $this->assertSame(Refund::STATUS_SELESAI, $refund->status);
        $this->assertEqualsWithDelta(1000000 - 150000, (float) $wallet->fresh()->saldo_tersedia, 0.01);
    }

    public function test_double_approve_is_refused(): void
    {
        [$superadmin, $refund, $wallet] = $this->refundSiap(1000000);
        Storage::fake('public');

        $this->actingAs($superadmin)->post(
            route('superadmin.pengembalian-dana.setujui', $refund->refund_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHasNoErrors();

        $this->actingAs($superadmin)->post(
            route('superadmin.pengembalian-dana.setujui', $refund->refund_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHas('toast');

        $this->assertSame(Refund::STATUS_SELESAI, $refund->fresh()->status);
    }

    /**
     * @return array{User, Refund, Wallet}
     */
    private function refundSiap(float $saldo): array
    {
        $superadmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', 'aktif')
            ->firstOrFail();

        $owner = User::create([
            'role_id' => Role::where('nama_role', Role::OWNER)->value('role_id'),
            'nama_lengkap' => 'Owner Ref ' . Str::random(6),
            'email' => 'owner-ref-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Ref ' . Str::random(6),
            'alamat' => 'Jl. Ref No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $wallet = Wallet::create(['store_id' => $store->store_id, 'saldo_tersedia' => $saldo, 'saldo_tertahan' => 0]);

        $cust = User::create([
            'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
            'nama_lengkap' => 'Cust Ref ' . Str::random(6),
            'email' => 'cust-ref-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 150000,
            'grand_total' => 150000,
            'status' => 'dibayar',
        ]);
        $product = Product::create([
            'store_id' => $store->store_id,
            'nama_produk' => 'Produk Ref ' . Str::random(6),
            'harga_dasar' => 150000,
            'status' => 'aktif',
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->product_id,
            'sku' => 'REF-' . uniqid(),
            'ukuran' => 'M',
            'harga' => 150000,
            'status' => 'aktif',
        ]);
        $order = Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'REF-' . strtoupper(Str::random(8)),
            'status' => Order::STATUS_SELESAI,
            'subtotal' => 150000,
            'grand_total' => 150000,
        ]);
        OrderItem::create([
            'order_id' => $order->order_id, 'product_variant_id' => $variant->product_variant_id,
            'nama_produk_snapshot' => 'Produk Ref', 'harga_snapshot' => 150000, 'quantity' => 1, 'subtotal' => 150000, 'total' => 150000,
        ]);
        $payment = \App\Models\Payment::create([
            'checkout_id' => $checkout->checkout_id,
            'jumlah' => 150000,
            'status' => \App\Models\Payment::STATUS_TERVERIFIKASI,
            'batas_waktu' => now()->addDay(),
        ]);
        $refund = Refund::create([
            'order_id' => $order->order_id,
            'payment_id' => $payment->payment_id,
            'requested_by' => $cust->user_id,
            'tipe_refund' => Refund::TIPE_FULL,
            'alasan' => 'Barang cacat.',
            'jumlah' => 150000,
            'status' => Refund::STATUS_REQUESTED,
            'diajukan_pada' => now(),
        ]);

        return [$superadmin, $refund->fresh(), $wallet->fresh()];
    }
}
