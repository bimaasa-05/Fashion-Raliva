<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
     * 1=Disiapkan (Produksi), 2=Dikemas (Produksi), 3=Dikirim (Admin), 4=Diterima (Customer).
     */
    public const STATUS_STEPS = [
        Order::STATUS_PENDING_PAYMENT => 0,
        Order::STATUS_DIBAYAR => 1,
        Order::STATUS_MENUNGGU_PRODUKSI => 1,
        Order::STATUS_DIPROSES => 1,
        Order::STATUS_MENUNGGU_QC => 1,
        Order::STATUS_SIAP_KIRIM => 2,
        Order::STATUS_DIKIRIM => 3,
        Order::STATUS_SELESAI => 4,
        Order::STATUS_DIBATALKAN => null,
        Order::STATUS_REFUND => null,
    ];

    public const STATUS_LABELS = [
        Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
        Order::STATUS_DIBAYAR => 'Disiapkan',
        Order::STATUS_MENUNGGU_PRODUKSI => 'Disiapkan',
        Order::STATUS_DIPROSES => 'Disiapkan',
        Order::STATUS_MENUNGGU_QC => 'Disiapkan',
        Order::STATUS_SIAP_KIRIM => 'Dikemas',
        Order::STATUS_DIKIRIM => 'Dikirim',
        Order::STATUS_SELESAI => 'Diterima',
        Order::STATUS_DIBATALKAN => 'Dibatalkan',
        Order::STATUS_REFUND => 'Refund',
    ];

    /**
     * Warna pill per status: [kelas pill, kelas dot]. Merah khusus tolak/batal.
     */
    public const STATUS_COLORS = [
        Order::STATUS_PENDING_PAYMENT => ['bg-amber-100 text-amber-800 border-amber-500/30', 'bg-amber-500'],
        Order::STATUS_DIBAYAR => ['bg-blue-100 text-blue-800 border-blue-500/30', 'bg-blue-500'],
        Order::STATUS_MENUNGGU_PRODUKSI => ['bg-blue-100 text-blue-800 border-blue-500/30', 'bg-blue-500'],
        Order::STATUS_DIPROSES => ['bg-blue-100 text-blue-800 border-blue-500/30', 'bg-blue-500'],
        Order::STATUS_MENUNGGU_QC => ['bg-blue-100 text-blue-800 border-blue-500/30', 'bg-blue-500'],
        Order::STATUS_SIAP_KIRIM => ['bg-purple-100 text-purple-800 border-purple-500/30', 'bg-purple-500'],
        Order::STATUS_DIKIRIM => ['bg-sky-100 text-sky-800 border-sky-500/30', 'bg-sky-500'],
        Order::STATUS_SELESAI => ['bg-emerald-100 text-emerald-800 border-emerald-500/30', 'bg-emerald-500'],
        Order::STATUS_DIBATALKAN => ['bg-red-100 text-red-800 border-red-500/30', 'bg-red-500'],
        Order::STATUS_REFUND => ['bg-orange-100 text-orange-800 border-orange-500/30', 'bg-orange-500'],
    ];

    /**
     * Halaman order tracking: menampilkan pesanan milik user dari data nyata.
     */
    public function index(Request $request)
    {
        \App\Support\PaymentExpiry::expireOverdue();
        \App\Support\OrderAutoComplete::selesaikanOtomatis();

        $orders = Auth::user()->orders()
            ->with([
                'store',
                'items.productVariant.product.images',
                'shipments.courier',
                'refunds',
                'complaints',
                'checkout.payment.paymentMethod',
                'checkout.payment.account',
            ])
            ->orderByDesc('orders.created_at')
            ->get();

        if ($orders->isEmpty()) {
            return view('customer.order-tracking.index', [
                'orders' => collect(),
                'selected' => null,
                'selectedStep' => 0,
                'isPickup' => false,
            ]);
        }

        $selectedOrderId = (int) $request->query('order');
        $selected = $orders->firstWhere('order_id', $selectedOrderId) ?? $orders->first();

        // Alasan pembatalan (ditulis admin) diambil dari log aktivitas — tanpa migration.
        $alasanPembatalan = null;
        if ($selected->status === Order::STATUS_DIBATALKAN) {
            $cancelLog = ActivityLog::where('aksi', 'admin.order.cancel')
                ->where('target_tipe', Order::class)
                ->where('target_id', $selected->order_id)
                ->orderByDesc('activity_log_id')
                ->first(['nilai_baru']);
            $alasanPembatalan = $cancelLog?->nilai_baru['alasan'] ?? null;
        }

        // Timeline ceklis berbasis aksi role:
        // Disiapkan ✓ saat Produksi klik Selesai (menunggu_qc),
        // Dikemas ✓ saat Produksi klik Selesai QC+PACKING (siap_kirim),
        // Dikirim ✓ saat Admin klik Tandai Dikirim (dikirim);
        // Siap Diambil ✓ saat pesanan offline mencapai siap_kirim;
        // Diterima / Selesai Diambil ✓ saat Customer klik Konfirmasi atau Admin tandai Selesai (selesai).
        // Pesanan offline (ambil di toko) memakai cabang pickup: tanpa resi/kurir.
        $isPickup = $selected->isOffline();
        $hasResi = ! $isPickup && $selected->shipments->contains(fn ($s) => ! empty($s->nomor_resi));
        $timelineStatus = $selected->status;
        $timeline = [
            [
                'label' => __('Disiapkan'),
                'role' => 'Produksi',
                'done' => in_array($timelineStatus, [Order::STATUS_MENUNGGU_QC, Order::STATUS_SIAP_KIRIM, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI], true),
            ],
            [
                'label' => __('Dikemas'),
                'role' => 'Produksi',
                'done' => in_array($timelineStatus, [Order::STATUS_SIAP_KIRIM, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI], true),
            ],
            [
                'label' => $isPickup ? __('Siap Diambil') : __('Dikirim'),
                'role' => 'Admin',
                'done' => $isPickup
                    ? in_array($timelineStatus, [Order::STATUS_SIAP_KIRIM, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI], true)
                    : in_array($timelineStatus, [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI], true),
            ],
            [
                'label' => $isPickup ? __('Selesai Diambil') : __('Diterima'),
                'role' => 'Customer',
                'done' => $timelineStatus === Order::STATUS_SELESAI,
            ],
        ];

        // Komplain existing pesanan terpilih (satu pesanan = satu komplain).
        $existingComplaint = $selected->complaints->sortByDesc('complaint_id')->first();

        return view('customer.order-tracking.index', [
            'orders' => $orders,
            'selected' => $selected,
            'selectedStep' => self::STATUS_STEPS[$selected->status] ?? 1,
            'alasanPembatalan' => $alasanPembatalan,
            'timeline' => $timeline,
            'hasResi' => $hasResi,
            'isPickup' => $isPickup,
            'existingComplaint' => $existingComplaint,
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
                \App\Support\StockDeductionService::deductForOrder($locked);

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

}