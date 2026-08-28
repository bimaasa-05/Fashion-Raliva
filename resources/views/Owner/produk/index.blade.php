@extends('layouts.owner')

@section('title', 'Data Produk')

@section('header-title', 'Data Produk')
@section('header-badge', '142 / 200 Slot')
@section('header-subtitle', 'Kelola produk, harga, variasi, dan stok toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['total'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium col-span-2">
            <div class="flex items-center justify-between gap-3">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kuota Slot Produk</span>
                <span class="text-xs font-semibold text-on-surface-variant flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">lock</span>Terkunci</span>
            </div>
            <span class="raliva-figure text-[26px] text-secondary"><span>{{ $usedSlot }}</span> / {{ $totalSlot }}</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="71"></div>
            </div>
            <span class="font-label-sm text-[11px] text-on-surface-variant">58 slot tersedia — aktif s.d. 12 Feb 2027</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Nonaktif</span>
            <span class="raliva-figure text-[26px] text-error">{{ $counts['nonaktif'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">visibility_off</span>
        </div>
    </section>

    {{-- Toolbar & Tabel --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari nama produk atau SKU..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="kategori" class="raliva-select">
                    <option value="">Semua Kategori</option>
                    <option value="outerwear">Outerwear</option>
                    <option value="atasan">Atasan</option>
                    <option value="bawahan">Bawahan</option>
                    <option value="aksesoris">Aksesoris</option>
                </select>
                <select data-table-filter="status-produk" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Harga</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Stok</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terjual</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr data-table-row data-kategori="{{ $p->category?->slug }}" data-status-produk="{{ $p->status }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-14 rounded-md bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
                                        <span class="material-symbols-outlined text-[22px] text-on-surface-variant">checkroom</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ $p->nama_produk }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->variants->first()?->sku ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $p->category?->nama_kategori ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ 'Rp ' . number_format($p->harga_dasar, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center text-on-surface">{{ $p->variants->count() }} <span class="text-xs text-on-surface-variant">varian</span></td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $p->terjual }} pcs</p>
                                <p class="text-xs text-on-surface-variant">{{ $p->variants->map(fn($v) => trim(($v->ukuran ?? '').' '.($v->warna ?? '')))->filter()->implode(', ') ?: '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($p->status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                                @elseif ($p->status === 'habis')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Habis</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="relative inline-block" data-dropdown-wrap>
                                    <button type="button" data-dropdown-toggle class="p-1.5 rounded-md hover:bg-surface-container-high transition-colors text-on-surface-variant hover:text-on-surface" aria-label="Aksi produk">
                                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                    </button>
                                    <div data-dropdown-menu class="hidden absolute right-0 top-full mt-1 w-44 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 py-1.5 text-left">
                                        <button type="button" onclick="showRalivaToast('Detail produk hanya bisa diubah via admin.', 'info')" class="w-full flex items-center gap-3 px-4 py-2.5 text-on-surface hover:bg-surface-container-low transition-colors text-sm">
                                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">visibility</span>Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <div>
                <p class="font-title-md text-title-md text-on-surface">Produk tidak ditemukan</p>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
            </div>
            <button type="button" data-filter-reset class="mt-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <div data-pagination class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
            <p class="text-xs text-on-surface-variant">Menampilkan 8 dari 148 produk</p>
            <div class="flex items-center gap-1">
                <button type="button" disabled class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant opacity-50 cursor-not-allowed"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
                <button type="button" class="w-9 h-9 rounded-lg bg-deep-onyx text-on-primary font-label-sm text-[12px]">1</button>
                <button type="button" onclick="showRalivaToast('Halaman berikutnya (demo).')" class="w-9 h-9 rounded-lg border border-muted-border font-label-sm text-[12px] text-on-surface hover:border-gold-accent transition-colors">2</button>
                <button type="button" onclick="showRalivaToast('Halaman berikutnya (demo).')" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface hover:border-gold-accent transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
            </div>
        </div>
    </section>
</div>

@endsection

@push('styles')
<style>
    .ukuran-chip.selected { background: #111; color: #fff; border-color: #111; }
    .dark .ukuran-chip.selected { background: #f0eeee; color: #111; border-color: #f0eeee; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.ukuran-chip').forEach((chip) => {
        chip.addEventListener('click', () => chip.classList.toggle('selected'));
    });
</script>
@endpush
