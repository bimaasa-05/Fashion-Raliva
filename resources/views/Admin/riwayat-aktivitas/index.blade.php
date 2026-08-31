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
        @forelse ($logs as $log)
        <div class="timeline-item relative">
            <div class="flex items-start">
                <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1">
                    <span class="material-symbols-outlined text-gold-accent text-sm">
                        @switch($log->aksi)
                            @case('verifikasi_pembayaran') fact_check
                            @break
                            @case('resi') local_shipping
                            @break
                            @case('komplain') support_agent
                            @break
                            @case('refund') paid
                            @break
                            @case('produk') inventory_2
                            @break
                            @default history
                        @endswitch
                    </span>
                </div>
                <div class="ml-element-gap flex-grow">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                        <span class="font-title-md text-title-md text-on-surface">{{ ucwords(str_replace('_', ' ', $log->aksi)) }}</span>
                        <span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">{{ optional($log->created_at)->translatedFormat('d M Y, H.i') ?? '-' }}</span>
                    </div>
                    <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                        <div class="text-sm">{!! $log->deskripsi !!}</div>
                        <div class="mt-3 flex gap-2">
                            @if ($log->target_tipe)
                            <span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">{{ $log->target_tipe }}</span>
                            @endif
                            @if ($log->user)
                            <span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">{{ $log->user->nama_lengkap }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada aktivitas.</p>
        @endforelse

        @if ($logs->hasPages())
        <div class="pt-4">{{ $logs->links() }}</div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<style>
    .timeline-line::before { content: ''; position: absolute; left: 20px; top: 48px; bottom: -24px; width: 1px; background: linear-gradient(to bottom, rgba(201,162,77,0.55), rgba(201,162,77,0.06)); z-index: 0; }
    .timeline-item:last-child .timeline-line::before { display: none; }
</style>
@endpush
