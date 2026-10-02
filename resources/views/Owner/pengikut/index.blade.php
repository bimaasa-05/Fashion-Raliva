@extends('layouts.owner')

@section('title', __('Pengikut Toko'))

@section('header-title', __('Pengikut Toko'))
@section('header-badge', number_format($summary['total'] ?? 0, 0, ',', '.') . ' ' . __('Pengikut'))
@section('header-subtitle', __('Semua orang yang mengikuti toko Anda — kenali mereka dan ubah jadi pembeli.'))

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    <div>
        <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>{{ __('Kembali ke Dashboard') }}
        </a>
    </div>
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">{{ __('Belum punya toko') }}</p>
                <p class="text-sm text-on-surface-variant mt-1">{{ __('Silakan') }} <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">{{ __('ajukan toko') }}</a> {{ __('untuk akses fitur ini.') }}</p>
            </div>
        </div>
    @endif

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Total Pengikut') }}</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($summary['total'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">{{ __('semua pengikut toko') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">group</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Pengikut Baru') }} ({{ now()->translatedFormat('M') }})</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ number_format($summary['baru'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-secondary">{{ __('bulan ini') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">person_add</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Pernah Berbelanja') }}</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ number_format($summary['belanja'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">{{ __('pengikut sudah belanja') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
        </div>
    </section>

    {{-- Daftar Pengikut --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading sm:whitespace-nowrap">{{ __('Daftar Pengikut') }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">{{ __('Semua pengikut toko Anda, terbaru di atas.') }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('owner.pengikut') }}" class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
            <div class="relative flex-1 min-w-[220px] max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" name="cari" value="{{ $cari }}" placeholder="{{ __('Cari nama atau email...') }}" class="raliva-search" />
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                <button type="submit" class="py-2.5 px-4 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity whitespace-nowrap">{{ __('Cari') }}</button>
                @if ($cari !== '')
                    <a href="{{ route('owner.pengikut') }}" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</a>
                @endif
            </div>
        </form>

        @if ($rows->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-gutter">
                @foreach ($rows as $row)
                    <article data-reveal class="bg-surface-container-low border border-muted-border rounded-xl p-5 card-premium relative overflow-hidden flex flex-col gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold-accent to-[#821E36] text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-md">{{ $row->initials }}</div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-on-surface truncate">{{ $row->name }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $row->email }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1 shrink-0">
                                @if ($row->is_customer)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ __('Pelanggan') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ __('Pengikut') }}</span>
                                @endif
                                @if (\Carbon\Carbon::parse($row->follow_date)->greaterThanOrEqualTo(now()->subDays(7)))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ __('Baru') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-muted-border text-sm mt-auto">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Mengikuti sejak') }}</p>
                                <p class="text-on-surface font-semibold text-xs mt-0.5">{{ \Carbon\Carbon::parse($row->follow_date)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Pesanan') }}</p>
                                <p class="text-on-surface font-semibold text-xs mt-0.5">{{ $row->jumlah_order }} {{ __('pesanan') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Total Belanja') }}</p>
                                @if ($row->jumlah_order > 0)
                                    <p class="font-bold text-gold-accent text-xs mt-0.5">Rp {{ number_format($row->total_belanja, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-on-surface-variant text-xs mt-0.5">{{ __('Belum berbelanja') }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Terakhir Belanja') }}</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">{{ $row->last_order ? \Carbon\Carbon::parse($row->last_order)->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6 flex justify-center">{{ $rows->links() }}</div>
        @else
            <div class="text-center flex flex-col items-center justify-center py-12">
                <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-4">group_add</span>
                @if ($cari !== '')
                    <p class="font-title-md text-title-md text-on-surface">{{ __('Tidak ada hasil pencarian.') }}</p>
                    <p class="text-on-surface-variant text-sm mt-2">{{ __('Coba kata kunci lain.') }}</p>
                @else
                    <p class="font-title-md text-title-md text-on-surface">{{ __('Belum ada pengikut') }}</p>
                    <p class="text-on-surface-variant text-sm mt-2">{{ __('Bagikan toko Anda agar pelanggan menekan Ikuti Toko.') }}</p>
                @endif
            </div>
        @endif
    </section>
</div>
@endsection
