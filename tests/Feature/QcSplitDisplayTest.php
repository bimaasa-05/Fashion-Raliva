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
 * Split "Hasil Produksi" vs "Hasil QC": snapshot produksi dipertahankan,
 * angka QC tercatat terpisah + tampil berdampingan dengan Catatan —
 * termasuk jalur Gagal (tak lagi "-").
 */
class QcSplitDisplayTest extends TestCase
{
    use DatabaseTransactions;

    public function test_qc_store_snapshots_production_and_records_qc_split(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 10, 10, 0);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 8, 'catatan' => 'Ada 2 pcs cacat jahit.']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_SIAP_KIRIM, $order->status);
        // Kompatibilitas alur shortage lama tetap dijaga.
        $this->assertSame(8, (int) $order->jumlah_berhasil);
        $this->assertSame(2, (int) $order->jumlah_gagal);
        // Split baru: produksi vs QC.
        $this->assertSame(10, (int) $order->hasil_produksi_berhasil);
        $this->assertSame(0, (int) $order->hasil_produksi_gagal);
        $this->assertSame(8, (int) $order->hasil_qc_lulus);
        $this->assertSame(2, (int) $order->hasil_qc_gagal);

        // Selesai → tab siap menampilkan kedua angka + catatan.
        $this->actingAsFresh($produksi)->get(route('produksi.pemeriksaan-kualitas', ['tab' => 'siap']))
            ->assertOk()
            ->assertSee('10/10 pcs berhasil', false)
            ->assertSee('8 lulus', false)
            ->assertSee('Ada 2 pcs cacat jahit.', false);
    }

    public function test_tandai_gagal_records_qc_numbers_and_note_without_changing_status(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 6, 6, 0);

        $this->actingAsFresh($produksi)->post(
            route('produksi.pemeriksaan-kualitas.gagal', ['order' => $order->order_id]),
            ['catatan' => 'Semua pcs gagal obras parah.']
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_MENUNGGU_QC, $order->status);
        $this->assertNotNull($order->qc_perlu_admin_pada);
        $this->assertSame(6, (int) $order->hasil_produksi_berhasil);
        $this->assertSame(0, (int) $order->hasil_qc_lulus);
        $this->assertSame(6, (int) $order->hasil_qc_gagal);

        // Kembali ke halaman: angka + catatan tampil (bukan "-").
        $this->actingAsFresh($produksi)->get(route('produksi.pemeriksaan-kualitas'))
            ->assertOk()
            ->assertSee('Menunggu Admin', false)
            ->assertSee('0 lulus', false)
            ->assertSee('6 gagal', false)
            ->assertSee('Semua pcs gagal obras parah.', false);
    }

    public function test_legacy_qc_row_still_displays_via_fallback(): void
    {
        [$produksi, $storeId] = $this->produksi();
        $order = $this->orderMenungguQc($storeId, 4, 4, 0);
        QualityCheck::create([
            'order_id' => $order->order_id,
            'checked_by' => $produksi->user_id,
            'jumlah_lulus' => 3,
            'jumlah_gagal' => 1,
            'status' => QualityCheck::STATUS_SEBAGIAN,
            'catatan' => 'Catatan lawas.',
            'diperiksa_pada' => now(),
        ]);
        $order->update([
            'status' => Order::STATUS_SIAP_KIRIM,
            'jumlah_berhasil' => 3,
            'jumlah_gagal' => 1,
            'tanggal_qc' => now(),
        ]);

        $this->actingAsFresh($produksi)->get(route('produksi.produk-selesai'))
            ->assertOk()
            ->assertSee('Catatan QC', false)
            ->assertSee('Catatan lawas.', false);
    }

    private function orderMenungguQc(int $storeId, int $qty, int $prodOk, int $prodFail): Order
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->firstOrFail();
        $variant = \App\Models\ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
        $email = 'qc-split-' . Str::random(8) . '@example.com';
        $items = [];
        for ($i = 0; $i < $qty; $i++) {
            $items[] = ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1];
        }

        $this->actingAsFresh($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'QC Split',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Split No.1',
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
