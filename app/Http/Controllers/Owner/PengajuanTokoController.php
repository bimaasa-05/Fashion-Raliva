<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreDocument;
use App\Models\StoreCategory;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengajuanTokoController extends Controller
{
    public function index(Request $request)
    {
        $store = OwnerContext::currentStore();
        $documents = $store
            ? StoreDocument::where('store_id', $store->store_id)->get()
            : collect();
        $storeCategories = StoreCategory::where('status', StoreCategory::STATUS_AKTIF)
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori');

        return view('Owner.pengajuan-toko.index', compact('store', 'documents', 'storeCategories'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $store = OwnerContext::currentStore();

        // Jalur yang diizinkan: belum punya toko, atau toko berstatus pending/ditolak
        // (pending masih boleh melengkapi dokumen; ditolak boleh mengajukan ulang).
        if ($store && ! in_array($store->status, [Store::STATUS_PENDING, Store::STATUS_DITOLAK], true)) {
            return back()->with('error', 'Pengajuan toko tidak dapat diubah pada status saat ini.');
        }

        $isNew = ! $store;
        $isRevising = $store && $store->status === Store::STATUS_DITOLAK;

        // Validasi dokumen SEBELUM menulis store ke database, agar tidak ada store
        // pending yang tercipta tanpa dokumen yang layak diverifikasi.
        $jenisList = ['ktp', 'npwp', 'foto_depan', 'siu'];
        $presentFiles = collect($jenisList)->filter(fn ($jenis) => $request->hasFile($jenis))->values()->all();

        if (! $presentFiles) {
            return back()->with('error', 'Pilih minimal satu dokumen untuk diunggah.')->withInput();
        }

        $request->validate(collect($presentFiles)->mapWithKeys(fn ($jenis) => [
            $jenis => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ])->all());

        $storeFields = [];
        if ($isNew) {
            $storeFields = $request->validate([
                'nama_toko' => ['required', 'string', 'max:150'],
                'kategori' => ['nullable', 'string', 'max:100', Rule::exists('store_categories', 'nama_kategori')->where('status', StoreCategory::STATUS_AKTIF)],
                'alamat' => ['required', 'string', 'max:500'],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);
        } elseif ($isRevising) {
            $storeFields = $request->validate([
                'nama_toko' => ['sometimes', 'string', 'max:150'],
                'kategori' => ['nullable', 'string', 'max:100', Rule::exists('store_categories', 'nama_kategori')->where('status', StoreCategory::STATUS_AKTIF)],
                'alamat' => ['sometimes', 'string', 'max:500'],
                'nomor_telepon' => ['sometimes', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);
        } elseif ($store->status === Store::STATUS_DITOLAK) {
            // Izinkan perbaikan data toko saat ditolak -> reset ke pending
            $validatedStore = $request->validate([
                'nama_toko' => ['sometimes', 'string', 'max:150'],
                'kategori' => ['nullable', 'string', 'max:100', Rule::exists('store_categories', 'nama_kategori')->where('status', StoreCategory::STATUS_AKTIF)],
                'alamat' => ['sometimes', 'string', 'max:500'],
                'nomor_telepon' => ['sometimes', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);
        }

        DB::transaction(function () use ($user, &$store, $isNew, $isRevising, $storeFields, $presentFiles, $request) {
            if ($isNew) {
                $store = Store::create([
                    'owner_id' => $user->user_id,
                    'nama_toko' => $storeFields['nama_toko'],
                    'alamat' => $storeFields['alamat'],
                    'nomor_telepon' => $storeFields['nomor_telepon'],
                    'deskripsi' => $storeFields['deskripsi'] ?? null,
                    'status' => Store::STATUS_PENDING,
                ]);
            } elseif ($isRevising) {
                $store->update(array_merge($storeFields, [
                    'status' => Store::STATUS_PENDING,
                    'alasan_penolakan' => null,
                ]));
            }

            foreach ($presentFiles as $jenis) {
                $path = $request->file($jenis)->store('store-documents/' . $store->store_id, 'public');

                StoreDocument::updateOrCreate(
                    ['store_id' => $store->store_id, 'jenis' => $jenis],
                    ['path' => $path, 'status' => 'pending', 'catatan' => null]
                );
            }
        });

        $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', \App\Models\User::STATUS_AKTIF)
            ->first();
        if ($sa) {
            \App\Models\Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $user->user_id,
                'tipe' => \App\Models\Notification::TIPE_SISTEM,
                'judul' => 'Pengajuan Toko Baru',
                'pesan' => sprintf('Owner %s mengajukan/merubah dokumen toko "%s" dan menunggu verifikasi.', $user->nama_lengkap ?? '-', $store->nama_toko),
                'url' => route('superadmin.manajemen-toko'),
            ]);
        }
        \App\Models\Notification::fireSelf(\App\Models\Notification::TIPE_SISTEM, 'Pengajuan Toko Terkirim', sprintf('%d dokumen toko "%s" terunggah dan menunggu verifikasi Super Admin.', count($presentFiles), $store->nama_toko), route('owner.pengajuan-toko'));

        return redirect()->route('owner.pengajuan-toko')
            ->with('success', count($presentFiles) . ' dokumen berhasil diunggah dan menunggu verifikasi.');
    }
}
