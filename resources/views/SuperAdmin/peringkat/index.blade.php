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
