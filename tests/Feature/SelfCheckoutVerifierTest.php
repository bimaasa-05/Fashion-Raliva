<?php

namespace Tests\Feature;

use App\Http\Controllers\Customer\CheckoutController;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SelfCheckoutVerifierTest extends TestCase
{
    use DatabaseTransactions;

    public function test_self_checkout_falls_back_to_store_admin(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('user_id')
            ->firstOrFail();
        $storeId = (int) $admin->storeAssignments()->where('status', 'aktif')->orderBy('store_id')->value('store_id');

        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $method = new \ReflectionMethod(CheckoutController::class, 'rekapVerifierId');

        $verifier = $method->invoke(null, $customer, $storeId);
        $verifierUser = User::find($verifier);
        $this->assertNotNull($verifierUser);
        $this->assertSame(Role::ADMIN, $verifierUser->role->nama_role);
        $this->assertTrue(
            $verifierUser->storeAssignments()->where('store_id', $storeId)->where('status', 'aktif')->exists()
        );
    }
}
