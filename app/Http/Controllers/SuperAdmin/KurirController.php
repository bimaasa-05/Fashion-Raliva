<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\ShippingService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function index()
    {
        $couriers = Courier::query()
            ->with('services')
            ->orderBy('nama_kurir')
            ->get();

        $totalLayanan = ShippingService::count();

        return view('SuperAdmin.kurir.index', [
            'couriers' => $couriers,
            'layananOptions' => ShippingService::where('status', ShippingService::STATUS_AKTIF)->get(),
            'stats' => [
                'total' => $couriers->count(),
                'aktif' => $couriers->where('status', Courier::STATUS_AKTIF)->count(),
                'layanan' => $totalLayanan,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kurir' => 'required|string|max:100',
            'kode_kurir' => 'required|string|max:50|unique:couriers,kode_kurir',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_kurir.required' => 'Nama kurir wajib diisi.',
            'nama_kurir.max' => 'Nama kurir maksimal 100 karakter.',
            'kode_kurir.required' => 'Kode kurir wajib diisi.',
            'kode_kurir.unique' => 'Kode kurir sudah digunakan.',
            'kode_kurir.max' => 'Kode kurir maksimal 50 karakter.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $kurir = Courier::create($data);

        ActivityLogger::log(
            'courier.create',
            Courier::class,
            $kurir->courier_id,
            null,
            ['nama_kurir' => $kurir->nama_kurir, 'kode_kurir' => $kurir->kode_kurir],
            sprintf('Menambahkan kurir "%s" (%s).', $kurir->nama_kurir, $kurir->kode_kurir)
        );

        return back()->with('toast', [
            'message' => 'Kurir "'.$kurir->nama_kurir.'" berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, Courier $kurir)
    {
        $data = $request->validate([
            'nama_kurir' => 'required|string|max:100',
            'kode_kurir' => 'required|string|max:50|unique:couriers,kode_kurir,'.$kurir->courier_id.',courier_id',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_kurir.required' => 'Nama kurir wajib diisi.',
            'kode_kurir.required' => 'Kode kurir wajib diisi.',
            'kode_kurir.unique' => 'Kode kurir sudah digunakan.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $lama = $kurir->only(['nama_kurir', 'kode_kurir', 'status']);

        $kurir->update($data);

        ActivityLogger::log(
            'courier.update',
            Courier::class,
            $kurir->courier_id,
            $lama,
            ['nama_kurir' => $kurir->nama_kurir, 'kode_kurir' => $kurir->kode_kurir],
            sprintf('Mengubah kurir "%s" → "%s".', $lama['nama_kurir'], $kurir->nama_kurir)
        );

        return back()->with('toast', [
            'message' => 'Perubahan kurir "'.$kurir->nama_kurir.'" berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function hapus(Request $request, Courier $kurir)
    {
        $adaShipment = $kurir->shipments()->exists();

        if ($adaShipment) {
            return back()->with('toast', [
                'message' => 'Hapus dibatalkan — kurir "'.$kurir->nama_kurir.'" masih memiliki riwayat pengiriman.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $kurir->only(['nama_kurir', 'kode_kurir', 'status']);
        $layananCount = $kurir->services()->count();

        ActivityLogger::log(
            'courier.delete',
            Courier::class,
            $kurir->courier_id,
            $lama,
            null,
            sprintf('Menghapus kurir "%s"%s.', $kurir->nama_kurir, $layananCount > 0 ? " beserta {$layananCount} layanan" : '')
        );

        $kurir->delete();

        return back()->with('toast', [
            'message' => 'Kurir "'.$lama['nama_kurir'].'" berhasil dihapus.',
            'icon' => 'delete',
        ]);
    }

    public function storeLayanan(Request $request)
    {
        $data = $request->validate([
            'courier_id' => 'required|exists:couriers,courier_id',
            'nama_layanan' => 'required|string|max:100',
            'estimasi_hari' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'courier_id.required' => 'Kurir wajib dipilih.',
            'courier_id.exists' => 'Kurir tidak ditemukan.',
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.max' => 'Nama layanan maksimal 100 karakter.',
            'estimasi_hari.max' => 'Estimasi hari maksimal 50 karakter.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $kurir = Courier::find($data['courier_id']);

        $exists = ShippingService::where('courier_id', $data['courier_id'])
            ->whereRaw('LOWER(nama_layanan) = ?', [mb_strtolower($data['nama_layanan'])])
            ->exists();

        if ($exists) {
            return back()->with('toast', [
                'message' => 'Layanan "'.$data['nama_layanan'].'" sudah ada di kurir ini.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $layanan = ShippingService::create($data);

        ActivityLogger::log(
            'shipping_service.create',
            ShippingService::class,
            $layanan->shipping_service_id,
            null,
            ['courier_id' => $kurir->courier_id, 'nama_layanan' => $layanan->nama_layanan, 'estimasi_hari' => $layanan->estimasi_hari],
            sprintf('Menambahkan layanan "%s" ke kurir "%s".', $layanan->nama_layanan, $kurir->nama_kurir)
        );

        return back()->with('toast', [
            'message' => 'Layanan "'.$layanan->nama_layanan.'" berhasil ditambahkan ke '.$kurir->nama_kurir.'.',
            'icon' => 'task_alt',
        ]);
    }

    public function updateLayanan(Request $request, ShippingService $layanan)
    {
        $data = $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'estimasi_hari' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $exists = ShippingService::where('courier_id', $layanan->courier_id)
            ->whereRaw('LOWER(nama_layanan) = ?', [mb_strtolower($data['nama_layanan'])])
            ->where('shipping_service_id', '!=', $layanan->shipping_service_id)
            ->exists();

        if ($exists) {
            return back()->with('toast', [
                'message' => 'Layanan "'.$data['nama_layanan'].'" sudah ada di kurir ini.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $layanan->only(['nama_layanan', 'estimasi_hari', 'status']);

        $layanan->update($data);

        ActivityLogger::log(
            'shipping_service.update',
            ShippingService::class,
            $layanan->shipping_service_id,
            $lama,
            ['nama_layanan' => $layanan->nama_layanan, 'estimasi_hari' => $layanan->estimasi_hari],
            sprintf('Mengubah layanan "%s" → "%s".', $lama['nama_layanan'], $layanan->nama_layanan)
        );

        return back()->with('toast', [
            'message' => 'Perubahan layanan "'.$layanan->nama_layanan.'" berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function hapusLayanan(Request $request, ShippingService $layanan)
    {
        $adaShipment = $layanan->shipments()->exists();

        if ($adaShipment) {
            return back()->with('toast', [
                'message' => 'Hapus dibatalkan — layanan "'.$layanan->nama_layanan.'" masih memiliki riwayat pengiriman.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $layanan->only(['nama_layanan', 'estimasi_hari', 'status']);
        $kurirNama = Courier::where('courier_id', $layanan->courier_id)->value('nama_kurir') ?? '-';

        ActivityLogger::log(
            'shipping_service.delete',
            ShippingService::class,
            $layanan->shipping_service_id,
            $lama,
            null,
            sprintf('Menghapus layanan "%s" dari kurir "%s".', $layanan->nama_layanan, $kurirNama)
        );

        $layanan->delete();

        return back()->with('toast', [
            'message' => 'Layanan "'.$lama['nama_layanan'].'" berhasil dihapus.',
            'icon' => 'delete',
        ]);
    }
}
