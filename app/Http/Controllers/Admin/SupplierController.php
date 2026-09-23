<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $sort = $request->input('sort', 'nama');
        if (! in_array($sort, ['nama', 'terbaru'], true)) {
            $sort = 'nama';
        }
        $suppliers = Supplier::with('bahans')
            ->when($q, fn ($qb) => $qb->where('nama_supplier', 'like', "%{$q}%"))
            ->when($sort === 'terbaru', fn ($qb) => $qb->orderByDesc('created_at')->orderByDesc('supplier_id'), fn ($qb) => $qb->orderBy('nama_supplier'))
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => $suppliers->total(),
            'aktif' => Supplier::where('status', 'aktif')->count(),
            'nonaktif' => Supplier::where('status', 'nonaktif')->count(),
            'kota' => Supplier::distinct('kota')->count('kota'),
        ];

        return view('Admin.supplier.supplier', compact('suppliers', 'stats', 'sort'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:120',
            'kontak' => 'nullable|string|max:40',
            'email' => 'nullable|string|max:120',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:80',
            'jenis' => 'nullable|string|max:30',
            'catatan' => 'nullable|string|max:1000',
            'stok' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'bahan' => 'nullable|array',
            'bahan.*.nama_bahan' => 'required|string|max:150',
            'bahan.*.satuan' => 'required|string|in:meter,cm,yard,roll,kg,gram,pcs',
            'bahan.*.jumlah' => 'nullable|integer|min:0',
        ]);

        $data['stok'] = $data['stok'] ?? 0;

        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $supplier = Supplier::create(collect($data)->except('bahan')->all());
            foreach ($data['bahan'] ?? [] as $bahan) {
                $supplier->bahans()->create([
                    'nama_bahan' => $bahan['nama_bahan'],
                    'satuan' => $bahan['satuan'],
                    'jumlah' => $bahan['jumlah'] ?? 0,
                ]);
            }
        });

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Supplier Ditambahkan', sprintf('Supplier "%s" berhasil ditambahkan.', $data['nama_supplier']), route('admin.supplier'));

        return back()->with('success', 'Supplier ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:120',
            'kontak' => 'nullable|string|max:40',
            'email' => 'nullable|string|max:120',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:80',
            'jenis' => 'nullable|string|max:30',
            'catatan' => 'nullable|string|max:1000',
            'stok' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'bahan' => 'nullable|array',
            'bahan.*.supplier_bahan_id' => 'nullable|integer|exists:supplier_bahan,supplier_bahan_id',
            'bahan.*.nama_bahan' => 'required|string|max:150',
            'bahan.*.satuan' => 'required|string|in:meter,cm,yard,roll,kg,gram,pcs',
            'bahan.*.jumlah' => 'nullable|integer|min:0',
        ]);

        $data['stok'] = $data['stok'] ?? 0;

        \Illuminate\Support\Facades\DB::transaction(function () use ($supplier, $data) {
            $supplier->update(collect($data)->except('bahan')->all());

            $dikirim = collect($data['bahan'] ?? []);
            $idsLama = $supplier->bahans()->pluck('supplier_bahan_id')->all();
            $idsKirim = $dikirim->pluck('supplier_bahan_id')->filter()->map(fn ($v) => (int) $v)->all();

            // Hapus baris yang dibuang di form; sisanya dipertahankan (tambah-bukan-hilang)
            $hapus = array_diff($idsLama, $idsKirim);
            if ($hapus) {
                $supplier->bahans()->whereIn('supplier_bahan_id', $hapus)->delete();
            }

            foreach ($dikirim as $bahan) {
                $payload = ['nama_bahan' => $bahan['nama_bahan'], 'satuan' => $bahan['satuan'], 'jumlah' => $bahan['jumlah'] ?? 0];
                if (! empty($bahan['supplier_bahan_id'])) {
                    $row = $supplier->bahans()->where('supplier_bahan_id', $bahan['supplier_bahan_id'])->first();
                    if ($row) $row->update($payload);
                } else {
                    $supplier->bahans()->create($payload);
                }
            }
        });

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Supplier Diperbarui', sprintf('Data supplier "%s" berhasil diperbarui.', $supplier->nama_supplier), route('admin.supplier'));

        return back()->with('success', 'Supplier diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Supplier Dihapus', sprintf('Supplier "%s" telah dihapus.', $supplier->nama_supplier), route('admin.supplier'));

        return back()->with('success', 'Supplier dihapus.');
    }
}
