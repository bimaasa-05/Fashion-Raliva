<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\PlatformBankAccount;
use App\Models\Product;
use App\Models\User;
use App\Support\OwnerContext;
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

        $rekenings = PlatformBankAccount::with('bank')->whereNotNull('bank_id')->where('status', PlatformBankAccount::STATUS_AKTIF)->orderBy('nomor_rekening')->get();
        $metode = PaymentMethod::where('status', PaymentMethod::STATUS_AKTIF)->orderBy('nama_metode')->get();

        return view('Owner.peringkat-iklan.index', compact('store', 'products', 'rekenings', 'metode', 'slots'));
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
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'platform_bank_account_id' => ['required', 'exists:platform_bank_accounts,platform_bank_account_id'],
            'metode_pembayaran' => ['required', 'integer', 'exists:payment_methods,payment_method_id'],
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'nominal_bid.min' => 'Minimal Rp 100.000.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
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

        $slot = AdSlot::create([
            'product_id' => $data['product_id'],
            'store_id' => $storeId,
            'nominal_bid' => $data['nominal_bid'],
            'payment_status' => AdSlot::PAYMENT_MENUNGGU,
            'metode_pembayaran' => $metode?->nama_metode,
            'platform_bank_account_id' => $data['platform_bank_account_id'],
            'file_bukti' => $path,
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'status' => AdSlot::STATUS_DITUNDA,
        ]);

        $mulai = \Illuminate\Support\Carbon::parse($data['tanggal_mulai'])->translatedFormat('d M Y');
        $selesai = \Illuminate\Support\Carbon::parse($data['tanggal_selesai'])->translatedFormat('d M Y');

        \App\Support\ActivityLogger::log(
            'iklan.request',
            \App\Models\AdSlot::class,
            $slot->ad_slot_id ?? $slot->getKey(),
            [],
            ['product_id' => $product->product_id, 'nominal_bid' => $data['nominal_bid'], 'tanggal_mulai' => $data['tanggal_mulai'], 'tanggal_selesai' => $data['tanggal_selesai']],
            sprintf('Mengajukan iklan peringkat untuk produk "%s" (%s s/d %s).', $product->nama_produk, $mulai, $selesai)
        );

        $sa = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', User::STATUS_AKTIF)->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $request->user()->user_id,
                'tipe' => Notification::TIPE_PROMO,
                'judul' => 'Pengajuan Iklan Peringkat',
                'pesan' => sprintf('Pengajuan iklan "%s" (Rp %s, %s s/d %s) menunggu persetujuan.', $product->nama_produk, number_format((float) $data['nominal_bid'], 0, ',', '.'), $mulai, $selesai),
                'url' => route('superadmin.peringkat-iklan'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_PROMO, 'Pengajuan Iklan Terkirim', sprintf('Pengajuan iklan "%s" (%s s/d %s) menunggu persetujuan Super Admin.', $product->nama_produk, $mulai, $selesai), route('owner.peringkat-iklan'));

        return back()->with('success', 'Pengajuan iklan berhasil diajukan ('.$mulai.' s/d '.$selesai.'). Menunggu persetujuan Super Admin.');
    }
}
