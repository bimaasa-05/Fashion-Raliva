<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Role;
use App\Models\ShippingService;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Pesanan offline buatan Admin: bisa pilih Ambil (default) atau Diantar
 * (kurir + layanan + ongkir masuk grand total); tombol Selesai (Diambil)
 * di Pengiriman harus membuka modal.
 */
class AdminOfflineFulfillmentTest extends TestCase
{
    use DatabaseTransactions;

    public function test_offline_defaults_to_ambil_with_zero_ongkir(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->buatOffline($admin, $storeId, []);

        $this->assertSame(Order::FULFILLMENT_AMBIL, $order->metode_fulfillment);
        $this->assertSame(0, (int) $order->total_ongkir);
    }

    public function test_offline_diantar_sets_tarif_into_grand_total(): void
    {
        [$admin, $storeId] = $this->admin();
        [$courier, $service] = $this->kurir(15000);
        $order = $this->buatOffline($admin, $storeId, [
            'fulfillment' => 'diantar',
            'courier_id' => $courier->courier_id,
            'shipping_service_id' => $service->shipping_service_id,
        ]);

        $this->assertSame(Order::FULFILLMENT_DIANTAR, $order->metode_fulfillment);
        $this->assertSame(15000, (int) $order->total_ongkir);
        $this->assertSame(15000, (int) $order->checkout->total_ongkir);
        $this->assertSame(
            (int) $order->subtotal + (int) $order->total_pajak + (int) $order->biaya_layanan + 15000,
            (int) $order->grand_total
        );
    }

    public function test_offline_diantar_rejects_inactive_courier(): void
    {
        [$admin, $storeId] = $this->admin();
        $courier = Courier::create([
            'nama_kurir' => 'Kurir Mati ' . uniqid(),
            'kode_kurir' => 'mati-' . uniqid(),
            'status' => 'nonaktif',
        ]);
        $email = 'ful-tolak-' . Str::random(8) . '@example.com';
        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();

        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Tolak Kurir',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Tolak No.1',
            'metode_bayar' => 'tunai',
            'fulfillment' => 'diantar',
            'courier_id' => $courier->courier_id,
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1],
            ],
        ]);

        $this->assertFalse(Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->exists());
    }

    public function test_pengiriman_renders_ambil_modal(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->buatOffline($admin, $storeId, []);
        $order->update(['status' => Order::STATUS_SIAP_KIRIM]);

        $this->actingAsFresh($admin)->get(route('admin.pengiriman'))
            ->assertOk()
            ->assertSee('modal-selesai-ambil-' . $order->order_id, false);
    }

    public function test_pengiriman_renders_resi_form_for_diantar(): void
    {
        [$admin, $storeId] = $this->admin();
        [$courier, $service] = $this->kurir(12000);
        $order = $this->buatOffline($admin, $storeId, [
            'fulfillment' => 'diantar',
            'courier_id' => $courier->courier_id,
            'shipping_service_id' => $service->shipping_service_id,
        ]);
        $order->update(['status' => Order::STATUS_SIAP_KIRIM]);

        $this->actingAsFresh($admin)->get(route('admin.pengiriman'))
            ->assertOk()
            ->assertSee('form-resi-' . $order->order_id, false);
    }

    /**
     * @return array{Courier, ShippingService}
     */
    private function kurir(int $tarif): array
    {
        $courier = Courier::create([
            'nama_kurir' => 'Kurir Uji ' . uniqid(),
            'kode_kurir' => 'uji-' . uniqid(),
            'status' => 'aktif',
        ]);
        $service = ShippingService::create([
            'courier_id' => $courier->courier_id,
            'nama_layanan' => 'Reg Uji',
            'estimasi_hari' => '2-3',
            'tarif' => $tarif,
            'status' => 'aktif',
        ]);

        return [$courier, $service];
    }

    private function buatOffline(User $admin, int $storeId, array $extra): Order
    {
        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
        $email = 'ful-' . Str::random(8) . '@example.com';

        $this->actingAs($admin)->post(route('admin.pesanan.store'), array_merge([
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Penerima Uji',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Uji No.1',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 2],
            ],
        ], $extra))->assertSessionHasNoErrors();

        return Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
    }

    /**
     * @return array{User, int}
     */
    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('user_id')
            ->firstOrFail();
        $storeId = (int) StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$admin, $storeId];
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }
}
