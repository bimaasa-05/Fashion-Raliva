@extends('layouts.owner')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', '3 Terbuka')
@section('header-subtitle', 'Pantau dan bantu tangani komplain customer toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="space-y-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-36 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Terbuka</span>
            <span class="raliva-figure text-[26px] text-error">{{ $terbuka }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">support_agent</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Respons Toko</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $menunggu }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $selesaiBulanIni }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Resolution Rate</span>
            <span class="raliva-figure text-[26px] text-on-surface"><span>{{ $resolution }}</span>%</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">thumb_up</span>
        </div>
    </section>

    {{-- Info Prioritas --}}
    <section data-reveal class="bg-error/5 border border-error/25 rounded-lg px-6 py-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-[22px] text-error mt-0.5 shrink-0">priority_high</span>
        <p class="font-body-md text-sm text-on-surface"><span class="font-bold">Respons wajib &le; 24 jam.</span> Komplain dengan prioritas tinggi yang tidak segera direspons dapat memengaruhi skor kualitas layanan toko.</p>
    </section>

    {{-- Daftar Komplain --}}
    <section data-table-scope>
        <div data-reveal class="flex items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Daftar Komplain</h2>
            <select data-table-filter="status-komplain" class="raliva-select">
                <option value="">Semua Status</option>
                <option value="baru">Komplain Baru</option>
                <option value="proses">Dalam Penanganan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>

        <div data-reveal-group class="space-y-gutter">
            @forelse ($complaints as $c)
                @php
                    $key = $c->status;
                    $prio = $key === 'baru' ? 'Tinggi' : ($key === 'proses' ? 'Sedang' : 'Rendah');
                    $prioClass = $prio === 'Tinggi'
                        ? 'bg-error/10 text-error border-error/20'
                        : ($prio === 'Sedang' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
                @endphp
                <article data-reveal data-komplain-row data-status="{{ $key }}" class="bg-surface-container-lowest border {{ $key === 'baru' ? 'border-error/25' : 'border-muted-border' }} rounded-lg p-5 md:p-6 card-premium">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl {{ $key === 'selesai' ? 'bg-secondary-container/20 text-secondary' : 'bg-error/10 text-error' }} border {{ $key === 'selesai' ? 'border-secondary/20' : 'border-error/25' }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined fill">{{ $key === 'selesai' ? 'check_circle' : 'report_problem' }}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold text-on-surface">{{ $c->complaint_id }}</p>
                                    <span class="text-xs text-on-surface-variant">• {{ $c->order_id ? '#'.$c->order_id : '-' }} • {{ $c->kategori }}</span>
                                </div>
                                <p class="font-body-md text-sm text-on-surface mt-1.5 leading-snug">{{ $c->subjek }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $c->user?->nama_lengkap ?? 'Customer' }} • {{ optional($c->dibuat_pada)->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-gutter shrink-0 self-start">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $prioClass }} text-[9px] font-bold uppercase border">Prioritas {{ $prio }}</span>
                            @if ($key === 'selesai')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Selesai</span>
                            @elseif ($key === 'proses')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Ditangani</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20 animate-pulse">Baru</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-gutter mt-5 pt-4 border-t border-muted-border">
                        <button type="button" data-modal-open="modal-komplain-{{ $c->complaint_id }}" class="py-2 px-4 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium">Lihat Detail</button>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada komplain.</p>
            @endforelse
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada komplain pada status ini.</p>
        </div>
    </section>
</div>

{{-- Modal Detail Komplain --}}
@foreach ($complaints as $c)
<div id="modal-komplain-{{ $c->complaint_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Komplain</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $c->subjek ?? $c->kategori }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $c->complaint_id }} • {{ $c->user?->nama_lengkap ?? 'Customer' }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Kategori</p>
                    <p class="font-bold text-on-surface capitalize">{{ $c->kategori }}</p>
                </div>
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Status</p>
                    <p class="font-bold text-on-surface capitalize">{{ $c->status }}</p>
                </div>
            </div>
            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Deskripsi</p>
                <p class="font-body-md text-sm text-on-surface">{{ $c->deskripsi }}</p>
            </div>
            @if ($c->order_id)
            <p class="text-xs text-on-surface-variant">Terkait pesanan <span class="font-mono text-on-surface">#{{ $c->order_id }}</span></p>
            @endif
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    document.querySelectorAll('form[data-toast-message]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.showRalivaToast(form.getAttribute('data-toast-message'));
            form.reset();
        });
    });
</script>
@endpush
