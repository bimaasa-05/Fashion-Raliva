<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shipment;
use App\Support\ActivityLogger;
use App\Support\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    public function index()
    {
        $query = Shipment::with([
            'order:order_id,nomor_order,store_id',
            'order.store:store_id,nama_toko',
            'order.checkout.user:user_id,nama_lengkap',
            'courier:courier_id,nama_kurir',
            'shippingService:shipping_service_id,nama_layanan',
        ])->orderByDesc('shipments.created_at');

        $stats = [
            'semua' => Shipment::count(),
            'pending' => Shipment::where('status', 'pending')->count(),
            'diproses' => Shipment::where('status', 'diproses')->count(),
            'dikirim' => Shipment::where('status', 'dikirim')->count(),
            'diterima' => Shipment::where('status', 'diterima')->count(),
            'gagal' => Shipment::where('status', 'gagal')->count(),
        ];

        $shipments = $query->paginate(20)->withQueryString();

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

        try {
            DB::transaction(function () use ($pengiriman, $newStatus) {
                $locked = Shipment::whereKey($pengiriman->shipment_id)->lockForUpdate()->first();

                if (! $locked) {
                    throw new \RuntimeException('Pengiriman tidak ditemukan.');
                }

                if (! $locked->canTransitionTo($newStatus)) {
                    throw new \RuntimeException(sprintf('Status pengiriman tidak dapat diubah dari "%s" menjadi "%s".', $locked->status, $newStatus));
                }

                if ($newStatus === Shipment::STATUS_DIKIRIM && empty(trim((string) $locked->nomor_resi))) {
                    throw new \RuntimeException('Nomor resi wajib diisi terlebih dahulu sebelum menandai dikirim.');
                }

                $old = $locked->only(['status', 'dikirim_pada', 'diterima_pada']);

                $updateData = ['status' => $newStatus];

                match ($newStatus) {
                    Shipment::STATUS_DIKIRIM => $updateData['dikirim_pada'] = now(),
                    Shipment::STATUS_DITERIMA => $updateData['diterima_pada'] = now(),
                    Shipment::STATUS_PENDING, Shipment::STATUS_DIPROSES => $updateData += ['dikirim_pada' => null, 'diterima_pada' => null],
                    default => null,
                };

                $locked->update($updateData);

                if ($newStatus === Shipment::STATUS_DITERIMA) {
                    $order = $locked->order()->lockForUpdate()->first();

                    if ($order && $order->status !== Order::STATUS_SELESAI) {
                        $order->update(['status' => Order::STATUS_SELESAI]);
                        WalletService::creditOrder($order);
                    }
                }

                ActivityLogger::log(
                    'sa.shipment.status',
                    Shipment::class,
                    $locked->shipment_id,
                    $old,
                    $updateData,
                    sprintf('Mengubah status pengiriman pesanan %s dari "%s" ke "%s".', $locked->order->nomor_order ?? '-', $old['status'], $newStatus)
                );
            });
        } catch (\Throwable $e) {
            return back()->with('toast', [
                'message' => 'Status pengiriman tidak dapat diperbarui: '.$e->getMessage(),
                'icon' => 'gpp_maybe',
            ]);
        }

        $pengiriman->refresh();

        if ($pengiriman->order?->checkout?->user_id) {
            Notification::create([
                'user_id' => $pengiriman->order->checkout->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PENGIRIMAN,
                'judul' => 'Status Pengiriman Diperbarui',
                'pesan' => sprintf('Pengiriman pesanan %s kini berstatus "%s".', $pengiriman->order->nomor_order, ucfirst($newStatus)),
                'url' => route('customer.order-tracking'),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_PENGIRIMAN, 'Status Pengiriman Diperbarui', sprintf('Status pengiriman pesanan %s diubah ke "%s".', $pengiriman->order->nomor_order ?? '-', ucfirst($newStatus)), route('superadmin.pengiriman'));

        return back()->with('toast', [
            'message' => 'Status pengiriman berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}
