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

        $hppLama = (float) ($beforeProduct['modal_produksi'] ?? 0);
        $hppBaru = array_key_exists('hpp', $after) && $after['hpp'] !== null && $after['hpp'] !== ''
            ? (float) $after['hpp']
            : $hppLama;
        $hargaLama = (float) ($beforeProduct['harga_dasar'] ?? 0);
        $hargaBaru = array_key_exists('harga_dasar', $after) && $after['harga_dasar'] !== null && $after['harga_dasar'] !== ''
            ? (float) $after['harga_dasar']
            : $hargaLama;
        $margin = fn ($harga, $hpp) => 'Rp '.number_format($harga - $hpp, 0, ',', '.').' ('.number_format($harga > 0 ? (($harga - $hpp) / $harga) * 100 : 0, 2, ',', '.').'%)';

        $fields = [
            ['label' => 'Nama produk', 'lama' => $beforeProduct['nama_produk'] ?? '-', 'baru' => $after['nama_produk'] ?? '-'],
            ['label' => 'Harga dasar', 'lama' => $this->rupiah($beforeProduct['harga_dasar'] ?? null), 'baru' => $this->rupiah($after['harga_dasar'] ?? null)],
            ['label' => 'HPP / Modal', 'lama' => $this->rupiah($beforeProduct['modal_produksi'] ?? null), 'baru' => $this->rupiah($after['hpp'] ?? $beforeProduct['modal_produksi'] ?? null)],
            ['label' => 'Kategori', 'lama' => $beforeProduct['kategori'] ?? '-', 'baru' => $categoryAfter ?? '-'],
            ['label' => 'Tipe produk', 'lama' => $beforeProduct['tipe_produk'] ?? '-', 'baru' => $after['tipe_produk'] ?? '-'],
            ['label' => 'Deskripsi', 'lama' => $beforeProduct['deskripsi'] ?? '-', 'baru' => $after['deskripsi'] ?? '-'],
            ['label' => 'Margin', 'lama' => $margin($hargaLama, $hppLama), 'baru' => $margin($hargaBaru, $hppBaru)],
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

        return compact('fields', 'beforeImages', 'afterImages', 'removed', 'beforeVariants', 'afterVariants');
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
