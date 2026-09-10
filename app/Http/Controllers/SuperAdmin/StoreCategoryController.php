<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class StoreCategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validateKategoriToko($request);

        if ($error = $this->duplicateCheck($data['nama_kategori'])) {
            return back()->with('toast', ['message' => $error, 'icon' => 'gpp_maybe']);
        }

        $kategori = StoreCategory::create([
            'nama_kategori' => $data['nama_kategori'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $data['status'] ?? StoreCategory::STATUS_AKTIF,
        ]);

        ActivityLogger::log(
            'store_category.create',
            StoreCategory::class,
            $kategori->store_category_id,
            null,
            ['nama_kategori' => $kategori->nama_kategori, 'deskripsi' => $kategori->deskripsi, 'status' => $kategori->status],
            sprintf('Menambahkan kategori toko "%s".', $kategori->nama_kategori)
        );

        return back()->with('toast', [
            'message' => "Kategori toko \"{$kategori->nama_kategori}\" berhasil ditambahkan.",
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, StoreCategory $storeCategory)
    {
        $data = $this->validateKategoriToko($request);

        if ($error = $this->duplicateCheck($data['nama_kategori'], $storeCategory->store_category_id)) {
            return back()->with('toast', ['message' => $error, 'icon' => 'gpp_maybe']);
        }

        $lama = $storeCategory->only(['nama_kategori', 'deskripsi', 'status']);

        $storeCategory->update([
            'nama_kategori' => $data['nama_kategori'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $data['status'],
        ]);

        ActivityLogger::log(
            'store_category.update',
            StoreCategory::class,
            $storeCategory->store_category_id,
            $lama,
            ['nama_kategori' => $storeCategory->nama_kategori, 'deskripsi' => $storeCategory->deskripsi, 'status' => $storeCategory->status],
            sprintf('Mengubah kategori toko "%s".', $storeCategory->nama_kategori)
        );

        return back()->with('toast', [
            'message' => "Perubahan kategori toko \"{$storeCategory->nama_kategori}\" berhasil disimpan.",
            'icon' => 'task_alt',
        ]);
    }

    public function hapus(Request $request, StoreCategory $storeCategory)
    {
        $digunakan = Store::where('kategori', $storeCategory->nama_kategori)->count();

        if ($digunakan > 0) {
            return back()->with('toast', [
                'message' => "Hapus dibatalkan — kategori toko \"{$storeCategory->nama_kategori}\" masih dipakai oleh {$digunakan} toko.",
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $storeCategory->only(['nama_kategori', 'deskripsi']);

        ActivityLogger::log(
            'store_category.delete',
            StoreCategory::class,
            $storeCategory->store_category_id,
            $lama,
            null,
            sprintf('Menghapus kategori toko "%s".', $storeCategory->nama_kategori)
        );

        $storeCategory->delete();

        return back()->with('toast', [
            'message' => "Kategori toko \"{$lama['nama_kategori']}\" berhasil dihapus.",
            'icon' => 'delete',
        ]);
    }

    private function validateKategoriToko(Request $request): array
    {
        return $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'nullable|in:' . StoreCategory::STATUS_AKTIF . ',' . StoreCategory::STATUS_NONAKTIF,
        ], [
            'nama_kategori.required' => 'Nama kategori toko wajib diisi.',
            'nama_kategori.max' => 'Nama kategori toko maksimal 100 karakter.',
            'status.in' => 'Status kategori toko tidak valid.',
        ]);
    }

    private function duplicateCheck(string $nama, ?int $ignoreId = null): ?string
    {
        $query = StoreCategory::whereRaw('LOWER(nama_kategori) = ?', [mb_strtolower($nama)]);

        if ($ignoreId) {
            $query->where('store_category_id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            return "Kategori toko dengan nama \"{$nama}\" sudah ada.";
        }

        return null;
    }
}