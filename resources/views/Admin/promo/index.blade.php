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

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium card-static">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Promo</h2>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-gutter mb-8">
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">local_offer</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Total Promo</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $promos->total() ?? $promos->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">check_circle</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Aktif</p>
                <p class="font-title-md text-title-md text-secondary mt-1">{{ $promos->where('status', 'aktif')->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">schedule</span>
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
                <div class="pt-4 border-t border-muted-border flex justify-between items-center gap-2">
                    <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">Dibuat oleh Owner</span>
                    <div class="flex items-center gap-2 shrink-0">
                    <button type="button" data-modal-open="modal-detail-promo-{{ $p->promotion_id }}" class="px-4 py-2 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:border-gold-accent transition-colors">Detail</button>
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
            </div>
            <div id="modal-detail-promo-{{ $p->promotion_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
                    <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                        <div class="min-w-0">
                            <p class="raliva-label text-gold-accent">Detail Promo</p>
                            <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $p->kode_promo }}</h3>
                            <p class="text-on-surface-variant font-body-md text-xs mt-1">{{ $p->nama_promo }}</p>
                        </div>
                        <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div><dt class="raliva-label">Status</dt><dd class="font-bold text-on-surface mt-1 capitalize">{{ $p->status }}</dd></div>
                            <div><dt class="raliva-label">Jenis Diskon</dt><dd class="text-on-surface mt-1">{{ $p->tipe_diskon === 'persen' ? 'Diskon '.$p->nilai_diskon.'%' : 'Diskon Rp '.number_format($p->nilai_diskon,0,',','.') }}</dd></div>
                            <div><dt class="raliva-label">Minimal Belanja</dt><dd class="text-on-surface mt-1">{{ $p->minimal_pembelian ? 'Rp '.number_format($p->minimal_pembelian,0,',','.') : 'Tanpa minimum' }}</dd></div>
                            <div><dt class="raliva-label">Maksimal Diskon</dt><dd class="text-on-surface mt-1">{{ $p->maksimal_diskon ? 'Rp '.number_format($p->maksimal_diskon,0,',','.') : '-' }}</dd></div>
                            <div><dt class="raliva-label">Mulai</dt><dd class="text-on-surface mt-1">{{ $p->mulai_pada?->translatedFormat('d M Y') ?? '-' }}</dd></div>
                            <div><dt class="raliva-label">Berakhir</dt><dd class="text-on-surface mt-1">{{ $p->berakhir_pada?->translatedFormat('d M Y') ?? '-' }}</dd></div>
                            <div class="sm:col-span-2"><dt class="raliva-label">Deskripsi</dt><dd class="text-on-surface mt-1">{{ $p->deskripsi ?: '—' }}</dd></div>
                        </dl>
                    </div>
                    <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
                        <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
                        <form method="POST" action="{{ route('admin.promo.toggle', $p) }}" class="flex-1">
                            @csrf
                            @if ($p->status === 'aktif')
                                <button type="submit" class="w-full py-2.5 border border-error/20 text-error font-label-sm text-label-sm uppercase rounded hover:bg-error/10 transition-colors">Nonaktifkan</button>
                            @else
                                <button type="submit" class="w-full py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Aktifkan</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $promos->links() }}</div>
        @endif
    </section>
</div>
@endsection
