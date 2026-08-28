@extends('layouts.owner')

@section('title', 'Promo Toko')

@section('header-title', 'Promo Toko')
@section('header-badge', '3 Aktif')
@section('header-subtitle', 'Buat dan kelola promo khusus untuk pelanggan toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 6; $i++)
            <div class="h-64 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Promo Berjalan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['aktif'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_offer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Penukaran Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['total'] }}</span>
            <span class="font-label-sm text-[11px] text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">trending_up</span>+22% vs Juli</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">redeem</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Estimasi Diskon Diberikan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">Rp 0</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">savings</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Konversi Promo</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>{{ $counts['aktif'] > 0 ? '18' : '0' }}</span>%</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">query_stats</span>
        </div>
    </section>

    {{-- Daftar Promo --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Promo</h2>
        </div>

        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-section-gap">
            @forelse ($promos as $promo)
                <article data-reveal class="bg-surface-container-lowest border {{ $promo->status === 'aktif' ? 'border-gold-accent/40' : 'border-muted-border' }} rounded-lg p-5 flex flex-col gap-4 card-premium relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1 {{ $promo->status === 'aktif' ? 'bg-gradient-to-r from-gold-accent to-secondary' : ($promo->mulai_pada && $promo->mulai_pada->isFuture() ? 'bg-gold-accent/40' : 'bg-surface-container-high') }}"></div>
                    <div class="flex items-start justify-between gap-3 pt-1">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px] text-gold-accent">sell</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-base text-on-surface tracking-wide truncate">{{ $promo->kode_promo }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $promo->nama_promo }}</p>
                            </div>
                        </div>
                        @if ($promo->status === 'aktif')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Aktif</span>
                        @elseif ($promo->mulai_pada && $promo->mulai_pada->isFuture())
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Terjadwal</span>
                        @elseif ($promo->status === 'nonaktif')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Selesai</span>
                        @endif
                    </div>

                    <dl class="space-y-1.5 font-body-md text-xs text-on-surface-variant">
                        <div class="flex justify-between gap-3"><dt>Tipe</dt><dd class="text-on-surface font-bold">{{ $promo->tipe_diskon === 'persen' ? 'Diskon '.$promo->nilai_diskon.'%' : 'Diskon Rp '.number_format($promo->nilai_diskon,0,',','.') }} @if($promo->maksimal_diskon)<span class="font-normal">• Maks. Rp {{ number_format($promo->maksimal_diskon,0,',','.') }}</span>@endif</dd></div>
                        <div class="flex justify-between gap-3"><dt>Syarat</dt><dd class="text-on-surface">{{ $promo->minimal_pembelian ? 'Min. belanja Rp '.number_format($promo->minimal_pembelian,0,',','.') : 'Tanpa minimum' }}</dd></div>
                        <div class="flex justify-between gap-3"><dt>Periode</dt><dd class="text-on-surface">{{ $promo->mulai_pada?->translatedFormat('d M Y') }} — {{ $promo->berakhir_pada?->translatedFormat('d M Y') }}</dd></div>
                    </dl>

                    <div class="flex items-center gap-gutter pt-1 mt-auto">
                        <button type="button" onclick="showRalivaToast('Detail promo hanya bisa diubah via admin.', 'info')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Detail</button>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm col-span-full py-8 text-center">Belum ada promo.</p>
            @endforelse
        </div>
    </section>
</div>

@endsection
