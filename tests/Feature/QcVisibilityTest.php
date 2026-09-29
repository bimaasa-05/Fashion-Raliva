<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class QcVisibilityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tanggapi_modal_shows_production_numbers(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->flaggedOrder($admin, $storeId, 'vis-angka-');
        $order->update(['jumlah_berhasil' => 1, 'jumlah_gagal' => 1]);

        $res = $this->actingAs($admin)->get(route('admin.pesanan', ['status' => 'semua']));
        $res->assertOk();
        $res->assertSee('Berhasil produksi:', false);
        $res->assertSee('Gagal produksi:', false);
        $res->assertSee('pcs', false);
    }

    public function test_qc_response_persists_admin_note(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->flaggedOrder($admin, $storeId, 'vis-note-');

        $this->actingAs($admin)->post(
            route('admin.pesanan.qcTanggapan', ['pesanan' => $order->order_id]),
            ['aksi' => 'rework', 'catatan' => 'Ulangi jahitan lengan kiri.']
        )->assertSessionHasNoErrors();

        $this->assertSame(
            'Ulangi jahitan lengan kiri.',
            $order->fresh()->qc_admin_catatan
        );
    }

    public function test_admin_detail_shows_production_qc_row(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->flaggedOrder($admin, $storeId, 'vis-detail-');
        $order->update(['jumlah_berhasil' => 2, 'jumlah_gagal' => 0]);

        $res = $this->actingAs($admin)->get(route('admin.pesanan', ['status' => 'semua']));
        $res->assertOk();
        $res->assertSee('Hasil Produksi/QC', false);
    }

    public function test_produksi_table_shows_pcs_and_admin_note(): void
    {
        [$admin, $storeId] = $this->admin();
        $order = $this->flaggedOrder($admin, $storeId, 'vis-prod-');
        $order->update(['jumlah_berhasil' => 1, 'jumlah_gagal' => 1, 'qc_admin_catatan' => 'Cek ulang obras.']);

        $produksi = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::PRODUKSI))
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $this->flushSession();
        $res = $this->actingAs($produksi)->get(route('produksi.pemeriksaan-kualitas'));
        $res->assertOk();
        $res->assertSee('pcs berhasil', false);
        $res->assertSee('Catatan Admin: Cek ulang obras.', false);
    }

    public function test_selesai_modal_shows_total_above_input(): void
    {
        [$admin, $storeId] = $this->admin();

        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
        $email = 'vis-accept-'.Str::random(8).'@example.com';
        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'QC Accept',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Accept No.1',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 3],
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

        $res = $this->actingAs($produksi)->get(route('produksi.data-produksi'));
        $res->assertOk();
        $html = $res->getContent();
        $posTotal = strpos($html, 'Total pesanan:');
        $this->assertNotFalse($posTotal, 'Total pesanan harus tampil di modal selesai.');
        $posInput = strpos($html, 'name="jumlah_berhasil"');
        $this->assertNotFalse($posInput, 'Input jumlah_berhasil harus ada.');
        $this->assertLessThan($posInput, $posTotal, 'Total pcs harus di atas input jumlah_berhasil.');
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

    private function flaggedOrder(User $admin, int $storeId, string $prefix): Order
    {
        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
        $email = $prefix.Str::random(8).'@example.com';

        $this->actingAs($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'QC Vis',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Vis No.1',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $variant->product_variant_id, 'quantity' => 2],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update([
            'status' => Order::STATUS_MENUNGGU_QC,
            'qc_perlu_admin_pada' => now(),
            'qc_perlu_admin_catatan' => 'Jahitan lepas di lengan.',
        ]);

        return $order->fresh();
    }
}
