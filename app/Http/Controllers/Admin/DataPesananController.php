<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $statuses = [
            Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            Order::STATUS_DIBAYAR => 'Dibayar',
            Order::STATUS_DIPROSES => 'Diproses',
            Order::STATUS_DIKIRIM => 'Dikirim',
            Order::STATUS_SELESAI => 'Selesai',
            Order::STATUS_DIBATALKAN => 'Dibatalkan',
        ];

        $storeIds = AdminContext::assignedStoreIds();
        $orders = Order::query()
            ->whereIn('store_id', $storeIds)
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap,email', 'checkout.payment.proofs', 'checkout.payment.paymentMethod', 'items.productVariant.product', 'shipments'])
            ->when(
                array_key_exists($status, $statuses),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending_payment' THEN 0 WHEN 'dibayar' THEN 1 WHEN 'diproses' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->get();

        $variants = \App\Models\ProductVariant::with(['product:product_id,store_id,nama_produk', 'warehouseStocks:warehouse_stock_id,product_variant_id,jumlah_stok'])
            ->whereHas('product', fn ($q) => $q->whereIn('store_id', $storeIds))
            ->orderBy('product_id')->limit(200)->get();
        $recentOrders = Order::with('checkout.user:user_id,nama_lengkap')
            ->whereIn('store_id', $storeIds)
            ->orderByDesc('order_id')->limit(20)->get();

        return view('Admin.pesanan.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'activeStatus' => $status,
            'variants' => $variants,
            'recentOrders' => $recentOrders,
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

        if ($pesanan->status !== Order::STATUS_DIBAYAR) {
            return back()->with('toast', [
                'message' => 'Hanya pesanan berstatus dibayar yang dapat diproses.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $pesanan->only(['status']);

        $pesanan->update(['status' => Order::STATUS_DIPROSES]);

        ActivityLogger::log(
            'admin.order.process',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['status' => Order::STATUS_DIPROSES],
            sprintf('Memproses pesanan %s (toko %s).', $pesanan->nomor_order, $pesanan->store->nama_toko ?? '-')
        );

        $this->notifyCustomer($pesanan, 'Pesanan Diproses', sprintf('Pesanan %s sedang diproses oleh toko.', $pesanan->nomor_order));

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} kini diproses.",
            'icon' => 'task_alt',
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Toko tunggal: admin hanya pegang 1 toko (1 owner = 1 toko)
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Admin belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $mode = $request->input('mode', 'baru');
        if (! in_array($mode, ['baru', 'salin'], true)) $mode = 'baru';
        $userId = $request->input('user_id');
        $customer = $userId ? \App\Models\User::find($userId) : null;
        if (! $customerId = $customer?->user_id) {
            return back()->with('toast', ['message' => 'Pilih customer terlebih dahulu.', 'icon' => 'gpp_maybe']);
        }

        if ($mode === 'salin') {
            $source = Order::with(['checkout.user', 'items.productVariant.product'])
                ->where('order_id', $request->input('order_id'))
                ->where('store_id', $storeId)
                ->first();
            if (! $source || $source->items->isEmpty()) {
                return back()->with('toast', ['message' => 'Pesanan sumber tidak ditemukan / tidak ada item untuk disalin.', 'icon' => 'gpp_maybe']);
            }
            if ((int) ($source->checkout?->user_id ?? 0) !== (int) $customerId) {
                return back()->with('toast', ['message' => 'Pesanan sumber milik customer lain.', 'icon' => 'gpp_maybe']);
            }
            $lines = $source->items->map(fn ($it) => [
                'variant' => $it->productVariant,
                'qty' => (int) $it->quantity,
            ]);
        } else {
            $data = $request->validate([
                'items' => ['required', 'array', 'min:1', 'max:3'],
                'items.*.product_variant_id' => ['nullable', 'exists:product_variants,product_variant_id'],
                'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
            ], [
                'items.required' => 'Isi minimal 1 baris produk.',
                'items.*.quantity.min' => 'Qty minimal 1.',
            ]);
            $data['items'] = collect($data['items'] ?? [])->filter(fn ($r) => ! empty($r['product_variant_id']))->values()->all();
            if (empty($data['items'])) {
                return back()->with('toast', ['message' => 'Isi minimal 1 baris produk dengan varian terpilih.', 'icon' => 'gpp_maybe']);
            }
            $variantIds = collect($data['items'])->pluck('product_variant_id')->all();
            $variants = \App\Models\ProductVariant::with(['product:product_id,store_id,nama_produk,harga_dasar', 'warehouseStocks'])
                ->whereIn('product_variant_id', $variantIds)->get()->keyBy('product_variant_id');
            $lines = collect();
            foreach ($data['items'] as $row) {
                $variant = $variants->get($row['product_variant_id']);
                if (! $variant || (int) $variant->product?->store_id !== (int) $storeId) {
                    return back()->with('toast', ['message' => 'Varian tidak valid untuk toko ini.', 'icon' => 'gpp_maybe']);
                }
                $qty = max(1, (int) ($row['quantity'] ?? 1));
                $stok = (int) $variant->warehouseStocks->sum('jumlah_stok');
                if ($stok > 0 && $qty > $stok) {
                    return back()->with('toast', ['message' => 'Stok ' . ($variant->product?->nama_produk ?? 'produk') . ' hanya tersisa ' . $stok . '.', 'icon' => 'gpp_maybe']);
                }
                $lines->push(['variant' => $variant, 'qty' => $qty]);
            }
        }

        $newOrder = \DB::transaction(function () use ($lines, $customerId, $storeId) {
            $subtotal = 0;
            $prepared = [];
            foreach ($lines as $line) {
                $variant = $line['variant'];
                $qty = $line['qty'];
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
            $checkout = \App\Models\Checkout::create([
                'user_id' => $customerId,
                'subtotal' => $subtotal,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $subtotal,
                'status' => \App\Models\Checkout::STATUS_PENDING,
            ]);

            $newOrder = Order::create([
                'store_id' => $storeId,
                'checkout_id' => $checkout->checkout_id,
                'nomor_order' => 'RLV-' . $storeId . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
                'status' => Order::STATUS_PENDING_PAYMENT,
            ]);

            foreach ($prepared as $p) {
                $variant = $p['variant'];
                \App\Models\OrderItem::create([
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

            return $newOrder;
        });

        Notification::create([
            'user_id' => $newOrder->checkout->user_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_ORDER,
            'judul' => 'Pesanan Dibuat Admin',
            'pesan' => sprintf('Admin membuat pesanan %s untuk Anda (menunggu pembayaran).', $newOrder->nomor_order),
            'url' => route('customer.order-tracking'),
        ]);
        Notification::fireSelf(Notification::TIPE_ORDER, 'Pesanan Manual Dibuat', sprintf('Pesanan %s berhasil dibuat untuk customer.', $newOrder->nomor_order), route('admin.pesanan'));

        return back()->with('toast', [
            'message' => 'Pesanan ' . ($newOrder->nomor_order ?? ('#'.$newOrder->order_id)) . ' dibuat (Menunggu Pembayaran).',
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

        if (! in_array($pesanan->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES], true)) {
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

        $pesanan->update(['status' => Order::STATUS_DIBATALKAN]);

        ActivityLogger::log(
            'admin.order.cancel',
            Order::class,
            $pesanan->order_id,
            $lama,
            ['status' => Order::STATUS_DIBATALKAN, 'alasan' => $data['alasan']],
            sprintf('Membatalkan pesanan %s dengan alasan: %s', $pesanan->nomor_order, $data['alasan'])
        );

        $this->notifyCustomer($pesanan, 'Pesanan Dibatalkan', sprintf('Pesanan %s dibatalkan. Alasan: %s', $pesanan->nomor_order, $data['alasan']));

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} dibatalkan.",
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
        $lamaTotal = $pesanan->only(['subtotal', 'grand_total']);

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

            $order->update(['subtotal' => $subtotal, 'grand_total' => $subtotal]);

            $checkout = $order->checkout;
            if ($checkout) {
                $siblingTotal = Order::where('checkout_id', $checkout->checkout_id)->sum('grand_total');
                $checkout->update(['subtotal' => $siblingTotal, 'grand_total' => $siblingTotal]);
            }

            $baruItems = OrderItem::where('order_id', $order->order_id)->get()->map(fn ($it) => $it->only(['order_item_id', 'product_variant_id', 'quantity', 'subtotal']))->all();
            $baruTotal = ['subtotal' => $subtotal, 'grand_total' => $subtotal];
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
