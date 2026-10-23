<?php

namespace Tests\Feature;

use App\Models\Checkout;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentVerification;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Regresi hapus pengguna: soft delete tidak boleh menabrak FK transaksi
 * dan riwayat (toko, pembayaran, checkout) harus tetap tersimpan.
 */
class ManajemenPenggunaDeleteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_superadmin_soft_delete_owner_tetapi_toko_tetap_ada(): void
    {
        $superAdmin = $this->superAdmin();
        $owner = $this->createUser(Role::OWNER, 'owner-hapus');

        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Uji Hapus ' . uniqid(),
            'alamat' => 'Jl. Uji Hapus No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);

        $response = $this->actingAs($superAdmin)
            ->delete(route('superadmin.manajemen-pengguna.destroy', $owner));

        $response->assertRedirect();
        $response->assertSessionHas('toast');

        $this->assertSoftDeleted('users', ['user_id' => $owner->user_id]);
        $this->assertDatabaseHas('stores', ['store_id' => $store->store_id]);
    }

    public function test_superadmin_soft_delete_admin_verifikator_tetapi_pembayaran_tetap_ada(): void
    {
        $superAdmin = $this->superAdmin();
        $admin = $this->createUser(Role::ADMIN, 'admin-verifikator');
        $customer = $this->createUser(Role::CUSTOMER, 'customer-verifikasi');

        $checkout = Checkout::create([
            'user_id' => $customer->user_id,
            'subtotal' => 10000,
            'grand_total' => 10000,
            'status' => Checkout::STATUS_PENDING,
        ]);

        $method = PaymentMethod::query()->first() ?? PaymentMethod::create([
            'kode_metode' => 'uji-' . uniqid(),
            'nama_metode' => 'Metode Uji',
            'batas_waktu_menit' => 60,
            'status' => PaymentMethod::STATUS_AKTIF,
        ]);

        $payment = Payment::create([
            'checkout_id' => $checkout->checkout_id,
            'payment_method_id' => $method->payment_method_id,
            'jumlah' => 10000,
            'status' => Payment::STATUS_MENUNGGU_VERIFIKASI,
            'batas_waktu' => now()->addHour(),
        ]);

        $verification = PaymentVerification::create([
            'payment_id' => $payment->payment_id,
            'verifier_id' => $admin->user_id,
            'status' => PaymentVerification::STATUS_DITERIMA,
            'diverifikasi_pada' => now(),
        ]);

        $response = $this->actingAs($superAdmin)
            ->delete(route('superadmin.manajemen-pengguna.destroy', $admin));

        $response->assertRedirect();
        $response->assertSessionHas('toast');

        $this->assertSoftDeleted('users', ['user_id' => $admin->user_id]);
        $this->assertDatabaseHas('payments', ['payment_id' => $payment->payment_id]);
        $this->assertDatabaseHas('payment_verifications', [
            'payment_verification_id' => $verification->payment_verification_id,
        ]);
    }

    public function test_customer_self_delete_soft_delete_dan_checkout_tetap_ada(): void
    {
        $customer = $this->createUser(Role::CUSTOMER, 'customer-hapus');

        $checkout = Checkout::create([
            'user_id' => $customer->user_id,
            'subtotal' => 25000,
            'grand_total' => 25000,
            'status' => Checkout::STATUS_PENDING,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('customer.settings.delete-account'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertGuest();
        $this->assertSoftDeleted('users', ['user_id' => $customer->user_id]);
        $this->assertDatabaseHas('checkouts', ['checkout_id' => $checkout->checkout_id]);
    }

    public function test_superadmin_tidak_bisa_menghapus_akun_sendiri(): void
    {
        $self = $this->createUser(Role::SUPER_ADMIN, 'superadmin-kedua');

        $response = $this->actingAs($self)
            ->delete(route('superadmin.manajemen-pengguna.destroy', $self));

        $response->assertRedirect();
        $response->assertSessionHas('toast');

        $this->assertNotSoftDeleted('users', ['user_id' => $self->user_id]);
    }

    private function superAdmin(): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    private function createUser(string $role, string $prefix): User
    {
        return User::create([
            'nama_lengkap' => $prefix . ' ' . uniqid(),
            'email' => $prefix . '-' . uniqid() . '@raliva.test',
            'password' => Hash::make('password'),
            'role_id' => (int) Role::where('nama_role', $role)->value('role_id'),
            'status' => User::STATUS_AKTIF,
        ]);
    }
}
