<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Notification;
use App\Models\StoreCourierSetting;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = (int) $request->query('store_id', $storeIds[0] ?? 0);
        if ($storeId && ! in_array($storeId, $storeIds, true)) {
            abort(403);
        }

        $stores = \App\Models\Store::whereIn('store_id', $storeIds)->get(['store_id', 'nama_toko']);

        $couriers = Courier::where('status', Courier::STATUS_AKTIF)
            ->where(fn ($q) => $q->whereNull('store_id')->orWhere('store_id', $storeId))
            ->with(['services' => fn ($q) => $q->where('status', 'aktif')
                ->where(fn ($qq) => $qq->whereNull('store_id')->orWhere('store_id', $storeId))
                ->orderBy('nama_layanan')])
            ->orderBy('nama_kurir')
            ->get();

        $settings = $storeId
            ? StoreCourierSetting::where('store_id', $storeId)->get()->keyBy(fn ($s) => $s->courier_id.'|'.($s->shipping_service_id ?? 0))
            : collect();

        return view('Admin.kurir.index', compact('stores', 'storeId', 'couriers', 'settings'));
    }

    public function sync(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = (int) $request->input('store_id');
        if (! $storeId || ! in_array($storeId, $storeIds, true)) {
            abort(403);
        }

        $data = $request->validate([
            'pengaturan' => 'nullable|array',
            'pengaturan.*.is_aktif' => 'nullable|boolean',
            'pengaturan.*.ongkir_override' => 'nullable|numeric|min:0',
            'pengaturan.*.estimasi_override' => 'nullable|integer|min:1|max:60',
        ]);

        foreach ($data['pengaturan'] ?? [] as $kunci => $row) {
            [$courierId, $serviceId] = array_pad(explode('|', (string) $kunci, 2), 2, null);
            $courierId = (int) $courierId;
            $serviceId = $serviceId ? (int) $serviceId : null;
            if (! $courierId) continue;

            StoreCourierSetting::updateOrCreate(
                ['store_id' => $storeId, 'courier_id' => $courierId, 'shipping_service_id' => $serviceId],
                [
                    'is_aktif' => ! empty($row['is_aktif']),
                    'ongkir_override' => $row['ongkir_override'] ?? null,
                    'estimasi_override' => $row['estimasi_override'] ?? null,
                ]
            );
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Metode Pengiriman Diperbarui', 'Pengaturan kurir toko berhasil disimpan.', route('admin.kurir'));

        return back()->with('success', 'Pengaturan kurir toko berhasil disimpan.');
    }

    public function storeCourier(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = (int) $request->input('store_id');
        if (! $storeId || ! in_array($storeId, $storeIds, true)) {
            abort(403);
        }

        $data = $request->validate([
            'nama_kurir' => 'required|string|max:100',
            'kode_kurir' => 'nullable|string|max:30|unique:couriers,kode_kurir',
        ]);

        $kode = trim((string) ($data['kode_kurir'] ?? ''));
        if ($kode === '') {
            $dasar = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $data['nama_kurir']), 0, 3));
            if ($dasar === '') $dasar = 'KR';
            $kode = $dasar;
            $i = 1;
            while (Courier::where('kode_kurir', $kode)->exists()) {
                $kode = $dasar.($i++);
            }
        }

        Courier::create([
            'store_id' => $storeId,
            'nama_kurir' => $data['nama_kurir'],
            'kode_kurir' => $kode,
            'status' => Courier::STATUS_AKTIF,
        ]);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kurir Ditambahkan', sprintf('Kurir "%s" ditambahkan untuk toko.', $data['nama_kurir']), route('admin.kurir'));

        return back()->with('success', 'Kurir baru berhasil ditambahkan untuk toko.');
    }

    public function updateCourier(Request $request, Courier $kurir)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! $kurir->store_id || ! in_array($kurir->store_id, $storeIds, true)) {
            abort(403, 'Hanya kurir milik toko yang bisa diubah. Kurir global dikelola SuperAdmin.');
        }

        $data = $request->validate([
            'nama_kurir' => 'required|string|max:100',
            'kode_kurir' => 'nullable|string|max:30',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $kurir->update($data);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kurir Diperbarui', sprintf('Kurir "%s" diperbarui.', $kurir->nama_kurir), route('admin.kurir'));

        return back()->with('success', 'Kurir berhasil diperbarui.');
    }

    public function destroyCourier(Courier $kurir)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! $kurir->store_id || ! in_array($kurir->store_id, $storeIds, true)) {
            abort(403, 'Hanya kurir milik toko yang bisa dihapus.');
        }
        if ($kurir->shipments()->exists() || $kurir->services()->exists()) {
            return back()->with('error', 'Kurir tidak dapat dihapus karena masih memiliki layanan/pengiriman.');
        }

        $nama = $kurir->nama_kurir;
        $kurir->delete();

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kurir Dihapus', sprintf('Kurir "%s" dihapus.', $nama), route('admin.kurir'));

        return back()->with('success', 'Kurir berhasil dihapus.');
    }

    public function storeLayanan(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();

        $data = $request->validate([
            'store_id' => 'required|integer',
            'courier_id' => 'required|integer|exists:couriers,courier_id',
            'nama_layanan' => 'required|string|max:100',
            'estimasi_hari' => 'required|integer|min:1|max:60',
            'tarif' => 'nullable|numeric|min:0|max:999999999',
        ]);

        if (! in_array((int) $data['store_id'], $storeIds, true)) {
            abort(403);
        }

        $courier = Courier::findOrFail($data['courier_id']);
        if ($courier->store_id && ! in_array($courier->store_id, $storeIds, true)) {
            abort(403);
        }

        \App\Models\ShippingService::create([
            'store_id' => (int) $data['store_id'],
            'courier_id' => $courier->courier_id,
            'nama_layanan' => $data['nama_layanan'],
            'estimasi_hari' => $data['estimasi_hari'],
            'tarif' => $data['tarif'] ?? 0,
            'status' => \App\Models\ShippingService::STATUS_AKTIF,
        ]);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Layanan Ditambahkan', sprintf('Layanan "%s" ditambahkan pada %s.', $data['nama_layanan'], $courier->nama_kurir), route('admin.kurir'));

        return back()->with('success', 'Layanan pengiriman berhasil ditambahkan.');
    }

    public function updateLayanan(Request $request, \App\Models\ShippingService $layanan)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! $layanan->store_id || ! in_array($layanan->store_id, $storeIds, true)) {
            abort(403, 'Hanya layanan milik toko yang bisa diubah.');
        }

        $data = $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'estimasi_hari' => 'required|integer|min:1|max:60',
            'tarif' => 'nullable|numeric|min:0|max:999999999',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data['tarif'] = $data['tarif'] ?? 0;

        $layanan->update($data);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Layanan Diperbarui', sprintf('Layanan "%s" diperbarui.', $layanan->nama_layanan), route('admin.kurir'));

        return back()->with('success', 'Layanan pengiriman berhasil diperbarui.');
    }

    public function destroyLayanan(\App\Models\ShippingService $layanan)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! $layanan->store_id || ! in_array($layanan->store_id, $storeIds, true)) {
            abort(403, 'Hanya layanan milik toko yang bisa dihapus.');
        }
        if ($layanan->shipments()->exists()) {
            return back()->with('error', 'Layanan tidak dapat dihapus karena sudah dipakai pengiriman.');
        }

        $nama = $layanan->nama_layanan;
        $layanan->delete();

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Layanan Dihapus', sprintf('Layanan "%s" dihapus.', $nama), route('admin.kurir'));

        return back()->with('success', 'Layanan pengiriman berhasil dihapus.');
    }
}
