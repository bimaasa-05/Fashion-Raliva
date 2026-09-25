<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class OrderTrackingPickupTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pickup_order_shows_takeaway_branch_without_courier(): void
    {
        $customer = $this->customerWithOfflineOrder();
        $order = $this->offlineOrder($customer, Order::STATUS_SIAP_KIRIM);

        $response = $this->actingAs($customer)
            ->get(route('customer.order-tracking', ['order' => $order->order_id]));

        $response->assertOk();
        $response->assertSee('Siap Diambil', false);
        $response->assertSee('Siap diambil', false);
        $response->assertSee('Ambil di', false);
        $response->assertDontSee('Nomor Resi', false);
    }

    public function test_delivery_order_keeps_courier_branch(): void
    {
        $customer = $this->customerWithOfflineOrder();
        $order = $this->offlineOrder($customer, Order::STATUS_SIAP_KIRIM);
        $order->update(['tipe_pesanan' => Order::TIPE_PESANAN_ONLINE]);

        $response = $this->actingAs($customer)
            ->get(route('customer.order-tracking', ['order' => $order->order_id]));

        $response->assertOk();
        $response->assertSee('Dikemas', false);
        $response->assertDontSee('Siap Diambil', false);
    }

    public function test_customer_refund_submission_route_is_removed(): void
    {
        $this->assertFalse(Route::has('customer.refund.store'));
    }

    private function customerWithOfflineOrder(): User
    {
        return User::whereHas('role', fn ($query) => $query->where('nama_role', Role::CUSTOMER))
            ->whereHas('orders', fn ($query) => $query->where('tipe_pesanan', Order::TIPE_PESANAN_OFFLINE))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    private function offlineOrder(User $customer, string $status): Order
    {
        $order = $customer->orders()
            ->where('tipe_pesanan', Order::TIPE_PESANAN_OFFLINE)
            ->with('store')
            ->firstOrFail();
        $order->update(['status' => $status]);

        return $order->fresh();
    }
}
