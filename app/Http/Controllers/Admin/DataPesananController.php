<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanProduksi;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentVerification;
use App\Models\PaymentProof;
use App\Models\PaymentMethod;
use App\Models\PlatformBankAccount;
use App\Models\ProductionOrderBahan;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use App\Support\CustomerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataPesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $statuses = [
            Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            Order::STATUS_MENUNGGU_PRODUKSI => 'Menunggu Produksi',
            Order::STATUS_DIPROSES => 'Diproses',
            Order::STATUS_DIKIRIM => 'Dikirim',
            Order::STATUS_SELESAI => 'Selesai',
            Order::STATUS_DIBATALKAN => 'Dibatalkan',
        ];

        $storeIds = AdminContext::assignedStoreIds();
        $orders = Order::query()
            ->whereIn('store_id', $storeIds)
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap,email', 'checkout.payment:payment_id,checkout_id,status', 'checkout.payment.proofs', 'checkout.payment.paymentMethod', 'items.productVariant.product', 'shipments', 'bahanList', 'qualityChecks'])
            ->when(
                array_key_exists($status, $statuses),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending_payment' THEN 0 WHEN 'menunggu_produksi' THEN 1 WHEN 'diproses' THEN 2 WHEN 'dikirim' THEN 3 WHEN 'selesai' THEN 4 ELSE 5 END")
            ->orderByDesc('created_at')
            ->get();

        $variants = \App\Models\ProductVariant::with(['product:product_id,store_id,nama_produk', 'warehouseStocks:warehouse_stock_id,product_variant_id,jumlah_stok'])
            ->whereHas('product', fn ($q) => $q->whereIn('store_id', $storeIds))
            ->orderBy('product_id')->limit(200)->get();
        $recentOrders = Order::with('checkout.user:user_id,nama_lengkap')
            ->whereIn('store_id', $storeIds)
            ->orderByDesc('order_id')->limit(20)->get();

        $customers = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
            ->orderByDesc('created_at')->limit(50)->get(['user_id', 'nama_lengkap', 'email']);

        $paymentAccounts = PlatformBankAccount::where('status', PlatformBankAccount::STATUS_AKTIF)
            ->orderBy('urutan')->get(['platform_bank_account_id', 'jenis', 'nama', 'kode', 'nomor_rekening', 'nama_pemilik']);

        $bahanList = BahanProduksi::whereIn('store_id', $storeIds)
            ->where('status', BahanProduksi::STATUS_AKTIF)
            ->orderBy('nama_bahan')->get();

        return view('Admin.pesanan.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'activeStatus' => $status,
            'variants' => $variants,
            'recentOrders' => $recentOrders,
            'customers' => $customers,
            'paymentAccounts' => $paymentAccounts,
            'bahanList' => $bahanList,
        ]);
    }

    public function proses(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($pesanan->status !== Order::STATUS_MENUNGGU_PRODUKSI) {
            return back()->with('toast', [
                'message' => 'Hanya pesanan menunggu produksi yang dapat diproses.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'bahan' => ['required', 'array', 'min:1'],
            'bahan.*.bahan_id' => ['nullable', 'exists:bahan_produksi,bahan_id'],
            'bahan.*.nama_bahan' => ['required', 'string', 'max:150'],
            'bahan.*.jumlah' => ['required', 'numeric', 'min:0.01'],
            'bahan.*.satuan' => ['required', 'string', 'max:20'],
            'bahan.*.catatan' => ['nullable', 'string', 'max:500'],
            'tgl_mulai_produksi' => ['required', 'date'],
            'tgl_berakhir_produksi' => ['required', 'date', 'after_or_equal:tgl_mulai_produksi'],
        ], [
            'bahan.required' => 'Input minimal 1 bahan produksi.',
            'bahan.min' => 'Input minimal 1 bahan produksi.',
            'bahan.*.nama_bahan.required' => 'Nama bahan wajib diisi.',
            'bahan.*.jumlah.required' => 'Jumlah bahan wajib diisi.',
            'bahan.*.satuan.required' => 'Satuan bahan wajib diisi.',
            'tgl_mulai_produksi.required' => 'Tanggal mulai produksi wajib diisi.',
            'tgl_berakhir_produksi.required' => 'Tanggal berakhir produksi wajib diisi.',
            'tgl_berakhir_produksi.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai.',
        ]);

        $lama = $pesanan->only(['status']);

        DB::transaction(function () use ($pesanan, $data) {
            foreach ($data['bahan'] as $bahan) {
                ProductionOrderBahan::create([
                    'order_id' => $pesanan->order_id,
                    'bahan_id' => $bahan['bahan_id'] ?? null,
                    'nama_bahan' => $bahan['nama_bahan'],
                    'jumlah' => $bahan['jumlah'],
                    'satuan' => $bahan['satuan'],
                    'catatan' => $bahan['catatan'] ?? null,
                    'created_by' => ActivityLogger::resolveActorId(),
                ]);
            }

            $pesanan->update([
                'status' => Order::STATUS_DIPROSES,
                'tgl_mulai_produksi' => $data['tgl_mulai_produksi'],
                'tgl_berakhir_produksi' => $data['tgl_berakhir_produksi'],
            ]);
        });

        ActivityLogger::log(
            'admin.order.process',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['status' => Order::STATUS_DIPROSES],
            sprintf('Memproses pesanan %s dengan input bahan produksi.', $pesanan->nomor_order)
        );

        $this->notifyCustomer($pesanan, 'Pesanan Diproses', sprintf('Pesanan %s sedang diproses oleh toko.', $pesanan->nomor_order));

        NotificationService::sendToRole(
            Role::PRODUKSI,
            Notification::TIPE_SISTEM,
            'Pesanan Diproses',
            sprintf('Pesanan %s sedang diproses. Bahan telah diinput oleh Admin.', $pesanan->nomor_order),
            ActivityLogger::resolveActorId(),
            route('produksi.data-produksi')
        );

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} kini diproses.",
            'icon' => 'task_alt',
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Admin belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $tipePesanan = $request->input('tipe_pesanan', 'online');

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'exists:product_variants,product_variant_id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'tipe_pesanan' => ['required', 'in:online,offline'],
            'user_id' => ['required_if:tipe_pesanan,online', 'exists:users,user_id'],
            'nama_penerima' => ['required_if:tipe_pesanan,offline', 'string', 'max:150'],
            'nomor_telepon' => ['required_if:tipe_pesanan,offline', 'string', 'max:30'],
            'email_pelanggan' => ['nullable', 'email', 'max:150'],
            'alamat' => ['required_if:tipe_pesanan,offline', 'string', 'max:500'],
            'metode_bayar' => ['required_if:tipe_pesanan,offline', 'in:tunai,transfer'],
            'payment_account_id' => ['required_if:metode_bayar,transfer', 'exists:platform_bank_accounts,platform_bank_account_id'],
            'bukti' => ['required_if:metode_bayar,transfer', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ], [
            'items.required' => 'Pilih minimal 1 produk.',
            'items.min' => 'Pilih minimal 1 produk.',
            'items.*.product_variant_id.required' => 'Pilih produk untuk setiap baris.',
            'items.*.quantity.min' => 'Qty minimal 1.',
            'nomor_telepon.required_if' => 'Nomor telepon wajib diisi untuk pesanan offline.',
            'nama_penerima.required_if' => 'Nama penerima wajib diisi untuk pesanan offline.',
            'alamat.required_if' => 'Alamat wajib diisi untuk pesanan offline.',
            'metode_bayar.required_if' => 'Pilih metode pembayaran untuk pesanan offline.',
        ]);

        $items = collect($data['items'])->filter(fn ($r) => ! empty($r['product_variant_id']))->values();
        if ($items->isEmpty()) {
            return back()->with('toast', ['message' => 'Pilih minimal 1 produk.', 'icon' => 'gpp_maybe']);
        }

        $variantIds = $items->pluck('product_variant_id')->all();
        $variants = ProductVariant::with(['product:product_id,store_id,nama_produk,harga_dasar', 'warehouseStocks'])
            ->whereIn('product_variant_id', $variantIds)->get()->keyBy('product_variant_id');

        $prepared = [];
        $subtotal = 0;
        foreach ($items as $row) {
            $variant = $variants->get($row['product_variant_id']);
            if (! $variant || (int) $variant->product?->store_id !== (int) $storeId) {
                return back()->with('toast', ['message' => 'Varian tidak valid untuk toko ini.', 'icon' => 'gpp_maybe']);
            }
            $qty = max(1, (int) $row['quantity']);
            $stok = (int) $variant->warehouseStocks->sum('jumlah_stok');
            if ($stok > 0 && $qty > $stok) {
                return back()->with('toast', ['message' => 'Stok ' . ($variant->product?->nama_produk ?? 'produk') . ' hanya tersisa ' . $stok . '.', 'icon' => 'gpp_maybe']);
            }
            $harga = (float) ($variant->harga ?? $variant->product?->harga_dasar ?? 0);
            $sub = $harga * $qty;
            $subtotal += $sub;
            $prepared[] = [
                'variant' => $variant,
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $sub,
            ];
        }

        $isOffline = $tipePesanan === 'offline';
        $userId = null;

        if ($isOffline) {
            // 1. Cari existing customer by email (jika ada)
            if (! empty($data['email_pelanggan'])) {
                $existing = \App\Models\User::where('email', $data['email_pelanggan'])->first();
                if ($existing && $existing->role?->nama_role === Role::CUSTOMER) {
                    $userId = $existing->user_id;
                }
            }

            // 2. Cari existing customer by nomor telepon (jika belum ketemu)
            if (! $userId && ! empty($data['nomor_telepon'])) {
                $existing = \App\Models\User::where('nomor_telepon', $data['nomor_telepon'])
                    ->whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
                    ->first();
                if ($existing) {
                    $userId = $existing->user_id;
                }
            }

            // 3. Auto-create customer jika belum ketemu
            if (! $userId) {
                $autoEmail = ! empty($data['email_pelanggan'])
                    ? $data['email_pelanggan']
                    : preg_replace('/[^0-9]/', '', $data['nomor_telepon'] ?? '') . '@offline.raliva.test';

                $user = \App\Models\User::create([
                    'nama_lengkap' => $data['nama_penerima'],
                    'email' => $autoEmail,
                    'password' => Hash::make('Raliva123'),
                    'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
                    'nomor_telepon' => $data['nomor_telepon'],
                    'status' => 'aktif',
                    'email_verified_at' => now(),
                ]);
                $userId = $user->user_id;
            }
        } else {
            $userId = (int) $data['user_id'];
        }

        $metodeBayar = $isOffline ? ($data['metode_bayar'] ?? 'tunai') : null;

        [$pajak, $biaya, $grand] = \App\Support\PricingService::computeTotals($subtotal, 0);

        $newOrder = DB::transaction(function () use ($prepared, $subtotal, $pajak, $biaya, $grand, $userId, $storeId, $isOffline, $data, $request, $metodeBayar) {
            $checkout = \App\Models\Checkout::create([
                'user_id' => $userId,
                'email_pelanggan' => $data['email_pelanggan'] ?? null,
                'nama_penerima' => $data['nama_penerima'] ?? null,
                'nomor_telepon' => $data['nomor_telepon'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'subtotal' => $subtotal,
                'total_diskon' => 0,
                'total_pajak' => $pajak,
                'biaya_layanan' => $biaya,
                'total_ongkir' => 0,
                'grand_total' => $grand,
                'status' => \App\Models\Checkout::STATUS_PENDING,
            ]);

            $newOrder = Order::create([
                'store_id' => $storeId,
                'checkout_id' => $checkout->checkout_id,
                'nomor_order' => 'RLV-' . $storeId . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                'subtotal' => $subtotal,
                'total_pajak' => $pajak,
                'biaya_layanan' => $biaya,
                'grand_total' => $grand,
                'status' => Order::STATUS_PENDING_PAYMENT,
            ]);

            foreach ($prepared as $p) {
                $variant = $p['variant'];
                OrderItem::create([
                    'order_id' => $newOrder->order_id,
                    'product_variant_id' => $variant->product_variant_id,
                    'nama_produk_snapshot' => $variant->product?->nama_produk ?? 'Produk',
                    'harga_snapshot' => $p['harga'],
                    'quantity' => $p['qty'],
                    'subtotal' => $p['subtotal'],
                    'diskon' => 0,
                    'total' => $p['subtotal'],
                ]);
            }

            // Payment untuk offline
            if ($isOffline) {
                $paymentMethodId = PaymentMethod::where('kode_metode', 'bank_transfer')->value('payment_method_id');

                if ($metodeBayar === 'tunai') {
                    $payment = Payment::create([
                        'checkout_id' => $checkout->checkout_id,
                        'payment_method_id' => $paymentMethodId,
                        'payment_account_id' => null,
                        'jumlah' => $grand,
                        'status' => Payment::STATUS_TERVERIFIKASI,
                        'batas_waktu' => now(),
                        'dibayar_pada' => now(),
                    ]);

                    PaymentVerification::create([
                        'payment_id' => $payment->payment_id,
                        'verifier_id' => ActivityLogger::resolveActorId(),
                        'status' => PaymentVerification::STATUS_DITERIMA,
                        'diverifikasi_pada' => now(),
                    ]);

                    $checkout->update(['status' => \App\Models\Checkout::STATUS_DIBAYAR]);
                    $newOrder->update(['status' => Order::STATUS_MENUNGGU_PRODUKSI]);
                } else {
                    $fileName = 'bukti-' . $checkout->checkout_id . '-' . time() . '.' . $request->file('bukti')->extension();
                    $path = $request->file('bukti')->storeAs('payment_proofs', $fileName, 'public');

                    $payment = Payment::create([
                        'checkout_id' => $checkout->checkout_id,
                        'payment_method_id' => $paymentMethodId,
                        'payment_account_id' => $data['payment_account_id'],
                        'jumlah' => $grand,
                        'status' => Payment::STATUS_MENUNGGU_VERIFIKASI,
                        'batas_waktu' => now()->addMinutes(1440),
                    ]);

                    PaymentProof::create([
                        'payment_id' => $payment->payment_id,
                        'file_bukti' => $path,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            return $newOrder;
        });

        if ($userId) {
            Notification::create([
                'user_id' => $userId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_ORDER,
                'judul' => 'Pesanan Dibuat Admin',
                'pesan' => sprintf('Admin membuat pesanan %s untuk Anda.', $newOrder->nomor_order),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_ORDER, 'Pesanan Manual Dibuat', sprintf('Pesanan %s berhasil dibuat.', $newOrder->nomor_order), route('admin.pesanan'));

        $msg = $isOffline && $metodeBayar === 'tunai'
            ? 'Pesanan offline dibuat (Tunai — Terverifikasi).'
            : 'Pesanan dibuat (Menunggu Pembayaran).';

        return back()->with('toast', [
            'message' => 'Pesanan ' . ($newOrder->nomor_order ?? ('#' . $newOrder->order_id)) . ' ' . $msg,
            'icon' => 'task_alt',
        ]);
    }

    public function batalkan(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if (! in_array($pesanan->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_MENUNGGU_PRODUKSI, Order::STATUS_DIPROSES], true)) {
            return back()->with('toast', [
                'message' => 'Pesanan yang sudah dikirim tidak dapat dibatalkan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan pembatalan wajib diisi.',
            'alasan.min' => 'Alasan pembatalan minimal 10 karakter.',
        ]);

        $lama = $pesanan->only(['status']);

        $isSaldoRefund = false;

        DB::transaction(function () use ($pesanan, $data, &$isSaldoRefund) {
            $order = Order::with(['checkout.payment.paymentMethod'])
                ->where('order_id', $pesanan->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $order->update(['status' => Order::STATUS_DIBATALKAN]);

            $payment = $order->checkout?->payment;

            if (
                $payment
                && $payment->status === Payment::STATUS_TERVERIFIKASI
                && $payment->paymentMethod?->kode_metode === PaymentMethod::KODE_SALDO_AKUN
                && $order->checkout?->user
            ) {
                try {
                    CustomerWalletService::refundToWallet(
                        $order,
                        (float) $order->grand_total,
                        'Refund otomatis pembatalan pesanan '.$order->nomor_order.' (saldo akun)'
                    );
                    $isSaldoRefund = true;
                } catch (\RuntimeException $e) {
                    \Illuminate\Support\Facades\Log::warning('Refund saldo dibatalkan: '.$e->getMessage());
                }
            }
        });

        ActivityLogger::log(
            'admin.order.cancel',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['status' => Order::STATUS_DIBATALKAN, 'alasan' => $data['alasan']],
            sprintf('Membatalkan pesanan %s dengan alasan: %s', $pesanan->nomor_order, $data['alasan'])
        );

        $this->notifyCustomer($pesanan, 'Pesanan Dibatalkan', sprintf('Pesanan %s dibatalkan. Alasan: %s', $pesanan->nomor_order, $data['alasan']));

        if ($isSaldoRefund && $pesanan->checkout?->user) {
            Notification::create([
                'user_id' => $pesanan->checkout->user->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_WALLET,
                'judul' => 'Dana Dikembalikan ke Saldo',
                'pesan' => sprintf('Pembatalan pesanan %s. Dana Rp %s dikembalikan ke saldo akun Anda.', $pesanan->nomor_order, number_format((float) $pesanan->grand_total, 0, ',', '.')),
                'url' => route('customer.saldo'),
            ]);
        }

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} dibatalkan.".($isSaldoRefund ? ' Dana dikembalikan ke saldo customer.' : ''),
            'icon' => 'block',
        ]);
    }

    public function updateItems(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', ['message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.', 'icon' => 'gpp_maybe']);
        }

        if (! in_array($pesanan->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_DIBAYAR], true)) {
            return back()->with('toast', ['message' => 'Hanya pesanan Menunggu Pembayaran atau Dibayar yang dapat diubah.', 'icon' => 'gpp_maybe']);
        }

        $pesanan->loadMissing(['checkout.payment', 'shipments']);

        if ($pesanan->checkout?->payment || Payment::where('checkout_id', $pesanan->checkout_id)->exists()) {
            return back()->with('toast', ['message' => 'Pesanan sudah memiliki pembayaran, tidak dapat diubah.', 'icon' => 'gpp_maybe']);
        }

        if ($pesanan->shipments->isNotEmpty() || $pesanan->shipments()->exists()) {
            return back()->with('toast', ['message' => 'Pesanan sudah memiliki pengiriman, tidak dapat diubah.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => ['nullable', 'integer', 'exists:order_items,order_item_id'],
            'items.*.product_variant_id' => ['nullable', 'integer', 'exists:product_variants,product_variant_id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'removed' => ['sometimes', 'array'],
            'removed.*' => ['integer', 'exists:order_items,order_item_id'],
        ]);

        $lamaItems = $pesanan->items()->get()->map(fn ($it) => $it->only(['order_item_id', 'product_variant_id', 'quantity', 'subtotal']))->all();
        $lamaTotal = $pesanan->only(['subtotal', 'total_pajak', 'biaya_layanan', 'grand_total']);

        DB::transaction(function () use ($pesanan, $data, &$baruItems, &$baruTotal) {
            $order = Order::with(['checkout.payment', 'shipments', 'items'])
                ->where('order_id', $pesanan->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($order->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_DIBAYAR], true)) {
                throw new \RuntimeException('Status pesanan berubah, tidak dapat diubah.');
            }

            if ($order->checkout?->payment || Payment::where('checkout_id', $order->checkout_id)->exists()) {
                throw new \RuntimeException('Pesanan sudah memiliki pembayaran, tidak dapat diubah.');
            }

            if ($order->shipments->isNotEmpty()) {
                throw new \RuntimeException('Pesanan sudah memiliki pengiriman, tidak dapat diubah.');
            }

            $removed = collect($data['removed'] ?? [])->map(fn ($id) => (int) $id)->all();

            $merged = [];
            foreach ($data['items'] as $row) {
                if (empty($row['product_variant_id'])) {
                    continue;
                }
                $variantId = (int) $row['product_variant_id'];
                $qty = max(1, (int) $row['quantity']);
                $merged[$variantId] = [
                    'qty' => ($merged[$variantId]['qty'] ?? 0) + $qty,
                    'order_item_id' => $row['order_item_id'] ?? null,
                ];
            }

            if (empty($merged)) {
                throw new \RuntimeException('Minimal 1 item tersisa; hapus via Batalkan.');
            }

            $variants = ProductVariant::with(['product:product_id,store_id,nama_produk,harga_dasar', 'warehouseStocks'])
                ->whereIn('product_variant_id', array_keys($merged))
                ->lockForUpdate()
                ->get()
                ->keyBy('product_variant_id');

            $prepared = [];
            $subtotal = 0;
            foreach ($merged as $variantId => $line) {
                $variant = $variants->get($variantId);

                if (! $variant || (int) $variant->product?->store_id !== (int) $order->store_id) {
                    throw new \RuntimeException('Varian tidak valid untuk toko ini.');
                }

                $stok = (int) $variant->warehouseStocks->sum('jumlah_stok');

                if ($line['qty'] > $stok) {
                    $nama = $variant->product?->nama_produk ?? 'Produk';
                    throw new \RuntimeException("Stok {$nama} hanya tersisa {$stok}.");
                }

                $harga = (float) ($variant->harga ?? $variant->product?->harga_dasar ?? 0);
                $sub = $harga * $line['qty'];
                $subtotal += $sub;
                $prepared[] = [
                    'variant' => $variant,
                    'qty' => $line['qty'],
                    'harga' => $harga,
                    'sub' => $sub,
                    'order_item_id' => $line['order_item_id'],
                    'nama' => $variant->product?->nama_produk ?? 'Produk',
                ];
            }

            $existingIds = $order->items->pluck('order_item_id')->all();
            $keepIds = [];

            foreach ($prepared as $p) {
                $row = null;

                if ($p['order_item_id'] && in_array($p['order_item_id'], $existingIds, true)) {
                    $row = OrderItem::where('order_item_id', $p['order_item_id'])->first();
                }

                if ($row) {
                    $row->update([
                        'product_variant_id' => $p['variant']->product_variant_id,
                        'nama_produk_snapshot' => $p['nama'],
                        'harga_snapshot' => $p['harga'],
                        'quantity' => $p['qty'],
                        'subtotal' => $p['sub'],
                        'total' => $p['sub'],
                    ]);
                } else {
                    $row = OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_variant_id' => $p['variant']->product_variant_id,
                        'nama_produk_snapshot' => $p['nama'],
                        'harga_snapshot' => $p['harga'],
                        'quantity' => $p['qty'],
                        'subtotal' => $p['sub'],
                        'diskon' => 0,
                        'total' => $p['sub'],
                    ]);
                }

                $keepIds[] = $row->order_item_id;
            }

            $dropIds = array_values(array_diff(array_merge($existingIds, $removed), $keepIds));
            if ($dropIds) {
                OrderItem::whereIn('order_item_id', $dropIds)->delete();
            }

            [$pajak, $biaya, $grand] = \App\Support\PricingService::computeTotals($subtotal, 0);

            $order->update([
                'subtotal' => $subtotal,
                'total_pajak' => $pajak,
                'biaya_layanan' => $biaya,
                'grand_total' => $grand,
            ]);

            $checkout = $order->checkout;
            if ($checkout) {
                $siblings = Order::where('checkout_id', $checkout->checkout_id)->get([
                    'subtotal', 'total_ongkir', 'total_pajak', 'biaya_layanan', 'grand_total',
                ]);
                $checkout->update([
                    'subtotal' => $siblings->sum('subtotal'),
                    'total_ongkir' => $siblings->sum('total_ongkir'),
                    'total_pajak' => $siblings->sum('total_pajak'),
                    'biaya_layanan' => $siblings->sum('biaya_layanan'),
                    'grand_total' => $siblings->sum('grand_total'),
                ]);
            }

            $baruItems = OrderItem::where('order_id', $order->order_id)->get()->map(fn ($it) => $it->only(['order_item_id', 'product_variant_id', 'quantity', 'subtotal']))->all();
            $baruTotal = ['subtotal' => $subtotal, 'total_pajak' => $pajak, 'biaya_layanan' => $biaya, 'grand_total' => $grand];
        });

        ActivityLogger::log('admin.order.items.update', Order::class, $pesanan->order_id, ['items' => $lamaItems, 'total' => $lamaTotal], ['items' => $baruItems ?? [], 'total' => $baruTotal ?? []], sprintf('Mengubah item pesanan %s.', $pesanan->nomor_order));
        $this->notifyCustomer($pesanan, 'Pesanan Diperbarui', sprintf('Pesanan %s diperbarui oleh toko.', $pesanan->nomor_order));
        Notification::fireSelf(Notification::TIPE_ORDER, 'Pesanan Diperbarui', sprintf('Pesanan %s berhasil diperbarui.', $pesanan->nomor_order), route('admin.pesanan'));

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} diperbarui.",
            'icon' => 'task_alt',
        ]);
    }

    private function notifyCustomer(Order $pesanan, string $judul, string $pesan): void
    {
        $userId = $pesanan->checkout?->user_id;

        if ($userId) {
            Notification::create([
                'user_id' => $userId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_ORDER,
                'judul' => $judul,
                'pesan' => $pesan,
                'url' => route('customer.order-tracking'),
            ]);
        }
    }
}
