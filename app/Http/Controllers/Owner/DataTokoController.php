<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use App\Models\Review;
use App\Models\StoreCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataTokoController extends Controller
{
    public function index()
    {
        $store = OwnerContext::currentStore();

        if (! $store) {
            return redirect()->route('owner.pengajuan-toko')
                ->with('info', 'Silakan ajukan pembuatan toko terlebih dahulu.');
        }

        $rating = $store ? (float) Review::where('store_id', $store->store_id)->avg('rating') : 0;
        $reviewCount = $store ? Review::where('store_id', $store->store_id)->count() : 0;
        $storeCategories = StoreCategory::where('status', StoreCategory::STATUS_AKTIF)
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori');

        return view('Owner.data-toko.index', compact('store', 'rating', 'reviewCount', 'storeCategories'));
    }

    public function update(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store) {
            return back()->with('error', 'Toko tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100', Rule::exists('store_categories', 'nama_kategori')->where('status', StoreCategory::STATUS_AKTIF)],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'alamat' => ['required', 'string', 'max:500'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($request->user()->user_id ?? 0, 'user_id')],
        ]);

        $store->update([
            'nama_toko' => $validated['nama_toko'],
            'kategori' => $validated['kategori'] ?? null,
            'deskripsi' => $validated['deskripsi'],
            'alamat' => $validated['alamat'],
            'nomor_telepon' => $validated['nomor_telepon'],
        ]);

        // Email ada di tabel users, bukan stores.
        $user = $request->user();
        if ($user && $user->email !== $validated['email']) {
            $user->update(['email' => $validated['email']]);
        }

        return redirect()->route('owner.data-toko')
            ->with('success', 'Data toko berhasil disimpan.');
    }
}
