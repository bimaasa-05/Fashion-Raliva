<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Opsi pengiriman (config). Default terpilih: Express (Rp 35.000).
     */
    protected const SHIPPING_OPTIONS = [
        ['kode' => 'regular', 'nama' => 'Regular Delivery', 'estimasi' => '3-5 Business Days', 'ongkir' => 0],
        ['kode' => 'express', 'nama' => 'Express Delivery', 'estimasi' => '1-2 Business Days', 'ongkir' => 35000],
    ];

    /**
     * Tampilkan halaman checkout dengan alamat pengiriman customer.
     */
    public function index()
    {
        $address = Auth::user()->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('updated_at', 'desc')
            ->first();

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['user_id' => Auth::id(), 'status' => Cart::STATUS_AKTIF]
        );

        $buyId = (int) request()->query('buy', 0);

        if ($buyId > 0) {
            $variant = ProductVariant::with([
                'product' => fn ($p) => $p->with([
                    'store:store_id,nama_toko,logo',
                    'images' => fn ($img) => $img->orderBy('urutan'),
                ]),
            ])->find($buyId);

            if ($variant) {
                $buyItem = new CartItem([
                    'product_variant_id' => $variant->product_variant_id,
                    'quantity' => 1,
                    'harga_snapshot' => $variant->harga,
                ]);
                $buyItem->setRelation('productVariant', $variant);

                $items = collect([$buyItem]);
            }
        }

        if (!isset($items) || $items->isEmpty()) {
            $items = $cart->items()
                ->with([
                    'productVariant' => fn ($q) => $q->with([
                        'product' => fn ($p) => $p->with([
                            'store:store_id,nama_toko,logo',
                            'images' => fn ($img) => $img->orderBy('urutan'),
                        ]),
                    ]),
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $count = $items->sum('quantity');
        $subtotal = $items->sum(fn ($i) => $i->quantity * $i->harga_snapshot);

        $shippingOptions = self::SHIPPING_OPTIONS;
        $shipping = 35000;
        $tax = 0;
        $total = $subtotal + $shipping;

        $paymentMethods = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)
            ->orderBy('payment_method_id')
            ->get();

        return view('customer.checkout.index', compact(
            'address',
            'items',
            'count',
            'subtotal',
            'shippingOptions',
            'shipping',
            'tax',
            'total',
            'paymentMethods'
        ));
    }
}