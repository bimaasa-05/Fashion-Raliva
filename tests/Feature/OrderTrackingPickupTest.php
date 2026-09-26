<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderTrackingPickupTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pickup_order_shows_takeaway_branch_without_courier(): void
    {
        [$admin, $storeId, $customer] = $this->actors();
        $order = $this->makeOrder($admin, $storeId, $customer, Order::TIPE_PESANAN_OFFLINE);

        $response = $this->actingAsFresh($customer)
            ->get(route('customer.order-tracking', ['order' => $order->order_id]));

        $response->assertOk();
        $response->assertSee('Siap Diambil', false);
        $response->assertSee('Siap diambil', false);
        $response->assertSee('Ambil di', false);
        $response->assertDontSee('Nomor Resi', false);
    }

    public function test_delivery_order_keeps_courier_branch(): void
    {
        [$admin, $storeId, $customer] = $this->actors();
        $order = $this->makeOrder($admin, $storeId, $customer, Order::TIPE_PESANAN_ONLINE);

        $response = $this->actingAsFresh($customer)
            ->get(route('customer.order-tracking', ['order' => $order->order_id]));

        $response->assertOk();
        $response->assertSee('Dikemas', false);
        $response->assertDontSee('Siap Diambil', false);
    }

    public function test_customer_refund_submission_route_is_removed(): void
    {
        $this->assertFalse(Route::has('customer.refund.store'));
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    private function actors(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');
        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $this->flushSession();

        return [$admin, $storeId, $customer];
    }

    private function makeOrder(User $admin, int $storeId, User $customer, string $tipe): Order
    {
        $variant = \App\Models\ProductVariant::with('warehouseStocks')
            ->whereHas('product', fn ($q) => $q->where('store_id', $storeId))
            ->get()
            ->first(fn ($v) => (int) $v->warehouseStocks->sum('jumlah_stok') > 0)
            ?? \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();

        $payload = [
            'tipe_pesanan' => $tipe,
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1],
            ],
        ];
        if ($tipe === Order::TIPE_PESANAN_ONLINE) {
            $payload['user_id'] = $customer->user_id;
        } else {
            $payload['nama_penerima'] = $customer->nama_lengkap;
            $payload['nomor_telepon'] = $customer->nomor_telepon ?: '081234567890';
            $payload['email_pelanggan'] = $customer->email;
            $payload['alamat'] = 'Jl. Merdeka No.1, Jakarta';
            $payload['metode_bayar'] = 'tunai';
        }

        $this->actingAsFresh($admin)->post(route('admin.pesanan.store'), $payload)->assertSessionHasNoErrors();

        $order = Order::where('store_id', $storeId)->orderByDesc('order_id')->firstOrFail();
        $order->update(['status' => Order::STATUS_SIAP_KIRIM]);

        return $order->fresh();
    }
}
