<?php

namespace Tests\Feature;

use App\Models\CustomerTopup;
use App\Models\CustomerWallet;
use App\Models\CustomerWalletTransaction;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Support\CustomerWalletService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regresi P0-2: approve topup idempoten — double-submit tidak
 * mengkredit ganda; tolak sesudah setuju ditolak.
 */
class TopupApproveIdempotencyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_double_approve_credits_once(): void
    {
        [$superadmin, $topup] = $this->topupSiap();

        $this->actingAs($superadmin)
            ->post(route('superadmin.verifikasi-topup.setujui', $topup->customer_topup_id))
            ->assertSessionHasNoErrors();

        $this->assertSame(CustomerTopup::STATUS_TERVERIFIKASI, $topup->fresh()->status);
        $this->assertEqualsWithDelta(50000, (float) CustomerWallet::where('user_id', $topup->user_id)->value('saldo_tersedia'), 0.01);

        // Submit ganda: ditolak ramah, saldo tetap.
        $this->actingAs($superadmin)
            ->post(route('superadmin.verifikasi-topup.setujui', $topup->customer_topup_id))
            ->assertSessionHas('toast');

        $this->assertEqualsWithDelta(50000, (float) CustomerWallet::where('user_id', $topup->user_id)->value('saldo_tersedia'), 0.01);
        $this->assertSame(1, CustomerWalletTransaction::where('customer_topup_id', $topup->customer_topup_id)
            ->where('jenis_transaksi', CustomerWalletTransaction::JENIS_TOPUP)->count());
    }

    public function test_reject_after_approve_is_refused(): void
    {
        [$superadmin, $topup] = $this->topupSiap();

        $this->actingAs($superadmin)
            ->post(route('superadmin.verifikasi-topup.setujui', $topup->customer_topup_id))
            ->assertSessionHasNoErrors();

        $this->actingAs($superadmin)
            ->post(route('superadmin.verifikasi-topup.tolak', $topup->customer_topup_id), ['alasan' => 'Alasan penolakan sahih.'])
            ->assertSessionHas('toast');

        $this->assertSame(CustomerTopup::STATUS_TERVERIFIKASI, $topup->fresh()->status);
    }

    public function test_direct_double_credit_throws(): void
    {
        [$superadmin, $topup] = $this->topupSiap();
        $user = User::whereKey($topup->user_id)->firstOrFail();

        CustomerWalletService::creditTopup($user, $topup->fresh());

        $this->expectException(\RuntimeException::class);
        CustomerWalletService::creditTopup($user, $topup->fresh());
    }

    /**
     * @return array{User, CustomerTopup}
     */
    private function topupSiap(): array
    {
        $superadmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', 'aktif')
            ->firstOrFail();

        $cust = User::create([
            'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
            'nama_lengkap' => 'Cust Topup ' . Str::random(6),
            'email' => 'topup-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        CustomerWallet::create(['user_id' => $cust->user_id, 'saldo_tersedia' => 0]);

        $payment = Payment::create([
            'checkout_id' => null,
            'jumlah' => 50000,
            'status' => Payment::STATUS_MENUNGGU_VERIFIKASI,
            'batas_waktu' => now()->addDay(),
        ]);
        $topup = CustomerTopup::create([
            'user_id' => $cust->user_id,
            'payment_id' => $payment->payment_id,
            'jumlah' => 50000,
            'status' => CustomerTopup::STATUS_MENUNGGU_VERIFIKASI,
            'batas_waktu' => now()->addDay(),
        ]);

        return [$superadmin, $topup->fresh()];
    }
}
