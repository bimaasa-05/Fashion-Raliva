<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ProductSlotPackage;
use App\Models\Setting;
use App\Models\SlotGrant;
use App\Models\SlotPurchaseRequest;
use App\Models\Store;
use App\Support\ActivityLogger;
use App\Support\SlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlotProdukController extends Controller
{
    public function index(Request $request)
    {
        $section = in_array($request->query('section'), ['kuota', 'paket', 'permintaan'], true)
            ? $request->query('section')
            : 'kuota';

        $query = Store::query();
        if ($q = trim((string) $request->query('q'))) {
            $query->where(function ($qq) use ($q) {
                $qq->where('nama_toko', 'like', "%{$q}%")
                    ->orWhere('owner_id', 'like', "%{$q}%");
            });
        }

        $stores = $query->orderBy('nama_toko')->get(['store_id', 'nama_toko', 'owner_id', 'status']);
        $summary = SlotService::summaries($stores);

        $packages = ProductSlotPackage::withCount('subscriptions')->orderByDesc('slot_package_id')->get();

        $purchaseRequests = SlotPurchaseRequest::with(['store:store_id,nama_toko', 'handler:user_id,nama_lengkap'])
            ->orderByRaw('CASE status WHEN "pending" THEN 0 ELSE 1 END')
            ->orderByDesc('diajukan_pada')
            ->get();
        $pendingCount = $purchaseRequests->where('status', SlotPurchaseRequest::STATUS_PENDING)->count();

        $grantLog = SlotGrant::with(['store:store_id,nama_toko', 'creator:user_id,nama_lengkap'])
            ->orderByDesc('slot_grant_id')
            ->limit(50)
            ->get();

        $slotAwalDefault = (int) Setting::get(Setting::SLOT_AWAL_DEFAULT, '5');

        return view('SuperAdmin.slot-produk.index', compact(
            'section',
            'stores',
            'summary',
            'packages',
            'purchaseRequests',
            'pendingCount',
            'grantLog',
            'slotAwalDefault'
        ));
    }

    public function updateDefault(Request $request)
    {
        $data = $request->validate([
            'slot_awal' => 'required|integer|min:0|max:100000',
        ], [
            'slot_awal.required' => 'Jumlah slot awal wajib diisi.',
            'slot_awal.integer' => 'Jumlah slot awal harus berupa angka.',
            'slot_awal.min' => 'Jumlah slot awal tidak boleh negatif.',
            'slot_awal.max' => 'Jumlah slot awal terlalu besar.',
        ]);

        $nilaiBaru = (int) $data['slot_awal'];
        $nilaiLama = (int) Setting::get(Setting::SLOT_AWAL_DEFAULT, '5');

        if ($nilaiBaru === $nilaiLama) {
            return back()->with('toast', [
                'message' => sprintf('Tidak ada perubahan — slot awal toko baru sudah %d.', $nilaiBaru),
                'icon' => 'info',
            ]);
        }

        Setting::set(Setting::SLOT_AWAL_DEFAULT, (string) $nilaiBaru);

        ActivityLogger::log(
            'setting.slot.default',
            Setting::class,
            null,
            ['nilai' => (string) $nilaiLama],
            ['nilai' => (string) $nilaiBaru],
            sprintf('Mengubah slot awal toko baru dari %d menjadi %d slot.', $nilaiLama, $nilaiBaru)
        );

        return back()->with('toast', [
            'message' => sprintf('Slot awal toko baru diatur menjadi %d slot.', $nilaiBaru),
            'icon' => 'task_alt',
        ]);
    }

    public function grantManual(Request $request, Store $store)
    {
        $data = $request->validate([
            'jumlah_slot' => 'required|integer|min:1|max:100000',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'jumlah_slot.required' => 'Jumlah slot wajib diisi.',
            'jumlah_slot.integer' => 'Jumlah slot harus berupa angka.',
            'jumlah_slot.min' => 'Jumlah slot minimal 1.',
        ]);

        SlotService::grant(
            $store->store_id,
            (int) $data['jumlah_slot'],
            SlotGrant::TIPE_MANUAL,
            $data['keterangan'] ?? null
        );

        ActivityLogger::log(
            'slot.grant.manual',
            Store::class,
            $store->store_id,
            null,
            ['jumlah_slot' => (int) $data['jumlah_slot']],
            sprintf('Menambah %d slot manual untuk toko "%s".', (int) $data['jumlah_slot'], $store->nama_toko)
        );

        $this->notifyOwner($store, 'Slot Produk Ditambah', sprintf('Super Admin menambahkan %d slot produk untuk toko "%s".', (int) $data['jumlah_slot'], $store->nama_toko));

        return back()->with('toast', [
            'message' => sprintf('%d slot berhasil ditambahkan ke toko "%s".', (int) $data['jumlah_slot'], $store->nama_toko),
            'icon' => 'task_alt',
        ]);
    }

    public function storePackage(Request $request)
    {
        $data = $request->validate([
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'jumlah_slot' => 'required|integer|min:1',
            'durasi_hari' => 'required|integer|min:1',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'harga.required' => 'Harga paket wajib diisi.',
            'jumlah_slot.required' => 'Jumlah slot wajib diisi.',
            'durasi_hari.required' => 'Durasi wajib diisi.',
        ]);

        $paket = ProductSlotPackage::create([
            'nama_paket' => $data['nama_paket'],
            'harga' => $data['harga'],
            'jumlah_slot' => $data['jumlah_slot'],
            'durasi_hari' => $data['durasi_hari'],
            'status' => ProductSlotPackage::STATUS_AKTIF,
        ]);

        ActivityLogger::log(
            'slot.package.create',
            ProductSlotPackage::class,
            $paket->slot_package_id,
            null,
            $paket->toArray(),
            sprintf('Menambahkan paket slot "%s" (%d slot / %d hari).', $paket->nama_paket, $paket->jumlah_slot, $paket->durasi_hari)
        );

        return back()->with('toast', [
            'message' => sprintf('Paket slot "%s" berhasil ditambahkan.', $paket->nama_paket),
            'icon' => 'task_alt',
        ]);
    }

    public function togglePackage(Request $request, ProductSlotPackage $paket)
    {
        $baru = $paket->status === ProductSlotPackage::STATUS_AKTIF
            ? ProductSlotPackage::STATUS_NONAKTIF
            : ProductSlotPackage::STATUS_AKTIF;

        $lama = $paket->only(['status']);
        $paket->update(['status' => $baru]);

        ActivityLogger::log(
            'slot.package.toggle',
            ProductSlotPackage::class,
            $paket->slot_package_id,
            $lama,
            ['status' => $baru],
            sprintf('Mengubah status paket slot "%s" menjadi "%s".', $paket->nama_paket, $baru)
        );

        return back()->with('toast', [
            'message' => sprintf('Paket slot "%s" kini %s.', $paket->nama_paket, $baru === ProductSlotPackage::STATUS_AKTIF ? 'aktif' : 'nonaktif'),
            'icon' => 'task_alt',
        ]);
    }

    public function approvePurchase(Request $request, SlotPurchaseRequest $rmt)
    {
        if ($rmt->status !== SlotPurchaseRequest::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Permintaan sudah diproses.',
                'icon' => 'gpp_maybe',
            ]);
        }

        SlotService::grant(
            $rmt->store_id,
            $rmt->jumlah_slot,
            SlotGrant::TIPE_BELI,
            $rmt->alasan ?: 'Pembelian slot produk',
            $rmt->slot_purchase_id,
            SlotPurchaseRequest::class
        );

        $lama = $rmt->only(['status']);
        $rmt->update([
            'status' => SlotPurchaseRequest::STATUS_DISETUJUI,
            'handled_by' => Auth::id(),
        ]);

        ActivityLogger::log(
            'slot.purchase.approve',
            SlotPurchaseRequest::class,
            $rmt->slot_purchase_id,
            $lama,
            $rmt->only(['status', 'handled_by']),
            sprintf('Menyetujui pembelian %d slot untuk toko %s.', $rmt->jumlah_slot, $rmt->store->nama_toko ?? '-')
        );

        $this->notifyOwner($rmt->store, 'Pembelian Slot Disetujui', sprintf('Pembelian %d slot produk untuk toko "%s" telah disetujui.', $rmt->jumlah_slot, $rmt->store->nama_toko ?? '-'));

        return back()->with('toast', [
            'message' => sprintf('%d slot disetujui dan ditambahkan ke toko.', $rmt->jumlah_slot),
            'icon' => 'task_alt',
        ]);
    }

    public function rejectPurchase(Request $request, SlotPurchaseRequest $rmt)
    {
        if ($rmt->status !== SlotPurchaseRequest::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Permintaan sudah diproses.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $rmt->only(['status']);
        $rmt->update([
            'status' => SlotPurchaseRequest::STATUS_DITOLAK,
            'alasan_penolakan' => $data['alasan'],
            'handled_by' => Auth::id(),
        ]);

        ActivityLogger::log(
            'slot.purchase.reject',
            SlotPurchaseRequest::class,
            $rmt->slot_purchase_id,
            $lama,
            $rmt->only(['status', 'alasan_penolakan', 'handled_by']),
            sprintf('Menolak pembelian %d slot untuk toko %s. Alasan: %s', $rmt->jumlah_slot, $rmt->store->nama_toko ?? '-', $data['alasan'])
        );

        $this->notifyOwner($rmt->store, 'Pembelian Slot Ditolak', sprintf('Pembelian %d slot untuk toko "%s" ditolak. Alasan: %s', $rmt->jumlah_slot, $rmt->store->nama_toko ?? '-', $data['alasan']));

        return back()->with('toast', [
            'message' => 'Permintaan pembelian slot ditolak.',
            'icon' => 'block',
        ]);
    }

    private function notifyOwner(?Store $store, string $judul, string $pesan): void
    {
        if (! $store || ! $store->owner_id) {
            return;
        }

        Notification::create([
            'user_id' => $store->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => $judul,
            'pesan' => $pesan,
        ]);
    }
}
