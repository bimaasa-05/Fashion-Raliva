<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\MaterialMovement;
use App\Models\ProductionMaterial;
use App\Models\StoreStaff;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class BahanProduksiController extends Controller
{
    private function storeIds(): array
    {
        return StoreStaff::where('user_id', auth()->id())
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->pluck('store_id')
            ->all();
    }

    public function index(Request $request)
    {
        $storeIds = $this->storeIds();

        $cari = trim((string) $request->query('cari'));

        $materials = ProductionMaterial::withCount(['movements as total_masuk' => fn ($q) => $q->where('tipe', MaterialMovement::TIPE_MASUK)])
            ->withCount(['movements as total_keluar' => fn ($q) => $q->where('tipe', MaterialMovement::TIPE_KELUAR)])
            ->when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->when($cari !== '', fn ($q) => $q->where('nama_bahan', 'like', "%{$cari}%"))
            ->orderBy('nama_bahan')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'jenis' => ProductionMaterial::whereIn('store_id', $storeIds)->count(),
            'menipis' => ProductionMaterial::whereIn('store_id', $storeIds)->whereColumn('stok_sekarang', '<=', 'minimal_stok')->count(),
            'pemakaian' => MaterialMovement::where('tipe', MaterialMovement::TIPE_KELUAR)
                ->whereHas('material', fn ($q) => $q->whereIn('store_id', $storeIds))
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('jumlah'),
            'pemasukan' => MaterialMovement::where('tipe', MaterialMovement::TIPE_MASUK)
                ->whereHas('material', fn ($q) => $q->whereIn('store_id', $storeIds))
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('jumlah'),
        ];

        $movements = MaterialMovement::with(['material', 'productionOrder', 'creator'])
            ->whereHas('material', fn ($q) => $q->whereIn('store_id', $storeIds))
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('Produksi.bahan-produksi.index', compact('materials', 'movements', 'stats', 'cari'));
    }

    public function store(Request $request)
    {
        $storeIds = $this->storeIds();

        $data = $request->validate([
            'nama_bahan' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:30'],
            'stok_awal' => ['nullable', 'numeric', 'min:0'],
            'minimal_stok' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $nama = trim($data['nama_bahan']);

        if (ProductionMaterial::whereIn('store_id', $storeIds)->whereRaw('LOWER(nama_bahan) = ?', [mb_strtolower($nama)])->exists()) {
            return back()->with('toast', ['message' => 'Bahan dengan nama tersebut sudah ada.', 'icon' => 'gpp_maybe']);
        }

        $material = ProductionMaterial::create([
            'store_id' => $storeIds ? $storeIds[0] : auth()->user()?->store_id,
            'nama_bahan' => $nama,
            'satuan' => $data['satuan'],
            'stok_sekarang' => $data['stok_awal'] ?? 0,
            'minimal_stok' => $data['minimal_stok'] ?? 0,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        if (($data['stok_awal'] ?? 0) > 0) {
            $this->recordMovement($material, MaterialMovement::TIPE_MASUK, (float) $data['stok_awal'], null, 'Stok awal (pemasukan) '.$nama);
        }

        ActivityLogger::log('produksi.material.store', ProductionMaterial::class, $material->production_material_id, [], $material->toArray(), 'Menambahkan bahan '.$nama);

        return back()->with('toast', ['message' => 'Bahan '.$nama.' ditambahkan.', 'icon' => 'task_alt']);
    }

    public function tambahStok(Request $request)
    {
        $storeIds = $this->storeIds();

        $data = $request->validate([
            'production_material_id' => ['required', 'exists:production_materials,production_material_id'],
            'jumlah' => ['required', 'numeric', 'gt:0'],
            'alasan' => ['nullable', 'string', 'max:1000'],
        ]);

        $material = ProductionMaterial::when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->findOrFail($data['production_material_id']);

        $material->increment('stok_sekarang', (float) $data['jumlah']);

        $this->recordMovement($material, MaterialMovement::TIPE_MASUK, (float) $data['jumlah'], null, $data['alasan'] ?? null);

        ActivityLogger::log('produksi.material.in', ProductionMaterial::class, $material->production_material_id, [], ['jumlah' => $data['jumlah']], 'Pemasukan bahan '.$material->nama_bahan.' +'.$data['jumlah'].' '.$material->satuan);

        return back()->with('toast', ['message' => 'Stok '.$material->nama_bahan.' bertambah.', 'icon' => 'task_alt']);
    }

    public function pakai(Request $request)
    {
        $storeIds = $this->storeIds();

        $data = $request->validate([
            'production_material_id' => ['required', 'exists:production_materials,production_material_id'],
            'jumlah' => ['required', 'numeric', 'gt:0'],
            'alasan' => ['nullable', 'string', 'max:1000'],
        ]);

        $material = ProductionMaterial::when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds))
            ->findOrFail($data['production_material_id']);

        if ($material->stok_sekarang < (float) $data['jumlah']) {
            return back()->with('toast', ['message' => sprintf('Stok %s tersisa %s %s.', $material->nama_bahan, $material->stok_sekarang, $material->satuan), 'icon' => 'gpp_maybe']);
        }

        $material->decrement('stok_sekarang', (float) $data['jumlah']);

        $this->recordMovement($material, MaterialMovement::TIPE_KELUAR, (float) $data['jumlah'], null, $data['alasan'] ?? null);

        ActivityLogger::log('produksi.material.out', ProductionMaterial::class, $material->production_material_id, [], ['jumlah' => $data['jumlah']], 'Pengeluaran bahan '.$material->nama_bahan.' -'.$data['jumlah'].' '.$material->satuan);

        return back()->with('toast', ['message' => 'Stok '.$material->nama_bahan.' berkurang.', 'icon' => 'task_alt']);
    }

    private function recordMovement(ProductionMaterial $material, string $tipe, float $jumlah, ?int $productionOrderId, ?string $alasan): void
    {
        MaterialMovement::create([
            'production_material_id' => $material->production_material_id,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'saldo_akhir' => $material->fresh()->stok_sekarang,
            'production_order_id' => $productionOrderId,
            'alasan' => $alasan,
            'dibuat_oleh' => auth()->id(),
        ]);
    }
}