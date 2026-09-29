<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use App\Services\KaryawanReportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProduksiRekapConnectTest extends TestCase
{
    use DatabaseTransactions;

    public function test_accept_masuk_rekap_ditugaskan(): void
    {
        [$produksi, $storeId, $order] = $this->acceptedOrder('conn-acc-');

        $rekap = app(KaryawanReportService::class)->rekapProduksi($produksi->user_id, [$storeId]);

        $this->assertSame(1, $rekap['ditugaskan']);
        $this->assertSame(2, (int) $rekap['rata_unit_diminta']);
    }

    public function test_selesai_dan_qc_mengisi_metrik(): void
    {
        [$produksi, $storeId, $order] = $this->acceptedOrder('conn-done-');

        $this->flushSession();
        $this->actingAs($produksi)->post(
            route('produksi.data-produksi.status', $order->order_id),
            ['jumlah_berhasil' => 2, 'catatan' => 'Oke']
        )->assertSessionHasNoErrors();

        $this->actingAs($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', $order->order_id),
            ['jumlah_lulus' => 2]
        )->assertSessionHasNoErrors();

        $rekap = app(KaryawanReportService::class)->rekapProduksi($produksi->user_id, [$storeId]);

        $this->assertSame(1, $rekap['selesai']);
        $this->assertSame(100.0, (float) $rekap['sukses_persen']);
        $this->assertSame(2, (int) $rekap['rata_output_layak']);
        $this->assertNotNull($rekap['rata_durasi_jam']);
        $this->assertSame(1, $rekap['sampel_durasi']);
    }

    /**
     * @return array{User,int,Order}
     */
    private function acceptedOrder(string $prefix): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
        $email = $prefix.Str::random(8).'@example.com';
        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Produksi Conn',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Conn No.1',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 2],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update(['status' => Order::STATUS_DIPROSES, 'produksi_dimulai_pada' => null]);

        $produksi = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::PRODUKSI))
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $this->flushSession();
        $this->actingAs($produksi)->post(route('produksi.data-produksi.accept', $order->order_id))
            ->assertSessionHasNoErrors();

        $this->assertSame($produksi->user_id, (int) $order->fresh()->produksi_oleh);

        return [$produksi, $storeId, $order->fresh()];
    }
}
