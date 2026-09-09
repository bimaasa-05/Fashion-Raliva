<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Checkout;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'paymentMethods',
            'buyId'
        ));
    }

    /**
     * Buat Checkout + Order per toko + OrderItem + Payment, lalu kosongkan keranjang.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,payment_method_id',
            'shipping' => 'required|numeric|in:0,35000',
            'address_id' => 'nullable|integer',
            'buy' => 'nullable|integer',
        ], [
            'payment_method_id.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'shipping.required' => 'Pilih metode pengiriman terlebih dahulu.',
        ]);

        $paymentMethod = PaymentMethod::where('payment_method_id', $validated['payment_method_id'])
            ->where('status', PaymentMethod::STATUS_AKTIF)
            ->first();

        if (! $paymentMethod) {
            return back()->with('toast', ['message' => 'Metode pembayaran tidak tersedia.', 'icon' => 'gpp_maybe']);
        }

        $address = $request->filled('address_id')
            ? Auth::user()->addresses()->find($validated['address_id'])
            : Auth::user()->addresses()->orderBy('is_default', 'desc')->orderBy('updated_at', 'desc')->first();

        if (! $address) {
            return back()->with('toast', ['message' => 'Tambahkan alamat pengiriman terlebih dahulu.', 'icon' => 'gpp_maybe']);
        }

        [$items, $fromCart] = $this->resolveItems($validated['buy'] ?? 0);

        if ($items->isEmpty()) {
            return back()->with('toast', ['message' => 'Tidak ada item untuk dipesan. Keranjang kosong.', 'icon' => 'gpp_maybe']);
        }

        $shipping = (int) $validated['shipping'];
        $subtotal = $items->sum(fn ($i) => $i['quantity'] * $i['harga']);

        [$checkout, $orders] = DB::transaction(function () use ($items, $subtotal, $shipping, $paymentMethod, $fromCart) {
            $checkout = Checkout::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => $shipping,
                'grand_total' => $subtotal + $shipping,
                'status' => Checkout::STATUS_PENDING,
            ]);

            $orders = [];
            $byStore = $items->groupBy(fn ($i) => $i['store_id']);

            $byStore->each(function ($group, $storeId) use ($checkout, $shipping, $subtotal, &$orders) {
                $storeSubtotal = $group->sum(fn ($i) => $i['quantity'] * $i['harga']);
                $storeShipping = $subtotal > 0
                    ? (int) round($shipping * ($storeSubtotal / $subtotal))
                    : 0;

                $order = Order::create([
                    'checkout_id' => $checkout->checkout_id,
                    'store_id' => (int) $storeId,
                    'nomor_order' => 'RLV-'.$storeId.'-'.strtoupper(substr(md5(uniqid()), 0, 6)),
                    'subtotal' => $storeSubtotal,
                    'total_diskon' => 0,
                    'total_pajak' => 0,
                    'biaya_layanan' => 0,
                    'total_ongkir' => $storeShipping,
                    'grand_total' => $storeSubtotal + $storeShipping,
                    'status' => Order::STATUS_PENDING_PAYMENT,
                    'tipe_order' => Order::TIPE_PRODUK_TETAP,
                ]);

                foreach ($group as $item) {
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_variant_id' => $item['variant_id'],
                        'nama_produk_snapshot' => $item['nama_produk'],
                        'harga_snapshot' => $item['harga'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['quantity'] * $item['harga'],
                        'diskon' => 0,
                        'total' => $item['quantity'] * $item['harga'],
                    ]);
                }

                $orders[] = $order;
            });

            Payment::create([
                'checkout_id' => $checkout->checkout_id,
                'payment_method_id' => $paymentMethod->payment_method_id,
                'jumlah' => $subtotal + $shipping,
                'status' => Payment::STATUS_PENDING,
                'batas_waktu' => now()->addMinutes($paymentMethod->batas_waktu_menit > 0 ? $paymentMethod->batas_waktu_menit : 1440),
            ]);

            if ($fromCart) {
                Auth::user()->cart?->items()->delete();
            }

            return [$checkout, $orders];
        });

        Notification::create([
            'user_id' => Auth::id(),
            'tipe' => Notification::TIPE_ORDER,
            'judul' => 'Pesanan Dibuat',
            'pesan' => sprintf('Pesanan %s berhasil dibuat. Silakan selesaikan pembayaran.', $orders[0]->nomor_order),
        ]);

        $seenOwners = [];
        foreach ($orders as $order) {
            $ownerId = $order->store?->owner_id;
            if ($ownerId && ! in_array($ownerId, $seenOwners, true)) {
                $seenOwners[] = $ownerId;
                Notification::create([
                    'user_id' => $ownerId,
                    'tipe' => Notification::TIPE_ORDER,
                    'judul' => 'Pesanan Baru',
                    'pesan' => sprintf(
                        'Pesanan %s masuk untuk toko Anda. Total belanja Rp %s.',
                        $order->nomor_order,
                        number_format((float) $order->grand_total, 0, ',', '.')
                    ),
                ]);
            }
        }

        return redirect()->route('customer.checkout.payment', $checkout->checkout_id)
            ->with('toast', ['message' => 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.', 'icon' => 'task_alt']);
    }

    /**
     * Halaman detail pembayaran + unggah bukti bayar.
     */
    public function payment(int $checkout)
    {
        $checkout = Checkout::where('checkout_id', $checkout)
            ->where('user_id', Auth::id())
            ->with(['orders.store:store_id,nama_toko', 'payment.paymentMethod'])
            ->firstOrFail();

        $payment = $checkout->payment;

        return view('customer.checkout.payment', compact('checkout', 'payment'));
    }

    /**
     * Unggah bukti transfer / pembayaran dari customer.
     */
    public function uploadProof(Request $request, int $checkout)
    {
        $checkout = Checkout::where('checkout_id', $checkout)
            ->where('user_id', Auth::id())
            ->with('payment')
            ->firstOrFail();

        $payment = $checkout->payment;

        if (! in_array($payment->status, [Payment::STATUS_PENDING, Payment::STATUS_DITOLAK], true)) {
            return back()->with('toast', ['message' => 'Bukti pembayaran sudah diproses.', 'icon' => 'gpp_maybe']);
        }

        $validated = $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'bukti.required' => 'Pilih file bukti pembayaran.',
            'bukti.image' => 'File harus berupa gambar.',
        ]);

        $fileName = 'bukti-'.$checkout->checkout_id.'-'.time().'.'.$validated['bukti']->extension();
        $path = $validated['bukti']->storeAs('payment_proofs', $fileName, 'public');

        PaymentProof::create([
            'payment_id' => $payment->payment_id,
            'file_bukti' => $path,
            'uploaded_at' => now(),
        ]);

        $payment->update(['status' => Payment::STATUS_MENUNGGU_VERIFIKASI]);

        Notification::create([
            'user_id' => Auth::id(),
            'tipe' => Notification::TIPE_PEMBAYARAN,
            'judul' => 'Bukti Pembayaran Diunggah',
            'pesan' => 'Bukti pembayaran Anda sedang diverifikasi oleh admin.',
        ]);

        return redirect()->route('customer.order-tracking')
            ->with('toast', ['message' => 'Bukti pembayaran diunggah. Menunggu verifikasi admin.', 'icon' => 'task_alt']);
    }

    /**
     * Resolusi item checkout: buy-now (1 varian) atau keranjang.
     *
     * @return array{0: \Illuminate\Support\Collection, 1: bool}
     */
    protected function resolveItems(int $buyId = 0): array
    {
        if ($buyId > 0) {
            $variant = ProductVariant::with([
                'product' => fn ($p) => $p->where('status', Product::STATUS_AKTIF)
                    ->with(['store' => fn ($s) => $s->where('status', Store::STATUS_AKTIF)]),
            ])->find($buyId);

            if (! $variant || ! $variant->product || ! $variant->product->store) {
                return [collect(), true];
            }

            return [collect([
                [
                    'variant_id' => $variant->product_variant_id,
                    'store_id' => $variant->product->store_id,
                    'nama_produk' => $variant->product->nama_produk,
                    'harga' => (float) $variant->harga,
                    'quantity' => 1,
                ],
            ]), false];
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['user_id' => Auth::id(), 'status' => Cart::STATUS_AKTIF]
        );

        $items = $cart->items()
            ->with(['productVariant.product.store', 'productVariant.product.images'])
            ->get()
            ->filter(function ($ci) {
                return $ci->productVariant?->product?->store
                    && $ci->productVariant->product->store->status === Store::STATUS_AKTIF
                    && $ci->productVariant->product->status === Product::STATUS_AKTIF;
            })
            ->values()
            ->map(fn ($ci) => [
                'variant_id' => $ci->product_variant_id,
                'store_id' => $ci->productVariant->product->store_id,
                'nama_produk' => $ci->productVariant->product->nama_produk,
                'harga' => (float) $ci->harga_snapshot,
                'quantity' => $ci->quantity,
            ]);

        return [$items, true];
    }
}