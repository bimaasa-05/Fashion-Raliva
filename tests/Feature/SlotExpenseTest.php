<?php

namespace Tests\Feature;

use App\Models\ProductSlotPackage;
use App\Models\Role;
use App\Models\SlotPurchaseRequest;
use App\Models\StoreExpense;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Support\SlotService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Pembelian slot (fleksibel + paket, Admin + Owner) tercatat sebagai
 * pengeluaran toko kategori "Slot" — tanpa memotong saldo wallet
 * (dibayar via transfer eksternal + bukti).
 */
class SlotExpenseTest extends TestCase
{
    use DatabaseTransactions;

    public function test_approve_flexible_creates_slot_expense_without_touching_wallet(): void
    {
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $rmt = $this->pendingRequest($storeId);

        $txSebelum = WalletTransaction::whereHas('wallet', fn ($q) => $q->where('store_id', $storeId))->count();

        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.slot-produk.permintaan.setujui', ['rmt' => $rmt->slot_purchase_id])
        )->assertSessionHasNoErrors();

        $expense = StoreExpense::where('store_id', $storeId)
            ->where('kategori', 'Slot')
            ->where('nama', 'like', '%req #' . $rmt->slot_purchase_id . '%')
            ->firstOrFail();
        $this->assertSame(20000.0, (float) $expense->nominal);
        $this->assertSame(
            $txSebelum,
            WalletTransaction::whereHas('wallet', fn ($q) => $q->where('store_id', $storeId))->count(),
            'Approve slot tidak boleh menggerakkan wallet.'
        );
    }

    public function test_approve_is_idempotent_for_expense(): void
    {
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $rmt = $this->pendingRequest($storeId);

        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.slot-produk.permintaan.setujui', ['rmt' => $rmt->slot_purchase_id])
        )->assertSessionHasNoErrors();
        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.slot-produk.permintaan.setujui', ['rmt' => $rmt->slot_purchase_id])
        );

        $this->assertSame(1, StoreExpense::where('store_id', $storeId)
            ->where('kategori', 'Slot')
            ->where('nama', 'like', '%req #' . $rmt->slot_purchase_id . '%')
            ->count());
    }

    public function test_admin_package_purchase_creates_expense_at_final_price(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $paket = $this->paket();
        $metode = \App\Models\PaymentMethod::where('status', \App\Models\PaymentMethod::STATUS_AKTIF)->firstOrFail();

        $this->actingAs($admin)->post(
            route('admin.slot.paket.beli', ['paket' => $paket->slot_package_id]),
            [
                'store_id' => $storeId,
                'metode_pembayaran' => $metode->payment_method_id,
                'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
            ]
        )->assertSessionHasNoErrors();

        $expense = StoreExpense::where('store_id', $storeId)
            ->where('kategori', 'Slot')
            ->where('nama', 'like', '%' . $paket->nama_paket . '%')
            ->firstOrFail();
        $this->assertSame(50000.0, (float) $expense->nominal);
    }

    public function test_owner_package_purchase_creates_expense(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $owner = $this->owner($storeId);
        \App\Models\StoreDocument::where('store_id', $storeId)
            ->whereIn('jenis', ['ktp', 'siu', 'npwp'])->delete();
        \App\Models\StoreDocument::create([
            'store_id' => $storeId,
            'jenis' => 'siu',
            'path' => 'uji/nib.pdf',
            'status' => 'terverifikasi',
        ]);
        $paket = $this->paket();
        $metode = \App\Models\PaymentMethod::where('status', \App\Models\PaymentMethod::STATUS_AKTIF)->firstOrFail();

        $this->actingAs($owner)->post(
            route('owner.paket-slot.beli', ['paket' => $paket->slot_package_id]),
            [
                'metode_pembayaran' => $metode->payment_method_id,
                'file_bukti' => UploadedFile::fake()->image('bukti.jpg', 600, 800),
            ]
        )->assertSessionHasNoErrors();

        $this->assertTrue(StoreExpense::where('store_id', $storeId)
            ->where('kategori', 'Slot')
            ->where('nama', 'like', '%' . $paket->nama_paket . '%')
            ->exists());
    }

    public function test_manual_grant_creates_no_expense(): void
    {
        [$admin, $storeId] = $this->admin();
        $sebelum = StoreExpense::where('store_id', $storeId)->where('kategori', 'Slot')->count();

        SlotService::grant($storeId, 5, \App\Models\SlotGrant::TIPE_MANUAL, 'Uji grant manual.');

        $this->assertSame($sebelum, StoreExpense::where('store_id', $storeId)->where('kategori', 'Slot')->count());
    }

    private function pendingRequest(int $storeId): SlotPurchaseRequest
    {
        return SlotPurchaseRequest::create([
            'store_id' => $storeId,
            'jumlah_slot' => 10,
            'harga_per_slot' => 2000,
            'total_harga' => 20000,
            'payment_status' => SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI,
            'status' => SlotPurchaseRequest::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);
    }

    private function paket(): ProductSlotPackage
    {
        return ProductSlotPackage::create([
            'nama_paket' => 'Paket Uji Expense ' . uniqid(),
            'harga' => 50000,
            'jumlah_slot' => 25,
            'durasi_hari' => 30,
            'status' => ProductSlotPackage::STATUS_AKTIF,
        ]);
    }

    /**
     * @return array{User, int}
     */
    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('user_id')
            ->firstOrFail();
        $storeId = (int) StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$admin, $storeId];
    }

    private function superAdmin(): User
    {
        $superAdmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('user_id')
            ->firstOrFail();

        $this->flushSession();

        return $superAdmin;
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
}
