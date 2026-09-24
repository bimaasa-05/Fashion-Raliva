<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Notification;
use App\Models\StoreCourierSetting;
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
            ->where('status', Order::STATUS_SIAP_KIRIM)
            ->where('tipe_pesanan', Order::TIPE_PESANAN_ONLINE)
            ->whereDoesntHave('shipments')
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $siapDiambil = Order::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_SIAP_KIRIM)
            ->where(function ($q) {
                $q->where('tipe_pesanan', Order::TIPE_PESANAN_OFFLINE)
                    ->orWhere(function ($qq) {
                        $qq->where('tipe_pesanan', Order::TIPE_PESANAN_ONLINE)
                            ->whereDoesntHave('shipments');
                    });
            })
            ->with(['store:store_id,nama_toko', 'checkout.user:user_id,nama_lengkap', 'checkout.payment', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $shipments = Shipment::query()
            ->whereHas('order', fn ($query) => $query->whereIn('store_id', $storeIds))
            ->with(['order.store:store_id,nama_toko', 'order.checkout.user:user_id,nama_lengkap', 'courier', 'shippingService'])
            ->orderByDesc('created_at')
            ->orderByDesc('shipment_id')
            ->get();

        $couriers = Courier::where('status', Courier::STATUS_AKTIF)
            ->where(fn ($q) => $q->whereNull('store_id')->orWhereIn('store_id', $storeIds))
            ->with(['services' => fn ($q) => $q->where('status', 'aktif')
                ->where(fn ($qq) => $qq->whereNull('store_id')->orWhereIn('store_id', $storeIds))
                ->orderBy('nama_layanan')])
            ->orderBy('nama_kurir')->get();

        // Kurir aktif per toko: bila toko punya pengaturan, hanya yang is_aktif; bila belum ada, semua global.
        $kurirPerToko = [];
        foreach ($storeIds as $sid) {
            $set = StoreCourierSetting::where('store_id', $sid)->get();
            if ($set->isEmpty()) {
                $kurirPerToko[$sid] = $couriers->pluck('courier_id')->all();
            } else {
                $kurirPerToko[$sid] = $set->where('is_aktif', true)->whereNull('shipping_service_id')->pluck('courier_id')->all();
            }
        }

        return view('Admin.pengiriman.index', [
            'siapDikirim' => $siapDikirim,
            'siapDiambil' => $siapDiambil,
            'shipments' => $shipments,
            'couriers' => $couriers,
            'kurirPerToko' => $kurirPerToko,
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

        if ($pesanan->status !== Order::STATUS_SIAP_KIRIM) {
            return back()->with('toast', [
                'message' => 'Hanya pesanan berstatus siap kirim yang dapat disiapkan pengirimannya.',
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

        if (! $this->kurirAllowed($pesanan->store_id, (int) $data['courier_id'], $data['shipping_service_id'] ? (int) $data['shipping_service_id'] : null)) {
            return back()->with('toast', [
                'message' => 'Kurir/layanan ini nonaktif untuk toko pesanan. Ubah di menu Metode Pengiriman.',
                'icon' => 'gpp_maybe',
            ]);
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

        Notification::fireSelf(Notification::TIPE_PENGIRIMAN, 'Resi Tersimpan', sprintf('Resi %s untuk pesanan %s tersimpan.', $data['nomor_resi'], $pesanan->nomor_order), route('admin.pengiriman'));

        if ($pesanan->checkout?->user_id) {
            Notification::create([
                'user_id' => $pesanan->checkout->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PENGIRIMAN,
                'judul' => 'Resi Pesanan Tersedia',
                'pesan' => sprintf('Pesanan %s akan dikirim via %s dengan resi %s.', $pesanan->nomor_order, $shipment->courier?->nama_kurir ?? 'kurir', $data['nomor_resi']),
                'url' => route('customer.order-tracking'),
            ]);
        }

        return back()->with('toast', [
            'message' => "Resi untuk pesanan {$pesanan->nomor_order} tersimpan. Siap ditandai dikirim.",
            'icon' => 'task_alt',
        ]);
    }

    private function kurirAllowed(int $storeId, int $courierId, ?int $serviceId): bool
    {
        $courier = Courier::where('courier_id', $courierId)
            ->where('status', Courier::STATUS_AKTIF)
            ->where(fn ($q) => $q->whereNull('store_id')->orWhere('store_id', $storeId))
            ->first();
        if (! $courier) {
            return false;
        }

        if ($serviceId) {
            $serviceOk = ShippingService::where('shipping_service_id', $serviceId)
                ->where('courier_id', $courierId)
                ->where('status', 'aktif')
                ->where(fn ($q) => $q->whereNull('store_id')->orWhere('store_id', $storeId))
                ->exists();
            if (! $serviceOk) {
                return false;
            }
        }

        $set = StoreCourierSetting::where('store_id', $storeId)->get();
        if ($set->isEmpty()) {
            return true;
        }

        $kurirOk = $set->where('courier_id', $courierId)->whereNull('shipping_service_id');
        if ($kurirOk->isNotEmpty() && ! $kurirOk->contains(fn ($s) => (bool) $s->is_aktif)) {
            return false;
        }

        if ($serviceId) {
            $layanan = $set->where('courier_id', $courierId)->where('shipping_service_id', $serviceId)->first();
            if ($layanan && ! $layanan->is_aktif) {
                return false;
            }
        }

        return true;
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

        if ($pesanan->status === Order::STATUS_SIAP_KIRIM) {
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

        Notification::fireSelf(Notification::TIPE_PENGIRIMAN, 'Pesanan Dikirim', sprintf('Pesanan %s ditandai dikirim (resi %s).', $pesanan->nomor_order, $pengiriman->nomor_resi), route('admin.pengiriman'));

        if ($pesanan->checkout?->user_id) {
            Notification::create([
                'user_id' => $pesanan->checkout->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PENGIRIMAN,
                'judul' => 'Pesanan Dikirim',
                'pesan' => sprintf('Pesanan %s telah dikirim via %s dengan resi %s.', $pesanan->nomor_order, $pengiriman->courier?->nama_kurir ?? 'kurir', $pengiriman->nomor_resi),
                'url' => route('customer.order-tracking'),
            ]);
        }

        return back()->with('toast', [
            'message' => "Pesanan {$pesanan->nomor_order} ditandai dikirim.",
            'icon' => 'local_shipping',
        ]);
    }
}
