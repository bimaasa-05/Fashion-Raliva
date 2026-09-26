<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminQcTanggapanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pesanan_page_shows_qc_gagal_badge_and_tanggapi_action(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'flag-ui-');

        $response = $this->actingAs($admin)->get(route('admin.pesanan', ['status' => 'semua']));

        $response->assertOk();
        $response->assertSee('QC Gagal', false);
        $response->assertSee('Tanggapi', false);
        $response->assertSee("modal-qctanggapan-{$order->order_id}", false);
        $response->assertSee(route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]), false);
    }

    public function test_pesanan_filter_menunggu_qc_renders(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'flag-filter-');

        $response = $this->actingAs($admin)->get(route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC]));

        $response->assertOk();
        $response->assertSee('Menunggu QC', false);
        $response->assertSee($order->nomor_order, false);
    }

    public function test_rework_returns_order_to_produksi_and_notifies_produksi(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'flag-rework-');

        $this->actingAs($admin)->post(
            route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]),
            ['aksi' => 'rework', 'catatan' => 'Produksi ulang 2 pcs, bahan sudah siap.']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_PRODUKSI, $order->status);
        $this->assertNull($order->qc_perlu_admin_pada);
        $this->assertNull($order->qc_perlu_admin_catatan);

        $produksi = $this->userByRole(Role::PRODUKSI, $storeId);
        $this->assertTrue(
            Notification::where('user_id', $produksi->user_id)
                ->where('judul', 'QC Gagal — Produksi Ulang')
                ->where('pesan', 'like', '%' . $order->nomor_order . '%')
                ->exists(),
            'Tim Produksi se-toko harus dinotifikasi rework.'
        );
    }

    public function test_lanjut_keeps_menunggu_qc_and_notifies_produksi(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'flag-lanjut-');

        $this->actingAs($admin)->post(
            route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]),
            ['aksi' => 'lanjut']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->status);
        $this->assertNull($order->qc_perlu_admin_pada);

        $produksi = $this->userByRole(Role::PRODUKSI, $storeId);
        $this->assertTrue(
            Notification::where('user_id', $produksi->user_id)
                ->where('judul', 'QC Gagal — Silakan QC Ulang')
                ->where('pesan', 'like', '%' . $order->nomor_order . '%')
                ->exists()
        );
    }

    public function test_response_rejected_without_flag(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'flag-none-');
        $order->update(['qc_perlu_admin_pada' => null, 'qc_perlu_admin_catatan' => null]);

        $this->actingAs($admin)->post(
            route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]),
            ['aksi' => 'rework']
        )->assertStatus(302);

        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->fresh()->status, 'Tanpa penanda, rework harus ditolak.');
    }

    public function test_response_rejected_outside_store_scope(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'flag-scope-');

        $adminLain = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->whereDoesntHave('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->first();

        if (! $adminLain) {
            $this->markTestSkipped('Tidak ada Admin toko lain untuk uji scope.');
        }

        $this->actingAs($adminLain)->post(
            route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]),
            ['aksi' => 'rework']
        )->assertStatus(302);

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->status);
        $this->assertNotNull($order->qc_perlu_admin_pada, 'Admin luar scope tidak boleh menanggapi.');
    }

    public function test_reminder_command_notifies_only_after_24_hours(): void
    {
        [$admin, $storeId] = $this->admin();
        $lama = $this->orderMenungguQcFlagged($admin, $storeId, 'remind-lama-');
        $lama->update(['qc_perlu_admin_pada' => now()->subHours(25)]);
        $baru = $this->orderMenungguQcFlagged($admin, $storeId, 'remind-baru-');
        $baru->update(['qc_perlu_admin_pada' => now()->subHours(2)]);

        $this->artisan('qc:remind')->assertSuccessful();

        $this->assertTrue(
            Notification::where('judul', 'Pengingat: QC Gagal Belum Ditanggapi')
                ->where('pesan', 'like', '%' . $lama->nomor_order . '%')
                ->exists(),
            'Order >24 jam harus diingatkan.'
        );
        $this->assertFalse(
            Notification::where('judul', 'Pengingat: QC Gagal Belum Ditanggapi')
                ->where('pesan', 'like', '%' . $baru->nomor_order . '%')
                ->exists(),
            'Order <24 jam tidak boleh diingatkan.'
        );
    }

    public function test_reminder_stops_after_response(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->orderMenungguQcFlagged($admin, $storeId, 'remind-done-');
        $order->update(['qc_perlu_admin_pada' => now()->subHours(30)]);

        $this->actingAs($admin)->post(
            route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]),
            ['aksi' => 'lanjut']
        )->assertSessionHasNoErrors();

        $this->artisan('qc:remind')->assertSuccessful();

        $this->assertFalse(
            Notification::where('judul', 'Pengingat: QC Gagal Belum Ditanggapi')
                ->where('pesan', 'like', '%' . $order->nomor_order . '%')
                ->exists(),
            'Setelah ditanggapi, order tidak lagi diingatkan.'
        );
    }

    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
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

    private function orderMenungguQcFlagged(User $admin, int $storeId, string $prefix): Order
    {
        $email = $prefix . Str::random(8) . '@example.com';

        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi QC',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->variant()->product_variant_id, 'quantity' => 2],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update([
            'status' => Order::STATUS_MENUNGGU_QC,
            'qc_perlu_admin_pada' => now(),
            'qc_perlu_admin_catatan' => 'Mesin jahit rusak, menunggu konfirmasi Admin.',
        ]);

        return $order->fresh();
    }

    private function userByRole(string $role, int $storeId): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', $role))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->firstOrFail();
    }
}
