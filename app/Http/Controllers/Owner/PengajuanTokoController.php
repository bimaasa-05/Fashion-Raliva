<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreDocument;
use App\Models\User;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PengajuanTokoController extends Controller
{
    public function index(Request $request)
    {
        // Kepemilikan murni: co-access tidak boleh mengajukan atas toko orang lain.
        $store = $request->user()?->ownedStores()->first();
        $documents = $store
            ? StoreDocument::where('store_id', $store->store_id)->get()
            : collect();
        $storeCategories = StoreCategory::where('status', StoreCategory::STATUS_AKTIF)
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori');
        $cities = \App\Models\City::orderBy('city_id')->get()->groupBy('pulau')
            ->map(fn ($g) => $g->pluck('nama_kota')->values()->all())->all();

        return view('Owner.pengajuan-toko.index', compact('store', 'documents', 'storeCategories', 'cities'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $store = $user?->ownedStores()->first();

        // Jalur yang diizinkan: belum punya toko atau ditolak.
        // Status pending terkunci (menunggu verifikasi Super Admin).
        if ($store && $store->status !== Store::STATUS_DITOLAK) {
            return back()->with('error', 'Pengajuan sedang menunggu verifikasi dan tidak dapat diubah.');
        }

        $isNew = ! $store;
        $isRevising = $store && $store->status === Store::STATUS_DITOLAK;

        // Validasi dokumen SEBELUM menulis store ke database, agar tidak ada store
        // pending yang tercipta tanpa dokumen yang layak diverifikasi.
        // Wajib: KTP + NPWP + SIU. Foto depan toko opsional.
        // Dokumen yang sudah terverifikasi terkunci (tidak bisa diunggah ulang);
        // yang ditolak / belum ada wajib dilengkapi.
        $jenisWajib = ['ktp', 'npwp', 'siu'];
        $jenisList = ['ktp', 'npwp', 'foto_depan', 'siu'];
        $presentFiles = collect($jenisList)->filter(fn ($jenis) => $request->hasFile($jenis))->values()->all();

        $statusDok = $store
            ? StoreDocument::where('store_id', $store->store_id)->pluck('status', 'jenis')->all()
            : [];

        // Tolak upload ulang dokumen yang sudah terverifikasi.
        $uploadTerlarang = collect($presentFiles)->filter(fn ($jenis) => ($statusDok[$jenis] ?? null) === 'terverifikasi')->values()->all();
        if ($uploadTerlarang) {
            $nama = $uploadTerlarang->map(fn ($jenis) => static::jenisLabel($jenis))->implode(', ');
            return back()->with('error', 'Dokumen '.$nama.' sudah terverifikasi dan tidak dapat diunggah ulang.')->withInput();
        }

        // Setiap jenis wajib harus terpenuhi = ada file baru ATAU status
        // existing pending/terverifikasi.
        $kurang = collect($jenisWajib)->filter(fn ($jenis) => ! in_array($jenis, $presentFiles, true) && ! in_array($statusDok[$jenis] ?? null, ['pending', 'terverifikasi'], true))->values()->all();
        if ($kurang) {
            $nama = $kurang->map(fn ($jenis) => static::jenisLabel($jenis))->implode(', ');
            return back()->with('error', 'Dokumen wajib belum lengkap: '.$nama.' (foto depan toko opsional).')->withInput();
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
                'kota' => ['required', 'string', 'max:100', Rule::exists('cities', 'nama_kota')],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);
        } elseif ($isRevising) {
            $storeFields = $request->validate([
                'nama_toko' => ['sometimes', 'string', 'max:150'],
                'kategori' => ['nullable', 'string', 'max:100', Rule::exists('store_categories', 'nama_kategori')->where('status', StoreCategory::STATUS_AKTIF)],
                'alamat' => ['sometimes', 'string', 'max:500'],
                'kota' => ['sometimes', 'string', 'max:100', Rule::exists('cities', 'nama_kota')],
                'nomor_telepon' => ['sometimes', 'string', 'max:20'],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ]);
        }

        DB::transaction(function () use ($user, &$store, $isNew, $isRevising, $storeFields, $presentFiles, $request) {
            if ($isNew) {
                $store = Store::create([
                    'owner_id' => $user->user_id,
                    'nama_toko' => $storeFields['nama_toko'],
                    'kategori' => $storeFields['kategori'] ?? null,
                    'alamat' => $storeFields['alamat'],
                    'kota' => $storeFields['kota'] ?? null,
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
                $lama = StoreDocument::where('store_id', $store->store_id)->where('jenis', $jenis)->first();
                if ($lama?->path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($lama->path);
                }

                $path = $request->file($jenis)->store('store-documents/'.$store->store_id, 'public');

                StoreDocument::updateOrCreate(
                    ['store_id' => $store->store_id, 'jenis' => $jenis],
                    ['path' => $path, 'status' => 'pending', 'catatan' => null]
                );
            }
        });

        $sa = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', User::STATUS_AKTIF)
            ->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $user->user_id,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Pengajuan Toko Baru',
                'pesan' => sprintf('Owner %s mengajukan/merubah dokumen toko "%s" dan menunggu verifikasi.', $user->nama_lengkap ?? '-', $store->nama_toko),
                'url' => route('superadmin.manajemen-toko'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengajuan Toko Terkirim', sprintf('%d dokumen toko "%s" terunggah dan menunggu verifikasi Super Admin.', count($presentFiles), $store->nama_toko), route('owner.pengajuan-toko'));

        return redirect()->route('owner.pengajuan-toko')
            ->with('success', count($presentFiles).' dokumen berhasil diunggah dan menunggu verifikasi.');
    }

    public function reupload(Request $request)
    {
        $user = $request->user();
        $store = $user?->ownedStores()->first();
        if (! $store || $store->status !== Store::STATUS_AKTIF) {
            return back()->with('error', 'Unggah ulang hanya untuk toko aktif.');
        }

        $jenisList = ['ktp', 'npwp', 'foto_depan', 'siu'];
        $presentFiles = collect($jenisList)->filter(fn ($jenis) => $request->hasFile($jenis))->values()->all();

        if (count($presentFiles) !== 1) {
            return back()->with('error', 'Pilih satu dokumen untuk diunggah ulang.')->withInput();
        }

        $jenis = $presentFiles[0];
        $existing = StoreDocument::where('store_id', $store->store_id)->where('jenis', $jenis)->first();
        if ($existing && in_array($existing->status, ['pending', 'terverifikasi'], true)) {
            return back()->with('error', 'Dokumen '.static::jenisLabel($jenis).' berstatus '.$existing->status.' dan tidak dapat diunggah ulang. Hanya dokumen ditolak yang bisa diunggah ulang.')->withInput();
        }

        $request->validate(collect($presentFiles)->mapWithKeys(fn ($jenis) => [
            $jenis => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ])->all());

        DB::transaction(function () use ($store, $jenis, $request) {
            $lama = StoreDocument::where('store_id', $store->store_id)->where('jenis', $jenis)->first();
            if ($lama?->path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($lama->path);
            }

            $path = $request->file($jenis)->store('store-documents/'.$store->store_id, 'public');

            StoreDocument::updateOrCreate(
                ['store_id' => $store->store_id, 'jenis' => $jenis],
                ['path' => $path, 'status' => 'pending', 'catatan' => null]
            );
        });

        $sa = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', User::STATUS_AKTIF)
            ->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $user->user_id,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Dokumen Toko Diunggah Ulang',
                'pesan' => sprintf('Owner mengunggah ulang dokumen %s toko "%s" dan menunggu verifikasi.', $jenis, $store->nama_toko),
                'url' => route('superadmin.manajemen-toko'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Dokumen Diunggah Ulang', sprintf('Dokumen %s toko "%s" terunggah dan menunggu verifikasi Super Admin.', $jenis, $store->nama_toko), route('owner.pengajuan-toko'));

        return redirect()->route('owner.pengajuan-toko')
            ->with('success', 'Dokumen berhasil diunggah ulang dan menunggu verifikasi.');
    }

    private static function jenisLabel(string $jenis): string
    {
        return match ($jenis) {
            'ktp' => 'KTP / Identitas Owner',
            'npwp' => 'NPWP Toko',
            'foto_depan' => 'Foto Depan Toko',
            'siu' => 'Surat Izin Usaha (NIB)',
            default => ucfirst($jenis),
        };
    }
}
