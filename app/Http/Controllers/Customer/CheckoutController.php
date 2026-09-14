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
use App\Models\PaymentMethodAccount;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
     * Tampilkan halaman checkout (Review) — guest-friendly.
     * - Tamu tanpa ?buy  -> redirect shop + toast
     * - Member role bukan Customer -> 403
     */
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->role?->nama_role !== Role::CUSTOMER) {
            abort(403);
        }

        $address = null;
        if (Auth::check()) {
            $address = $user->addresses()
                ->orderBy('is_default', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();
        }

        $buyId = (int) request()->query('buy', 0);

        // Tamu wajib pakai ?buy
        if (! Auth::check() && $buyId === 0) {
            return redirect()->route('customer.shop')
                ->with('toast', ['message' => 'Pilih produk dan klik Beli Sekarang untuk checkout.', 'icon' => 'gpp_maybe']);
        }

        $items = collect();
        $count = 0;
        $subtotal = 0;

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

        if ($items->isEmpty() && Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['user_id' => Auth::id(), 'status' => Cart::STATUS_AKTIF]
            );
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

        // Tamu tanpa item (buyId invalid) -> balik shop
        if ($items->isEmpty() && ! Auth::check()) {
            return redirect()->route('customer.shop')
                ->with('toast', ['message' => 'Produk tidak tersedia.', 'icon' => 'gpp_maybe']);
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
     * Buat Checkout + Order per toko + OrderItem + Payment — guest auto-register.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penerima' => 'required|string|max:150',
            'nomor_telepon' => 'required|string|max:30',
            'email_pelanggan' => 'required|email|max:150',
            'alamat' => 'required|string|max:500',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:20',
            'catatan' => 'nullable|string|max:1000',
            'shipping' => 'required|numeric|in:0,35000',
            'buy' => 'nullable|integer',
        ], [
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'email_pelanggan.required' => 'Email wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kota.required' => 'Kota wajib diisi.',
            'provinsi.required' => 'Provinsi wajib diisi.',
            'kode_pos.required' => 'Kode pos wajib diisi.',
            'shipping.required' => 'Pilih metode pengiriman terlebih dahulu.',
        ]);

        $isGuest = ! Auth::check();
        $isNewAccount = false;
        $flashEmail = null;

        if ($isGuest) {
            $existing = User::where('email', $validated['email_pelanggan'])->first();
            if ($existing) {
                // Cek role bukan Customer -> tolak
                $roleName = $existing->role?->nama_role;
                if ($roleName && $roleName !== Role::CUSTOMER) {
                    return back()
                        ->with('toast', ['message' => 'Email sudah terdaftar untuk akun ' . $roleName . '. Gunakan email lain.', 'icon' => 'gpp_maybe'])
                        ->withErrors(['email_pelanggan' => 'Email sudah digunakan.'])
                        ->withInput();
                }
                // Coba auto-login dengan password default
                $credentials = ['email' => $validated['email_pelanggan'], 'password' => 'Raliva123'];
                if (Auth::attempt($credentials)) {
                    $request->session()->regenerate();
                } else {
                    // Email sudah ada tapi bukan Raliva123 -> minta login manual
                    $loginUrl = route('login');
                    $buyParam = $validated['buy'] ? '?buy=' . (int) $validated['buy'] : '';
                    $redirect = url('/customer/checkout') . $buyParam;
                    return back()
                        ->with('toast', ['message' => 'Email sudah terdaftar — silakan Masuk lalu checkout.', 'icon' => 'gpp_maybe'])
                        ->withErrors(['email_pelanggan' => 'Email sudah terdaftar. Silakan masuk dulu, lalu lanjut checkout.'])
                        ->withInput();
                }
            } else {
                // Buat akun baru (password default Raliva123)
                $roleId = Role::where('nama_role', Role::CUSTOMER)->value('role_id');
                if (! $roleId) {
                    $roleId = Role::first()->role_id ?? 1;
                }
                $user = User::create([
                    'nama_lengkap' => $validated['nama_penerima'],
                    'email' => $validated['email_pelanggan'],
                    'password' => Hash::make('Raliva123'),
                    'role_id' => $roleId,
                    'nomor_telepon' => $validated['nomor_telepon'],
                    'status' => User::STATUS_AKTIF,
                    'email_verified_at' => now(),
                ]);
                Auth::login($user);
                $request->session()->regenerate();
                $isNewAccount = true;
                $flashEmail = $validated['email_pelanggan'];
            }
        } else {
            $authRole = Auth::user()->role?->nama_role;
            if ($authRole !== Role::CUSTOMER) {
                abort(403);
            }
        }

        /** @var \App\Models\User $actor */
        $actor = Auth::user();

        [$items, $fromCart] = $this->resolveItems((int) ($validated['buy'] ?? 0));

        if ($items->isEmpty()) {
            return back()->with('toast', ['message' => 'Tidak ada item untuk dipesan. Keranjang kosong.', 'icon' => 'gpp_maybe']);
        }

        $shipping = (int) $validated['shipping'];
        $subtotal = $items->sum(fn ($i) => $i['quantity'] * $i['harga']);
        $catatan = $validated['catatan'] ?? null;

        [$checkout, $orders] = DB::transaction(function () use ($items, $subtotal, $shipping, $fromCart, $actor, $validated, $catatan) {
            $checkout = Checkout::create([
                'user_id' => $actor->user_id,
                'email_pelanggan' => $validated['email_pelanggan'],
                'nama_penerima' => $validated['nama_penerima'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'alamat' => $validated['alamat'],
                'kota' => $validated['kota'],
                'provinsi' => $validated['provinsi'],
                'kode_pos' => $validated['kode_pos'],
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

            $byStore->each(function ($group, $storeId) use ($checkout, $shipping, $subtotal, &$orders, $catatan) {
                $storeSubtotal = $group->sum(fn ($i) => $i['quantity'] * $i['harga']);
                $storeShipping = $subtotal > 0
                    ? (int) round($shipping * ($storeSubtotal / $subtotal))
                    : 0;

                $order = Order::create([
                    'checkout_id' => $checkout->checkout_id,
                    'store_id' => (int) $storeId,
                    'nomor_order' => 'RLV-'.$storeId.'-'.strtoupper(substr(md5(uniqid((string) $storeId, true)), 0, 6)),
                    'subtotal' => $storeSubtotal,
                    'total_diskon' => 0,
                    'total_pajak' => 0,
                    'biaya_layanan' => 0,
                    'total_ongkir' => $storeShipping,
                    'grand_total' => $storeSubtotal + $storeShipping,
                    'status' => Order::STATUS_PENDING_PAYMENT,
                    'tipe_order' => Order::TIPE_PRODUK_TETAP,
                    'catatan' => $catatan,
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

            // Payment: method null dulu, dipilih di Bayar
            Payment::create([
                'checkout_id' => $checkout->checkout_id,
                'payment_method_id' => null,
                'jumlah' => $subtotal + $shipping,
                'status' => Payment::STATUS_PENDING,
                'batas_waktu' => now()->addMinutes(1440),
            ]);

            if ($fromCart) {
                $actor->cart?->items()->delete();
            }

            return [$checkout, $orders];
        });

        // Notifikasi personal (owner tetap dapat notif pesanan baru)
        if ($actor) {
            Notification::create([
                'user_id' => $actor->user_id,
                'tipe' => Notification::TIPE_ORDER,
                'judul' => 'Pesanan Dibuat',
                'pesan' => sprintf('Pesanan %s berhasil dibuat. Silakan selesaikan pembayaran.', $orders[0]->nomor_order),
            ]);
        }

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

        $redirect = redirect()->route('customer.checkout.payment', $checkout->checkout_id)
            ->with('toast', ['message' => 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.', 'icon' => 'task_alt']);

        if ($isNewAccount && $flashEmail) {
            $redirect = $redirect->with('akun_baru', $flashEmail);
        }

        return $redirect;
    }

    /**
     * Halaman Bayar (langkah 2) — verify owner + banner akun_baru + pilih metode + upload.
     */
    public function payment(int $checkout)
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect' => route('customer.checkout.payment', $checkout)]);
        }
        if (Auth::user()->role?->nama_role !== Role::CUSTOMER) {
            abort(403);
        }

        $checkoutModel = Checkout::where('checkout_id', $checkout)
            ->where('user_id', Auth::id())
            ->with(['orders.store:store_id,nama_toko', 'payment.paymentMethod', 'payment.account', 'payment.proofs'])
            ->firstOrFail();

        $payment = $checkoutModel->payment;

        $paymentMethods = PaymentMethod::with('accounts')
            ->where('status', PaymentMethod::STATUS_AKTIF)
            ->orderBy('payment_method_id')
            ->get();

        return view('customer.checkout.payment', [
            'checkout' => $checkoutModel,
            'payment' => $payment,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Halaman Selesai (langkah 3) — hanya pemilik checkout.
     */
    public function selesai(int $checkout)
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect' => route('customer.checkout.selesai', $checkout)]);
        }
        if (Auth::user()->role?->nama_role !== Role::CUSTOMER) {
            abort(403);
        }

        $checkoutModel = Checkout::where('checkout_id', $checkout)
            ->where('user_id', Auth::id())
            ->with(['orders.store:store_id,nama_toko', 'orders.items', 'payment.paymentMethod'])
            ->firstOrFail();

        return view('customer.checkout.selesai', [
            'checkout' => $checkoutModel,
            'payment' => $checkoutModel->payment,
        ]);
    }

    /**
     * Unggah bukti pembayaran — sekarang wajib payment_method_id.
     */
    public function uploadProof(Request $request, int $checkout)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        if (Auth::user()->role?->nama_role !== Role::CUSTOMER) {
            abort(403);
        }

        $checkoutModel = Checkout::where('checkout_id', $checkout)
            ->where('user_id', Auth::id())
            ->with('payment')
            ->firstOrFail();

        $payment = $checkoutModel->payment;

        if (! in_array($payment->status, [Payment::STATUS_PENDING, Payment::STATUS_DITOLAK], true)) {
            return back()->with('toast', ['message' => 'Bukti pembayaran sudah diproses.', 'icon' => 'gpp_maybe']);
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,payment_method_id',
            'payment_method_account_id' => 'nullable|integer|exists:payment_method_accounts,payment_method_account_id',
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'payment_method_id.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'bukti.required' => 'Pilih file bukti pembayaran.',
            'bukti.image' => 'File harus berupa gambar.',
        ]);

        $paymentMethod = PaymentMethod::where('payment_method_id', $validated['payment_method_id'])
            ->where('status', PaymentMethod::STATUS_AKTIF)
            ->first();
        if (! $paymentMethod) {
            return back()->with('toast', ['message' => 'Metode pembayaran tidak tersedia.', 'icon' => 'gpp_maybe']);
        }

        $account = null;
        if (! empty($validated['payment_method_account_id'])) {
            $account = PaymentMethodAccount::where('payment_method_account_id', $validated['payment_method_account_id'])
                ->where('payment_method_id', $paymentMethod->payment_method_id)
                ->where('status', PaymentMethodAccount::STATUS_AKTIF)
                ->first();
            if (! $account) {
                return back()->with('toast', ['message' => 'Tujuan pembayaran tidak cocok dengan metode dipilih.', 'icon' => 'gpp_maybe']);
            }
        }

        $fileName = 'bukti-'.$checkoutModel->checkout_id.'-'.time().'.'.$validated['bukti']->extension();
        $path = $validated['bukti']->storeAs('payment_proofs', $fileName, 'public');

        DB::transaction(function () use ($payment, $paymentMethod, $account, $path, $validated) {
            $updatePayload = [];
            if (is_null($payment->payment_method_id)) {
                $batas = $paymentMethod->batas_waktu_menit > 0 ? $paymentMethod->batas_waktu_menit : 1440;
                $updatePayload['payment_method_id'] = $paymentMethod->payment_method_id;
                $updatePayload['batas_waktu'] = now()->addMinutes($batas);
            }
            if ($account) {
                $updatePayload['payment_method_account_id'] = $account->payment_method_account_id;
            }
            if ($updatePayload) {
                $payment->update($updatePayload);
            }
            PaymentProof::create([
                'payment_id' => $payment->payment_id,
                'file_bukti' => $path,
                'uploaded_at' => now(),
            ]);
            $payment->update(['status' => Payment::STATUS_MENUNGGU_VERIFIKASI]);
        });

        Notification::create([
            'user_id' => Auth::id(),
            'tipe' => Notification::TIPE_PEMBAYARAN,
            'judul' => 'Bukti Pembayaran Diunggah',
            'pesan' => 'Bukti pembayaran Anda sedang diverifikasi oleh admin.',
        ]);

        // Jika akun baru (flash masih ada) -> ke Selesai, else order-tracking
        $hasAkunBaru = $request->session()->has('akun_baru') || session()->has('akun_baru');
        // Fallback: user dibuat <15 menit & masih pending? tetap anggap baru
        if (! $hasAkunBaru) {
            $userCreatedRecently = Auth::user()->created_at && Auth::user()->created_at->gt(now()->subMinutes(15));
            // Jika batas_waktu baru di-set dan payment tadinya null -> kemungkinan akun baru
            // Simpler: tidak auto-detect, biarkan ke order-tracking
        }

        if ($hasAkunBaru) {
            return redirect()->route('customer.checkout.selesai', $checkoutModel->checkout_id)
                ->with('akun_baru', session('akun_baru'))
                ->with('toast', ['message' => 'Bukti pembayaran diunggah. Menunggu verifikasi admin.', 'icon' => 'task_alt']);
        }

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
                return [collect(), false];
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

        if (! Auth::check()) {
            return [collect(), false];
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
