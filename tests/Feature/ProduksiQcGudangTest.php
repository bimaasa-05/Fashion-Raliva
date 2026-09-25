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

class ProduksiQcGudangTest extends TestCase
{
    use DatabaseTransactions;

    public function test_selesai_produksi_computes_gagal_automatically(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderSiapProduksi($storeId, 2);

        $this->actingAsFresh($produksi)->post(
            route('produksi.data-produksi.status', ['order' => $order->order_id]),
            ['jumlah_berhasil' => 1, 'jumlah_gagal' => 99, 'catatan' => 'Selesai sebagian.']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->status);
        $this->assertSame(1, (int) $order->jumlah_berhasil);
        $this->assertSame(1, (int) $order->jumlah_gagal, 'Gagal harus = total − berhasil, input manual diabaikan.');
    }

    public function test_selesai_produksi_rejects_berhasil_above_total(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderSiapProduksi($storeId, 2);

        $this->actingAsFresh($produksi)->post(
            route('produksi.data-produksi.status', ['order' => $order->order_id]),
            ['jumlah_berhasil' => 9]
        )->assertStatus(302);

        $this->assertSame(Order::STATUS_DIPROSES, $order->fresh()->status);
    }

    public function test_qc_lulus_shortfall_records_kekurangan_and_notifies(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 5);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 4]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_SIAP_KIRIM, $order->status);
        $this->assertSame(4, (int) $order->jumlah_berhasil);
        $this->assertSame(1, (int) $order->jumlah_gagal);
        $this->assertSame(1, (int) $order->kekurangan_gudang);

        $gudang = $this->userByRole(Role::GUDANG, $storeId);
        $this->assertTrue(
            Notification::where('user_id', $gudang->user_id)
                ->where('judul', 'Kekurangan Produksi — Siapkan dari Gudang')
                ->where('pesan', 'like', '%'.$order->nomor_order.'%')
                ->exists()
        );

        $admin = $this->userByRole(Role::ADMIN, $storeId);
        $this->assertTrue(
            Notification::where('user_id', $admin->user_id)
                ->where('judul', 'Kekurangan Produksi Diambil dari Gudang')
                ->where('pesan', 'like', '%'.$order->nomor_order.'%')
                ->exists()
        );
    }

    public function test_qc_lulus_full_has_no_shortfall_notification(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 3);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 3]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(0, (int) $order->kekurangan_gudang);
        $this->assertFalse(
            Notification::where('pesan', 'like', '%'.$order->nomor_order.'%')
                ->where('judul', 'like', 'Kekurangan Produksi%')
                ->exists()
        );
    }

    public function test_qc_gagal_keeps_status_and_notifies_store_admin_only(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 2);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.gagal', ['order' => $order->order_id]),
            ['catatan' => 'Mesin jahit rusak, perlu teknisi dari Admin.']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->status);
        $this->assertNotNull($order->qc_perlu_admin_pada);

        $admin = $this->userByRole(Role::ADMIN, $storeId);
        $this->assertTrue(
            Notification::where('user_id', $admin->user_id)
                ->where('judul', 'QC Gagal — Perlu Tindak Lanjut')
                ->where('pesan', 'like', '%'.$order->nomor_order.'%')
                ->exists()
        );

        $adminLain = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->whereDoesntHave('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->first();
        if ($adminLain) {
            $this->assertFalse(
                Notification::where('user_id', $adminLain->user_id)
                    ->where('judul', 'QC Gagal — Perlu Tindak Lanjut')
                    ->where('pesan', 'like', '%'.$order->nomor_order.'%')
                    ->exists(),
                'Admin toko lain tidak boleh menerima notifikasi.'
            );
        } else {
            $this->markTestSkipped('Tidak ada Admin toko lain untuk uji scope.');
        }
    }

    public function test_qc_gagal_requires_catatan(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 2);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.gagal', ['order' => $order->order_id]),
            ['catatan' => 'pendek']
        )->assertSessionHasErrors('catatan');

        $this->assertNull($order->fresh()->qc_perlu_admin_pada);
    }

    public function test_data_produksi_renders_siap_kirim_filter_and_customer_note(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderSiapProduksi($storeId, 1, 'Tolong bungkus kado ya kak.');
        $order->update(['status' => Order::STATUS_SIAP_KIRIM]);

        $response = $this->actingAsFresh($produksi)->get(route('produksi.data-produksi'));

        $response->assertOk();
        $response->assertSee('value="siap_kirim"', false);
        $response->assertSee('Tolong bungkus kado ya kak.', false);
        $response->assertSee($order->nomor_order, false);
    }

    public function test_qc_page_renders_two_buttons_and_admin_badge(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 1);
        $order->update(['qc_perlu_admin_pada' => now(), 'qc_perlu_admin_catatan' => 'Perlu konfirmasi Admin toko.']);

        $response = $this->actingAsFresh($produksi)->get(route('produksi.pemeriksaan-kualitas'));

        $response->assertOk();
        $response->assertSee('Gagal — Hubungi Admin', false);
        $response->assertSee('Selesai QC + Packing', false);
        $response->assertSee('Menunggu Admin', false);
        $response->assertSee(route('produksi.pemeriksaan-kualitas.gagal', ['order' => $order->order_id]), false);
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    private function produksi(): array
    {
        $produksi = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::PRODUKSI))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $produksi->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$produksi, $storeId];
    }

    private function userByRole(string $role, int $storeId): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', $role))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->firstOrFail();
    }

    private function variant(int $storeId)
    {
        return \App\Models\ProductVariant::with('warehouseStocks')
            ->whereHas('product', fn ($q) => $q->where('store_id', $storeId))
            ->get()
            ->first(fn ($v) => (int) $v->warehouseStocks->sum('jumlah_stok') > 0)
            ?? \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
    }

    private function orderSiapProduksi(int $storeId, int $qty, ?string $catatan = null): Order
    {
        $admin = $this->userByRole(Role::ADMIN, $storeId);
        $email = 'prod-'.Str::random(8).'@example.com';
        $variantId = $this->variant($storeId)->product_variant_id;
        $items = [];
        for ($i = 0; $i < $qty; $i++) {
            $items[] = ['product_variant_id' => $variantId, 'quantity' => 1];
        }

        $this->actingAsFresh($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Produksi',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => $items,
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update([
            'status' => Order::STATUS_DIPROSES,
            'produksi_dimulai_pada' => now(),
            'catatan' => $catatan,
        ]);

        return $order->fresh();
    }

    private function orderMenungguQc(int $storeId, int $qty): Order
    {
        $order = $this->orderSiapProduksi($storeId, $qty);
        $order->update(['status' => Order::STATUS_MENUNGGU_QC, 'jumlah_berhasil' => $qty, 'jumlah_gagal' => 0]);

        return $order->fresh();
    }
}
