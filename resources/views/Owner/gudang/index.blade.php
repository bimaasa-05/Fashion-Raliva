@extends('layouts.owner')

@section('title', 'Gudang')

@section('header-title', 'Gudang')
@section('header-badge', '2 Gudang Aktif')
@section('header-subtitle', 'Kelola data gudang dan pantau stok di setiap lokasi.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Gudang</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['total'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warehouse</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Unit Tersimpan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($summary['unit'], 0, ',', '.') }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory_2</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kapasitas Terpakai</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>63</span>%</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="63"></div>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">equalizer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
            <span class="raliva-figure text-[26px] text-error">{{ $summary['menipis'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warning</span>
        </div>
    </section>

    {{-- Daftar Gudang --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Gudang</h2>
            <p data-reveal class="text-xs text-on-surface-variant flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">lock</span> Halaman ini read-only</p>
        </div>

        <div data-reveal-group class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            @forelse ($warehouses as $g)
                @php
                    $petugas = $g->staff->pluck('nama_lengkap')->filter()->values()->all();
                @endphp
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[24px]">warehouse</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $g->nama_gudang }}</p>
                                <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">{{ $g->alamat }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">{{ ucfirst($g->status) }}</span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Kapasitas Terpakai</span>
                            <span class="font-label-sm text-[11px] font-bold text-on-surface">{{ $g->kapasitas }}%</span>
                        </div>
                        <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $g->kapasitas }}"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-gutter pt-4 border-t border-muted-border text-center">
                        <div>
                            <p class="font-title-md text-base text-on-surface">{{ number_format($summary['unit'], 0, ',', '.') }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Unit Stok</p>
                        </div>
                        <div class="border-x border-muted-border">
                            <p class="font-title-md text-base text-on-surface">{{ $g->produk ?? 0 }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Varian Produk</p>
                        </div>
                        <div>
                            <p class="font-title-md text-base {{ $summary['menipis'] > 0 ? 'text-error' : 'text-secondary' }}">{{ $summary['menipis'] }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Stok Menipis</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @foreach ($petugas as $p)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                                <span class="material-symbols-outlined text-[14px] text-secondary">person</span>{{ $p }}
                            </span>
                        @endforeach
                    </div>

                    <div class="flex gap-gutter mt-auto">
                        <button type="button" onclick="showRalivaToast('Detail gudang {{ $g->nama_gudang }} (read-only).', 'visibility')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Detail</button>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-on-surface-variant text-sm py-8 text-center">Belum ada gudang terdaftar untuk toko ini.</p>
            @endforelse
    </section>

    {{-- Ringkasan Stok Antar Gudang --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Ringkasan Stok Kritis</h2>
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari produk..." data-table-search class="raliva-search" />
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Gudang</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Sisa Stok</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terjual / Minggu</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses->flatMap->variants ?: [] as $v)
                        <tr data-table-row class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $v->product->nama_produk ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $v->sku }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">—</td>
                            <td class="py-3.5 px-4 text-center font-bold text-on-surface-variant">—</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">—</td>
                            <td class="py-3.5 px-4 text-right text-xs font-semibold text-on-surface-variant">Read-only</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-on-surface-variant text-sm">Tidak ada data stok kritis.</td></tr>
                    @endforelse
            </table>
        </div>
    </section>
</div>

@endsection
