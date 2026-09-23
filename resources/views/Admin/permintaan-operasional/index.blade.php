@extends('layouts.admin')

@section('title', 'Permintaan Operasional')

@section('header-title', 'Permintaan Operasional')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Permintaan operasional dari staf toko yang menunggu persetujuan.')

@php
    $filterChips = [
        'semua' => 'Semua',
        'pending' => 'Pending',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
    ];
@endphp

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Stats Cards --}}
    <section data-reveal-group class="grid grid-cols-2 lg:grid-cols-3 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Menunggu Persetujuan</span>
            <span class="raliva-figure text-[26px] text-gold-accent relative">{{ $stats['pending'] ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">check_circle</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Disetujui</span>
            <span class="raliva-figure text-[26px] text-secondary relative">{{ $stats['disetujui'] ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">cancel</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Ditolak</span>
            <span class="raliva-figure text-[26px] text-error relative">{{ $stats['ditolak'] ?? 0 }}</span>
        </div>
    </section>

    {{-- Filter Chips --}}
    <section data-reveal class="flex flex-wrap items-center gap-2">
        @foreach ($filterChips as $key => $label)
            <a href="{{ route('admin.permintaan-operasional', $key === 'semua' ? [] : ['status' => $key]) }}"
                class="px-3 py-1.5 font-label-sm text-[11px] uppercase tracking-wider rounded-lg transition-colors {{ $activeStatus === $key
                    ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                    : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                {{ $label }}
            </a>
        @endforeach
    </section>

    {{-- Table Section --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg card-premium overflow-hidden">
        <div class="p-5 border-b border-muted-border">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Permintaan Operasional</h2>
        </div>
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left w-16">No</th>
                        <th class="p-4 text-left">Pemohon</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-left">Jenis</th>
                        <th class="p-4 text-left">Judul</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($permintaan as $index => $p)
                        @php
                            $statusClass = match($p->status) {
                                'pending' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                                'disetujui' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                                'ditolak' => 'bg-error/10 text-error border-error/20',
                                default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                            };
                            $statusLabel = match($p->status) {
                                'pending' => 'Pending',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                default => ucfirst($p->status),
                            };
                            $jenisLabel = match($p->jenis_permintaan) {
                                'stok' => 'Stok',
                                'produksi' => 'Produksi',
                                'gudang' => 'Gudang',
                                'pengiriman' => 'Pengiriman',
                                'supplier' => 'Supplier',
                                'lainnya' => 'Lainnya',
                                default => ucfirst($p->jenis_permintaan),
                            };
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface-variant">{{ $permintaan->firstItem() + $index }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gold-accent/10 border border-gold-accent/20 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-gold-accent text-[16px]">person</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-on-surface font-semibold truncate">{{ $p->pemohon?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $p->pemohon?->role?->nama_role ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-on-surface">{{ $p->store?->nama_toko ?? '-' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">
                                    {{ $jenisLabel }}
                                </span>
                            </td>
                            <td class="p-4">
                                <p class="text-on-surface font-medium truncate max-w-[200px]" title="{{ $p->judul }}">{{ $p->judul }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $statusClass }} text-[10px] font-bold uppercase border">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface-variant">{{ $p->created_at?->translatedFormat('d M Y H:i') }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($p->status === 'pending')
                                        <button type="button" data-modal-open="modal-setujui-{{ $p->permintaan_id }}" class="px-3 py-1.5 bg-secondary-container/20 text-secondary border border-secondary/20 rounded-lg text-xs font-semibold hover:bg-secondary-container/30 transition-colors">
                                            Setujui
                                        </button>
                                        <button type="button" data-modal-open="modal-tolak-{{ $p->permintaan_id }}" class="px-3 py-1.5 bg-error/10 text-error border border-error/20 rounded-lg text-xs font-semibold hover:bg-error/20 transition-colors">
                                            Tolak
                                        </button>
                                    @endif
                                    <button type="button" data-modal-open="modal-detail-{{ $p->permintaan_id }}" class="px-3 py-1.5 border border-muted-border text-on-surface rounded-lg text-xs font-semibold hover:border-gold-accent transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada permintaan operasional pada filter ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden grid grid-cols-1 gap-gutter p-4">
            @forelse ($permintaan as $index => $p)
                @php
                    $statusClass = match($p->status) {
                        'pending' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                        'disetujui' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                        'ditolak' => 'bg-error/10 text-error border-error/20',
                        default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                    };
                    $statusLabel = match($p->status) {
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($p->status),
                    };
                    $jenisLabel = match($p->jenis_permintaan) {
                        'stok' => 'Stok',
                        'produksi' => 'Produksi',
                        'gudang' => 'Gudang',
                        'pengiriman' => 'Pengiriman',
                        'supplier' => 'Supplier',
                        'lainnya' => 'Lainnya',
                        default => ucfirst($p->jenis_permintaan),
                    };
                @endphp
                <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-gold-accent/10 border border-gold-accent/20 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-gold-accent text-[16px]">person</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-on-surface font-semibold truncate">{{ $p->pemohon?->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $p->pemohon?->role?->nama_role ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full {{ $statusClass }} text-[10px] font-bold uppercase border">{{ $statusLabel }}</span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-muted-border space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $jenisLabel }}</span>
                            <span class="text-xs text-on-surface-variant">{{ $p->created_at?->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <p class="text-on-surface font-medium truncate" title="{{ $p->judul }}">{{ $p->judul }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $p->store?->nama_toko ?? '-' }}</p>
                    </div>
                    <div class="mt-3 pt-3 border-t border-muted-border flex items-center justify-end gap-2 flex-wrap">
                        @if ($p->status === 'pending')
                            <button type="button" data-modal-open="modal-setujui-{{ $p->permintaan_id }}" class="px-3 py-1.5 bg-secondary-container/20 text-secondary border border-secondary/20 rounded-lg text-xs font-semibold hover:bg-secondary-container/30 transition-colors">Setujui</button>
                            <button type="button" data-modal-open="modal-tolak-{{ $p->permintaan_id }}" class="px-3 py-1.5 bg-error/10 text-error border border-error/20 rounded-lg text-xs font-semibold hover:bg-error/20 transition-colors">Tolak</button>
                        @endif
                        <button type="button" data-modal-open="modal-detail-{{ $p->permintaan_id }}" class="px-3 py-1.5 border border-muted-border text-on-surface rounded-lg text-xs font-semibold hover:border-gold-accent transition-colors">Detail</button>
                    </div>
                </article>
            @empty
                <div class="flex flex-col items-center gap-3 py-8">
                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada permintaan operasional pada filter ini.</p>
                </div>
            @endforelse
        </div>
        @if ($permintaan->hasPages())
            <div class="p-4 border-t border-muted-border">
                {{ $permintaan->links() }}
            </div>
        @endif
    </section>
</div>

{{-- Detail Modals --}}
@foreach ($permintaan as $p)
    @php
        $jenisLabel = match($p->jenis_permintaan) {
            'stok' => 'Stok',
            'produksi' => 'Produksi',
            'gudang' => 'Gudang',
            'pengiriman' => 'Pengiriman',
            'supplier' => 'Supplier',
            'lainnya' => 'Lainnya',
            default => ucfirst($p->jenis_permintaan),
        };
        $statusClass = match($p->status) {
            'pending' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
            'disetujui' => 'bg-secondary-container/20 text-secondary border-secondary/20',
            'ditolak' => 'bg-error/10 text-error border-error/20',
            default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
        };
        $statusLabel = match($p->status) {
            'pending' => 'Pending',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($p->status),
        };
    @endphp
    
    {{-- Detail Modal --}}
    <div id="modal-detail-{{ $p->permintaan_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div class="min-w-0">
                    <p class="raliva-label text-gold-accent">Detail Permintaan Operasional</p>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $p->judul }}</h3>
                    <p class="text-on-surface-variant font-body-md text-xs mt-1">ID: #{{ str_pad((string) $p->permintaan_id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 space-y-5">
                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                        <p class="text-[10px] uppercase text-on-surface-variant">Pemohon</p>
                        <p class="font-bold text-on-surface">{{ $p->pemohon?->nama_lengkap ?? '-' }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $p->pemohon?->role?->nama_role ?? '-' }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                        <p class="text-[10px] uppercase text-on-surface-variant">Toko</p>
                        <p class="font-bold text-on-surface">{{ $p->store?->nama_toko ?? '-' }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                        <p class="text-[10px] uppercase text-on-surface-variant">Jenis Permintaan</p>
                        <p class="font-bold text-on-surface">{{ $jenisLabel }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-lg p-3 border border-muted-border">
                        <p class="text-[10px] uppercase text-on-surface-variant">Status</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $statusClass }} text-[10px] font-bold uppercase border mt-1">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                    <p class="text-[10px] uppercase text-on-surface-variant mb-2">Deskripsi</p>
                    <p class="font-body-md text-sm text-on-surface whitespace-pre-wrap">{{ $p->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                {{-- Payload JSON --}}
                @if ($p->payload)
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                        <p class="text-[10px] uppercase text-on-surface-variant mb-2">Data Tambahan (Payload)</p>
                        <pre class="font-mono text-xs text-on-surface bg-surface-container-high p-3 rounded-lg overflow-x-auto">{{ json_encode($p->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif

                {{-- Admin Response --}}
                @if ($p->status !== 'pending')
                    <div class="border-t border-muted-border pt-4">
                        <p class="text-[10px] uppercase text-on-surface-variant mb-3">Respons Admin</p>
                        <div class="bg-surface-container-low rounded-lg p-4 border border-muted-border">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-secondary-container/20 border border-secondary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-secondary text-[20px]">admin_panel_settings</span>
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface">{{ $p->admin?->nama_lengkap ?? 'Admin' }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $p->diproses_pada?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                                </div>
                            </div>
                            @if ($p->catatan_admin)
                                <div class="bg-surface-container-high rounded-lg p-3">
                                    <p class="text-[10px] uppercase text-on-surface-variant mb-1">Catatan</p>
                                    <p class="font-body-md text-sm text-on-surface">{{ $p->catatan_admin }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Metadata --}}
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <p class="text-on-surface-variant">Diajukan pada</p>
                        <p class="text-on-surface font-medium">{{ $p->created_at?->translatedFormat('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-on-surface-variant">Terakhir diupdate</p>
                        <p class="text-on-surface font-medium">{{ $p->updated_at?->translatedFormat('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-2">
                @if ($p->status === 'pending')
                    <button type="button" data-modal-open="modal-tolak-{{ $p->permintaan_id }}" data-modal-close class="px-5 py-2.5 bg-error/10 text-error border border-error/20 rounded-lg text-xs font-semibold hover:bg-error/20 transition-colors">
                        Tolak
                    </button>
                    <button type="button" data-modal-open="modal-setujui-{{ $p->permintaan_id }}" data-modal-close class="px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium">
                        Setujui
                    </button>
                @endif
                <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border text-on-surface rounded-lg text-xs font-semibold hover:border-gold-accent transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Setujui Modal --}}
    @if ($p->status === 'pending')
        <div id="modal-setujui-{{ $p->permintaan_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <form method="POST" action="{{ route('admin.permintaan-operasional.setujui', $p->permintaan_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-8">
                @csrf
                <div class="w-14 h-14 rounded-full bg-secondary-container/20 border border-secondary/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-secondary text-[28px]">task_alt</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Setujui Permintaan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-6">
                    Setujui permintaan <span class="font-bold text-on-surface">"{{ $p->judul }}"</span> dari <span class="font-bold text-on-surface">{{ $p->pemohon?->nama_lengkap }}</span>?
                </p>
                
                <div class="mb-4">
                    <label class="raliva-label" for="catatan-setujui-{{ $p->permintaan_id }}">Catatan (opsional)</label>
                    <textarea id="catatan-setujui-{{ $p->permintaan_id }}" name="catatan" rows="3" class="raliva-textarea" placeholder="Tambahkan catatan untuk pemohon..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>

        {{-- Tolak Modal --}}
        <div id="modal-tolak-{{ $p->permintaan_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <form method="POST" action="{{ route('admin.permintaan-operasional.tolak', $p->permintaan_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-8">
                @csrf
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Tolak Permintaan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">
                    Permintaan <span class="font-bold text-on-surface">"{{ $p->judul }}"</span> akan ditolak.
                </p>
                
                <div class="mb-4">
                    <label class="raliva-label" for="alasan-tolak-{{ $p->permintaan_id }}">Alasan Penolakan <span class="text-error">*</span></label>
                    <textarea id="alasan-tolak-{{ $p->permintaan_id }}" name="alasan" required minlength="10" maxlength="1000" rows="4" class="raliva-textarea" placeholder="Jelaskan alasan penolakan dengan jelas (minimal 10 karakter)..."></textarea>
                    <p class="text-xs text-on-surface-variant mt-1">Minimal 10 karakter</p>
                </div>

                <div class="flex space-x-3">
                    <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    @endif
@endforeach

@endsection
