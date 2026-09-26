<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KeuanganOmzetTest extends TestCase
{
    use DatabaseTransactions;

    public function test_owner_investor_pemasukan_does_not_touch_saldo(): void
    {
        [$owner, $storeId] = $this->owner();
        $wallet = Wallet::firstOrCreate(['store_id' => $storeId], ['saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
        $awal = (float) $wallet->saldo_tersedia;

        $this->actingAs($owner)->post(route('owner.keuangan.pemasukan.store'), [
            'sumber' => 'Investor A',
            'nominal' => 1000000,
            'tanggal' => now()->toDateString(),
            'kategori' => 'Investor',
        ])->assertSessionHasNoErrors();

        $this->assertSame($awal, (float) $wallet->fresh()->saldo_tersedia);
        $this->assertTrue(
            WalletTransaction::where('wallet_id', $wallet->wallet_id)
                ->where('kategori', 'Investor')
                ->where('jumlah', 1000000)
                ->where('saldo_sebelum', $awal)
                ->where('saldo_sesudah', $awal)
                ->exists()
        );
    }

    public function test_owner_penjualan_pemasukan_credits_saldo(): void
    {
        [$owner, $storeId] = $this->owner();
        $wallet = Wallet::firstOrCreate(['store_id' => $storeId], ['saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
        $awal = (float) $wallet->saldo_tersedia;

        $this->actingAs($owner)->post(route('owner.keuangan.pemasukan.store'), [
            'sumber' => 'Kasir',
            'nominal' => 250000,
            'tanggal' => now()->toDateString(),
            'kategori' => 'Penjualan',
        ])->assertSessionHasNoErrors();

        $this->assertSame($awal + 250000, (float) $wallet->fresh()->saldo_tersedia);
    }

    public function test_admin_pengeluaran_decrements_saldo_with_journal(): void
    {
        [$admin, $storeId] = $this->admin();
        $wallet = Wallet::firstOrCreate(['store_id' => $storeId], ['saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
        $wallet->update(['saldo_tersedia' => 500000]);
        $awal = 500000.0;

        $this->actingAs($admin)->post(route('admin.transaksi.pengeluaran'), [
            'nama' => 'Listrik uji',
            'kategori' => 'Operasional',
            'nominal' => 50000,
            'tanggal' => now()->toDateString(),
        ])->assertSessionHasNoErrors();

        $this->assertSame($awal - 50000, (float) $wallet->fresh()->saldo_tersedia);
        $this->assertTrue(
            WalletTransaction::where('wallet_id', $wallet->wallet_id)
                ->where('jenis_transaksi', WalletTransaction::JENIS_PENGELUARAN)
                ->where('jumlah', 50000)
                ->exists()
        );
    }

    public function test_admin_investor_pemasukan_does_not_touch_saldo(): void
    {
        [$admin, $storeId] = $this->admin();
        $wallet = Wallet::firstOrCreate(['store_id' => $storeId], ['saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
        $awal = (float) $wallet->saldo_tersedia;

        $this->actingAs($admin)->post(route('admin.transaksi.pemasukan'), [
            'jumlah' => 750000,
            'keterangan' => 'Modal awal uji',
            'kategori' => 'Modal',
        ])->assertSessionHasNoErrors();

        $this->assertSame($awal, (float) $wallet->fresh()->saldo_tersedia);
    }

    private function owner(): array
    {
        $owner = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::OWNER))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('ownedStores')
            ->first();

        if (! $owner) {
            $this->markTestSkipped('Tidak ada Owner dengan toko untuk uji keuangan.');
        }
        $storeId = $owner->ownedStores()->value('store_id');

        $this->flushSession();

        return [$owner, $storeId];
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
