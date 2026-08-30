<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use Illuminate\Http\Request;

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

        $orders = Order::query()
            ->whereIn('store_id', AdminContext::assignedStoreIds())
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap', 'items', 'shipments'])
            ->when(
                array_key_exists($status, $statuses),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending_payment' THEN 0 WHEN 'dibayar' THEN 1 WHEN 'diproses' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.pesanan.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'activeStatus' => $status,
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
        $sourceOrderId = $request->input('order_id');
        $userId = $request->input('user_id');

        if ($sourceOrderId) {
            $source = Order::with(['checkout.user', 'items'])->findOrFail($sourceOrderId);
            $customerId = $source->checkout?->user_id ?? $userId;
        } elseif ($userId) {
            $source = Order::with(['checkout.user', 'items'])
                ->whereHas('checkout', fn ($q) => $q->where('user_id', $userId))
                ->orderByDesc('order_id')
                ->first();
            $customerId = $userId;
        } else {
            return back()->with('toast', ['message' => 'Pilih customer atau pesanan sumber.', 'icon' => 'gpp_maybe']);
        }

        if (! $source || $source->items->isEmpty()) {
            return back()->with('toast', ['message' => 'Tidak ada item pesanan yang bisa disalin.', 'icon' => 'gpp_maybe']);
        }

        $newOrder = \DB::transaction(function () use ($source, $customerId) {
            $checkout = \App\Models\Checkout::create([
                'user_id' => $customerId,
                'subtotal' => $source->checkout?->subtotal ?? $source->total_harga ?? 0,
                'total_diskon' => $source->checkout?->total_diskon ?? 0,
                'total_pajak' => $source->checkout?->total_pajak ?? 0,
                'biaya_layanan' => $source->checkout?->biaya_layanan ?? 0,
                'total_ongkir' => $source->checkout?->total_ongkir ?? 0,
                'grand_total' => $source->checkout?->grand_total ?? $source->total_harga ?? 0,
                'status' => \App\Models\Checkout::STATUS_DIBAYAR,
            ]);

            $newOrder = Order::create([
                'store_id' => $source->store_id,
                'checkout_id' => $checkout->checkout_id,
                'nomor_order' => 'RLV-' . $source->store_id . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                'subtotal' => $source->subtotal ?? $source->total_harga ?? 0,
                'grand_total' => $source->grand_total ?? $source->total_harga ?? 0,
                'status' => Order::STATUS_PENDING_PAYMENT,
                'total_harga' => $source->total_harga ?? $source->checkout?->grand_total ?? 0,
            ]);

            foreach ($source->items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $newOrder->order_id,
                    'product_variant_id' => $item->product_variant_id,
                    'nama_produk_snapshot' => $item->nama_produk_snapshot,
                    'harga_snapshot' => $item->harga_snapshot,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'diskon' => $item->diskon,
                    'total' => $item->total,
                ]);
            }

            return $newOrder;
        });

        return back()->with('toast', [
            'message' => 'Pesanan ' . ($newOrder->nomor_order ?? ('#'.$newOrder->order_id)) . ' dibuat ulang untuk customer.',
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

    private function notifyCustomer(Order $pesanan, string $judul, string $pesan): void
    {
        $userId = $pesanan->checkout?->user_id;

        if ($userId) {
            Notification::create([
                'user_id' => $userId,
                'tipe' => Notification::TIPE_ORDER,
                'judul' => $judul,
                'pesan' => $pesan,
            ]);
        }
    }
}
