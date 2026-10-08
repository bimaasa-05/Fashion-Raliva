<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\QualityCheck;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regresi P0-1: jumlah_lulus QC tidak boleh melebihi output produksi
 * yang tercatat (jumlah_berhasil) — mencegah klaim fiktif.
 */
class QcLulusBoundTest extends TestCase
{
    use DatabaseTransactions;

    public function test_qc_rejects_lulus_above_production_output(): void
    {
        [$produksi, $storeId] = $this->produksi();
        // Order 10 pcs, produksi hanya menghasilkan 8.
        $order = $this->orderMenungguQc($storeId, 10, 8, 0);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 9, 'catatan' => 'Klaim lebih.']
        )->assertSessionHas('toast');

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->status);
        $this->assertSame(8, (int) $order->jumlah_berhasil);
        $this->assertSame(0, QualityCheck::where('order_id', $order->order_id)->count());
    }

    public function test_qc_accepts_lulus_within_production_output(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 10, 8, 0);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 8, 'catatan' => 'Sesuai output.']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_SIAP_KIRIM, $order->status);
        $this->assertSame(1, QualityCheck::where('order_id', $order->order_id)->count());
    }

    private function orderMenungguQc(int $storeId, int $qty, int $prodOk, int $prodFail): Order
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->firstOrFail();
        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
        $email = 'qc-bound-' . Str::random(8) . '@example.com';
        $items = [];
        for ($i = 0; $i < $qty; $i++) {
            $items[] = ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1];
        }

        $this->actingAsFresh($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'QC Bound',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Bound No.1',
            'metode_bayar' => 'tunai',
            'items' => $items,
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update([
            'status' => Order::STATUS_MENUNGGU_QC,
            'jumlah_berhasil' => $prodOk,
            'jumlah_gagal' => $prodFail,
        ]);

        return $order->fresh();
    }

    /**
     * @return array{User, int}
     */
    private function produksi(): array
    {
        $produksi = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::PRODUKSI))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $storeId = (int) StoreStaff::where('user_id', $produksi->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$produksi, $storeId];
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }
}
