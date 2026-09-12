@extends('layouts.admin')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Terima, balas, proses, dan eskalasi komplain customer.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success'))
        <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error/30 text-error rounded-lg px-4 py-3 text-sm font-body-md">{{ session('error') }}</div>
    @endif

    @if ($complaints->isEmpty())
        <p class="text-on-surface-variant text-sm py-10 text-center bg-surface-container-lowest border border-muted-border rounded-lg">Belum ada komplain.</p>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        @foreach ($complaints as $c)
        <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div>
                    <p class="font-mono text-xs text-on-surface-variant">{{ $c->complaint_id }} &#8226; {{ $c->kategori }}</p>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-0.5">{{ $c->subjek }}</h3>
                </div>
                @php $st = $c->status; @endphp
                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border shrink-0
                    @if($st==='open') bg-error/10 text-error border-error/20
                    @elseif($st==='diproses') bg-secondary-container/20 text-secondary border-secondary/20
                    @elseif($st==='escalated') bg-warning/10 text-warning border-warning/20
                    @else bg-surface-container-high text-on-surface-variant border-outline-variant @endif">
                    {{ $st }}
                </span>
            </div>

            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-4">
                <p class="font-body-md text-sm text-on-surface-variant">"{{ $c->deskripsi }}" — <span class="text-on-surface font-bold">{{ $c->user?->nama_lengkap ?? 'Customer' }}</span></p>
            </div>

            @if ($c->messages->isNotEmpty())
            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-4 space-y-2 max-h-40 overflow-y-auto">
                @foreach ($c->messages as $m)
                <p class="font-body-md text-sm text-on-surface"><span class="font-bold">{{ $m->sender?->nama_lengkap ?? 'Admin' }}:</span> {{ $m->pesan }}</p>
                @endforeach
            </div>
            @endif

            <div class="flex gap-3 mt-auto">
                <button type="button" data-modal-open="modal-detail-{{ $c->complaint_id }}" class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:border-gold-accent transition-colors">Detail</button>
                <button type="button" data-modal-open="modal-balas-{{ $c->complaint_id }}" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Balas</button>
                @if ($st !== 'selesai' && $st !== 'ditutup')
                <button type="button" data-modal-open="modal-eskalasi-{{ $c->complaint_id }}" class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Eskalasi</button>
                @endif
            </div>
        </article>

        {{-- Modal Balas --}}
        <div id="modal-balas-{{ $c->complaint_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <form method="POST" action="{{ route('admin.komplain.balas', $c) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                @csrf
                <p class="raliva-label text-gold-accent">Balas Komplain</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $c->subjek }}</h3>
                <label class="block mt-4 text-xs uppercase text-on-surface-variant mb-1">Pesan Balasan</label>
                <textarea name="pesan" rows="4" class="raliva-textarea" placeholder="Tulis balasan untuk customer..."></textarea>
                <div class="flex gap-3 mt-5">
                    <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Kirim Balasan</button>
                </div>
            </form>
        </div>

        {{-- Modal Eskalasi --}}
        <div id="modal-eskalasi-{{ $c->complaint_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <form method="POST" action="{{ route('admin.komplain.eskalasi', $c) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                @csrf
                <p class="raliva-label text-gold-accent">Eskalasi Komplain</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $c->subjek }}</h3>
                <p class="text-sm text-on-surface-variant mt-3">Eskalasi komplain ini ke Owner Toko untuk tindak lanjut?</p>
                <div class="flex gap-3 mt-6">
                    <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Ya, Eskalasi</button>
                </div>
            </form>
        </div>
        <div id="modal-detail-{{ $c->complaint_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
                <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                    <div class="min-w-0">
                        <p class="raliva-label text-gold-accent">Detail Komplain</p>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $c->subjek }}</h3>
                        <p class="text-on-surface-variant font-body-md text-xs mt-1">{{ $c->complaint_id }} &#8226; {{ $c->kategori }} &#8226; {{ $c->user?->nama_lengkap ?? 'Customer' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="border border-muted-border rounded-lg px-4 py-3">
                            <p class="text-xs text-on-surface-variant">Pesanan</p>
                            <p class="font-bold text-on-surface mt-1">#{{ $c->order?->nomor_order ?? $c->order_id ?? '-' }}</p>
                        </div>
                        <div class="border border-muted-border rounded-lg px-4 py-3">
                            <p class="text-xs text-on-surface-variant">Toko</p>
                            <p class="font-bold text-on-surface mt-1 truncate">{{ $c->order?->store?->nama_toko ?? $c->store?->nama_toko ?? '-' }}</p>
                        </div>
                        <div class="border border-muted-border rounded-lg px-4 py-3 col-span-2">
                            <p class="text-xs text-on-surface-variant">Produk</p>
                            <p class="font-bold text-on-surface mt-1">{{ $c->orderItem?->productVariant?->product?->nama_produk ?? $c->orderItem?->nama_produk_snapshot ?? '-' }}</p>
                            @if ($c->orderItem?->productVariant)<p class="text-xs text-on-surface-variant mt-0.5">{{ $c->orderItem->productVariant->sku }} &#8226; {{ trim(($c->orderItem->productVariant->warna ?? '') . ' ' . ($c->orderItem->productVariant->ukuran ?? '')) }}</p>@endif
                        </div>
                        <div class="border border-muted-border rounded-lg px-4 py-3">
                            <p class="text-xs text-on-surface-variant">Status</p>
                            <p class="font-bold text-on-surface mt-1 capitalize">{{ $c->status }}</p>
                        </div>
                        <div class="border border-muted-border rounded-lg px-4 py-3">
                            <p class="text-xs text-on-surface-variant">Dibuat</p>
                            <p class="font-bold text-on-surface mt-1">{{ $c->dibuat_pada?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                        <p class="text-xs text-on-surface-variant mb-1">Isi Komplain</p>
                        <p class="text-sm text-on-surface">"{{ $c->deskripsi }}"</p>
                    </div>
                    <div>
                        <p class="raliva-label mb-2">Thread ({{ $c->messages->count() }})</p>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @forelse ($c->messages as $m)
                                <div class="border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                                    <p class="text-xs text-on-surface-variant">{{ $m->sender?->nama_lengkap ?? '-' }} &#8226; {{ $m->created_at?->translatedFormat('d M H:i') }}</p>
                                    <p class="text-sm text-on-surface mt-1">{{ $m->pesan }}</p>
                                    @if ($m->lampiran)<a href="{{ asset('storage/' . ltrim($m->lampiran, '/')) }}" target="_blank" rel="noopener" class="text-xs text-gold-accent hover:underline">Lihat lampiran</a>@endif
                                </div>
                            @empty
                                <p class="text-xs text-on-surface-variant border border-dashed border-outline-variant rounded-lg px-4 py-3 text-center">Belum ada balasan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
