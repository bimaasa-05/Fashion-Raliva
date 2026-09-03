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
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Riwayat</span>
            </div>
            <form method="GET" class="flex flex-wrap items-end gap-gutter">
                <div class="flex flex-col gap-2">
                    <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Aktivitas</label>
                    <select name="tipe" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option value="">Semua Aktivitas</option>
                        @foreach ($tipeList as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['tipe'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-2 min-w-[200px] flex-1">
                    <label class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Cari Produk</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama produk..." class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Terapkan</button>
                <a href="{{ route('gudang.riwayat-stok') }}" class="px-4 py-2 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</a>
            </form>
        </div>

        <div data-table-wrap class="overflow-x-auto">
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

        @if ($items->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} aktivitas • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $items->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>
</div>
@endsection
