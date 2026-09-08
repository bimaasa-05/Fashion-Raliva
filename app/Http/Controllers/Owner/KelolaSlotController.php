<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\SlotGrant;
use App\Models\SlotPurchaseRequest;
use App\Support\OwnerContext;
use App\Support\SlotService;
use Illuminate\Http\Request;

class KelolaSlotController extends Controller
{
    public function index()
    {
        $storeId = OwnerContext::firstStoreId();
        $store = OwnerContext::currentStore();

        $total = SlotService::totalQuota($storeId ?? 0);
        $used = SlotService::usedSlots($storeId ?? 0);
        $sisa = max(0, $total - $used);
        $pct = $total > 0 ? round($used / $total * 100) : 0;

        $beli = SlotPurchaseRequest::where('store_id', $storeId)
            ->select('created_at', 'jumlah_slot', 'status', 'payment_status', 'total_harga', 'alasan', 'slot_purchase_id')
            ->get()
            ->map(fn ($r) => [
                'tanggal' => $r->created_at,
                'jumlah_slot' => $r->jumlah_slot,
                'tipe' => 'permintaan',
                'catatan' => $r->alasan,
                'status' => $r->status === SlotPurchaseRequest::STATUS_PENDING ? 'pending' : $r->status,
                'payment_status' => $r->payment_status,
                'total_harga' => $r->total_harga,
                'ref' => $r->slot_purchase_id,
            ]);

        $grants = SlotGrant::where('store_id', $storeId)
            ->select('created_at', 'jumlah_slot', 'tipe', 'keterangan', 'slot_grant_id')
            ->get()
            ->map(fn ($g) => [
                'tanggal' => $g->created_at,
                'jumlah_slot' => $g->jumlah_slot,
                'tipe' => $g->tipe,
                'catatan' => $g->keterangan,
                'status' => 'aktif',
                'payment_status' => null,
                'total_harga' => null,
                'ref' => $g->slot_grant_id,
            ]);

        $riwayat = $grants->concat($beli)->sortByDesc('tanggal')->take(15)->values();

        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->orderBy('nama_metode')->get();
        $hargaPerSlot = SlotService::hargaPerSlot();

        return view('Owner.kelola-slot.index', compact('store', 'total', 'used', 'sisa', 'pct', 'riwayat', 'metode', 'hargaPerSlot'));
    }

    public function store(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) return back()->with('error', 'Anda belum memiliki toko.');

        $data = $request->validate([
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

        $metode = PaymentMethod::find($data['metode_pembayaran']);
        $hargaPerSlot = SlotService::hargaPerSlot();
        $totalHarga = (int) $data['jumlah_slot'] * $hargaPerSlot;

        $path = $request->file('file_bukti')->store('slot-bukti/'.$storeId, 'public');

        SlotPurchaseRequest::create([
            'store_id' => $storeId,
            'jumlah_slot' => (int) $data['jumlah_slot'],
            'harga_per_slot' => $hargaPerSlot,
            'total_harga' => $totalHarga,
            'payment_status' => SlotPurchaseRequest::PEMBAYARAN_MENUNGGU_VERIFIKASI,
            'metode_pembayaran' => $metode?->nama_metode,
            'alasan' => $data['alasan'] ?? null,
            'file_bukti' => $path,
            'status' => SlotPurchaseRequest::STATUS_PENDING,
            'diajukan_pada' => now(),
        ]);

        return back()->with('success', 'Pengajuan pembelian '.$data['jumlah_slot'].' slot (Rp '.number_format($totalHarga, 0, ',', '.').') diajukan. Bukti pembayaran akan diverifikasi SuperAdmin.');
    }
}