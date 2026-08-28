@extends('layouts.superadmin')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola dan eskalasi komplain dari seluruh toko di platform.')

@php
    $tabs = [
        \App\Models\Complaint::STATUS_OPEN => 'Terbuka',
        \App\Models\Complaint::STATUS_DIPROSES => 'Diproses',
        \App\Models\Complaint::STATUS_SELESAI => 'Selesai',
        \App\Models\Complaint::STATUS_DITUTUP => 'Ditutup',
    ];

    $badgeMap = [
        \App\Models\Complaint::STATUS_OPEN => ['label' => 'Terbuka', 'class' => 'bg-error/10 text-error border-error/20'],
        \App\Models\Complaint::STATUS_DIPROSES => ['label' => 'Diproses', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        \App\Models\Complaint::STATUS_SELESAI => ['label' => 'Selesai', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Complaint::STATUS_DITUTUP => ['label' => 'Ditutup', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
    ];
@endphp

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Komplain</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Terbuka</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error">{{ $stats['terbuka'] }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diproses</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['diproses'] }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">schedule</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">{{ $stats['selesai'] }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditutup</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['ditutup'] }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">lock</span>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Komplain Lintas Toko</h2>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('superadmin.komplain') }}"
                class="px-3 py-1.5 font-label-sm text-[11px] uppercase tracking-wider rounded-lg transition-colors {{ $activeStatus === 'semua'
                    ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                    : 'border border-muted-border bg-surface text-on-surface hover:bg-surface-container-low' }}">Semua</a>
            @foreach ($tabs as $key => $label)
                <a href="{{ route('superadmin.komplain', ['status' => $key]) }}"
                    class="px-3 py-1.5 font-label-sm text-[11px] uppercase tracking-wider rounded-lg transition-colors {{ $activeStatus === $key
                        ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                        : 'border border-muted-border bg-surface text-on-surface hover:bg-surface-container-low' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[950px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No.</th>
                        <th class="p-4 text-left">ID Komplain</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-left">Kategori</th>
                        <th class="p-4 text-left">Topik</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Waktu</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($complaints as $complaint)
                        @php $rowNumber = $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage(); @endphp
                        @php
                            $badge = $badgeMap[$complaint->status];
                            $kode = 'KOM-' . str_pad((string) $complaint->complaint_id, 4, '0', STR_PAD_LEFT);
                            $statusLabel = $complaint->eskalasi_oleh_sa && $complaint->status === \App\Models\Complaint::STATUS_DIPROSES
                                ? 'Eskalasi ke Owner'
                                : $badge['label'];
                            $bisaAksi = in_array($complaint->status, [\App\Models\Complaint::STATUS_OPEN, \App\Models\Complaint::STATUS_DIPROSES], true);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors"
                            data-id="{{ $complaint->complaint_id }}" data-kode="{{ $kode }}" data-subjek="{{ $complaint->subjek }}">
                            <td class="p-4 text-center text-on-surface-variant font-mono">{{ $rowNumber }}</td>
                            <td class="p-4 font-mono text-on-surface">{{ $kode }}</td>
                            <td class="p-4 text-on-surface">{{ $complaint->user?->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-on-surface">{{ $complaint->store?->nama_toko ?? '-' }}</td>
                            <td class="p-4"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ ucfirst($complaint->kategori) }}</span></td>
                            <td class="p-4 text-on-surface max-w-[240px]" title="{{ $complaint->deskripsi }}">{{ $complaint->subjek }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $statusLabel }}</span></td>
                            <td class="p-4 text-on-surface-variant text-xs">{{ $complaint->dibuat_pada ? \Carbon\Carbon::parse($complaint->dibuat_pada)->locale('id')->diffForHumans() : '-' }}</td>
                            <td class="p-4 text-right whitespace-nowrap">
                                @if ($bisaAksi && ! $complaint->eskalasi_oleh_sa)
                                    <form method="POST" action="{{ route('superadmin.komplain.eskalasi', $complaint->complaint_id) }}" class="inline-block" onsubmit="return confirm('Eskalasi komplain {{ $kode }} ke Owner toko {{ $complaint->store?->nama_toko }}?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Eskalasi ke Owner</button>
                                    </form>
                                @endif
                                @if ($bisaAksi)
                                    <button type="button" onclick="openTutupModal(this.closest('tr'))"
                                        class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tutup</button>
                                @endif
                                <button type="button" data-detail-open="detail-komplain"
                                    data-d-nomor="{{ $kode }}"
                                    data-d-pelanggan="{{ $complaint->user?->nama_lengkap }}"
                                    data-d-toko="{{ $complaint->store?->nama_toko }}"
                                    data-d-kategori="{{ $complaint->kategori }}"
                                    data-d-topik="{{ $complaint->subjek }} — {{ \Illuminate\Support\Str::limit($complaint->deskripsi, 80) }}"
                                    data-d-status="{{ $statusLabel }}"
                                    data-d-waktu="{{ $complaint->dibuat_pada ? \Carbon\Carbon::parse($complaint->dibuat_pada)->locale('id')->diffForHumans() : '-' }}"
                                    class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="py-12 text-center text-on-surface-variant">Tidak ada komplain pada status ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<form method="POST" action="" id="tutup-komplain-form" onsubmit="closeTutupModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="tutupKomplainModal" onclick="if (event.target === this) closeTutupModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">lock</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Tutup Komplain</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Komplain <span id="tutup-kode" class="font-mono font-bold text-on-surface">-</span> akan ditutup dan Customer dinotifikasi.</p>
                <textarea name="catatan" maxlength="1000" rows="3"
                    class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent mb-4"
                    placeholder="Catatan penutupan untuk Customer (opsional)..."></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeTutupModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Tutup Komplain</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="detail-komplain" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Komplain</h3>
                <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Customer</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface text-right"><span data-slot="toko"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Kategori</dt><dd class="text-on-surface text-right"><span data-slot="kategori"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Topik</dt><dd class="text-on-surface text-right"><span data-slot="topik"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Waktu</dt><dd class="text-on-surface text-right"><span data-slot="waktu"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const komplainTutupUrl = '{{ route('superadmin.komplain.tutup', ':id:') }}';

    function openTutupModal(row) {
        document.getElementById('tutup-kode').textContent = row.dataset.kode;
        document.getElementById('tutup-komplain-form').action = komplainTutupUrl.replace(':id:', row.dataset.id);
        document.querySelector('#tutup-komplain-form textarea').value = '';
        const modal = document.getElementById('tutupKomplainModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeTutupModal() {
        const modal = document.getElementById('tutupKomplainModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTutupModal();
    });
</script>
@endpush
