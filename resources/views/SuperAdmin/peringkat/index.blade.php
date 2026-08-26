@extends('layouts.superadmin')

@section('title', 'Peringkat Platform')

@section('header-title', 'Peringkat Platform')
@section('header-badge', 'Monitoring')

@section('header-subtitle', 'Peringkat lengkap toko, kategori, dan pelanggan terbaik di Raliva.')

@push('styles')
<style>
    .peringkat-bar-fill {
        width: 0%;
        transition: width 0.9s cubic-bezier(0.22, 1, 0.36, 1);
    }
    ol li:nth-child(1) .peringkat-bar-fill { transition-delay: 0.10s; }
    ol li:nth-child(2) .peringkat-bar-fill { transition-delay: 0.18s; }
    ol li:nth-child(3) .peringkat-bar-fill { transition-delay: 0.26s; }
    ol li:nth-child(4) .peringkat-bar-fill { transition-delay: 0.34s; }
    ol li:nth-child(5) .peringkat-bar-fill { transition-delay: 0.42s; }
    ol li:nth-child(6) .peringkat-bar-fill { transition-delay: 0.50s; }
    ol li:nth-child(7) .peringkat-bar-fill { transition-delay: 0.58s; }
    ol li:nth-child(8) .peringkat-bar-fill { transition-delay: 0.66s; }
    ol li:nth-child(9) .peringkat-bar-fill { transition-delay: 0.74s; }
    ol li:nth-child(10) .peringkat-bar-fill { transition-delay: 0.82s; }
</style>
@endpush

@php
    $periodes = [
        'all' => 'Semua Waktu',
        '7' => '7 Hari Terakhir',
        '30' => '30 Hari Terakhir',
    ];

    $sections = [
        ['id' => 'toko', 'label' => 'Top Toko', 'icon' => 'emoji_events', 'rows' => $topToko],
        ['id' => 'kategori', 'label' => 'Top Kategori', 'icon' => 'category', 'rows' => $topKategori],
        ['id' => 'pelanggan', 'label' => 'Top Pelanggan', 'icon' => 'military_tech', 'rows' => $topPelanggan],
    ];
@endphp

@section('content')
<div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Periode Perhitungan</span>
    </div>
    <div class="flex flex-wrap gap-2 border-b border-muted-border pb-4">
        @foreach ($periodes as $key => $label)
            <a href="{{ route('superadmin.peringkat', ['periode' => $key]) }}"
                class="px-4 py-2 font-label-sm uppercase tracking-wider transition-colors rounded-lg {{ $periode === $key
                    ? 'bg-secondary-container/10 text-secondary border border-secondary'
                    : 'border border-muted-border bg-surface text-on-surface hover:bg-surface-container-low' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <div class="flex flex-wrap items-center gap-2 mt-4">
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mr-1">Lompat ke:</span>
        @foreach ($sections as $section)
            <a href="#{{ $section['id'] }}" class="px-3 py-1.5 border border-muted-border bg-surface rounded-lg font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant hover:text-gold-accent hover:border-gold-accent/40 transition-colors inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px]">{{ $section['icon'] }}</span>{{ $section['label'] }}
            </a>
        @endforeach
    </div>
</div>

@foreach ($sections as $section)
    <section id="{{ $section['id'] }}" data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium scroll-mt-24">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">{{ $section['label'] }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">10 besar berdasarkan nilai transaksi • {{ $periodes[$periode] }}</p>
            </div>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">{{ $section['icon'] }}</span>
        </div>

        @if (count($section['rows']))
            <ol class="flex flex-col">
                @foreach ($section['rows'] as $row)
                    <li class="flex items-start gap-3 py-3.5 {{ !$loop->last ? 'border-b border-muted-border' : '' }}">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center font-label-sm text-xs font-bold shrink-0 {{ $row['peringkat'] <= 3
                            ? 'bg-gold-accent/15 text-gold-accent border border-gold-accent/40'
                            : 'bg-surface-container-high text-on-surface-variant border border-transparent' }}">
                            {{ $row['peringkat'] }}
                        </span>
                        <div class="flex-1 min-w-0 pt-0.5">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-title-md text-sm text-on-surface truncate">{{ $row['nama'] }}</p>
                                <span class="shrink-0 font-title-md text-sm text-gold-accent">{{ $row['display'] }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-0.5 mb-1.5">{{ $row['meta'] }}@if ($row['sub_meta'])<span class="hidden sm:inline"> • {{ $row['sub_meta'] }}</span>@endif</p>
                            <div class="h-1 w-full bg-surface-container-high rounded-full overflow-hidden peringkat-bar-track">
                                <div class="h-full rounded-full bg-gradient-to-r from-gold-accent/60 to-gold-accent peringkat-bar-fill" data-w="{{ $row['persentase'] }}%" style="width: 0%"></div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <span class="material-symbols-outlined text-[36px] text-on-surface-variant/50 mb-2">inbox</span>
                <p class="font-title-md text-sm text-on-surface">Belum ada data pada periode ini</p>
                <p class="text-xs text-on-surface-variant mt-1">Coba pilih rentang waktu yang lebih panjang.</p>
            </div>
        @endif
    </section>
@endforeach
@endsection

@push('scripts')
<script>
    (() => {
        const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const fillBarsInSection = (section) => {
            section.querySelectorAll('.peringkat-bar-fill').forEach((bar) => {
                if (prefersReducedMotion()) bar.style.transition = 'none';
                bar.style.width = bar.dataset.w;
            });
        };

        const init = () => {
            const sections = document.querySelectorAll('#toko, #kategori, #pelanggan');

            if (!sections.length) return;

            if (!('IntersectionObserver' in window)) {
                sections.forEach(fillBarsInSection);

                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;

                    fillBarsInSection(entry.target);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.2 });

            sections.forEach((section) => observer.observe(section));
        };

        if (typeof window.ralivaOnReady === 'function') {
            window.ralivaOnReady(init);
        } else {
            document.addEventListener('DOMContentLoaded', init);
        }
    })();
</script>
@endpush
