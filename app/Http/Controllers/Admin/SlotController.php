<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\SlotPurchaseRequest;
use App\Models\User;
use App\Support\AdminContext;
use App\Support\SlotService;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $stores = \App\Models\Store::whereIn('store_id', $storeIds)->get(['store_id', 'nama_toko']);

        $kuota = [];
        foreach ($storeIds as $sid) {
            $total = SlotService::totalQuota($sid);
            $used = SlotService::usedSlots($sid);
            $kuota[$sid] = [
                'total' => $total,
                'used' => $used,
                'sisa' => max(0, $total - $used),
                'progress' => SlotService::progress($sid),
            ];
        }

        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->orderBy('nama_metode')->get();
        $hargaPerSlot = SlotService::hargaPerSlot();

        return view('Admin.slot.index', compact('stores', 'kuota', 'metode', 'hargaPerSlot'));
    }

    public function store(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();

        $data = $request->validate([
            'store_id' => ['required', 'integer'],
            'jumlah_slot' => ['required', 'integer', 'min:1', 'max:1000'],
            'alasan' => ['nullable', 'string', 'max:500'],
            'metode_pembayaran' => ['required', 'integer', 'exists:payment_methods,payment_method_id'],
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'file_bukti.required' => 'Bukti pembayaran wajib dilampirkan.',
            'file_bukti.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti pembayaran maksimal 4 MB.',
        ]);

        if (! in_array((int) $data['store_id'], $storeIds, true)) {
            abort(403);
        }
        $storeId = (int) $data['store_id'];

        $metode = PaymentMethod::find($data['metode_pembayaran']);
        $hargaPerSlot = SlotService::hargaPerSlot();
        $totalHarga = (int) $data['jumlah_slot'] * $hargaPerSlot;

        $path = $request->file('file_bukti')->store('slot-bukti/'.$storeId, 'public');

        SlotPurchaseRequest::create([
            'store_id' => $storeId,
            'jumlah_slot' => (int) $data['jumlah_slot'],
            'harga_per_slot' => $hargaPerSlot,
            'total_harga' => $totalHarga,
            'payment_status' => SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI,
            'paid_at' => now(),
            'metode_pembayaran' => $metode?->nama_metode,
            'alasan' => $data['alasan'] ?? null,
            'file_bukti' => $path,
            'status' => SlotPurchaseRequest::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);

        $sa = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', User::STATUS_AKTIF)
            ->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $request->user()->user_id,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Pengajuan Pembelian Slot',
                'pesan' => sprintf('Admin mengajukan pembelian %d slot (Rp %s) menunggu persetujuan.', (int) $data['jumlah_slot'], number_format($totalHarga, 0, ',', '.')),
                'url' => route('superadmin.slot-produk'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengajuan Slot Terkirim', sprintf('Pengajuan pembelian %d slot diajukan.', (int) $data['jumlah_slot']), route('admin.slot'));

        return back()->with('success', 'Pengajuan pembelian '.$data['jumlah_slot'].' slot diajukan. Super Admin dapat langsung menyetujui atau menolak.');
    }
}
