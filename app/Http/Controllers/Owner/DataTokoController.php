<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Review;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DataTokoController extends Controller
{
    public function index()
    {
        $store = OwnerContext::currentStore();

        if (! $store || $store->status !== Store::STATUS_AKTIF) {
            return redirect()->route('owner.pengajuan-toko')
                ->with('info', ! $store ? 'Silakan ajukan pembuatan toko terlebih dahulu.' : 'Data Toko terbuka setelah toko aktif. Pantau progres di Pengajuan Toko.');
        }

        $rating = $store ? (float) Review::where('store_id', $store->store_id)->avg('rating') : 0;
        $reviewCount = $store ? Review::where('store_id', $store->store_id)->count() : 0;
        $storeCategories = StoreCategory::where('status', StoreCategory::STATUS_AKTIF)
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori');

        $updatePending = \App\Models\StoreUpdateRequest::where('store_id', $store->store_id)
            ->where('status', \App\Models\StoreUpdateRequest::STATUS_PENDING)
            ->latest('store_update_request_id')
            ->first();

        $cities = \App\Models\City::orderBy('city_id')->get()->groupBy('pulau')
            ->map(fn ($g) => $g->pluck('nama_kota')->values()->all())->all();

        return view('Owner.data-toko.index', compact('store', 'rating', 'reviewCount', 'storeCategories', 'updatePending', 'cities'));
    }

    public function update(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store) {
            return back()->with('error', 'Toko tidak ditemukan.');
        }

        if ($store->status !== Store::STATUS_AKTIF) {
            return back()->with('error', 'Data Toko hanya dapat diubah setelah toko aktif.');
        }

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100', Rule::exists('store_categories', 'nama_kategori')->where('status', StoreCategory::STATUS_AKTIF)],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'alamat' => ['required', 'string', 'max:500'],
            'kota' => ['nullable', 'string', 'max:100', Rule::exists('cities', 'nama_kota')],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($request->user()->user_id ?? 0, 'user_id')],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, PNG, atau WebP.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
        ]);

        // Email ada di tabel users, bukan stores — langsung disimpan.
        $user = $request->user();
        if ($user && $user->email !== $validated['email']) {
            $user->update(['email' => $validated['email']]);
        }

        if (\App\Models\StoreUpdateRequest::where('store_id', $store->store_id)
            ->where('status', \App\Models\StoreUpdateRequest::STATUS_PENDING)->exists()) {
            return back()->with('error', 'Masih ada pengajuan perubahan yang menunggu verifikasi Super Admin.');
        }

        $sama = $store->nama_toko === $validated['nama_toko']
            && ($store->kategori ?? null) === ($validated['kategori'] ?? null)
            && ($store->deskripsi ?? null) === ($validated['deskripsi'] ?? null)
            && $store->alamat === $validated['alamat']
            && ($store->kota ?? null) === ($validated['kota'] ?? null)
            && $store->nomor_telepon === $validated['nomor_telepon']
            && ! $request->hasFile('logo');

        if ($sama) {
            return back()->with('info', 'Tidak ada perubahan data toko.');
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store-logos/'.$store->store_id.'/pending', 'public');
            // Bersihkan file pending yatim (tak terikat request mana pun), kecuali file baru & logo aktif.
            $terpakai = \App\Models\StoreUpdateRequest::where('store_id', $store->store_id)
                ->whereNotNull('logo')->pluck('logo')->all();
            $terpakai[] = $logoPath;
            if ($store->logo) {
                $terpakai[] = $store->logo;
            }
            foreach (Storage::disk('public')->files('store-logos/'.$store->store_id.'/pending') as $f) {
                if (! in_array($f, $terpakai, true)) {
                    Storage::disk('public')->delete($f);
                }
            }
        }

        $permintaan = \App\Models\StoreUpdateRequest::create([
            'store_id' => $store->store_id,
            'nama_toko' => $validated['nama_toko'],
            'kategori' => $validated['kategori'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'alamat' => $validated['alamat'],
            'kota' => $validated['kota'] ?? null,
            'nomor_telepon' => $validated['nomor_telepon'],
            'logo' => $logoPath,
            'status' => \App\Models\StoreUpdateRequest::STATUS_PENDING,
        ]);

        \App\Support\ActivityLogger::log(
            'toko.update.request',
            Store::class,
            $store->store_id,
            $store->only(['nama_toko', 'kategori', 'deskripsi', 'alamat', 'kota', 'nomor_telepon', 'logo']),
            $permintaan->only(['nama_toko', 'kategori', 'deskripsi', 'alamat', 'kota', 'nomor_telepon', 'logo']),
            sprintf('Mengajukan perubahan data toko "%s".', $store->nama_toko)
        );

        $saUsers = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', User::STATUS_AKTIF)
            ->get();
        foreach ($saUsers as $sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $user?->user_id,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Pengajuan Perubahan Data Toko',
                'pesan' => sprintf('Owner mengajukan perubahan data toko "%s" dan menunggu verifikasi.', $store->nama_toko),
                'url' => route('superadmin.manajemen-toko'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Perubahan Diajukan', sprintf('Perubahan data toko "%s" dikirim dan menunggu verifikasi Super Admin.', $store->nama_toko), route('owner.data-toko'));

        return redirect()->route('owner.data-toko')
            ->with('success', 'Perubahan data toko dikirim dan menunggu verifikasi Super Admin.');
    }
}
