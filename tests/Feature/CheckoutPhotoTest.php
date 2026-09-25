<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CheckoutPhotoTest extends TestCase
{
    use DatabaseTransactions;

    public function test_direct_checkout_uses_public_storage_photo_url(): void
    {
        $customer = $this->customer();
        $variant = $this->variantWithPhoto('products/checkout-photo-test.jpg');

        $response = $this->actingAs($customer)
            ->get(route('customer.checkout', ['buy' => $variant->product_variant_id]));

        $response->assertOk();
        $response->assertSee('storage/products/checkout-photo-test.jpg', false);
    }

    public function test_cart_checkout_uses_public_storage_photo_url(): void
    {
        $customer = $this->customer();
        $variant = $this->variantWithPhoto('products/checkout-cart-photo-test.jpg');

        $cart = Cart::firstOrCreate(
            ['user_id' => $customer->user_id],
            ['user_id' => $customer->user_id, 'status' => Cart::STATUS_AKTIF]
        );
        $cart->items()->delete();
        $cart->items()->create([
            'product_variant_id' => $variant->product_variant_id,
            'quantity' => 1,
            'harga_snapshot' => $variant->harga,
        ]);

        $response = $this->actingAs($customer)->get(route('customer.checkout'));

        $response->assertOk();
        $response->assertSee('storage/products/checkout-cart-photo-test.jpg', false);
    }

    public function test_checkout_keeps_absolute_photo_url(): void
    {
        $customer = $this->customer();
        $variant = $this->variantWithPhoto('https://example.com/checkout-photo-test.jpg');

        $response = $this->actingAs($customer)
            ->get(route('customer.checkout', ['buy' => $variant->product_variant_id]));

        $response->assertOk();
        $response->assertSee('https://example.com/checkout-photo-test.jpg', false);
    }

    public function test_checkout_without_photo_uses_placeholder(): void
    {
        $customer = $this->customer();
        $variant = ProductVariant::whereHas('product.images')
            ->with('product.images')
            ->firstOrFail();
        $variant->product->images()->delete();

        $response = $this->actingAs($customer)
            ->get(route('customer.checkout', ['buy' => $variant->product_variant_id]));

        $response->assertOk();
        $response->assertSee('https://picsum.photos/seed/checkout/600/800', false);
    }

    private function customer(): User
    {
        return User::whereHas('role', fn ($query) => $query->where('nama_role', Role::CUSTOMER))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    private function variantWithPhoto(string $path): ProductVariant
    {
        $variant = ProductVariant::whereHas('product.images')
            ->with(['product.images' => fn ($query) => $query->orderBy('urutan')])
            ->firstOrFail();
        $variant->product->images->firstOrFail()->update(['file_gambar' => $path]);

        return $variant;
    }
}
