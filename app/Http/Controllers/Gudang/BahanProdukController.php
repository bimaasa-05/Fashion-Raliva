<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductionOrderBahan;
use App\Models\StoreStaff;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BahanProdukController extends Controller
{
    public function index()
    {
        $storeIds = $this->storeIds();

        $products = Product::whereIn('store_id', $storeIds)
            ->with(['store:store_id,nama_toko', 'materialRequirements'])
            ->withCount('materialRequirements as bahan_count')
            ->orderBy('bahan_count')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('Gudang.bahan-produk.index', [
            'products' => $products,
            'satuanList' => ProductionOrderBahan::SATUAN,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $storeIds = $this->storeIds();
        if (! in_array($product->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Produk di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        $request->merge([
            'bahan' => collect($request->input('bahan', []))->map(fn ($row) => is_array($row)
                ? array_merge($row, [
                    'jumlah' => \App\Support\NumberParser::decimalInput($row['jumlah'] ?? ''),
                ])
                : $row)->all(),
        ]);

        $data = $request->validate([
            'bahan' => ['required', 'array', 'min:1', 'max:50'],
            'bahan.*.nama_bahan' => ['required', 'string', 'max:150'],
            'bahan.*.jumlah' => ['required', 'numeric', 'min:0.001', 'max:1000000'],
            'bahan.*.satuan' => ['required', 'string', Rule::in(ProductionOrderBahan::SATUAN)],
        ], [
            'bahan.required' => 'Isi minimal 1 bahan produksi.',
            'bahan.min' => 'Isi minimal 1 bahan produksi.',
            'bahan.*.nama_bahan.required' => 'Nama bahan wajib diisi.',
            'bahan.*.jumlah.required' => 'Jumlah bahan per unit wajib diisi.',
            'bahan.*.jumlah.min' => 'Jumlah bahan per unit minimal 0,001.',
            'bahan.*.satuan.required' => 'Satuan bahan wajib dipilih.',
        ]);

        $rows = collect($data['bahan'])->map(fn ($row) => [
            'material_id' => null,
            'nama_bahan' => trim((string) $row['nama_bahan']),
            'satuan' => $row['satuan'],
            'jumlah_per_unit' => (float) $row['jumlah'],
            'biaya_per_unit' => 0,
        ])->all();

        DB::transaction(function () use ($product, $rows) {
            $product->materialRequirements()->delete();
            $product->materialRequirements()->createMany($rows);
        });

        ActivityLogger::log('gudang.bahan.simpan', Product::class, $product->product_id, null,
            ['bahan_count' => count($rows)],
            sprintf('Gudang menyimpan %d bahan produksi untuk produk %s.', count($rows), $product->nama_produk));

        return back()->with('toast', [
            'message' => "Bahan produksi untuk {$product->nama_produk} disimpan.",
            'icon' => 'task_alt',
        ]);
    }

    private function storeIds(): array
    {
        return StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();
    }
}
