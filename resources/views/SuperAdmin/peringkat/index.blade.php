@extends('layouts.superadmin')

@section('title', 'Peringkat')

@section('header-title', 'Peringkat')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Leaderboard toko, kategori, dan pelanggan berdasarkan total transaksi.')

@section('content')
<div class="space-y-6">
    <div data-reveal class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gold-accent/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">emoji_events</span>
            </div>
            <div>
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Leaderboard Platform</h2>
                <p class="text-on-surface-variant text-xs mt-0.5">Peringkat berdasarkan total transaksi sukses.</p>
            </div>
        </div>
        <div class="inline-flex self-start sm:self-auto bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
            <a href="{{ route('superadmin.peringkat', ['periode' => 'all']) }}"
               class="periode-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $periode === 'all' ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Semua</a>
            <a href="{{ route('superadmin.peringkat', ['periode' => '7']) }}"
               class="periode-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $periode === '7' ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">7 Hari</a>
            <a href="{{ route('superadmin.peringkat', ['periode' => '30']) }}"
               class="periode-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $periode === '30' ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">30 Hari</a>
        </div>
    </div>

    @php
        $podiums = [
            ['judul' => 'Podium Peringkat Toko', 'ikon' => 'storefront', 'items' => $topToko ?? []],
            ['judul' => 'Podium Peringkat Kategori', 'ikon' => 'category', 'items' => $topKategori ?? []],
            ['judul' => 'Podium Peringkat Pelanggan', 'ikon' => 'military_tech', 'items' => $topPelanggan ?? []],
        ];
    @endphp

    @foreach($podiums as $pd)
        @if(count($pd['items']) > 0)
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">
                <span class="material-symbols-outlined text-gold-accent text-[20px] align-middle mr-2">{{ $pd['ikon'] }}</span>{{ $pd['judul'] }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter items-end">
                @if(isset($pd['items'][0]))
                <div class="md:order-2 border-2 border-amber-400 rounded-xl p-6 flex flex-col items-center text-center gap-3 relative overflow-hidden bg-gradient-to-b from-amber-400/20 via-amber-400/5 to-transparent">
                    <span class="absolute top-3 right-3 material-symbols-outlined text-amber-400 fill text-[28px]">workspace_premium</span>
                    <span class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-white flex items-center justify-center font-title-md text-title-md font-bold shadow-lg">1</span>
                    <div>
                        <p class="font-title-md text-title-md text-on-surface leading-snug">{{ $pd['items'][0]['nama'] }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $pd['items'][0]['sub_meta'] ?? $pd['items'][0]['meta'] }}</p>
                    </div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-black leading-none">{{ $pd['items'][0]['display'] }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-400/15 text-amber-500 text-[10px] font-bold uppercase border border-amber-400/40"><span class="material-symbols-outlined text-[12px]">check_circle</span>Posisi Teratas</span>
                </div>
                @endif
                @if(isset($pd['items'][1]))
                <div class="md:order-1 bg-surface-container-low bg-gradient-to-b from-slate-400/30 via-slate-400/10 to-transparent border border-slate-400/60 rounded-xl p-6 flex flex-col items-center text-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-400 via-slate-500 to-slate-700 text-white flex items-center justify-center font-title-md font-bold shadow-lg">2</span>
                    <div>
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $pd['items'][1]['nama'] }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $pd['items'][1]['sub_meta'] ?? $pd['items'][1]['meta'] }}</p>
                    </div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">{{ $pd['items'][1]['display'] }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-400/25 text-on-surface-variant text-[10px] font-bold uppercase border border-slate-400/60">Posisi 2</span>
                </div>
                @endif
                @if(isset($pd['items'][2]))
                <div class="md:order-3 bg-surface-container-low bg-gradient-to-b from-amber-500/10 to-transparent border border-muted-border rounded-xl p-6 flex flex-col items-center text-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-amber-600 text-white flex items-center justify-center font-title-md font-bold">3</span>
                    <div>
                        <p class="font-title-md text-sm text-on-surface leading-snug">{{ $pd['items'][2]['nama'] }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $pd['items'][2]['sub_meta'] ?? $pd['items'][2]['meta'] }}</p>
                    </div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">{{ $pd['items'][2]['display'] }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Posisi 3</span>
                </div>
                @endif
            </div>
        </section>
        @endif
    @endforeach

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Toko</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">storefront</span>
        </div>
        <div data-leaderboard='@json($topToko)'></div>
        @if(count($topToko) === 0)
            <p class="text-on-surface-variant text-sm text-center py-8">Belum ada data toko.</p>
        @endif
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Kategori</h2>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">category</span>
            </div>
            <div data-leaderboard='@json($topKategori)'></div>
            @if(count($topKategori) === 0)
                <p class="text-on-surface-variant text-sm text-center py-8">Belum ada data kategori.</p>
            @endif
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Pelanggan</h2>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">military_tech</span>
            </div>
            <div data-leaderboard='@json($topPelanggan)'></div>
            @if(count($topPelanggan) === 0)
                <p class="text-on-surface-variant text-sm text-center py-8">Belum ada data pelanggan.</p>
            @endif
        </section>
    </div>
</div>
@endsection
