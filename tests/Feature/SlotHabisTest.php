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

    public function test_full_quota_redirects_to_produk_with_slot_modal(): void
    {
        [$admin, $storeId] = $this->admin();
        // Simulasi kuota habis dalam transaksi (di-rollback setelah test).
        \App\Models\SlotGrant::where('store_id', $storeId)->delete();
        $this->assertFalse(SlotService::canAdd($storeId));

        $sebelum = \App\Models\Product::where('store_id', $storeId)->count();
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

        // Gate di atas validasi: input tidak hilang, tidak ada produk dibuat, popup yang dibuka.
        $response->assertRedirect(route('admin.produk', ['slot_habis' => 1]));
        $response->assertSessionHas('error');
        $response->assertSessionHasInput('nama_produk', 'Produk Slot Penuh');
        $this->assertSame($sebelum, \App\Models\Product::where('store_id', $storeId)->count());

        $page = $this->actingAs($admin)->get(route('admin.produk', ['slot_habis' => 1]));
        $page->assertOk();
        $page->assertSee('modal-slot-habis', false);
        $page->assertSee('Beli Slot', false);
        $page->assertSee('Beli Paket', false);
    }

    public function test_produk_index_shows_slot_gate_modal_when_quota_full(): void
    {
        [$admin, $storeId] = $this->admin();
        \App\Models\SlotGrant::where('store_id', $storeId)->delete();
        $this->assertFalse(SlotService::canAdd($storeId));

        $page = $this->actingAs($admin)->get(route('admin.produk'));
        $page->assertOk();
        $page->assertSee('data-modal-open="modal-slot-habis"', false);
        $page->assertSee('id="modal-slot-beli"', false);
        $page->assertDontSee('data-modal-open="modal-form-produk"', false);
    }

    public function test_produk_index_shows_create_modal_when_quota_available(): void
    {
        [$admin, $storeId] = $this->admin();
        \App\Models\SlotGrant::where('store_id', $storeId)->delete();
        SlotService::grant($storeId, 100, \App\Models\SlotGrant::TIPE_MANUAL, 'Uji kuota.');
        $this->assertTrue(SlotService::canAdd($storeId));

        $page = $this->actingAs($admin)->get(route('admin.produk'));
        $page->assertOk();
        $page->assertSee('data-modal-open="modal-form-produk"', false);
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

    public function test_slot_request_approve_grants_and_reject_grants_nothing(): void
    {
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $sebelum = SlotService::totalQuota($storeId);

        $rmt = \App\Models\SlotPurchaseRequest::create([
            'store_id' => $storeId,
            'jumlah_slot' => 10,
            'harga_per_slot' => 2000,
            'total_harga' => 20000,
            'payment_status' => \App\Models\SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI,
            'status' => \App\Models\SlotPurchaseRequest::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);

        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.slot-produk.permintaan.setujui', ['rmt' => $rmt->slot_purchase_id])
        )->assertSessionHasNoErrors();

        $this->assertSame(\App\Models\SlotPurchaseRequest::STATUS_DISETUJUI, $rmt->fresh()->status);
        $this->assertSame($sebelum + 10, SlotService::totalQuota($storeId));

        $rmt2 = \App\Models\SlotPurchaseRequest::create([
            'store_id' => $storeId,
            'jumlah_slot' => 10,
            'harga_per_slot' => 2000,
            'total_harga' => 20000,
            'payment_status' => \App\Models\SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI,
            'status' => \App\Models\SlotPurchaseRequest::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);

        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.slot-produk.permintaan.tolak', ['rmt' => $rmt2->slot_purchase_id]),
            ['alasan' => 'Bukti pembayaran tidak valid untuk pengujian.']
        )->assertSessionHasNoErrors();

        $this->assertSame(\App\Models\SlotPurchaseRequest::STATUS_DITOLAK, $rmt2->fresh()->status);
        $this->assertSame($sebelum + 10, SlotService::totalQuota($storeId));
    }

    public function test_slot_approve_is_idempotent(): void
    {
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $sebelum = SlotService::totalQuota($storeId);

        $rmt = \App\Models\SlotPurchaseRequest::create([
            'store_id' => $storeId,
            'jumlah_slot' => 7,
            'harga_per_slot' => 2000,
            'total_harga' => 14000,
            'payment_status' => \App\Models\SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI,
            'status' => \App\Models\SlotPurchaseRequest::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);
        $url = route('superadmin.slot-produk.permintaan.setujui', ['rmt' => $rmt->slot_purchase_id]);

        $this->actingAsFresh($superAdmin)->post($url)->assertSessionHasNoErrors();
        $this->actingAsFresh($superAdmin)->post($url)->assertSessionHasNoErrors();

        $this->assertSame($sebelum + 7, SlotService::totalQuota($storeId));
    }

    public function test_owner_produk_shows_slot_popup_options(): void
    {
        [$admin, $storeId] = $this->admin();
        $owner = $this->owner($storeId);

        $page = $this->actingAs($owner)->get(route('owner.produk'));
        $page->assertOk();
        $page->assertSee('data-modal-open="modal-pilih-slot"', false);
        $page->assertSee('id="modal-pilih-slot"', false);
        $page->assertSee('id="modal-kelola-slot"', false);
        $page->assertSee('Beli Paket Slot', false);
        $page->assertSee('KTP, NIB', false);
    }

    public function test_owner_popup_requires_verified_legal_document(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $owner = $this->owner($storeId);
        \App\Models\StoreDocument::where('store_id', $storeId)
            ->whereIn('jenis', ['ktp', 'siu', 'npwp'])->delete();
        $this->assertFalse(\App\Support\DokumenLegal::satisfied($storeId));

        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->firstOrFail();
        $sebelum = \App\Models\SlotPurchaseRequest::where('store_id', $storeId)->count();

        $response = $this->actingAs($owner)->post(route('owner.kelola-slot.request'), [
            'jumlah_slot' => 10,
            'metode_pembayaran' => $metode->payment_method_id,
            'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
        ]);

        $response->assertSessionHas('error');
        $this->assertSame($sebelum, \App\Models\SlotPurchaseRequest::where('store_id', $storeId)->count());
    }

    public function test_owner_popup_allows_purchase_with_verified_nib(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $owner = $this->owner($storeId);
        \App\Models\StoreDocument::where('store_id', $storeId)
            ->whereIn('jenis', ['ktp', 'siu', 'npwp'])->delete();
        \App\Models\StoreDocument::create([
            'store_id' => $storeId,
            'jenis' => 'siu', // NIB tampil sebagai Surat Izin Usaha (NIB).
            'path' => 'uji/nib.pdf',
            'status' => 'terverifikasi',
        ]);
        $this->assertTrue(\App\Support\DokumenLegal::satisfied($storeId));

        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->firstOrFail();
        $sebelum = \App\Models\SlotPurchaseRequest::where('store_id', $storeId)->count();

        $response = $this->actingAs($owner)->post(route('owner.kelola-slot.request'), [
            'jumlah_slot' => 10,
            'metode_pembayaran' => $metode->payment_method_id,
            'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $this->assertSame($sebelum + 1, \App\Models\SlotPurchaseRequest::where('store_id', $storeId)->count());
    }

    public function test_owner_paket_requires_verified_legal_document(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $owner = $this->owner($storeId);
        \App\Models\StoreDocument::where('store_id', $storeId)
            ->whereIn('jenis', ['ktp', 'siu', 'npwp'])->delete();
        $this->assertFalse(\App\Support\DokumenLegal::satisfied($storeId));

        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->firstOrFail();
        $paket = ProductSlotPackage::create([
            'nama_paket' => 'Paket Uji Legal',
            'harga' => 50000,
            'jumlah_slot' => 25,
            'durasi_hari' => 30,
            'status' => ProductSlotPackage::STATUS_AKTIF,
        ]);
        $sebelum = SlotService::totalQuota($storeId);

        $response = $this->actingAs($owner)->post(
            route('owner.paket-slot.beli', ['paket' => $paket->slot_package_id]),
            [
                'metode_pembayaran' => $metode->payment_method_id,
                'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
            ]
        );

        $response->assertSessionHas('error');
        $this->assertSame($sebelum, SlotService::totalQuota($storeId));
    }

    public function test_pengajuan_toko_success_banner_is_green(): void
    {
        [$admin, $storeId] = $this->admin();
        $owner = $this->owner($storeId);

        $page = $this->actingAs($owner)
            ->withSession(['success' => 'Pengajuan toko berhasil dikirim.'])
            ->get(route('owner.pengajuan-toko'));

        $page->assertOk();
        $page->assertSee('Pengajuan toko berhasil dikirim.', false);
        $page->assertSee('border-success/20', false);
    }

    private function owner(int $storeId): User
    {
        $ownerId = \App\Models\Store::where('store_id', $storeId)->value('owner_id');
        $owner = User::where('user_id', $ownerId)->firstOrFail();

        $this->flushSession();

        return $owner;
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    private function superAdmin(): User
    {
        $superAdmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $this->flushSession();

        return $superAdmin;
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
