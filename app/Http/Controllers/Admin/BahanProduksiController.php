<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanProduksi;
use App\Models\Notification;
use App\Models\Supplier;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class BahanProduksiController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $bahans = BahanProduksi::whereIn('store_id', $storeIds)
            ->with('supplier')
            ->orderBy('nama_bahan')
            ->paginate(15);

        $suppliers = Supplier::whereIn('store_id', $storeIds)
            ->orWhereNull('store_id')
            ->orderBy('nama_supplier')
            ->get(['supplier_id', 'nama_supplier']);

        $stats = [
            'total'   => BahanProduksi::whereIn('store_id', $storeIds)->count(),
            'aktif'   => BahanProduksi::whereIn('store_id', $storeIds)->where('status', BahanProduksi::STATUS_AKTIF)->count(),
            'menipis' => BahanProduksi::whereIn('store_id', $storeIds)
                ->where('status', BahanProduksi::STATUS_AKTIF)
                ->whereColumn('stok', '<=', 'stok_minimum')
                ->count(),
        ];

        return view('Admin.bahan-produksi.index', compact('bahans', 'suppliers', 'stats'));
    }

    public function store(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Admin belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'nama_bahan' => 'required|string|max:150',
            'kategori' => 'required|in:kain,aksesoris,kemasan,lainnya',
            'satuan' => 'required|string|max:20',
            'stok' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data['store_id'] = $storeId;
        $data['stok'] = $data['stok'] ?? 0;
        $data['stok_minimum'] = $data['stok_minimum'] ?? 0;

        BahanProduksi::create($data);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Bahan Ditambahkan',
            sprintf('Bahan produksi "%s" ditambahkan.', $data['nama_bahan']),
            route('admin.bahan-produksi'));

        return back()->with('toast', [
            'message' => 'Bahan produksi berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, BahanProduksi $bahan)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! in_array($bahan->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Bahan ini di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'nama_bahan' => 'required|string|max:150',
            'kategori' => 'required|in:kain,aksesoris,kemasan,lainnya',
            'satuan' => 'required|string|max:20',
            'stok' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data['stok'] = $data['stok'] ?? 0;
        $data['stok_minimum'] = $data['stok_minimum'] ?? 0;

        $lama = $bahan->only(['nama_bahan', 'stok', 'status']);
        $bahan->update($data);

        ActivityLogger::log('admin.bahan.update', BahanProduksi::class, $bahan->bahan_id,
            $lama, $data, sprintf('Mengubah bahan produksi "%s".', $data['nama_bahan']));

        return back()->with('toast', [
            'message' => 'Bahan produksi berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }
}
