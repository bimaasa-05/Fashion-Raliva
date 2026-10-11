<?php

namespace Tests\Feature;

use App\Models\CustomerWallet;
use App\Models\CustomerWithdrawal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regresi P0-4: alur penarikan 2 tahap
 * pending -> setujui -> disetujui -> tandai-dibayar -> dibayar.
 */
class WithdrawalTwoStepTest extends TestCase
{
    use DatabaseTransactions;

    public function test_full_two_step_flow(): void
    {
        [$superadmin, $wd] = $this->penarikanSiap();
        Storage::fake('public');

        $this->actingAs($superadmin)->post(
            route('superadmin.verifikasi-penarikan-saldo.setujui', $wd->customer_withdrawal_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHasNoErrors();

        $this->assertSame(CustomerWithdrawal::STATUS_DISETUJUI, $wd->fresh()->status);

        $this->actingAs($superadmin)
            ->post(route('superadmin.verifikasi-penarikan-saldo.tandai-dibayar', $wd->customer_withdrawal_id))
            ->assertSessionHasNoErrors();

        $this->assertSame(CustomerWithdrawal::STATUS_DIBAYAR, $wd->fresh()->status);
    }

    public function test_repeat_actions_are_refused(): void
    {
        [$superadmin, $wd] = $this->penarikanSiap();
        Storage::fake('public');

        $this->actingAs($superadmin)->post(
            route('superadmin.verifikasi-penarikan-saldo.setujui', $wd->customer_withdrawal_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHasNoErrors();

        // Setujui ulang + tandai di status pending (order kedua) ditolak ramah.
        $this->actingAs($superadmin)->post(
            route('superadmin.verifikasi-penarikan-saldo.setujui', $wd->customer_withdrawal_id),
            ['file_bukti' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg')]
        )->assertSessionHas('toast');

        [, $wd2] = $this->penarikanSiap();
        $this->actingAs($superadmin)
            ->post(route('superadmin.verifikasi-penarikan-saldo.tandai-dibayar', $wd2->customer_withdrawal_id))
            ->assertSessionHas('toast');

        $this->assertSame(CustomerWithdrawal::STATUS_DISETUJUI, $wd->fresh()->status);
        $this->assertSame(CustomerWithdrawal::STATUS_PENDING, $wd2->fresh()->status);
    }

    /**
     * @return array{User, CustomerWithdrawal}
     */
    private function penarikanSiap(): array
    {
        $superadmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', 'aktif')
            ->firstOrFail();

        $cust = User::create([
            'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
            'nama_lengkap' => 'Cust WD ' . Str::random(6),
            'email' => 'cust-wd-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        CustomerWallet::create(['user_id' => $cust->user_id, 'saldo_tersedia' => 200000]);

        $wd = CustomerWithdrawal::create([
            'user_id' => $cust->user_id,
            'jumlah' => 100000,
            'fee' => 0,
            'jumlah_bersih' => 100000,
            'tipe_tujuan' => 'bank',
            'nomor_tujuan' => '1234567890',
            'nama_pemilik' => 'Cust WD',
            'status' => CustomerWithdrawal::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);

        return [$superadmin, $wd->fresh()];
    }
}
