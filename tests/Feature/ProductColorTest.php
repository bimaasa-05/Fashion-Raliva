<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductColorTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_detail_resolves_palette_color_case_insensitively(): void
    {
        $variant = $this->activeVariant();
        $variant->update(['warna' => 'MERAH', 'warna_hex' => '']);

        $response = $this->get(route('customer.shop.produk-detail', $variant->product_id));

        $response->assertOk();
        $response->assertSee('background-color:#c62828', false);
        $response->assertSee('MERAH');
    }

    public function test_product_detail_prefers_stored_variant_hex(): void
    {
        $variant = $this->activeVariant();
        $variant->update(['warna' => 'Tosca Elektrik', 'warna_hex' => '#123456']);

        $response = $this->get(route('customer.shop.produk-detail', $variant->product_id));

        $response->assertOk();
        $response->assertSee('background-color:#123456', false);
        $response->assertSee('Tosca Elektrik');
    }

    public function test_product_detail_hides_color_selector_without_colors(): void
    {
        $variant = $this->activeVariant();
        $variant->product->variants()->update(['warna' => null, 'warna_hex' => null]);

        $response = $this->get(route('customer.shop.produk-detail', $variant->product_id));

        $response->assertOk();
        $response->assertDontSee('id="pd-color-label"', false);
        $response->assertSee($variant->ukuran, false);
        $response->assertSee((string) $variant->product_variant_id, false);
    }

    public function test_colorless_variant_can_be_added_to_cart_and_checked_out(): void
    {
        $customer = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::CUSTOMER))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $variant = $this->activeVariant();
        $variant->update(['warna' => null, 'warna_hex' => null]);

        $this->actingAs($customer)
            ->postJson(route('customer.cart.add'), ['product_variant_id' => $variant->product_variant_id])
            ->assertOk()
            ->assertJsonPath('status', 'added');

        $response = $this->actingAs($customer)->get(route('customer.checkout'));

        $response->assertOk();
        $response->assertSee($variant->product->nama_produk, false);
        $response->assertSee($variant->ukuran, false);
    }

    private function activeVariant(): ProductVariant
    {
        $product = Product::where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($query) => $query->where('status', Store::STATUS_AKTIF))
            ->whereHas('variants', fn ($query) => $query->where('status', ProductVariant::STATUS_AKTIF))
            ->firstOrFail();

        return ProductVariant::where('product_id', $product->product_id)
            ->where('status', ProductVariant::STATUS_AKTIF)
            ->firstOrFail();
    }
}
