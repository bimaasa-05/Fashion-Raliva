<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreSocial;
use App\Support\ActivityLogger;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreSocialController extends Controller
{
    private function storeAktif(): ?Store
    {
        $store = OwnerContext::currentStore();

        return ($store && $store->status === Store::STATUS_AKTIF) ? $store : null;
    }

    public function store(Request $request)
    {
        $store = $this->storeAktif();
        if (! $store) {
            return back()->with('error', __('Media sosial hanya dapat dikelola setelah toko aktif.'));
        }

        $validated = $request->validate([
            'sosmed_platform_id' => ['nullable', Rule::exists('sosmed_platforms', 'sosmed_platform_id')->where('status', 'aktif')],
            'nama_custom' => ['nullable', 'string', 'max:50', 'required_without:sosmed_platform_id'],
            'url' => ['required', 'url', 'max:255'],
        ], [
            'nama_custom.required_without' => __('Pilih platform atau isi nama custom.'),
            'url.required' => __('Link/URL wajib diisi.'),
            'url.url' => __('Link harus berupa URL valid (cth: https://...).'),
        ]);

        if (! empty($validated['sosmed_platform_id'])
            && StoreSocial::where('store_id', $store->store_id)
                ->where('sosmed_platform_id', $validated['sosmed_platform_id'])->exists()) {
            return back()->with('error', __('Platform ini sudah ditambahkan untuk toko Anda.'));
        }

        $social = StoreSocial::create([
            'store_id' => $store->store_id,
            'sosmed_platform_id' => $validated['sosmed_platform_id'] ?? null,
            'nama_custom' => empty($validated['sosmed_platform_id']) ? trim($validated['nama_custom']) : null,
            'url' => $validated['url'],
        ]);

        ActivityLogger::log(
            'toko.sosmed.tambah',
            StoreSocial::class,
            $social->store_social_id,
            [],
            $social->only(['sosmed_platform_id', 'nama_custom', 'url']),
            sprintf('Menambahkan media sosial "%s" pada toko "%s".', $social->label(), $store->nama_toko)
        );

        return back()->with('success', __('Media sosial ditambahkan.'));
    }

    public function destroy(Request $request, StoreSocial $social)
    {
        $store = $this->storeAktif();
        if (! $store || (int) $social->store_id !== (int) $store->store_id) {
            return back()->with('error', __('Data tidak ditemukan.'));
        }

        $label = $social->label();
        $social->delete();

        ActivityLogger::log(
            'toko.sosmed.hapus',
            Store::class,
            $store->store_id,
            ['sosmed' => $label],
            [],
            sprintf('Menghapus media sosial "%s" dari toko "%s".', $label, $store->nama_toko)
        );

        return back()->with('success', __('Media sosial dihapus.'));
    }
}
