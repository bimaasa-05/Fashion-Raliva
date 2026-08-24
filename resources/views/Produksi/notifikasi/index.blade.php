@extends('layouts.produksi')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-subtitle', 'Semua pemberitahuan penting untuk tim produksi Anda.')

@section('content')
<div data-skeleton class="space-y-gutter">
    @for ($i = 0; $i < 5; $i++)
        <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
    @endfor
</div>

<div data-real class="hidden space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-4 flex items-center justify-between gap-4 card-premium">
        <div class="flex items-center gap-3">
            <span class="relative flex w-2.5 h-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gold-accent opacity-60"></span>
                <span class="relative inline-flex rounded-full w-2.5 h-2.5 bg-gold-accent"></span>
            </span>
            <p class="font-title-md text-sm text-on-surface">5 notifikasi belum dibaca</p>
        </div>
        <button type="button" onclick="showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'mark_email_read')" class="text-xs font-semibold text-gold-accent hover:underline shrink-0">Tandai Semua Dibaca</button>
    </section>

    {{-- Hari Ini --}}
    <section>
        <h2 data-reveal class="raliva-label mb-gutter px-1">Hari Ini</h2>
        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['assignment', 'Permintaan baru PRQ-0043 dari Owner menunggu konfirmasi produksi.', '14:32', true],
                ['fact_check', 'Pemeriksaan QC-0012 selesai: 38 layak, 2 defect perlu rework.', '13:05', true],
                ['task_alt', 'Produk selesai 45 unit Knit Cardigan siap serah ke Gudang Utama.', '11:40', false],
                ['report', 'Defect baru DEF-0012: 2 unit Blazer butuh keputusan.', '09:15', false],
                ['inventory', 'Stok bahan Wool Charcoal menipis — sisa 18 meter.', '08:02', true],
            ] as $n)
                <article data-reveal class="bg-surface-container-lowest border {{ $n[3] ? 'border-l-[3px] border-l-gold-accent border-muted-border' : 'border-muted-border' }} rounded-lg px-5 py-4 flex items-start gap-4 card-premium hover:border-gold-accent/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">{{ $n[0] }}</span>
                    </div>
                    <p class="flex-1 font-body-md text-sm text-on-surface leading-relaxed">{{ $n[1] }}</p>
                    <span class="raliva-label whitespace-nowrap mt-1">{{ $n[2] }}</span>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Sebelumnya --}}
    <section>
        <h2 data-reveal class="raliva-label mb-gutter px-1">Sebelumnya</h2>
        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['history', 'PRD-0015 Wide Leg Trousers selesai 60 unit dan telah diserahkan.', 'Kemarin, 19:22'],
                ['precision_manufacturing', 'Permintaan PRQ-0041 Blazer Wool 65% progres — penjahitan tahap 3.', 'Kemarin, 12:00'],
                ['warehouse', 'Gudang Utama menerima batch FIN-0009 — 115 unit Kemeja Linen.', '20 Agu, 17:30'],
                ['inventory', 'Bahan Kain Katun Premium masuk 50 meter dari supplier.', '20 Agu, 14:00'],
                ['groups', 'Tim produksi menambah 1 staf baru: Bagas (helper jahit).', '18 Agu, 09:30'],
            ] as $n)
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-5 py-4 flex items-start gap-4 card-premium hover:border-gold-accent/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">{{ $n[0] }}</span>
                    </div>
                    <p class="flex-1 font-body-md text-sm text-on-surface-variant leading-relaxed">{{ $n[1] }}</p>
                    <span class="raliva-label whitespace-nowrap mt-1">{{ $n[2] }}</span>
                </article>
            @endforeach
        </div>
    </section>

    <div data-reveal class="flex justify-center pt-2">
        <button type="button" onclick="showRalivaToast('Memuat notifikasi lama (demo).', 'history')" class="px-8 py-3 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Muat Lebih Banyak</button>
    </div>
</div>
@endsection
