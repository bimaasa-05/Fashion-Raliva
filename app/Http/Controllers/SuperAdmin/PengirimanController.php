<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shipment;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with([
            'order:order_id,nomor_order,store_id',
            'order.store:store_id,nama_toko',
            'order.checkout.user:user_id,nama_lengkap',
            'courier:courier_id,nama_kurir',
            'shippingService:shipping_service_id,nama_layanan',
        ])->orderByDesc('shipments.created_at')->get();

        $stats = [
            'semua' => $shipments->count(),
            'pending' => $shipments->where('status', 'pending')->count(),
            'diproses' => $shipments->where('status', 'diproses')->count(),
            'dikirim' => $shipments->where('status', 'dikirim')->count(),
            'diterima' => $shipments->where('status', 'diterima')->count(),
            'gagal' => $shipments->where('status', 'gagal')->count(),
        ];

        return view('SuperAdmin.pengiriman.index', [
            'shipments' => $shipments,
            'stats' => $stats,
        ]);
    }

    public function updateStatus(Request $request, Shipment $pengiriman)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,diterima,gagal',
        ]);

        $newStatus = $validated['status'];
        $old = $pengiriman->only(['status', 'dikirim_pada', 'diterima_pada']);

        $updateData = ['status' => $newStatus];

        match ($newStatus) {
            'dikirim' => $updateData['dikirim_pada'] = now(),
            'diterima' => $updateData['diterima_pada'] = now(),
            'pending', 'diproses' => $updateData += ['dikirim_pada' => null, 'diterima_pada' => null],
            default => null,
        };

        $pengiriman->update($updateData);

        if ($newStatus === 'diterima' && $pengiriman->order) {
            $pengiriman->order->update(['status' => Order::STATUS_DITERIMA]);
        }

        if ($pengiriman->order?->checkout?->user_id) {
            Notification::create([
                'user_id' => $pengiriman->order->checkout->user_id,
                'tipe' => Notification::TIPE_PENGIRIMAN,
                'judul' => 'Status Pengiriman Diperbarui',
                'pesan' => sprintf('Pengiriman pesanan %s kini berstatus "%s".', $pengiriman->order->nomor_order, ucfirst($newStatus)),
            ]);
        }

        ActivityLogger::log(
            'sa.shipment.status',
            Shipment::class,
            $pengiriman->shipment_id,
            $old,
            $updateData,
            sprintf('Mengubah status pengiriman pesanan %s dari "%s" ke "%s".', $pengiriman->order->nomor_order ?? '-', $old['status'], $newStatus)
        );

        return back()->with('toast', [
            'message' => 'Status pengiriman berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}
