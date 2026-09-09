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

        DB::transaction(function () use ($order) {
            $order->update(['status' => Order::STATUS_SELESAI]);
            WalletService::creditOrder($order);

            if ($order->store && $order->store->owner_id) {
                Notification::create([
                    'user_id' => $order->store->owner_id,
                    'tipe' => Notification::TIPE_ORDER,
                    'judul' => 'Pesanan Selesai',
                    'pesan' => sprintf('Pesanan %s telah dikonfirmasi diterima oleh customer.', $order->nomor_order),
                ]);
            }
        });

        return redirect()->route('customer.order-tracking', ['order' => $order->order_id])
            ->with('toast', ['message' => 'Pesanan dikonfirmasi diterima. Terima kasih!', 'icon' => 'task_alt']);
    }
}