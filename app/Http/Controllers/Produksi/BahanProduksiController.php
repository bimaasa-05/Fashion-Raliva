<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\BahanProduksi;
use App\Models\Notification;
use App\Models\ProductionMaterial;
use App\Models\StoreStaff;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class BahanProduksiController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $bahans = BahanProduksi::whereIn('store_id', $storeIds)
            ->with('supplier')
            ->orderBy('nama_bahan')
            ->paginate(15);

        $stats = [
            'total'   => BahanProduksi::whereIn('store_id', $storeIds)->count(),
            'aktif'   => BahanProduksi::whereIn('store_id', $storeIds)->where('status', BahanProduksi::STATUS_AKTIF)->count(),
            'menipis' => BahanProduksi::whereIn('store_id', $storeIds)
                ->where('status', BahanProduksi::STATUS_AKTIF)
                ->whereColumn('stok', '<=', 'stok_minimum')
                ->count(),
        ];

        $materials = ProductionMaterial::whereIn('store_id', $storeIds)
            ->orderBy('nama_bahan')
            ->get();

        $cari = trim((string) $request->query('cari'));

        return view('Produksi.bahan-produksi.index', compact('bahans', 'stats', 'materials', 'cari'));
    }

    public function store(Request $request)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Anda belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'nama_bahan' => 'required|string|max:150',
            'kategori' => 'required|in:kain,aksesoris,kemasan,lainnya',
            'satuan' => 'required|string|max:20',
            'stok' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
        ], [
            'nama_bahan.required' => 'Nama bahan wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'satuan.required' => 'Satuan wajib diisi.',
        ]);

        $data['store_id'] = $storeId;
        $data['status'] = BahanProduksi::STATUS_AKTIF;
        $data['stok'] = $data['stok'] ?? 0;
        $data['stok_minimum'] = $data['stok_minimum'] ?? 0;

        BahanProduksi::create($data);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Bahan Ditambahkan',
            sprintf('Bahan produksi "%s" ditambahkan.', $data['nama_bahan']),
            route('produksi.bahan-produksi'));

        return back()->with('toast', [
            'message' => 'Bahan produksi berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }
}
