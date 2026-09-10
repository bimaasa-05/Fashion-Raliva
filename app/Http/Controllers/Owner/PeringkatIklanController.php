<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\PaymentMethod;
use App\Models\PlatformBankAccount;
use App\Models\Product;
use App\Models\Setting;
use App\Support\OwnerContext;
use App\Support\PeringkatService;
use Illuminate\Http\Request;

class PeringkatIklanController extends Controller
{
    public function index()
    {
        $storeId = OwnerContext::firstStoreId();
        $store = OwnerContext::currentStore();

        $products = collect();
        $rekenings = collect();
        $metode = collect();
        $slots = collect();

        if ($storeId) {
            $products = Product::where('store_id', $storeId)
                ->where('status', Product::STATUS_AKTIF)
                ->orderBy('nama_produk')
                ->get(['product_id', 'nama_produk']);

            $slots = AdSlot::with(['product:product_id,nama_produk', 'bankAccount.bank', 'handler:user_id,nama_lengkap'])
                ->where('store_id', $storeId)
                ->orderByDesc('created_at')
                ->paginate(10)->withQueryString();
        }

        $rekenings = PlatformBankAccount::with('bank')->where('status', PlatformBankAccount::STATUS_AKTIF)->orderBy('nomor_rekening')->get();
        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->orderBy('nama_metode')->get();
        $tiers = PeringkatService::defaultTiers();
        $raw = Setting::get(Setting::PERINGKAT_TIER, null);
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && $decoded !== []) $tiers = $decoded;
        }

        return view('Owner.peringkat-iklan.index', compact('store', 'products', 'rekenings', 'metode', 'slots', 'tiers'));
    }

    public function store(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) {
            return back()->with('error', 'Anda belum memiliki toko.');
        }

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'nominal_bid' => ['required', 'numeric', 'min:100000'],
            'platform_bank_account_id' => ['required', 'exists:platform_bank_accounts,platform_bank_account_id'],
            'metode_pembayaran' => ['required', 'integer', 'exists:payment_methods,payment_method_id'],
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'nominal_bid.min' => 'Minimal Rp 100.000.',
            'platform_bank_account_id.required' => 'Pilih rekening tujuan transfer.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'file_bukti.required' => 'Bukti pembayaran wajib dilampirkan.',
            'file_bukti.mimes' => 'Bukti harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti maksimal 5 MB.',
        ]);

        $product = Product::where('product_id', $data['product_id'])->where('store_id', $storeId)->first();
        if (! $product) {
            return back()->with('error', 'Produk tidak valid untuk toko Anda.');
        }

        $metode = PaymentMethod::find($data['metode_pembayaran']);
        $path = $request->file('file_bukti')->store('bukti-iklan/'.$storeId, 'public');

        $hari = PeringkatService::resolveHari((int) $data['nominal_bid']);

        $slot = AdSlot::create([
            'product_id' => $data['product_id'],
            'store_id' => $storeId,
            'nominal_bid' => $data['nominal_bid'],
            'payment_status' => AdSlot::PAYMENT_MENUNGGU,
            'metode_pembayaran' => $metode?->nama_metode,
            'platform_bank_account_id' => $data['platform_bank_account_id'],
            'file_bukti' => $path,
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
            'status' => AdSlot::STATUS_DITUNDA,
        ]);

        $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', \App\Models\User::STATUS_AKTIF)->first();
        if ($sa) {
            \App\Models\Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $request->user()->user_id,
                'tipe' => \App\Models\Notification::TIPE_PROMO,
                'judul' => 'Pengajuan Iklan Peringkat',
                'pesan' => sprintf('Pengajuan iklan "%s" (Rp %s, %d hari) menunggu verifikasi.', $product->nama_produk, number_format((float) $data['nominal_bid'], 0, ',', '.'), $hari),
                'url' => route('superadmin.peringkat-iklan'),
            ]);
        }

        return back()->with('success', 'Pengajuan iklan berhasil diajukan ('.$hari.' hari, periode aktif sejak disetujui). Menunggu verifikasi Super Admin.');
    }
}