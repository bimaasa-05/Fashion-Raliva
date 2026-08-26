<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShippingService;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();

        $siapDikirim = Order::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_DIPROSES)
            ->whereDoesntHave('shipments')
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap'])
            ->orderByDesc('created_at')
            ->get();

        $shipments = Shipment::query()
            ->whereHas('order', fn ($query) => $query->whereIn('store_id', $storeIds))
            ->with(['order.store:store_id,nama_toko', 'order.checkout.user:user_id,nama_lengkap', 'courier', 'shippingService'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'diproses' THEN 1 WHEN 'dikirim' THEN 2 ELSE 3 END")
            ->orderByDesc('shipment_id')
            ->get();

        return view('Admin.pengiriman.index', [
            'siapDikirim' => $siapDikirim,
            'shipments' => $shipments,
            'couriers' => Courier::where('status', Courier::STATUS_AKTIF)->with('services')->orderBy('nama_kurir')->get(),
        ]);
    }

    public function simpanResi(Request $request, Order $pesanan)
    {
        if (! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($pesanan->status !== Order::STATUS_DIPROSES) {
            return back()->with('toast', [
                'message' => 'Hanya pesanan berstatus diproses yang dapat disiapkan pengirimannya.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'courier_id' => 'required|integer|exists:couriers,courier_id',
            'shipping_service_id' => 'nullable|integer|exists:shipping_services,shipping_service_id',
            'nomor_resi' => 'required|string|min:4|max:50',
            'estimasi_tiba' => 'nullable|date|after_or_equal:today',
        ], [
            'nomor_resi.required' => 'Nomor resi wajib diisi.',
            'nomor_resi.min' => 'Nomor resi minimal 4 karakter.',
        ]);

        $service = null;

        if (! empty($data['shipping_service_id'])) {
            $service = ShippingService::find($data['shipping_service_id']);

            if ($service && (int) $service->courier_id !== (int) $data['courier_id']) {
                return back()->with('toast', [
                    'message' => 'Layanan pengiriman tidak sesuai dengan kurir yang dipilih.',
                    'icon' => 'gpp_maybe',
                ]);
            }
        }

        $shipment = $pesanan->shipments()->first();

        $lama = $shipment?->only(['nomor_resi', 'courier_id', 'status']);

        if ($shipment) {
            $shipment->update([
                'courier_id' => $data['courier_id'],
                'shipping_service_id' => $service?->shipping_service_id,
                'nomor_resi' => $data['nomor_resi'],
                'estimasi_tiba' => $data['estimasi_tiba'] ?? ($service ? now()->addDays((int) $service->estimasi_hari) : null),
                'status' => Shipment::STATUS_DIPROSES,
            ]);
        } else {
            $shipment = Shipment::create([
                'order_id' => $pesanan->order_id,
                'courier_id' => $data['courier_id'],
                'shipping_service_id' => $service?->shipping_service_id,
                'nomor_resi' => $data['nomor_resi'],
                'ongkir' => $pesanan->total_ongkir,
                'estimasi_tiba' => $data['estimasi_tiba'] ?? ($service ? now()->addDays((int) $service->estimasi_hari) : null),
                'status' => Shipment::STATUS_DIPROSES,
            ]);
        }

        ActivityLogger::log(
            'admin.shipment.resi',
            Shipment::class,
            $shipment->shipment_id,
            $lama,
            ['nomor_resi' => $data['nomor_resi'], 'courier_id' => $data['courier_id']],
            sprintf('Input resi %s untuk pesanan %s.', $data['nomor_resi'], $pesanan->nomor_order)
        );

        return back()->with('toast', [
            'message' => "Resi untuk pesanan {$pesanan->nomor_order} tersimpan. Siap ditandai dikirim.",
            'icon' => 'task_alt',
        ]);
    }

    public function kirim(Request $request, Shipment $pengiriman)
    {
        $pesanan = $pengiriman->order;

        if (! $pesanan || ! AdminContext::canAccessStore($pesanan->store_id)) {
            return back()->with('toast', [
                'message' => 'Pengiriman ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if (! in_array($pengiriman->status, [Shipment::STATUS_PENDING, Shipment::STATUS_DIPROSES], true)) {
            return back()->with('toast', [
                'message' => 'Pengiriman ini sudah dikirim atau bermasalah.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if (empty($pengiriman->nomor_resi)) {
            return back()->with('toast', [
                'message' => 'Isi nomor resi terlebih dahulu sebelum menandai dikirim.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $pengiriman->update([
            'status' => Shipment::STATUS_DIKIRIM,
            'dikirim_pada' => now(),
        ]);

        if ($pesanan->status === Order::STATUS_DIPROSES) {
            $pesanan->update(['status' => Order::STATUS_DIKIRIM]);
        }

        ActivityLogger::log(
            'admin.shipment.send',
            Shipment::class,
            $pengiriman->shipment_id,
            ['status' => Shipment::STATUS_DIPROSES],
            ['status' => Shipment::STATUS_DIKIRIM],
            sprintf('Menandai pesanan %s dikirim dengan resi %s.', $pesanan->nomor_order, $pengiriman->nomor_resi)
        );

        if ($pesanan->checkout?->user_id) {
            Notification::create([
                'user_id' => $pesanan->checkout->user_id,
                'tipe' => Notification::TIPE_PENGIRIMAN,
                'judul' => 'Pesanan Dikirim',
                'pesan' => sprintf('Pesanan %s telah dikirim via %s dengan resi %s.', $pesanan->nomor_order, $pengiriman->courier?->nama_kurir ?? 'kurir', $pengiriman->nomor_resi),
            ]);
        }

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} ditandai dikirim.",
            'icon' => 'local_shipping',
        ]);
    }
}
