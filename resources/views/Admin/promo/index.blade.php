@extends('layouts.admin')

@section('title', 'Promo Toko')

@section('header-title', 'Promo Toko')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Kelola promo toko jika diberi izin oleh Owner.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success'))
        <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
    @endif

    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Pembuatan promo baru memerlukan persetujuan Owner. Kamu dapat mengaktifkan/menonaktifkan promo yang sudah dibuat Owner.</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Promo</h2>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-gutter mb-8">
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_offer</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Total Promo</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $promos->total() ?? $promos->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Aktif</p>
                <p class="font-title-md text-title-md text-secondary mt-1">{{ $promos->where('status', 'aktif')->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">schedule</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Non-aktif</p>
                <p class="font-title-md text-title-md text-on-surface-variant mt-1">{{ $promos->where('status', '!=', 'aktif')->count() }}</p>
            </div>
        </div>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Daftar Promo Toko</h2>
        @if ($promos->isEmpty())
            <p class="text-on-surface-variant text-sm py-8 text-center bg-surface-container-lowest border border-muted-border rounded-lg">Belum ada promo.</p>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @foreach ($promos as $p)
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">{{ $p->nama_promo }}</span>
                    @if ($p->status === 'aktif')
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Non-aktif</span>
                    @endif
                </div>
                <p class="font-body-md text-sm text-on-surface-variant flex-1">{{ $p->deskripsi ?: '—' }}</p>
                <div class="pt-4 border-t border-muted-border flex justify-between items-center">
                    <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">Dibuat oleh Owner</span>
                    <form method="POST" action="{{ route('admin.promo.toggle', $p) }}">
                        @csrf
                        @if ($p->status === 'aktif')
                            <button type="submit" class="px-4 py-2 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/10 transition-colors">Nonaktifkan</button>
                        @else
                            <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Aktifkan</button>
                        @endif
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $promos->links() }}</div>
        @endif
    </section>
</div>
@endsection
