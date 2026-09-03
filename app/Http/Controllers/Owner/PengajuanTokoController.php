<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreDocument;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanTokoController extends Controller
{
    public function index(Request $request)
    {
        $store = OwnerContext::currentStore();
        $documents = $store
            ? StoreDocument::where('store_id', $store->store_id)->get()
            : collect();

        return view('Owner.pengajuan-toko.index', compact('store', 'documents'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $store = OwnerContext::currentStore();

        if (! $store) {
            $validatedStore = $request->validate([
                'nama_toko' => ['required', 'string', 'max:150'],
                'alamat' => ['required', 'string', 'max:500'],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);

            $store = Store::create([
                'owner_id' => $user->user_id,
                'nama_toko' => $validatedStore['nama_toko'],
                'alamat' => $validatedStore['alamat'],
                'nomor_telepon' => $validatedStore['nomor_telepon'],
                'deskripsi' => $validatedStore['deskripsi'] ?? null,
                'status' => Store::STATUS_PENDING,
            ]);
        } elseif ($store->status === Store::STATUS_DITOLAK) {
            // Izinkan perbaikan data toko saat ditolak -> reset ke pending
            $validatedStore = $request->validate([
                'nama_toko' => ['sometimes', 'string', 'max:150'],
                'alamat' => ['sometimes', 'string', 'max:500'],
                'nomor_telepon' => ['sometimes', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);
            if (!empty($validatedStore)) {
                $store->update(array_merge($validatedStore, ['status' => Store::STATUS_PENDING, 'alasan_penolakan' => null]));
            } else {
                $store->update(['status' => Store::STATUS_PENDING, 'alasan_penolakan' => null]);
            }
        }

        $jenisList = ['ktp', 'npwp', 'foto_depan', 'siu'];
        $uploaded = 0;

        foreach ($jenisList as $jenis) {
            if ($request->hasFile($jenis)) {
                $request->validate([
                    $jenis => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                ]);

                $path = $request->file($jenis)->store('store-documents/' . $store->store_id, 'public');

                StoreDocument::updateOrCreate(
                    ['store_id' => $store->store_id, 'jenis' => $jenis],
                    ['path' => $path, 'status' => 'pending', 'catatan' => null]
                );
                $uploaded++;
            }
        }

        if ($uploaded === 0) {
            return back()->with('error', 'Pilih minimal satu dokumen untuk diunggah.');
        }

        return redirect()->route('owner.pengajuan-toko')
            ->with('success', $uploaded . ' dokumen berhasil diunggah dan menunggu verifikasi.');
    }
}
