<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->with('parent:category_id,nama_kategori')
            ->withCount(['products', 'children'])
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('nama_kategori')
            ->get();

        return view('SuperAdmin.kategori-produk.index', [
            'categories' => $categories,
            'parents' => Category::whereNull('parent_id')->where('status', Category::STATUS_AKTIF)->orderBy('nama_kategori')->get(['category_id', 'nama_kategori']),
            'stats' => [
                'total' => $categories->count(),
                'aktif' => $categories->where('status', Category::STATUS_AKTIF)->count(),
                'induk' => $categories->whereNull('parent_id')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateKategori($request);

        if ($error = $this->duplicateCheck($data['nama_kategori'], $data['parent_id'] ?? null)) {
            return back()->with('toast', ['message' => $error, 'icon' => 'gpp_maybe']);
        }

        $kategori = Category::create([
            'parent_id' => $data['parent_id'] ?? null,
            'nama_kategori' => $data['nama_kategori'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => Category::STATUS_AKTIF,
        ]);

        ActivityLogger::log(
            'category.create',
            Category::class,
            $kategori->category_id,
            null,
            ['nama_kategori' => $kategori->nama_kategori, 'parent_id' => $kategori->parent_id],
            sprintf('Menambahkan kategori "%s"%s.', $kategori->nama_kategori, $kategori->parent ? " di bawah \"{$kategori->parent->nama_kategori}\"" : '')
        );

        return back()->with('toast', [
            'message' => "Kategori \"{$kategori->nama_kategori}\" berhasil ditambahkan.",
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, Category $kategori)
    {
        $data = $this->validateKategori($request);

        if ((int) ($data['parent_id'] ?? 0) === (int) $kategori->category_id) {
            return back()->with('toast', [
                'message' => 'Kategori tidak dapat menjadi induk bagi dirinya sendiri.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($data['parent_id'] && $this->isDescendant($kategori, (int) $data['parent_id'])) {
            return back()->with('toast', [
                'message' => 'Kategori induk tidak boleh berada di bawah sub-kategori miliknya sendiri.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($error = $this->duplicateCheck($data['nama_kategori'], $data['parent_id'] ?? null, $kategori->category_id)) {
            return back()->with('toast', ['message' => $error, 'icon' => 'gpp_maybe']);
        }

        $lama = $kategori->only(['nama_kategori', 'deskripsi', 'parent_id']);

        $kategori->update([
            'parent_id' => $data['parent_id'] ?? null,
            'nama_kategori' => $data['nama_kategori'],
            'deskripsi' => $data['deskripsi'] ?? null,
        ]);

        ActivityLogger::log(
            'category.update',
            Category::class,
            $kategori->category_id,
            $lama,
            ['nama_kategori' => $kategori->nama_kategori, 'deskripsi' => $kategori->deskripsi, 'parent_id' => $kategori->parent_id],
            sprintf('Mengubah kategori "%s" menjadi "%s".', $lama['nama_kategori'], $kategori->nama_kategori)
        );

        return back()->with('toast', [
            'message' => "Perubahan kategori \"{$kategori->nama_kategori}\" berhasil disimpan.",
            'icon' => 'task_alt',
        ]);
    }

    public function hapus(Request $request, Category $kategori)
    {
        $produkCount = $kategori->products()->count();
        $childrenCount = $kategori->children()->count();

        if ($produkCount > 0 || $childrenCount > 0) {
            $alasan = [];
            if ($produkCount > 0) {
                $alasan[] = "masih memiliki {$produkCount} produk";
            }
            if ($childrenCount > 0) {
                $alasan[] = "memiliki {$childrenCount} sub-kategori";
            }

            return back()->with('toast', [
                'message' => 'Hapus dibatalkan — kategori "'.$kategori->nama_kategori.'" '.implode(' dan ', $alasan).'.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $kategori->only(['nama_kategori', 'deskripsi', 'parent_id']);

        ActivityLogger::log(
            'category.delete',
            Category::class,
            $kategori->category_id,
            $lama,
            null,
            sprintf('Menghapus kategori "%s".', $kategori->nama_kategori)
        );

        $kategori->delete();

        return back()->with('toast', [
            'message' => "Kategori \"{$lama['nama_kategori']}\" berhasil dihapus.",
            'icon' => 'delete',
        ]);
    }

    private function validateKategori(Request $request): array
    {
        return $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'parent_id' => 'nullable|integer|exists:categories,category_id',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'parent_id.exists' => 'Kategori induk tidak ditemukan.',
        ]);
    }

    private function duplicateCheck(string $nama, ?int $parentId, ?int $ignoreId = null): ?string
    {
        $query = Category::whereRaw('LOWER(nama_kategori) = ?', [mb_strtolower($nama)]);

        if ($parentId) {
            $query->where(function ($q) use ($parentId) {
                $q->where('parent_id', $parentId)->orWhereNull('parent_id');
            });
        }

        if ($ignoreId) {
            $query->where('category_id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            return "Kategori dengan nama \"{$nama}\" sudah ada.";
        }

        return null;
    }

    private function isDescendant(Category $kategori, int $calonParentId): bool
    {
        $current = Category::find($calonParentId);
        $batas = 10;

        while ($current && $batas-- > 0) {
            if ((int) $current->category_id === (int) $kategori->category_id) {
                return true;
            }

            $current = $current->parent_id ? Category::find($current->parent_id) : null;
        }

        return false;
    }
}
