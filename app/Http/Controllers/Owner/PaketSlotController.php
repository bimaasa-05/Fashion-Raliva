<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\ProductSlotPackage;
use App\Models\Role;
use App\Models\SlotGrant;
use App\Models\StoreSlotSubscription;
use App\Services\NotificationService;
use App\Support\OwnerContext;
use App\Support\SlotService;
use Illuminate\Http\Request;

class PaketSlotController extends Controller
{
    public function index()
    {
        $storeId = OwnerContext::firstStoreId();

        $subs = StoreSlotSubscription::where('store_id', $storeId)
            ->where('status', StoreSlotSubscription::STATUS_AKTIF)
            ->first();

        $total = SlotService::totalQuota($storeId ?? 0);
        $used = SlotService::usedSlots($storeId ?? 0);
        $sisa = max(0, $total - $used);
        $progress = $total > 0 ? (int) round($used / $total * 100) : 0;

        $paketAktif = $subs?->package;

        $active = [
            'nama' => $paketAktif?->nama_paket ?? 'Fleksibel',
            'harga' => $paketAktif?->harga !== null
                ? 'Rp '.number_format($paketAktif->harga, 0, ',', '.')
                : 'Rp '.number_format(SlotService::hargaPerSlot(), 0, ',', '.').' /slot',
            'total' => $total,
            'used' => $used,
            'sisa' => $sisa,
            'progress' => $progress,
        ];

        $packages = ProductSlotPackage::where('status', ProductSlotPackage::STATUS_AKTIF)
            ->orderBy('jumlah_slot')
            ->get();

        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->orderBy('nama_metode')->get();
        $hargaPerSlot = SlotService::hargaPerSlot();

        $riwayat = StoreSlotSubscription::where('store_id', $storeId)
            ->with('package')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('Owner.paket-slot.index', compact('active', 'packages', 'metode', 'hargaPerSlot', 'riwayat'));
    }

    public function purchase(Request $request, ProductSlotPackage $paket)
    {
        if ($paket->status !== ProductSlotPackage::STATUS_AKTIF) {
            return back()->with('toast', ['message' => 'Paket ini sedang tidak tersedia.', 'icon' => 'gpp_maybe']);
        }

        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) return back()->with('error', 'Anda belum memiliki toko.');

        $data = $request->validate([
            'metode_pembayaran' => ['required', 'integer', 'exists:payment_methods,payment_method_id'],
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'file_bukti.required' => 'Bukti pembayaran wajib dilampirkan.',
            'file_bukti.mimes' => 'Bukti pembayaran harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti pembayaran maksimal 4 MB.',
        ]);

        $metode = PaymentMethod::find($data['metode_pembayaran']);
        $path = $request->file('file_bukti')->store('slot-bukti/'.$storeId, 'public');

        $sub = StoreSlotSubscription::create([
            'store_id' => $storeId,
            'slot_package_id' => $paket->slot_package_id,
            'tanggal_mulai' => now(),
            'tanggal_berakhir' => now()->addDays($paket->durasi_hari),
            'jumlah_slot' => $paket->jumlah_slot,
            'slot_terpakai' => 0,
            'status' => StoreSlotSubscription::STATUS_AKTIF,
        ]);

        SlotService::grant(
            $storeId,
            $paket->jumlah_slot,
            SlotGrant::TIPE_BELI,
            'Pembelian paket '.$paket->nama_paket,
            $sub->slot_subscription_id,
            StoreSlotSubscription::class
        );

        NotificationService::sendToRole(
            Role::SUPER_ADMIN,
            Notification::TIPE_SISTEM,
            'Pembelian Paket Slot',
            sprintf('Toko membeli paket slot "%s" (%d slot).', $paket->nama_paket, $paket->jumlah_slot),
            auth()->id(),
            route('superadmin.slot-produk')
        );
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Paket Slot Aktif', sprintf('Paket "%s" (%d slot) berhasil aktif.', $paket->nama_paket, $paket->jumlah_slot), route('owner.paket-slot'));

        return back()->with('success', 'Paket "'.$paket->nama_paket.'" ('.$paket->jumlah_slot.' slot) berhasil aktif. Kuota toko bertambah.');
    }
}