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
            ->with(['services' => fn ($q) => $q->where('status', 'aktif')->orderBy('nama_layanan')])
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
}
