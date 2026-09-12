<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Support\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderTrackingController extends Controller
{
    /**
     * Status order yang dianggap titik aktif pada timeline.
     * 1=Preparing, 2=Packed, 3=Shipped, 4=Delivered.
     */
    public const STATUS_STEPS = [
        Order::STATUS_PENDING_PAYMENT => 0,
        Order::STATUS_DIBAYAR => 1,
        Order::STATUS_DIPROSES => 1,
        Order::STATUS_DIKIRIM => 3,
        Order::STATUS_SELESAI => 4,
        Order::STATUS_DIBATALKAN => null,
        Order::STATUS_REFUND => null,
    ];

    public const STATUS_LABELS = [
        Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
        Order::STATUS_DIBAYAR => 'Pembayaran Diterima',
        Order::STATUS_DIPROSES => 'Sedang Diproses',
        Order::STATUS_DIKIRIM => 'Dikirim',
        Order::STATUS_SELESAI => 'Selesai',
        Order::STATUS_DIBATALKAN => 'Dibatalkan',
        Order::STATUS_REFUND => 'Refund',
    ];

    /**
     * Halaman order tracking: menampilkan pesanan milik user dari data nyata.
     */
    public function index(Request $request)
    {
        $orders = Auth::user()->orders()
            ->with([
                'store',
                'items.productVariant.product.images',
                'shipments.courier',
                'checkout.payment',
            ])
            ->orderByDesc('orders.created_at')
            ->get();

        if ($orders->isEmpty()) {
            return view('customer.order-tracking.index', [
                'orders' => collect(),
                'selected' => null,
                'selectedStep' => 0,
            ]);
        }

        $selectedOrderId = (int) $request->query('order');
        $selected = $orders->firstWhere('order_id', $selectedOrderId) ?? $orders->first();

        return view('customer.order-tracking.index', [
            'orders' => $orders,
            'selected' => $selected,
            'selectedStep' => self::STATUS_STEPS[$selected->status] ?? 1,
        ]);
    }

    /**
     * Konfirmasi customer bahwa pesanan sudah diterima.
     * Mengubah status menjadi selesai + mengkredit dana penjualan ke wallet owner.
     */
    public function confirm(Request $request, int $order)
    {
        $order = Auth::user()->orders()->with('store')->findOrFail($order);

        if ($order->status !== Order::STATUS_DIKIRIM) {
            return redirect()->route('customer.order-tracking', ['order' => $order->order_id])
                ->with('toast', ['message' => 'Pesanan tidak dapat dikonfirmasi pada status ini.', 'icon' => 'info']);
        }

        try {
            DB::transaction(function () use ($order) {
                $locked = Order::whereKey($order->order_id)->lockForUpdate()->first();

                if (! $locked || $locked->status !== Order::STATUS_DIKIRIM) {
                    throw new \RuntimeException('Pesanan sudah dikonfirmasi atau tidak berstatus dikirim.');
                }

                $locked->update(['status' => Order::STATUS_SELESAI]);
                WalletService::creditOrder($locked);

                $locked->shipments()->where('status', \App\Models\Shipment::STATUS_DIKIRIM)->lockForUpdate()->get()->each(function ($shipment) {
                    if ($shipment->canTransitionTo(\App\Models\Shipment::STATUS_DITERIMA)) {
                        $shipment->update(['status' => \App\Models\Shipment::STATUS_DITERIMA, 'diterima_pada' => now()]);
                    }
                });

                if ($locked->store && $locked->store->owner_id) {
                    Notification::create([
                        'user_id' => $locked->store->owner_id,
                        'tipe' => Notification::TIPE_ORDER,
                        'judul' => 'Pesanan Selesai',
                        'pesan' => sprintf('Pesanan %s telah dikonfirmasi diterima oleh customer.', $locked->nomor_order),
                    ]);
                }
            });
        } catch (\Throwable $e) {
            return redirect()->route('customer.order-tracking', ['order' => $order->order_id])
                ->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        return redirect()->route('customer.order-tracking', ['order' => $order->order_id])
            ->with('toast', ['message' => 'Pesanan dikonfirmasi diterima. Terima kasih!', 'icon' => 'task_alt']);
    }

    /**
     * Pengajuan refund oleh customer beserta foto bukti barang.
     */
    public function storeRefund(Request $request)
    {
        $order = Auth::user()->orders()->with('checkout.payment')->findOrFail((int) $request->input('order_id'));

        if (! in_array($order->status, [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI], true)) {
            return back()->with('toast', ['message' => 'Refund hanya dapat diajukan untuk pesanan yang sudah dikirim atau selesai.', 'icon' => 'info']);
        }

        if (\App\Models\Refund::where('order_id', $order->order_id)->whereIn('status', [\App\Models\Refund::STATUS_REQUESTED, \App\Models\Refund::STATUS_ESKALASI, \App\Models\Refund::STATUS_DISETUJUI])->exists()) {
            return back()->with('toast', ['message' => 'Pesanan ini sudah memiliki pengajuan refund aktif.', 'icon' => 'info']);
        }

        $data = $request->validate([
            'tipe_refund' => ['required', 'in:full,partial'],
            'jumlah' => ['required', 'numeric', 'min:1', 'max:' . (float) $order->grand_total],
            'alasan' => ['required', 'string', 'min:20', 'max:2000'],
            'file_bukti_request' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'deskripsi_bukti_request' => ['nullable', 'string', 'max:1000'],
        ]);

        $path = $request->file('file_bukti_request')->store('bukti-refund-request/' . $order->order_id, 'public');

        $refund = \App\Models\Refund::create([
            'order_id' => $order->order_id,
            'payment_id' => $order->checkout?->payment?->payment_id,
            'requested_by' => Auth::id(),
            'tipe_refund' => $data['tipe_refund'],
            'alasan' => $data['alasan'],
            'jumlah' => $data['jumlah'],
            'status' => \App\Models\Refund::STATUS_REQUESTED,
            'diajukan_pada' => now(),
            'file_bukti_request' => $path,
            'deskripsi_bukti_request' => $data['deskripsi_bukti_request'] ?? null,
            'bukti_request_diupload_pada' => now(),
        ]);

        if ($order->store?->owner_id) {
            Notification::create([
                'user_id' => $order->store->owner_id,
                'tipe' => Notification::TIPE_ORDER,
                'judul' => 'Pengajuan Refund Baru',
                'pesan' => sprintf('Customer mengajukan refund %s untuk pesanan %s.', $refund->kode, $order->nomor_order),
                'url' => route('owner.pengembalian-dana'),
            ]);
        }

        return redirect()->route('customer.order-tracking', ['order' => $order->order_id])
            ->with('toast', ['message' => 'Pengajuan refund terkirim, menunggu diproses toko.', 'icon' => 'task_alt']);
    }
}