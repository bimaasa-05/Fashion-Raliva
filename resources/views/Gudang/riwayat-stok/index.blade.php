@extends('layouts.gudang')

@section('title', 'Riwayat Stok')

@section('header-title', 'Riwayat Stok')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Jejak audit seluruh perubahan stok pada gudang Anda.')

@section('content')
@php
    $aktivitasLabel = [
        'masuk' => 'Barang Masuk',
        'keluar' => 'Barang Keluar',
        'mutasi_masuk' => 'Pemindahan',
        'mutasi_keluar' => 'Pemindahan',
        'penyesuaian' => 'Penyesuaian',
    ];
    $aktivitasIcon = [
        'masuk' => 'archive',
        'keluar' => 'unarchive',
        'mutasi_masuk' => 'swap_horiz',
        'mutasi_keluar' => 'swap_horiz',
        'penyesuaian' => 'tune',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hidden md:block">Filter Riwayat</span>
                </div>
                <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors btn-premium">
                    <span class="material-symbols-outlined text-[18px]" data-filter-icon>tune</span>
                    Filter
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
                </button>
            </div>
            <div data-filter-panel class="hidden md:block bg-surface-container-low border border-muted-border rounded-lg p-4">
                <form method="GET" class="flex flex-wrap items-end gap-gutter">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Aktivitas</label>
                        <select name="tipe" class="raliva-select">
                            <option value="">Semua Aktivitas</option>
                            @foreach ($tipeList as $key => $label)
                                <option value="{{ $key }}" {{ ($filters['tipe'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-2 min-w-[200px] flex-1">
                        <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Cari Produk</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama produk..." class="raliva-search" />
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Terapkan</button>
                    <a href="{{ route('gudang.riwayat-stok') }}" class="px-4 py-2 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</a>
                </form>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[1000px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No.</th>
                        <th class="p-4 text-left">Waktu</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Aktivitas</th>
                        <th class="p-4 text-center">Perubahan</th>
                        <th class="p-4 text-center">Referensi</th>
                        <th class="p-4 text-center">Petugas</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($items as $m)
                        @php
                            $aktivitas = $aktivitasLabel[$m->tipe_pergerakan] ?? ucfirst($m->tipe_pergerakan);
                            $icon = $aktivitasIcon[$m->tipe_pergerakan] ?? 'inventory_2';
                            $isMasuk = in_array($m->tipe_pergerakan, ['masuk', 'mutasi_masuk', 'penyesuaian']) ? $m->jumlah >= 0 : false;
                            $changeColor = in_array($m->tipe_pergerakan, ['masuk', 'mutasi_masuk', 'penyesuaian']) ? 'text-secondary' : 'text-error';
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row>
                            <td class="p-4 text-center text-on-surface-variant">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                            <td class="p-4 text-on-surface-variant whitespace-nowrap">{{ $m->created_at?->format('d M Y • H:i') ?? '-' }}</td>
                            <td class="p-4"><span class="text-on-surface">{{ $m->productVariant?->product?->nama_produk ?? '-' }}</span></td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] {{ in_array($m->tipe_pergerakan, ['keluar', 'mutasi_keluar']) ? 'text-error' : ($m->tipe_pergerakan === 'penyesuaian' ? 'text-gold-accent' : 'text-on-surface-variant') }}">{{ $icon }}</span>
                                    {{ $aktivitas }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-bold {{ $changeColor }}">{{ $m->jumlah > 0 ? '+' . $m->jumlah : $m->jumlah }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $m->sumber_tipe ?? '-' }}</span></td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $m->creator->nama_lengkap ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-on-surface-variant">Belum ada riwayat stok pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: kartu per aktivitas --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($items as $m)
                @php
                    $aktivitasM = $aktivitasLabel[$m->tipe_pergerakan] ?? ucfirst($m->tipe_pergerakan);
                    $iconM = $aktivitasIcon[$m->tipe_pergerakan] ?? 'inventory_2';
                    $changeColorM = in_array($m->tipe_pergerakan, ['masuk', 'mutasi_masuk', 'penyesuaian']) ? 'text-secondary' : 'text-error';
                    $iconColorM = in_array($m->tipe_pergerakan, ['keluar', 'mutasi_keluar']) ? 'text-error' : ($m->tipe_pergerakan === 'penyesuaian' ? 'text-gold-accent' : 'text-on-surface-variant');
                @endphp
                <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined {{ $iconColorM }}">{{ $iconM }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface leading-tight">{{ $m->productVariant?->product?->nama_produk ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $m->created_at?->format('d M Y • H:i') ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="font-bold {{ $changeColorM }} shrink-0">{{ $m->jumlah > 0 ? '+' . $m->jumlah : $m->jumlah }}</span>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Aktivitas</dt>
                            <dd class="text-on-surface text-right">{{ $aktivitasM }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Referensi</dt>
                            <dd><span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $m->sumber_tipe ?? '-' }}</span></dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Petugas</dt>
                            <dd class="text-on-surface text-right">{{ $m->creator->nama_lengkap ?? '-' }}</dd>
                        </div>
                    </dl>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada riwayat stok pada gudang ini.</p>
            @endforelse
        </div>

        @if ($items->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} aktivitas • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $items->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>
</div>
@endsection
