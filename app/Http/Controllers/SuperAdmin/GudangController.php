<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        if (! in_array($status, ['semua', 'aktif', 'nonaktif'], true)) {
            $status = 'semua';
        }

        $q = $request->query('q');
        $q = is_string($q) ? trim($q) : '';

        $stats = [
            'semua' => Warehouse::count(),
            'aktif' => Warehouse::where('status', 'aktif')->count(),
            'nonaktif' => Warehouse::where('status', 'nonaktif')->count(),
        ];

        $warehouses = Warehouse::with(['store:store_id,nama_toko'])
            ->withCount('stocks')
            ->when($status !== 'semua', fn ($query) => $query->where('status', $status))
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('nama_gudang', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhereHas('store', fn ($store) => $store->where('nama_toko', 'like', "%{$q}%"));
            }))
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('SuperAdmin.gudang.index', [
            'warehouses' => $warehouses,
            'stats' => $stats,
            'activeStatus' => $status,
            'q' => $q,
        ]);
    }

    public function detailJson(Warehouse $warehouse)
    {
        $warehouse->load([
            'store:store_id,nama_toko',
            'staff:user_id,nama_lengkap',
            'stocks.productVariant.product:product_id,nama_produk',
        ]);

        $staffs = $warehouse->staff
            ->pluck('nama_lengkap')
            ->filter()
            ->values()
            ->all();

        $stocks = $warehouse->stocks
            ->map(fn ($s) => [
                'nama' => $s->productVariant?->product?->nama_produk ?? '-',
                'sku' => $s->productVariant?->sku ?? '-',
                'varian' => trim(($s->productVariant?->warna ?? '') . ' ' . ($s->productVariant?->ukuran ?? '')),
                'jumlah' => (int) $s->jumlah_stok,
                'reservasi' => (int) $s->jumlah_direservasi,
            ])
            ->values()
            ->all();

        return response()->json([
            'warehouse' => [
                'nama' => $warehouse->nama_gudang,
                'toko' => $warehouse->store?->nama_toko ?? '-',
                'alamat' => $warehouse->alamat ?? '-',
                'telepon' => $warehouse->nomor_telepon ?? '-',
                'status' => $warehouse->status,
                'total_item' => $warehouse->stocks->count(),
                'created' => $warehouse->created_at ? $warehouse->created_at->translatedFormat('d M Y • H.i') : '-',
                'updated' => $warehouse->updated_at ? $warehouse->updated_at->translatedFormat('d M Y • H.i') : '-',
            ],
            'staffs' => $staffs,
            'stocks' => $stocks,
        ]);
    }
}
