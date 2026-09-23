@extends('layouts.admin')

@section('title', 'Detail Permintaan')

@section('header-title', 'Detail Permintaan Operasional')
@section('header-badge', 'Admin')
@section('header-subtitle', 'Lihat detail dan proses permintaan dari staf toko.')

@section('content')
@include('partials.flash-toast')

@php
    $jenisLabel = match($permintaan->jenis_permintaan) {
        'stok' => 'Stok', 'produksi' => 'Produksi', 'gudang' => 'Gudang',
        'pengiriman' => 'Pengiriman', 'supplier' => 'Supplier', 'lainnya' => 'Lainnya',
        default => ucfirst($permintaan->jenis_permintaan),
    };
    $statusClass = match($permintaan->status) {
        'pending' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'disetujui' => 'bg-success/10 text-success border-success/20',
        'ditolak' => 'bg-error/10 text-error border-error/20',
        default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    };
    $statusLabel = match($permintaan->status) {
        'pending' => 'Menunggu Persetujuan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak',
        default => ucfirst($permintaan->status),
    };
@endphp

<div class="space-y-section-gap max-w-4xl">
    {{-- Header Card --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5">
            <div>
                <p class="raliva-label text-gold-accent mb-1">Detail Permintaan</p>
                <h2 class="font-title-lg text-title-lg text-on-surface premium-heading">{{ $permintaan->judul }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">ID: #{{ str_pad((string) $permintaan->permintaan_id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full {{ $statusClass }} text-[11px] font-bold uppercase border whitespace-nowrap">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Jenis</p>
                <p class="font-bold text-on-surface text-sm">{{ $jenisLabel }}</p>
            </div>
            <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Toko</p>
                <p class="font-bold text-on-surface text-sm">{{ $permintaan->store?->nama_toko ?? '-' }}</p>
            </div>
            <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Pemohon</p>
                <p class="font-bold text-on-surface text-sm">{{ $permintaan->pemohon?->nama_lengkap ?? '-' }}</p>
                <p class="text-[10px] text-on-surface-variant">{{ $permintaan->pemohon?->role?->nama_role ?? '-' }}</p>
            </div>
            <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Diajukan</p>
                <p class="font-bold text-on-surface text-sm">{{ $permintaan->created_at?->translatedFormat('d M Y') }}</p>
                <p class="text-[10px] text-on-surface-variant">{{ $permintaan->created_at?->format('H:i') }} WIB</p>
            </div>
        </div>
    </section>

    {{-- Deskripsi --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-gold-accent text-[22px]">description</span>
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Deskripsi</h3>
        </div>
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
            <p class="font-body-md text-sm text-on-surface whitespace-pre-wrap leading-relaxed">{{ $permintaan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
        </div>
    </section>

    {{-- Payload --}}
    @if ($permintaan->payload)
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-gold-accent text-[22px]">data_object</span>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Data Tambahan</h3>
            </div>
            <pre class="font-mono text-xs text-on-surface bg-surface-container-high p-4 rounded-lg overflow-x-auto border border-muted-border">{{ json_encode($permintaan->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </section>
    @endif

    {{-- Respons Admin --}}
    @if ($permintaan->status !== 'pending')
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-secondary text-[22px]">admin_panel_settings</span>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Respons Admin</h3>
            </div>
            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-secondary-container/20 border border-secondary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-[20px]">person</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface">{{ $permintaan->admin?->nama_lengkap ?? 'Admin' }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $permintaan->diproses_pada?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                    </div>
                </div>
                @if ($permintaan->catatan_admin)
                    <div class="bg-surface-container-high rounded-lg p-3">
                        <p class="text-[10px] uppercase text-on-surface-variant mb-1">Catatan</p>
                        <p class="font-body-md text-sm text-on-surface whitespace-pre-wrap">{{ $permintaan->catatan_admin }}</p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Action Buttons (only when pending) --}}
    @if ($permintaan->status === 'pending')
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-gold-accent text-[22px]">gavel</span>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Proses Permintaan</h3>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" data-modal-open="modal-setujui" class="flex-1 px-5 py-3 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold uppercase tracking-widest btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">task_alt</span> Setujui Permintaan
                </button>
                <button type="button" data-modal-open="modal-tolak" class="flex-1 px-5 py-3 bg-error/10 text-error border border-error/20 rounded-lg text-xs font-semibold uppercase tracking-widest hover:bg-error/20 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">gpp_bad</span> Tolak Permintaan
                </button>
            </div>
        </section>
    @endif

    {{-- Back Link --}}
    <div data-reveal>
        <a href="{{ route('admin.permintaan-operasional') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-on-surface-variant hover:border-gold-accent hover:text-on-surface transition-colors font-label-sm text-xs">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar
        </a>
    </div>
</div>

{{-- Setujui Modal --}}
@if ($permintaan->status === 'pending')
    <div id="modal-setujui" data-modal class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <form method="POST" action="{{ route('admin.permintaan-operasional.setujui', $permintaan->permintaan_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-8">
            @csrf
            <div class="w-14 h-14 rounded-full bg-secondary-container/20 border border-secondary/25 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-secondary text-[28px]">task_alt</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Setujui Permintaan</h3>
            <p class="text-on-surface-variant text-sm text-center mb-6">
                Setujui permintaan <span class="font-bold text-on-surface">"{{ $permintaan->judul }}"</span> dari <span class="font-bold text-on-surface">{{ $permintaan->pemohon?->nama_lengkap }}</span>?
            </p>

            <div class="mb-4">
                <label class="raliva-label" for="catatan-setujui">Catatan (opsional)</label>
                <textarea id="catatan-setujui" name="catatan" rows="3" class="raliva-textarea" placeholder="Tambahkan catatan untuk pemohon..."></textarea>
            </div>

            <div class="flex space-x-3">
                <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                <button type="submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Konfirmasi</button>
            </div>
        </form>
    </div>

    <div id="modal-tolak" data-modal class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <form method="POST" action="{{ route('admin.permintaan-operasional.tolak', $permintaan->permintaan_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-8">
            @csrf
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Tolak Permintaan</h3>
            <p class="text-on-surface-variant text-sm text-center mb-4">
                Permintaan <span class="font-bold text-on-surface">"{{ $permintaan->judul }}"</span> akan ditolak.
            </p>

            <div class="mb-4">
                <label class="raliva-label" for="alasan-tolak">Alasan Penolakan <span class="text-error">*</span></label>
                <textarea id="alasan-tolak" name="alasan" required minlength="10" maxlength="500" rows="4" class="raliva-textarea" placeholder="Jelaskan alasan penolakan dengan jelas (minimal 10 karakter)..."></textarea>
                <p class="text-xs text-on-surface-variant mt-1">Minimal 10 karakter</p>
            </div>

            <div class="flex space-x-3">
                <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
            </div>
        </form>
    </div>
@endif

@endsection
