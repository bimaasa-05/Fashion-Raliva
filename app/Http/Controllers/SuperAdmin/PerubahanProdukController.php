<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductUpdateRequest;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Support\ActivityLogger;
use App\Support\ProductUpdateApplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PerubahanProdukController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', ProductUpdateRequest::STATUS_PENDING);
        if (! in_array($status, [
            ProductUpdateRequest::STATUS_PENDING,
            ProductUpdateRequest::STATUS_DISETUJUI,
            ProductUpdateRequest::STATUS_DITOLAK,
        ], true)) {
            $status = ProductUpdateRequest::STATUS_PENDING;
        }

        $stats = [
            ProductUpdateRequest::STATUS_PENDING => ProductUpdateRequest::where('status', ProductUpdateRequest::STATUS_PENDING)->count(),
            ProductUpdateRequest::STATUS_DISETUJUI => ProductUpdateRequest::where('status', ProductUpdateRequest::STATUS_DISETUJUI)->count(),
            ProductUpdateRequest::STATUS_DITOLAK => ProductUpdateRequest::where('status', ProductUpdateRequest::STATUS_DITOLAK)->count(),
        ];

        $requests = ProductUpdateRequest::with([
                'product.store',
                'product.category',
                'store',
                'requester',
                'reviewer',
            ])
            ->where('status', $status)
            ->orderByDesc('updated_at')
            ->get()
            ->each(fn ($item) => $item->setAttribute('perbandingan', $this->compare($item)));

        return view('SuperAdmin.perubahan-produk.index', [
            'requests' => $requests,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }

    public function approve(Request $request, Product $produk, ProductUpdateRequest $permintaan)
    {
        if ($permintaan->product_id !== $produk->product_id || $permintaan->status !== ProductUpdateRequest::STATUS_PENDING) {
            return back()->with('toast', ['message' => 'Pengajuan tidak valid.', 'icon' => 'gpp_bad']);
        }

        return DB::transaction(function () use ($produk, $permintaan) {
            $lockedRequest = ProductUpdateRequest::whereKey($permintaan->product_update_request_id)->lockForUpdate()->first();
            $lockedProduct = Product::whereKey($produk->product_id)->lockForUpdate()->first();
            if (! $lockedRequest || $lockedRequest->status !== ProductUpdateRequest::STATUS_PENDING || ! $lockedProduct) {
                return back()->with('toast', ['message' => 'Pengajuan sudah diputuskan atau produk tidak ditemukan.', 'icon' => 'gpp_bad']);
            }

            ProductUpdateApplier::apply($lockedProduct, $lockedRequest->after_payload ?? []);
            $lockedRequest->update([
                'status' => ProductUpdateRequest::STATUS_DISETUJUI,
                'review_note' => null,
                'reviewed_by' => ActivityLogger::resolveActorId(),
            ]);

            ActivityLogger::log(
                'product.update.approve',
                Product::class,
                $lockedProduct->product_id,
                ['status' => 'pengajuan_pending'],
                ['status' => 'perubahan_berlaku'],
                sprintf('Menyetujui pengajuan perubahan produk "%s".', $lockedProduct->nama_produk)
            );
            $this->notifyDecision($lockedRequest, true, null);

            return back()->with('toast', ['message' => 'Pengajuan perubahan disetujui dan sudah berlaku.', 'icon' => 'task_alt']);
        });
    }

    public function reject(Request $request, Product $produk, ProductUpdateRequest $permintaan)
    {
        if ($permintaan->product_id !== $produk->product_id || $permintaan->status !== ProductUpdateRequest::STATUS_PENDING) {
            return back()->with('toast', ['message' => 'Pengajuan tidak valid.', 'icon' => 'gpp_bad']);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $staged = collect($permintaan->staged_images ?? [])
            ->map(fn ($path) => trim((string) $path))
            ->filter()
            ->values()
            ->all();

        $permintaan->update([
            'status' => ProductUpdateRequest::STATUS_DITOLAK,
            'review_note' => $data['alasan'],
            'reviewed_by' => ActivityLogger::resolveActorId(),
        ]);

        if ($staged !== []) {
            Storage::disk('public')->delete($staged);
            Storage::disk('public')->deleteDirectory(dirname($staged[0]));
        }

        ActivityLogger::log(
            'product.update.reject',
            Product::class,
            $produk->product_id,
            ['status' => 'pengajuan_pending'],
            ['status' => 'pengajuan_ditolak', 'alasan' => $data['alasan']],
            sprintf('Menolak pengajuan perubahan produk "%s": %s', $produk->nama_produk, $data['alasan'])
        );
        $this->notifyDecision($permintaan, false, $data['alasan']);

        return back()->with('toast', ['message' => 'Pengajuan perubahan ditolak.', 'icon' => 'block']);
    }

    private function compare(ProductUpdateRequest $permintaan): array
    {
        $before = $permintaan->before_snapshot ?? [];
        $after = $permintaan->after_payload ?? [];
        $beforeProduct = $before['product'] ?? [];
        $categoryAfter = isset($after['category_id']) && $after['category_id']
            ? Category::where('category_id', $after['category_id'])->value('nama_kategori')
            : null;

        $recipes = $this->compareRecipes($permintaan, $before, $after);
        $fields = [
            ['label' => 'Nama produk', 'lama' => $beforeProduct['nama_produk'] ?? '-', 'baru' => $after['nama_produk'] ?? '-'],
            ['label' => 'Harga dasar', 'lama' => $this->rupiah($beforeProduct['harga_dasar'] ?? null), 'baru' => $this->rupiah($after['harga_dasar'] ?? null)],
            ['label' => 'Kategori', 'lama' => $beforeProduct['kategori'] ?? '-', 'baru' => $categoryAfter ?? '-'],
            ['label' => 'Tipe produk', 'lama' => $beforeProduct['tipe_produk'] ?? '-', 'baru' => $after['tipe_produk'] ?? '-'],
            ['label' => 'Deskripsi', 'lama' => $beforeProduct['deskripsi'] ?? '-', 'baru' => $after['deskripsi'] ?? '-'],
            ['label' => 'Target produksi', 'lama' => $this->unit($recipes['before']['target']), 'baru' => $this->unit($recipes['after']['target'])],
            ['label' => 'Biaya tambahan', 'lama' => $this->rupiahDecimal($recipes['before']['overhead']), 'baru' => $this->rupiahDecimal($recipes['after']['overhead'])],
            ['label' => 'Modal per unit', 'lama' => $this->rupiahDecimal($recipes['before']['summary']['modal_per_unit'] ?? null), 'baru' => $this->rupiahDecimal($recipes['after']['summary']['modal_per_unit'] ?? null)],
            ['label' => 'Margin', 'lama' => $this->margin($recipes['before']['summary'] ?? []), 'baru' => $this->margin($recipes['after']['summary'] ?? [])],
        ];

        $beforeImages = collect($before['images'] ?? [])->pluck('file_gambar')->all();
        $removed = collect($permintaan->remove_image_ids ?? [])->map(fn ($id) => (int) $id)->all();
        $afterImages = collect($before['images'] ?? [])
            ->reject(fn ($image) => in_array((int) ($image['product_image_id'] ?? 0), $removed, true))
            ->pluck('file_gambar')
            ->merge($permintaan->staged_images ?? [])
            ->values()
            ->all();

        $beforeVariants = collect($before['variants'] ?? [])->mapWithKeys(fn ($variant) => [
            trim(($variant['ukuran'] ?? '').'|'.($variant['warna'] ?? '')) => $variant,
        ]);
        $afterVariants = $this->proposedVariants($after);

        return compact('fields', 'beforeImages', 'afterImages', 'removed', 'beforeVariants', 'afterVariants', 'recipes');
    }

    private function compareRecipes(ProductUpdateRequest $permintaan, array $before, array $after): array
    {
        $beforeRows = collect($before['resep'] ?? [])->map(fn ($row) => [
            'material_id' => $row['material_id'] ?? null,
            'nama_bahan' => trim((string) ($row['nama_bahan'] ?? '')),
            'satuan' => $row['satuan'] ?? '',
            'jumlah_per_unit' => (float) ($row['jumlah_per_unit'] ?? 0),
            'biaya_per_unit' => (float) ($row['biaya_per_unit'] ?? 0),
        ])->values()->all();
        $beforeTarget = isset($before['product']['target_produksi']) ? (int) $before['product']['target_produksi'] : null;
        $beforeOverhead = isset($before['product']['biaya_tambahan']) ? (float) $before['product']['biaya_tambahan'] : null;
        $changed = ! empty($after['resep_diubah']);
        $afterRows = $changed
            ? collect($after['resep'] ?? [])->map(function ($row) use ($permintaan) {
                $material = ! empty($row['material_id'])
                    ? \App\Models\BahanProduksi::where('bahan_id', $row['material_id'])->where('store_id', $permintaan->store_id)->first()
                    : null;

                return [
                    'material_id' => $material?->bahan_id,
                    'nama_bahan' => trim((string) ($row['nama_bahan'] ?? '')),
                    'satuan' => $row['satuan'] ?? '',
                    'jumlah_per_unit' => (float) ($row['jumlah_per_unit'] ?? 0),
                    'biaya_per_unit' => (float) ($row['biaya_per_unit'] ?? 0),
                ];
            })->values()->all()
            : $beforeRows;
        $afterTarget = $changed && array_key_exists('target_produksi', $after) && $after['target_produksi'] !== null
            ? (int) $after['target_produksi']
            : $beforeTarget;
        $afterOverhead = $changed && array_key_exists('biaya_tambahan', $after) && $after['biaya_tambahan'] !== null && $after['biaya_tambahan'] !== ''
            ? (float) $after['biaya_tambahan']
            : $beforeOverhead;

        return [
            'changed' => $changed,
            'before' => [
                'rows' => $beforeRows,
                'target' => $beforeTarget,
                'overhead' => $beforeOverhead,
                'summary' => \App\Support\ProductCostCalculator::calculate($beforeRows, (float) ($beforeOverhead ?? 0), (int) ($beforeTarget ?? 0), (float) ($before['product']['harga_dasar'] ?? 0)),
            ],
            'after' => [
                'rows' => $afterRows,
                'target' => $afterTarget,
                'overhead' => $afterOverhead,
                'summary' => \App\Support\ProductCostCalculator::calculate($afterRows, (float) ($afterOverhead ?? 0), (int) ($afterTarget ?? 0), (float) ($after['harga_dasar'] ?? 0)),
            ],
        ];
    }

    private function proposedVariants(array $after): array
    {
        $sizes = isset($after['ukuran_terpilih']) && $after['ukuran_terpilih'] !== ''
            ? array_values(array_filter(array_map('trim', explode(',', $after['ukuran_terpilih']))))
            : [];
        $submittedColors = array_values($after['warna'] ?? []);
        $colors = array_values(array_filter(array_map(fn ($color) => trim((string) $color), $submittedColors), fn ($color) => $color !== ''));
        $hasVariantRows = collect($after['varian_stok'] ?? [])->isNotEmpty();
        $colorRows = $colors !== [] ? $colors : ($hasVariantRows ? [null] : []);
        if (! $sizes || $colorRows === []) {
            return [];
        }

        $stocks = collect($after['varian_stok'] ?? [])->keyBy(fn ($row) => trim((string) ($row['ukuran'] ?? '')).'|'.trim((string) ($row['warna'] ?? '')));
        $rows = [];
        foreach ($sizes as $size) {
            foreach ($colorRows as $index => $color) {
                $colorName = $color === null ? null : trim((string) $color);
                $detail = $stocks->get(trim((string) $size).'|'.trim((string) $colorName), []);
                $rows[trim((string) $size).'|'.trim((string) $colorName)] = [
                    'ukuran' => trim((string) $size),
                    'warna' => $colorName,
                    'warna_hex' => $colorName === null ? null : trim((string) ($after['warna_hex'][$index] ?? '')),
                    'harga' => $after['harga_dasar'] ?? null,
                    'stok' => (int) ($detail['stok'] ?? 0),
                    'stok_minimum' => (int) ($detail['stok_minimum'] ?? 0),
                ];
            }
        }

        return $rows;
    }

    private function rupiah(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return 'Rp '.number_format((float) $value, 0, ',', '.');
    }

    private function rupiahDecimal(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return 'Rp '.number_format((float) $value, 2, ',', '.');
    }

    private function unit(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return number_format((int) $value, 0, ',', '.').' unit';
    }

    private function margin(array $summary): string
    {
        if (! array_key_exists('margin_per_unit', $summary)) {
            return '-';
        }
        $percent = $summary['margin_persen'];

        return $this->rupiahDecimal($summary['margin_per_unit']).($percent === null ? '' : ' ('.number_format((float) $percent, 2, ',', '.').'%)');
    }

    private function notifyDecision(ProductUpdateRequest $permintaan, bool $approved, ?string $reason): void
    {
        $productName = $permintaan->product?->nama_produk ?? 'Produk';
        $actor = ActivityLogger::resolveActorId();
        $adminIds = StoreStaff::where('store_id', $permintaan->store_id)
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->whereHas('user.role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->pluck('user_id')
            ->push($permintaan->requested_by)
            ->filter()
            ->unique()
            ->reject(fn ($id) => (int) $id === (int) $actor)
            ->values();

        foreach ($adminIds as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'aktor_id' => $actor,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => $approved ? 'Pengajuan Produk Disetujui' : 'Pengajuan Produk Ditolak',
                'pesan' => $approved
                    ? sprintf('Pengajuan perubahan "%s" disetujui dan sudah berlaku.', $productName)
                    : sprintf('Pengajuan perubahan "%s" ditolak. Alasan: %s', $productName, $reason),
                'url' => route('admin.produk'),
            ]);
        }

        if ($permintaan->product?->store?->owner_id) {
            Notification::create([
                'user_id' => $permintaan->product->store->owner_id,
                'aktor_id' => $actor,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => $approved ? 'Perubahan Produk Disetujui' : 'Perubahan Produk Ditolak',
                'pesan' => $approved
                    ? sprintf('Perubahan produk "%s" disetujui dan sudah berlaku.', $productName)
                    : sprintf('Perubahan produk "%s" ditolak. Alasan: %s', $productName, $reason),
                'url' => route('owner.produk'),
            ]);
        }

        Notification::fireSelf(
            Notification::TIPE_SISTEM,
            $approved ? 'Perubahan Produk Disetujui' : 'Perubahan Produk Ditolak',
            sprintf('Pengajuan perubahan "%s" %s.', $productName, $approved ? 'disetujui' : 'ditolak'),
            route('superadmin.perubahan-produk')
        );
    }
}
