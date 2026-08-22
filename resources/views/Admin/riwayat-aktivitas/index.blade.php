@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas')

@section('header-title', 'Riwayat Aktivitas')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Catatan aktivitas dalam scope tugasmu sebagai Admin Toko.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Riwayat ini hanya mencakup aktivitas yang berkaitan dengan tugas operasionalmu di toko yang ditugaskan.</p>
    </div>

    <section class="space-y-6">
        <div class="timeline-item relative">
            <div class="flex items-start">
                <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">fact_check</span></div>
                <div class="ml-element-gap flex-grow">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Pembayaran Diverifikasi</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Hari ini, 09.15</span></div>
                    <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                        <div class="text-sm">Kamu memverifikasi pembayaran <span class="font-bold text-on-surface">#RLV-2076</span> sebesar <span class="font-bold text-on-surface">Rp 1.150.000</span>.</div>
                        <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Transaksi</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">LUNARA Fashion</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="timeline-item relative">
            <div class="flex items-start">
                <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">local_shipping</span></div>
                <div class="ml-element-gap flex-grow">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Resi Dimasukkan</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Kemarin, 16.30</span></div>
                    <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                        <div class="text-sm">Kamu memasukkan resi <span class="font-bold text-on-surface">JNE2608210041</span> untuk pesanan <span class="font-bold text-on-surface">#RLV-2075</span>.</div>
                        <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Pengiriman</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">LUNARA Fashion</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="timeline-item relative">
            <div class="flex items-start">
                <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">support_agent</span></div>
                <div class="ml-element-gap flex-grow">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Komplain Dibalas</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Kemarin, 11.05</span></div>
                    <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                        <div class="text-sm">Kamu membalas komplain <span class="font-bold text-on-surface">KOM-0311</span> dari <span class="font-bold text-on-surface">Andi Pratama</span>.</div>
                        <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Komplain</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Velvet Closet</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 text-center">
            <button type="button" onclick="showRalivaToast('Halaman demo: aktivitas lebih lama belum tersedia.', 'info')" class="px-6 py-3 border border-on-surface text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-high transition-colors">Muat Aktivitas Lebih Lama</button>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .timeline-line::before { content: ''; position: absolute; left: 20px; top: 48px; bottom: -24px; width: 1px; background: linear-gradient(to bottom, rgba(201,162,77,0.55), rgba(201,162,77,0.06)); z-index: 0; }
    .timeline-item:last-child .timeline-line::before { display: none; }
</style>
@endpush
