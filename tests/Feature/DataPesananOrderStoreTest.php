<?php

namespace Tests\Feature;

use App\Models\Checkout;
use App\Models\Commission;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use App\Support\OrderAutoComplete;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class DataPesananOrderStoreTest extends TestCase
{
    use DatabaseTransactions;

    private array $fixture = [];

    private function setupAdminStoreVariant(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->firstOrFail();

        $storeId = StoreStaff::where('user_id', $admin->user_id)
            ->where('status', 'aktif')
            ->value('store_id');

        $variants = ProductVariant::with('warehouseStocks')
            ->whereHas('product', fn ($q) => $q->where('store_id', $storeId))
            ->get();

        $variant = $variants->first(fn ($v) => (int) $v->warehouseStocks->sum('jumlah_stok') > 0)
            ?? $variants->first();

        $this->fixture = compact('admin', 'storeId', 'variant');

        $this->flushSession();
        $this->actingAs($admin);
    }

    public function test_offline_order_created_without_user_id(): void
    {
        $this->setupAdminStoreVariant();
        $email = 'offline-'.Str::random(8).'@example.com';

        $res = $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'fulfillment' => 'ambil',
            'user_id' => '',
            'nama_penerima' => 'Budi Offline',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasNoErrors();

        $checkout = Checkout::where('email_pelanggan', $email)->first();
        $this->assertNotNull($checkout);
        $this->assertDatabaseHas('orders', [
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $this->fixture['storeId'],
        ]);
    }

    public function test_online_order_created_without_receiver_name(): void
    {
        $this->setupAdminStoreVariant();
        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))->firstOrFail();

        $res = $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'online',
            'fulfillment' => 'diantar',
            'user_id' => $customer->user_id,
            'nama_penerima' => '',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasNoErrors();

        $checkout = Checkout::where('user_id', $customer->user_id)->orderByDesc('checkout_id')->first();
        $this->assertNotNull($checkout);
        $this->assertDatabaseHas('orders', [
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $this->fixture['storeId'],
        ]);
    }

    public function test_online_order_without_customer_is_rejected(): void
    {
        $this->setupAdminStoreVariant();

        $res = $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'online',
            'fulfillment' => 'diantar',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasErrors('user_id');
    }

    public function test_offline_cash_order_gets_status_baru(): void
    {
        $this->setupAdminStoreVariant();
        $email = 'offline-cash-'.Str::random(8).'@example.com';

        $res = $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'fulfillment' => 'ambil',
            'user_id' => '',
            'nama_penerima' => 'Budi Cash',
            'nomor_telepon' => '081298765432',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Sudirman No.7, Bandung',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))
            ->firstOrFail();

        $this->assertNotNull($order->checkout);
        $this->assertSame(\App\Models\Checkout::STATUS_DIBAYAR, $order->checkout->status);
        $this->assertSame(Order::STATUS_DIBAYAR, $order->status);
    }

    public function test_proses_from_baru_moves_to_diproses(): void
    {
        $this->setupAdminStoreVariant();
        $email = 'offline-proses-'.Str::random(8).'@example.com';

        $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'fulfillment' => 'ambil',
            'user_id' => '',
            'nama_penerima' => 'Budi Proses',
            'nomor_telepon' => '081277788899',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Gatot Subroto No.3, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))
            ->firstOrFail();
        $this->assertSame(Order::STATUS_DIBAYAR, $order->status);

        $res = $this->post(route('admin.pesanan.proses', ['pesanan' => $order->order_id]), [
            'bahan' => [
                ['bahan_id' => null, 'nama_bahan' => 'Kain Katun', 'jumlah' => 1, 'satuan' => 'meter', 'catatan' => ''],
            ],
            'tgl_mulai_produksi' => now()->toDateString(),
            'tgl_berakhir_produksi' => now()->addDay()->toDateString(),
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_DIPROSES, $order->status);
    }

    public function test_offline_order_stores_tipe_pesanan_offline(): void
    {
        $this->setupAdminStoreVariant();
        $email = 'offline-tipe-'.Str::random(8).'@example.com';

        $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'fulfillment' => 'ambil',
            'user_id' => '',
            'nama_penerima' => 'Budi Tipe',
            'nomor_telepon' => '081222233344',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Diponegoro No.2, Yogyakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))
            ->firstOrFail();

        $this->assertSame(Order::TIPE_PESANAN_OFFLINE, $order->tipe_pesanan);
        $this->assertTrue($order->isOffline());
    }

    public function test_online_order_stores_tipe_pesanan_online(): void
    {
        $this->setupAdminStoreVariant();
        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))->firstOrFail();
        $email = 'online-tipe-'.Str::random(8).'@example.com';

        $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'online',
            'fulfillment' => 'diantar',
            'user_id' => $customer->user_id,
            'email_pelanggan' => $email,
            'nama_penerima' => '',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))
            ->firstOrFail();

        $this->assertSame(Order::TIPE_PESANAN_ONLINE, $order->tipe_pesanan);
        $this->assertFalse($order->isOffline());
    }

    private function createOfflineSiapKirimOrder(string $email): Order
    {
        $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'fulfillment' => 'ambil',
            'user_id' => '',
            'nama_penerima' => 'Budi Selesai',
            'nomor_telepon' => '081299988877',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Malioboro No.5, Yogyakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))
            ->firstOrFail();
        $order->update(['status' => Order::STATUS_SIAP_KIRIM]);

        return $order->fresh();
    }

    public function test_offline_siap_kirim_order_finished_by_admin(): void
    {
        $this->setupAdminStoreVariant();
        $order = $this->createOfflineSiapKirimOrder('offline-selesai-'.Str::random(8).'@example.com');

        $res = $this->post(route('admin.pesanan.selesai', ['pesanan' => $order->order_id]), [
            'catatan' => 'Sudah diambil di kasir.',
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasNoErrors();
        $res->assertSessionHas('toast.message', "Pesanan {$order->nomor_order} diselesaikan.");

        $order->refresh();
        $this->assertSame(Order::STATUS_SELESAI, $order->status);
        $this->assertNotNull($order->diambil_pada);

        $this->assertTrue(Commission::where('order_id', $order->order_id)
            ->where('status', Commission::STATUS_AKTIF)
            ->exists());
    }

    public function test_online_order_cannot_be_finished_directly(): void
    {
        $this->setupAdminStoreVariant();
        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))->firstOrFail();
        $email = 'online-selesai-'.Str::random(8).'@example.com';

        $this->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'online',
            'fulfillment' => 'diantar',
            'user_id' => $customer->user_id,
            'email_pelanggan' => $email,
            'nama_penerima' => '',
            'items' => [
                ['product_variant_id' => $this->fixture['variant']->product_variant_id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))
            ->firstOrFail();
        $order->update(['status' => Order::STATUS_SIAP_KIRIM]);

        $res = $this->post(route('admin.pesanan.selesai', ['pesanan' => $order->order_id]));
        $res->assertStatus(302);

        $order->refresh();
        $this->assertSame(Order::STATUS_SIAP_KIRIM, $order->status);
    }

    public function test_offline_non_siap_kirim_order_cannot_be_finished(): void
    {
        $this->setupAdminStoreVariant();
        $order = $this->createOfflineSiapKirimOrder('offline-blm-siap-'.Str::random(8).'@example.com');
        $order->update(['status' => Order::STATUS_DIBAYAR]);

        $res = $this->post(route('admin.pesanan.selesai', ['pesanan' => $order->order_id]));
        $res->assertStatus(302);

        $order->refresh();
        $this->assertSame(Order::STATUS_DIBAYAR, $order->status);
    }

    public function test_offline_siap_kirim_older_than_three_days_auto_completed(): void
    {
        $this->setupAdminStoreVariant();
        $order = $this->createOfflineSiapKirimOrder('offline-auto-'.Str::random(8).'@example.com');

        Order::whereKey($order->order_id)->update([
            'updated_at' => now()->subDays(4),
        ]);

        $count = OrderAutoComplete::selesaikanOtomatis();

        $order->refresh();
        $this->assertSame(Order::STATUS_SELESAI, $order->status);
        $this->assertGreaterThanOrEqual(1, $count);
        $this->assertTrue(Commission::where('order_id', $order->order_id)
            ->where('status', Commission::STATUS_AKTIF)
            ->exists());
    }

    public function test_offline_siap_kirim_recent_not_auto_completed(): void
    {
        $this->setupAdminStoreVariant();
        $order = $this->createOfflineSiapKirimOrder('offline-baru-'.Str::random(8).'@example.com');

        OrderAutoComplete::selesaikanOtomatis();

        $order->refresh();
        $this->assertSame(Order::STATUS_SIAP_KIRIM, $order->status);

        $this->assertFalse(Commission::where('order_id', $order->order_id)
            ->where('status', Commission::STATUS_AKTIF)
            ->exists());
    }
}