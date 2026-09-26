<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderPriorityCancelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_menunggu_produksi_sorts_above_newer_updated_orders(): void
    {
        [$admin] = $this->admin();
        $variant = $this->variant();
        $lama = $this->createOfflineOrder($admin, $variant, 'prioritas-lama-'.Str::random(8).'@example.com');
        $baru = $this->createOfflineOrder($admin, $variant, 'prioritas-baru-'.Str::random(8).'@example.com');
        $lama->update(['status' => Order::STATUS_MENUNGGU_PRODUKSI, 'updated_at' => now()->subDays(9)]);
        $baru->update(['status' => Order::STATUS_DIPROSES, 'updated_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.pesanan', ['status' => 'semua']));
        $html = $response->getContent();

        $response->assertOk();
        // Ukur urutan baris tabel (data-nomor), bukan posisi mentah di HTML:
        // dropdown notifikasi header juga memuat nomor pesanan terbaru.
        $posLama = strpos($html, 'data-nomor="' . $lama->nomor_order . '"');
        $posBaru = strpos($html, 'data-nomor="' . $baru->nomor_order . '"');
        $this->assertTrue(
            $posLama !== false && $posBaru !== false && $posLama < $posBaru,
            'Pesanan menunggu produksi harus tampil di atas pesanan yang lebih baru diupdate.'
        );
    }

    public function test_verified_cash_order_has_no_cancel_route(): void
    {
        [$admin] = $this->admin();
        $email = 'batal-'.Str::random(8).'@example.com';
        $variant = $this->variant();

        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Batal',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $this->assertTrue($order->isPaymentVerified());

        $this->assertFalse(Route::has('admin.pesanan.batalkan'));
        $this->assertSame(Order::STATUS_DIBAYAR, $order->fresh()->status);
    }

    public function test_rejected_transfer_keeps_order_pending_for_reupload(): void
    {
        Storage::fake('public');
        [$admin] = $this->admin();
        $email = 'batal-ok-'.Str::random(8).'@example.com';
        $variant = $this->variant();
        $rekening = \App\Models\PlatformBankAccount::where('status', \App\Models\PlatformBankAccount::STATUS_AKTIF)->firstOrFail();

        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Tolak',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'transfer',
            'payment_account_id' => $rekening->platform_bank_account_id,
            'bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $payment = $order->checkout->payment;
        $this->assertSame(\App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI, $payment->status);

        $this->actingAs($admin)->post(
            route('admin.verifikasi-pembayaran.tolak', ['pembayaran' => $payment->payment_id]),
            ['alasan' => 'Bukti pembayaran tidak valid untuk pengujian.']
        )->assertSessionHasNoErrors();

        $this->assertSame(\App\Models\Payment::STATUS_DITOLAK, $payment->fresh()->status);
        $this->assertSame(Order::STATUS_PENDING_PAYMENT, $order->fresh()->status);
    }

    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$admin, $storeId];
    }

    private function createOfflineOrder(User $admin, $variant, string $email): Order
    {
        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Prioritas',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        return Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
    }

    private function variant()
    {
        [$admin, $storeId] = $this->admin();

        return \App\Models\ProductVariant::with('warehouseStocks')
            ->whereHas('product', fn ($q) => $q->where('store_id', $storeId))
            ->get()
            ->first(fn ($v) => (int) $v->warehouseStocks->sum('jumlah_stok') > 0)
            ?? \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
    }
}
