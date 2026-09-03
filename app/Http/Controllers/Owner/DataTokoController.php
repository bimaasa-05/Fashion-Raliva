<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use App\Models\Review;
use Illuminate\Http\Request;

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

        return view('Owner.data-toko.index', compact('store', 'rating', 'reviewCount'));
    }

    public function update(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store) {
            return back()->with('error', 'Toko tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'alamat' => ['required', 'string', 'max:500'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150'],
            'operational_hours' => ['nullable', 'array'],
            'operational_hours.*.buka' => ['nullable', 'boolean'],
            'operational_hours.*.mulai' => ['nullable', 'string', 'max:5'],
            'operational_hours.*.selesai' => ['nullable', 'string', 'max:5'],
        ]);

        $store->update([
            'nama_toko' => $validated['nama_toko'],
            'deskripsi' => $validated['deskripsi'],
            'alamat' => $validated['alamat'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'operational_hours' => $validated['operational_hours'] ?? $store->operational_hours,
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
