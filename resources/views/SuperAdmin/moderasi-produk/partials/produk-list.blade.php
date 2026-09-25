<span data-moderasi-total="{{ $products->count() }}" hidden></span>

<div id="moderasi-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-gutter gap-y-container-margin">
    @forelse ($products as $product)
        @php
            $normFoto = function ($raw) {
                if (filter_var($raw, FILTER_VALIDATE_URL)) return $raw;
                $raw = ltrim($raw, '/');
                if (str_starts_with($raw, 'assets/')) return asset($raw);
                return asset('storage/' . $raw);
            };
            $fotos = $product->images->map(fn ($img) => $normFoto($img->file_gambar))->values()->all();
            $statusIconMap = [
                \App\Models\Product::STATUS_PENDING => 'pending',
                \App\Models\Product::STATUS_DITOLAK => 'gpp_bad',
            ];
        @endphp
        <div class="group cursor-pointer bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium flex flex-col"
            onclick="openDetailModal(this)"
            data-table-row
            data-search="{{ strtolower($product->nama_produk.' '.($product->store->nama_toko ?? '').' '.($product->category->nama_kategori ?? '').' '.$product->tipe_produk.' '.$product->deskripsi) }}"
            data-id="{{ $product->product_id }}"
            data-name="{{ $product->nama_produk }}"
            data-store="{{ $product->store->nama_toko ?? '-' }}"
            data-price="Rp {{ number_format($product->harga_dasar, 0, ',', '.') }}"
            data-category="{{ ($product->category->nama_kategori ?? '-') }}"
            data-desc="{{ $product->deskripsi }}"
            data-status="{{ $product->status }}"
            data-reason="{{ $product->alasan_penolakan }}"
            data-tipe="{{ ucfirst($product->tipe_produk) }}"
            data-variants="{{ $product->variants->map(fn ($v) => trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')))->filter()->implode(', ') }}"
            data-images='{{ json_encode($product->images->pluck('file_gambar')->values(), JSON_UNESCAPED_SLASHES) }}'
            data-produk-images='@json($fotos)'
            data-slot-total="{{ $product->slot_total }}"
            data-slot-used="{{ $product->slot_used }}"
            data-slot-available="{{ $product->slot_available }}"
            data-slot-full="{{ $product->slot_full ? '1' : '0' }}">
            <div class="relative w-full aspect-[4/3] bg-surface-container-low overflow-hidden rounded-lg isolate" data-produk-gallery>
                @if (count($fotos))
                    <div class="block w-full h-full" data-produk-main>
                        <img src="{{ $fotos[0] }}" alt="{{ $product->nama_produk }}" data-produk-main-img class="w-full h-full object-cover transition-opacity duration-300" loading="lazy" />
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-[42px] text-on-surface-variant/40">checkroom</span>
                    </div>
                @endif
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">{{ $statusIconMap[$product->status] ?? 'pending' }}</span></div>
                @if ($product->status === \App\Models\Product::STATUS_DITOLAK)
                    <div class="absolute bottom-2 left-2 right-2 px-2 py-1 bg-error/90 text-on-error text-[9px] font-bold uppercase tracking-widest rounded text-center">Ditolak • Lihat Alasan</div>
                @endif
            </div>
            @if (count($fotos) > 1)
                <div class="flex gap-2 px-4 pt-3 overflow-x-auto" data-produk-strip>
                    @foreach ($fotos as $i => $f)
                        <button type="button" data-produk-pin="{{ $i }}" aria-label="Tampilkan foto {{ $i + 1 }} dari {{ $product->nama_produk }}" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" class="h-14 w-16 shrink-0 rounded-md overflow-hidden border transition-colors {{ $i === 0 ? 'border-gold-accent ring-2 ring-gold-accent/30' : 'border-outline-variant hover:border-gold-accent' }}">
                            <img src="{{ $f }}" alt="" class="w-full h-full object-cover" loading="lazy" />
                        </button>
                    @endforeach
                </div>
            @endif
            <div class="flex flex-col flex-grow px-4 pb-4 pt-2 gap-1">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide">{{ strtoupper($product->store->nama_toko ?? '-') }}</span>
                <h3 class="font-body-md text-body-md font-semibold text-on-surface leading-tight truncate">{{ $product->nama_produk }}</h3>
                <div class="font-body-md text-body-md font-bold text-gold-accent mt-0.5">Rp {{ number_format($product->harga_dasar, 0, ',', '.') }}</div>
                <div class="flex items-center gap-1.5 flex-wrap mt-2 pt-2 border-t border-muted-border">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">{{ ucfirst($product->tipe_produk) }}</span>
                    @if ($product->status === \App\Models\Product::STATUS_PENDING)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $product->slot_full ? 'bg-error/10 text-error border-error/30' : 'bg-success/10 text-success border-success/20' }}">{{ $product->slot_full ? 'Kuota Penuh' : 'Slot ' . $product->slot_used . '/' . $product->slot_total }}</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p id="moderasi-kosong" class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-16">Belum ada produk pada status ini.</p>
    @endforelse
</div>
<p id="moderasi-empty-search" class="hidden text-center text-on-surface-variant font-body-md text-sm py-16">Tidak ada produk yang cocok.</p>