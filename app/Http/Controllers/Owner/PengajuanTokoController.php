<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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
        $store = OwnerContext::currentStore();
        if (! $store) {
            return back()->with('error', 'Anda belum memiliki toko untuk mengajukan dokumen.');
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
