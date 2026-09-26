<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\ProductSlotPackage;
use App\Models\Role;
use App\Models\StoreSlotSubscription;
use App\Models\StoreStaff;
use App\Models\User;
use App\Support\SlotService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SlotHabisTest extends TestCase
{
    use DatabaseTransactions;

    public function test_full_quota_redirects_to_slot_page_with_options(): void
    {
        [$admin, $storeId] = $this->admin();
        // Simulasi kuota habis dalam transaksi (di-rollback setelah test).
        \App\Models\SlotGrant::where('store_id', $storeId)->delete();
        $this->assertFalse(SlotService::canAdd($storeId));

        $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
            'nama_produk' => 'Produk Slot Penuh',
            'harga_dasar' => '100000',
            'hpp' => '50000',
            'category_id' => \App\Models\Category::where('status', 'aktif')->value('category_id'),
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji slot penuh minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => '', 'stok' => 10],
            ],
        ]);

        $response->assertRedirect(route('admin.slot', ['habis' => 1]));
        $response->assertSessionHas('error');

        $page = $this->actingAs($admin)->get(route('admin.slot', ['habis' => 1]));
        $page->assertOk();
        $page->assertSee('Slot produk habis', false);
    }

    public function test_admin_can_buy_package_instantly(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->firstOrFail();
        $paket = ProductSlotPackage::create([
            'nama_paket' => 'Paket Uji Admin',
            'harga' => 50000,
            'jumlah_slot' => 25,
            'durasi_hari' => 30,
            'status' => ProductSlotPackage::STATUS_AKTIF,
        ]);
        $sebelum = SlotService::totalQuota($storeId);

        $response = $this->actingAs($admin)->post(
            route('admin.slot.paket.beli', ['paket' => $paket->slot_package_id]),
            [
                'store_id' => $storeId,
                'metode_pembayaran' => $metode->payment_method_id,
                'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
            ]
        );

        $response->assertSessionHasNoErrors();
        $this->assertSame($sebelum + 25, SlotService::totalQuota($storeId));
        $this->assertTrue(
            StoreSlotSubscription::where('store_id', $storeId)
                ->where('slot_package_id', $paket->slot_package_id)
                ->where('status', StoreSlotSubscription::STATUS_AKTIF)
                ->exists()
        );
        $this->assertTrue(SlotService::canAdd($storeId));
    }

    public function test_admin_package_purchase_rejects_foreign_store(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->firstOrFail();
        $paket = ProductSlotPackage::create([
            'nama_paket' => 'Paket Asing Uji',
            'harga' => 50000,
            'jumlah_slot' => 25,
            'durasi_hari' => 30,
            'status' => ProductSlotPackage::STATUS_AKTIF,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.slot.paket.beli', ['paket' => $paket->slot_package_id]),
            [
                'store_id' => $storeId + 9999,
                'metode_pembayaran' => $metode->payment_method_id,
                'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
            ]
        );

        $response->assertStatus(403);
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
}
