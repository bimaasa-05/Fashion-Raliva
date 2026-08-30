@extends('layouts.owner')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-subtitle', 'Semua pemberitahuan penting untuk toko Anda.')

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
            <p class="font-title-md text-sm text-on-surface"><span>{{ $unread }}</span> notifikasi belum dibaca</p>
        </div>
        <button type="button" onclick="showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'mark_email_read')" class="text-xs font-semibold text-gold-accent hover:underline shrink-0">Tandai Semua Dibaca</button>
    </section>

    {{-- Hari Ini --}}
    <section>
        <h2 data-reveal class="text-xs font-medium text-on-surface-variant mb-gutter px-1">Hari Ini</h2>
        <div data-reveal-group class="space-y-gutter">
            @forelse ($today as $n)
                <article data-reveal class="bg-surface-container-lowest border {{ !$n->is_read ? 'border-l-[3px] border-l-gold-accent border-muted-border' : 'border-muted-border' }} rounded-lg px-5 py-4 flex items-start gap-4 card-premium hover:border-gold-accent/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">notifications</span>
                    </div>
                    <p class="flex-1 font-body-md text-sm text-on-surface leading-relaxed">{{ $n->pesan }}</p>
                    <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant whitespace-nowrap mt-1">{{ $n->created_at?->translatedFormat('H:i') }}</span>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-4 text-center">Tidak ada notifikasi hari ini.</p>
            @endforelse
        </div>
    </section>

    {{-- Sebelumnya --}}
    <section>
        <h2 data-reveal class="text-xs font-medium text-on-surface-variant mb-gutter px-1">Sebelumnya</h2>
        <div data-reveal-group class="space-y-gutter">
            @forelse ($earlier as $n)
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-5 py-4 flex items-start gap-4 card-premium hover:border-gold-accent/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">notifications</span>
                    </div>
                    <p class="flex-1 font-body-md text-sm text-on-surface-variant leading-relaxed">{{ $n->pesan }}</p>
                    <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant whitespace-nowrap mt-1">{{ $n->created_at?->translatedFormat('d M Y, H:i') }}</span>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-4 text-center">Tidak ada notifikasi sebelumnya.</p>
            @endforelse
        </div>
    </section>

    <div data-reveal class="flex justify-center pt-2">
        <button type="button" onclick="showRalivaToast('Memuat notifikasi lama.', 'history')" class="px-8 py-3 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Muat Lebih Banyak</button>
    </div>
</div>
@endsection
