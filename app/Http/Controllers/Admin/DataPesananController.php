<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentVerification;
use App\Models\PaymentProof;
use App\Models\PaymentMethod;
use App\Models\PlatformBankAccount;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use App\Support\CustomerWalletService;
use App\Support\WalletService;
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
            Order::STATUS_DIBAYAR => 'Baru',
            Order::STATUS_MENUNGGU_PRODUKSI => 'Menunggu Produksi',
            Order::STATUS_DIPROSES => 'Diproses',
            Order::STATUS_MENUNGGU_QC => 'Menunggu QC',
            Order::STATUS_SIAP_KIRIM => 'Siap Kirim',
            Order::STATUS_DIKIRIM => 'Dikirim',
            Order::STATUS_SELESAI => 'Selesai',
            Order::STATUS_DIBATALKAN => 'Dibatalkan',
        ];

        $storeIds = AdminContext::assignedStoreIds();
        $orders = Order::query()
            ->whereIn('store_id', $storeIds)
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap,email,nomor_telepon', 'checkout.payment:payment_id,checkout_id,status', 'checkout.payment.proofs', 'checkout.payment.paymentMethod', 'items.productVariant.product', 'shipments', 'bahanList', 'qualityChecks'])
            ->when(
                array_key_exists($status, $statuses),
                fn ($query) => $query->where('status', $status)
            )
            ->prioritasStatus()
            ->orderByDesc('updated_at')
            ->orderByDesc('order_id')
            ->get();

        $variants = \App\Models\ProductVariant::with(['product:product_id,store_id,nama_produk', 'warehouseStocks:warehouse_stock_id,product_variant_id,jumlah_stok'])
            ->whereHas('product', fn ($q) => $q->whereIn('store_id', $storeIds))
            ->orderBy('product_id')->limit(200)->get();
        $recentOrders = Order::with('checkout.user:user_id,nama_lengkap')
            ->whereIn('store_id', $storeIds)
            ->orderByDesc('order_id')->limit(20)->get();

        $customers = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
            ->orderByDesc('created_at')->limit(50)->get(['user_id', 'nama_lengkap', 'email', 'nomor_telepon']);

        $paymentAccounts = PlatformBankAccount::where('status', PlatformBankAccount::STATUS_AKTIF)
            ->orderBy('urutan')->get(['platform_bank_account_id', 'jenis', 'nama', 'kode', 'nomor_rekening', 'nama_pemilik']);

        return view('Admin.pesanan.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'activeStatus' => $status,
            'variants' => $variants,
            'recentOrders' => $recentOrders,
            'customers' => $customers,
            'paymentAccounts' => $paymentAccounts,
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

        if (! in_array($pesanan->status, [Order::STATUS_MENUNGGU_PRODUKSI, Order::STATUS_DIBAYAR], true)) {
            return back()->with('toast', [
                'message' => 'Hanya pesanan berstatus Baru atau Menunggu Produksi yang dapat diproses.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'tgl_mulai_produksi' => ['required', 'date'],
            'tgl_berakhir_produksi' => ['required', 'date', 'after_or_equal:tgl_mulai_produksi'],
        ], [
            'tgl_mulai_produksi.required' => 'Tanggal mulai produksi wajib diisi.',
            'tgl_berakhir_produksi.required' => 'Tanggal berakhir produksi wajib diisi.',
            'tgl_berakhir_produksi.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai.',
        ]);

        $lama = $pesanan->only(['status']);

        $pesanan->update([
            'status' => Order::STATUS_DIPROSES,
            'tgl_mulai_produksi' => $data['tgl_mulai_produksi'],
            'tgl_berakhir_produksi' => $data['tgl_berakhir_produksi'],
        ]);

        ActivityLogger::log(
            'admin.order.process',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['status' => Order::STATUS_DIPROSES],
            sprintf('Memproses pesanan %s. Input bahan dilakukan oleh Produksi.', $pesanan->nomor_order)
        );

        $this->notifyCustomer($pesanan, 'Pesanan Diproses', sprintf('Pesanan %s sedang diproses oleh toko.', $pesanan->nomor_order));

        Notification::fireSelf(Notification::TIPE_ORDER, 'Pesanan Diproses', sprintf('Pesanan %s diteruskan ke produksi.', $pesanan->nomor_order), route('admin.pesanan'));

        NotificationService::sendToRole(
            Role::PRODUKSI,
            Notification::TIPE_SISTEM,
            'Pesanan Diproses',
            sprintf('Pesanan %s sedang diproses. Silakan input kebutuhan bahan.', $pesanan->nomor_order),
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
            'user_id' => ['required_if:tipe_pesanan,online', 'nullable', 'exists:users,user_id'],
            'nama_penerima' => ['required_if:tipe_pesanan,offline', 'nullable', 'string', 'max:150'],
            'nomor_telepon' => ['required_if:tipe_pesanan,offline', 'nullable', 'string', 'max:30'],
            'email_pelanggan' => ['nullable', 'email', 'max:150'],
            'alamat' => ['required_if:tipe_pesanan,offline', 'nullable', 'string', 'max:500'],
            'metode_bayar' => ['required_if:tipe_pesanan,offline', 'nullable', 'in:tunai,transfer'],
            'payment_account_id' => ['required_if:metode_bayar,transfer', 'nullable', 'exists:platform_bank_accounts,platform_bank_account_id'],
            'bukti' => ['required_if:metode_bayar,transfer', 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
            'catatan' => ['nullable', 'string', 'max:1000'],
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
                'tipe_pesanan' => $isOffline ? Order::TIPE_PESANAN_OFFLINE : Order::TIPE_PESANAN_ONLINE,
                'metode_fulfillment' => $isOffline ? Order::FULFILLMENT_AMBIL : Order::FULFILLMENT_DIANTAR,
                'catatan' => $data['catatan'] ?? null,
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
                    $newOrder->update(['status' => Order::STATUS_DIBAYAR]);
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
            ? 'Pesanan offline dibuat (Tunai — Baru).'
            : 'Pesanan dibuat (Menunggu Pembayaran).';

        return back()->with('toast', [
            'message' => 'Pesanan ' . ($newOrder->nomor_order ?? ('#' . $newOrder->order_id)) . ' ' . $msg,
            'icon' => 'task_alt',
        ]);
    }


    public function selesai(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($pesanan->status !== Order::STATUS_SIAP_KIRIM) {
            return back()->with('toast', [
                'message' => 'Pesanan dapat ditandai selesai setelah QC + packing (status Siap Kirim).',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($pesanan->isDiantar()) {
            return back()->with('toast', [
                'message' => 'Pesanan diantar kurir diselesaikan lewat pengiriman (input resi), bukan di sini.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $lama = $pesanan->only(['status']);

        DB::transaction(function () use ($pesanan, $data) {
            $locked = Order::whereKey($pesanan->order_id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== Order::STATUS_SIAP_KIRIM) {
                throw new \RuntimeException('Status pesanan berubah, tidak dapat diselesaikan.');
            }
            if (($locked->metode_fulfillment ?? Order::FULFILLMENT_DIANTAR) === Order::FULFILLMENT_DIANTAR) {
                throw new \RuntimeException('Pesanan diantar kurir diselesaikan lewat pengiriman.');
            }

            $locked->update([
                'status' => Order::STATUS_SELESAI,
                'diambil_pada' => now(),
            ]);

            WalletService::creditOrder($locked);
        });

        ActivityLogger::log(
            'admin.order.pickup',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['status' => Order::STATUS_SELESAI, 'diambil_pada' => now()->format('Y-m-d H:i:s'), 'catatan' => $data['catatan'] ?? null],
            sprintf('Pesanan %s ditandai selesai — diambil langsung oleh customer.', $pesanan->nomor_order)
        );

        $this->notifyCustomer($pesanan, 'Pesanan Selesai', sprintf('Pesanan %s telah selesai dan diambil. Terima kasih sudah berbelanja!', $pesanan->nomor_order));

        Notification::fireSelf(Notification::TIPE_ORDER, 'Pesanan Diselesaikan', sprintf('Pesanan %s ditandai selesai (diambil langsung).', $pesanan->nomor_order), route('admin.pesanan'));

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} diselesaikan.",
            'icon' => 'task_alt',
        ]);
    }

    public function alihFulfillment(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'fulfillment' => ['required', 'in:ambil,diantar'],
        ], [
            'fulfillment.required' => 'Pilihan fulfillment wajib diisi.',
            'fulfillment.in' => 'Pilihan fulfillment tidak valid.',
        ]);

        $target = $data['fulfillment'];

        if ($pesanan->status !== Order::STATUS_SIAP_KIRIM) {
            return back()->with('toast', [
                'message' => 'Fulfillment hanya dapat diubah saat pesanan Siap Kirim.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($target === ($pesanan->metode_fulfillment ?? Order::FULFILLMENT_DIANTAR)) {
            return back()->with('toast', [
                'message' => 'Fulfillment pesanan memang sudah begitu.',
                'icon' => 'info',
            ]);
        }

        if ($pesanan->shipments()->where('status', '!=', \App\Models\Shipment::STATUS_GAGAL)->exists()) {
            return back()->with('toast', [
                'message' => 'Pesanan sudah memiliki pengiriman aktif — fulfillment tidak dapat diubah.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $pesanan->only(['metode_fulfillment', 'total_ongkir', 'grand_total']);
        $ongkirDibatalkan = 0.0;

        try {
            DB::transaction(function () use ($pesanan, $target, &$ongkirDibatalkan) {
                $order = Order::whereKey($pesanan->order_id)->lockForUpdate()->firstOrFail();

                if ($order->status !== Order::STATUS_SIAP_KIRIM) {
                    throw new \RuntimeException('Status pesanan berubah, fulfillment tidak dapat diubah.');
                }
                if ($target === ($order->metode_fulfillment ?? Order::FULFILLMENT_DIANTAR)) {
                    throw new \RuntimeException('Fulfillment pesanan memang sudah begitu.');
                }
                if ($order->shipments()->where('status', '!=', \App\Models\Shipment::STATUS_GAGAL)->exists()) {
                    throw new \RuntimeException('Pesanan sudah memiliki pengiriman aktif.');
                }

                if ($target === Order::FULFILLMENT_AMBIL) {
                    $ongkir = (float) ($order->total_ongkir ?? 0);
                    $baruGrand = max(0, (float) $order->grand_total - $ongkir);

                    $order->update([
                        'metode_fulfillment' => Order::FULFILLMENT_AMBIL,
                        'total_ongkir' => 0,
                        'grand_total' => $baruGrand,
                    ]);

                    if ($ongkir > 0) {
                        $checkout = \App\Models\Checkout::whereKey($order->checkout_id)->lockForUpdate()->first();
                        if ($checkout) {
                            $checkout->update([
                                'total_ongkir' => max(0, (float) $checkout->total_ongkir - $ongkir),
                                'grand_total' => max(0, (float) $checkout->grand_total - $ongkir),
                            ]);
                        }
                        $ongkirDibatalkan = $ongkir;
                    }
                } else {
                    $order->update(['metode_fulfillment' => Order::FULFILLMENT_DIANTAR]);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        $pesanLog = $target === Order::FULFILLMENT_AMBIL
            ? sprintf(
                'Pesanan %s dialihkan ke ambil di toko. Ongkir Rp %s dibatalkan, grand total jadi Rp %s.',
                $pesanan->nomor_order,
                number_format($ongkirDibatalkan, 0, ',', '.'),
                number_format((float) Order::whereKey($pesanan->order_id)->value('grand_total'), 0, ',', '.')
            )
            : sprintf('Pesanan %s dialihkan ke kirim kurir. Ongkir tidak dipulihkan otomatis.', $pesanan->nomor_order);

        ActivityLogger::log(
            'admin.order.fulfillment',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['metode_fulfillment' => $target, 'total_ongkir' => $target === Order::FULFILLMENT_AMBIL ? 0 : $lama['total_ongkir']],
            $pesanLog
        );

        if ($target === Order::FULFILLMENT_AMBIL) {
            $this->notifyCustomer($pesanan, 'Pesanan Diambil di Toko', sprintf(
                'Pesanan %s dialihkan menjadi ambil di toko. Silakan ambil pesanan Anda di toko kami setelah dikonfirmasi siap.',
                $pesanan->nomor_order
            ));
        } else {
            $this->notifyCustomer($pesanan, 'Pesanan Dikirim', sprintf(
                'Pesanan %s dialihkan menjadi dikirim ke alamat Anda.',
                $pesanan->nomor_order
            ));
        }

        Notification::fireSelf(Notification::TIPE_ORDER, 'Fulfillment Diubah', $pesanLog, route('admin.pesanan'));

        return back()->with('toast', [
            'message' => $target === Order::FULFILLMENT_AMBIL
                ? "Pesanan {$pesanan->nomor_order} dialihkan ke ambil di toko."
                : "Pesanan {$pesanan->nomor_order} dialihkan ke kirim kurir.",
            'icon' => 'task_alt',
        ]);
    }

    public function qcTanggapan(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if (! $pesanan->qc_perlu_admin_pada || $pesanan->status !== Order::STATUS_MENUNGGU_QC) {
            return back()->with('toast', [
                'message' => 'Pesanan ini tidak dalam antrian QC Gagal.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'aksi' => ['required', 'in:rework,lanjut'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'aksi.required' => 'Pilih tanggapan QC.',
            'aksi.in' => 'Tanggapan QC tidak valid.',
        ]);

        $rework = $data['aksi'] === 'rework';
        $catatanProduksi = $pesanan->qc_perlu_admin_catatan;
        $lama = $pesanan->only(['status', 'qc_perlu_admin_pada', 'qc_perlu_admin_catatan']);

        $pesanan->update([
            'status' => $rework ? Order::STATUS_MENUNGGU_PRODUKSI : Order::STATUS_MENUNGGU_QC,
            'qc_perlu_admin_pada' => null,
            'qc_perlu_admin_catatan' => null,
        ]);

        $pesanLog = $rework
            ? sprintf('QC Gagal: pesanan %s dikirim ulang ke Produksi (rework).', $pesanan->nomor_order)
            : sprintf('QC Gagal: pesanan %s dilanjutkan ke QC ulang oleh Admin.', $pesanan->nomor_order);
        if (! empty($data['catatan'])) {
            $pesanLog .= ' Catatan Admin: ' . $data['catatan'];
        }

        ActivityLogger::log('admin.order.qc-tanggapan', Order::class, $pesanan->order_id, $lama,
            [
                'aksi' => $data['aksi'],
                'status' => $pesanan->status,
                'qc_perlu_admin_pada' => null,
                'catatan' => $data['catatan'] ?? null,
            ],
            $pesanLog);

        $subjek = $rework ? 'QC Gagal — Produksi Ulang' : 'QC Gagal — Silakan QC Ulang';
        $isi = $rework
            ? sprintf('Pesanan %s gagal QC. Produksi ulang: %s', $pesanan->nomor_order, $catatanProduksi ?: '—')
            : sprintf('Pesanan %s dilanjutkan ke QC ulang. Silakan periksa kembali.', $pesanan->nomor_order);
        if (! empty($data['catatan'])) {
            $isi .= ' Catatan Admin: ' . $data['catatan'];
        }

        NotificationService::sendToRoleInStores(Role::PRODUKSI, [$pesanan->store_id], Notification::TIPE_SISTEM,
            $subjek, $isi, ActivityLogger::resolveActorId(), route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC]));

        return back()->with('toast', [
            'message' => $rework
                ? "Pesanan {$pesanan->nomor_order} dikirim ulang ke Produksi."
                : "Pesanan {$pesanan->nomor_order} dilanjutkan ke QC ulang.",
            'icon' => 'task_alt',
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

    public function invoice(Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            abort(403, 'Pesanan ini di luar scope toko yang Anda tugaskan.');
        }

        $pesanan->load([
            'store',
            'checkout.user',
            'checkout.payment.paymentMethod',
            'items.productVariant.product',
            'shipments.courier',
            'shipments.shippingService',
        ]);

        return view('Admin.pesanan.invoice', compact('pesanan'));
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
