<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Models\Shipment;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminFulfillmentSwitchTest extends TestCase
{
    use DatabaseTransactions;

    public function test_diantar_order_switched_to_pickup_voids_shipping_fee(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOnlineOrder($admin);

        $checkout = $order->checkout;
        $ongkir = 50000;
        $oldGrand = (float) $order->grand_total + $ongkir;
        $order->update(['metode_fulfillment' => Order::FULFILLMENT_DIANTAR, 'total_ongkir' => $ongkir, 'grand_total' => $oldGrand]);
        $checkout->update(['total_ongkir' => $ongkir, 'grand_total' => (float) $checkout->grand_total + $ongkir]);

        $this->actingAs($admin)->post(
            route('admin.pesanan.alihFulfillment', ['pesanan' => $order->order_id]),
            ['fulfillment' => Order::FULFILLMENT_AMBIL]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $checkout->refresh();

        $this->assertSame(Order::FULFILLMENT_AMBIL, $order->metode_fulfillment);
        $this->assertSame(0, (int) $order->total_ongkir);
        $this->assertSame((int) ($oldGrand - $ongkir), (int) $order->grand_total);
        $this->assertSame(0, (int) $checkout->total_ongkir);
        $this->assertSame((int) $checkout->grand_total, (int) $order->grand_total);
        $this->assertTrue(
            Notification::where('user_id', $checkout->user_id)
                ->where('judul', 'Pesanan Diambil di Toko')
                ->exists()
        );
    }

    public function test_pickup_order_switched_back_to_delivery_keeps_totals(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOfflineOrder($admin, 'alih-off-'.Str::random(8).'@example.com');
        $order->update(['metode_fulfillment' => Order::FULFILLMENT_AMBIL]);
        $oldGrand = (int) $order->grand_total;

        $this->actingAs($admin)->post(
            route('admin.pesanan.alihFulfillment', ['pesanan' => $order->order_id]),
            ['fulfillment' => Order::FULFILLMENT_DIANTAR]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::FULFILLMENT_DIANTAR, $order->metode_fulfillment);
        $this->assertSame(0, (int) $order->total_ongkir);
        $this->assertSame($oldGrand, (int) $order->grand_total);
    }

    public function test_offline_customer_defaults_to_pickup(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOfflineOrder($admin, 'alih-mix-'.Str::random(8).'@example.com');

        $this->assertSame(Order::TIPE_PESANAN_OFFLINE, $order->tipe_pesanan);
        $this->assertSame(Order::FULFILLMENT_AMBIL, $order->metode_fulfillment);
        $this->assertTrue($order->isOffline());
        $this->assertTrue($order->isAmbil());
    }

    public function test_online_customer_defaults_to_delivery(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOnlineOrder($admin);

        $this->assertSame(Order::TIPE_PESANAN_ONLINE, $order->tipe_pesanan);
        $this->assertSame(Order::FULFILLMENT_DIANTAR, $order->metode_fulfillment);
        $this->assertFalse($order->isOffline());
        $this->assertTrue($order->isDiantar());
    }

    public function test_switch_rejected_for_dispatched_and_completed_orders(): void
    {
        foreach ([Order::STATUS_DIKIRIM, Order::STATUS_SELESAI, Order::STATUS_DIBATALKAN] as $status) {
            [$admin] = $this->admin();
            $order = $this->createOfflineOrder($admin, 'alih-blok-'.Str::random(8).'@example.com');
            $order->update(['status' => $status, 'metode_fulfillment' => Order::FULFILLMENT_AMBIL]);

            $this->actingAs($admin)->post(
                route('admin.pesanan.alihFulfillment', ['pesanan' => $order->order_id]),
                ['fulfillment' => Order::FULFILLMENT_DIANTAR]
            )->assertStatus(302);

            $this->assertSame(Order::FULFILLMENT_AMBIL, $order->fresh()->metode_fulfillment, "Status {$status} harus menolak pengalihan.");
        }
    }

    public function test_switch_rejected_when_active_shipment_exists(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOnlineOrder($admin);
        $order->update(['status' => Order::STATUS_SIAP_KIRIM, 'metode_fulfillment' => Order::FULFILLMENT_DIANTAR]);
        Shipment::create([
            'order_id' => $order->order_id,
            'nomor_resi' => 'RESI-'.Str::upper(Str::random(6)),
            'status' => Shipment::STATUS_DIPROSES,
            'ongkir' => 0,
        ]);

        $this->actingAs($admin)->post(
            route('admin.pesanan.alihFulfillment', ['pesanan' => $order->order_id]),
            ['fulfillment' => Order::FULFILLMENT_AMBIL]
        )->assertStatus(302);

        $this->assertSame(Order::FULFILLMENT_DIANTAR, $order->fresh()->metode_fulfillment);
    }

    public function test_invalid_target_rejected(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOfflineOrder($admin, 'alih-inv-'.Str::random(8).'@example.com');

        $this->actingAs($admin)->post(
            route('admin.pesanan.alihFulfillment', ['pesanan' => $order->order_id]),
            ['fulfillment' => 'kurir']
        )->assertSessionHasErrors('fulfillment');

        $this->assertSame(Order::FULFILLMENT_AMBIL, $order->fresh()->metode_fulfillment);
    }

    public function test_alihkan_button_and_modal_render_for_eligible_order(): void
    {
        [$admin] = $this->admin();
        $order = $this->createOfflineOrder($admin, 'alih-ui-'.Str::random(8).'@example.com');
        $order->update(['status' => Order::STATUS_DIPROSES]);

        $response = $this->actingAs($admin)->get(route('admin.pesanan', ['status' => 'semua']));

        $response->assertOk();
        $response->assertSee("modal-alihkan-{$order->order_id}", false);
        $response->assertSee(route('admin.pesanan.alihFulfillment', ['pesanan' => $order->order_id]), false);
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

    private function variant()
    {
        [, $storeId] = $this->admin();

        return \App\Models\ProductVariant::with('warehouseStocks')
            ->whereHas('product', fn ($q) => $q->where('store_id', $storeId))
            ->get()
            ->first(fn ($v) => (int) $v->warehouseStocks->sum('jumlah_stok') > 0)
            ?? \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
    }

    private function createOfflineOrder(User $admin, string $email): Order
    {
        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Alih',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->variant()->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        return Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
    }

    private function createOnlineOrder(User $admin): Order
    {
        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))->firstOrFail();
        $maxId = (int) Order::max('order_id');

        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'online',
            'user_id' => $customer->user_id,
            'items' => [
                ['product_variant_id' => $this->variant()->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        return Order::where('order_id', '>', $maxId)->where('tipe_pesanan', Order::TIPE_PESANAN_ONLINE)->firstOrFail();
    }
}
