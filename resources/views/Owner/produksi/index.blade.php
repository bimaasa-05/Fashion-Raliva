@extends('layouts.owner')

@section('title', 'Produksi')

@section('header-title', 'Produksi')
@section('header-badge', '3 Berjalan')
@section('header-subtitle', 'Pantau permintaan produksi barang untuk toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="space-y-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-36 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Permintaan Berjalan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['berjalan'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $summary['selesai'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Unit Diproduksi (Agu)</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['unit'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Persetujuan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $summary['menunggu'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
    </section>

    {{-- Daftar Permintaan Produksi --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Permintaan Produksi</h2>
            <p data-reveal class="text-xs text-on-surface-variant flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">lock</span> Halaman ini read-only</p>
        </div>

        <div data-reveal-group class="space-y-gutter">
            @forelse ($orders as $pr)
                @php
                    $produk = $pr->items->first()?->productVariant?->product?->nama_produk ?? '-';
                    $qty = $pr->items->sum('jumlah_diminta') ?: 0;
                    $st = $pr->status;
                    $key = $st === 'selesai' ? 'selesai' : ($st === 'menunggu' || $st === 'pending' ? 'menunggu' : 'diproses');
                    $progress = $key === 'selesai' ? 100 : ($key === 'menunggu' ? 0 : 50);
                    $tahap = $key === 'selesai' ? 'Selesai — masuk gudang' : ($key === 'menunggu' ? 'Belum dimulai' : 'Sedang diproses');
                    $pic = $pr->targetWarehouse?->nama_gudang ?? '—';
                    $target = $pr->selesai_pada ? $pr->selesai_pada->translatedFormat('d M Y') : '—';
                @endphp
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 md:p-6 card-premium">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[22px] text-gold-accent">precision_manufacturing</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface">{{ $pr->nomor_produksi }} — {{ $produk }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ number_format($qty, 0, ',', '.') }} unit • Target {{ $target }} • {{ $pic }}</p>
                            </div>
                        </div>
                        @if ($key === 'diproses')
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $st }}</span>
                        @elseif ($key === 'selesai')
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-deep-onyx text-on-primary text-[10px] font-bold uppercase">{{ $st }}</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $st }}</span>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-1 lg:grid-cols-5 gap-4 items-end">
                        <div class="lg:col-span-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ $tahap }}</span>
                                <span class="font-label-sm text-[11px] font-bold text-secondary">{{ $progress }}%</span>
                            </div>
                            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                                <div class="progress-fill h-full rounded-full" data-progress="{{ $progress }}"></div>
                            </div>
                        </div>
                        <div class="flex gap-gutter lg:justify-end">
                            <button type="button" onclick="showRalivaToast('Detail produksi {{ $pr->nomor_produksi }} (read-only).', 'visibility')" class="flex-1 lg:flex-none py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Lihat Detail</button>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada permintaan produksi untuk toko ini.</p>
            @endforelse
        </div>
    </section>
</div>

{{-- Modal Buat Permintaan Produksi --}}

@endsection
