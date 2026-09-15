<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $existing = Category::whereRaw('LOWER(nama_kategori) = ?', [strtolower($data['nama_kategori'])])->first();
        if ($existing) {
            return back()->with('toast', ['message' => 'Kategori dengan nama tersebut sudah ada.', 'icon' => 'gpp_maybe']);
        }

        $data['status'] = Category::STATUS_AKTIF;

        $category = Category::create($data);

        ActivityLogger::log(
            'admin.category.create',
            Category::class,
            $category->category_id,
            null,
            $data,
            sprintf('Menambahkan kategori "%s".', $category->nama_kategori)
        );

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Kategori Ditambahkan',
            sprintf('Kategori "%s" berhasil dibuat.', $category->nama_kategori),
            route('admin.produk'));

        return back()->with('toast', [
            'message' => 'Kategori "' . $category->nama_kategori . '" berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }
}
