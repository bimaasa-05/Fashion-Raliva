@extends('layouts.superadmin')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Monitor dan tangani kasus refund yang dieskalasikan ke platform.')

@php
    $badgeMap = [
        'requested' => ['label' => 'Menunggu Keputusan', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'selesai' => ['label' => 'Selesai', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
@endphp

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Refund</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kasus Menunggu</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">{{ $stats['requested'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">perlu keputusan</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nominal Menunggu</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format($stats['nominal_menunggu'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">diajukan Customer</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Disetujui</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['disetujui'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">sedang diproses</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['selesai'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">dana dikembalikan</span>
            </div>
        </div>
    </section>

    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Daftar Pengembalian Dana</h2>

        <!-- Filters -->
        <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
            </div>
            <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
            <div id="chip-group" class="flex flex-wrap gap-2">
                <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $stats['semua'] }})</button>
                <button type="button" data-chip="requested" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu ({{ $stats['requested'] }})</button>
                <button type="button" data-chip="disetujui" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Disetujui ({{ $stats['disetujui'] }})</button>
                <button type="button" data-chip="selesai" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Selesai ({{ $stats['selesai'] }})</button>
                <button type="button" data-chip="ditolak" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditolak ({{ $stats['ditolak'] }})</button>
            </div>
        </div>

        <!-- Search + Result Count -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="refund-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari ID refund, nomor order, nama pelanggan, atau toko..." />
                <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                <span id="result-count">{{ $refunds->count() }}</span> refund
            </p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="p-6 w-12 text-center">No.</th>
                        <th class="p-6">ID Refund</th>
                        <th class="p-6">Pesanan</th>
                        <th class="p-6">Pelanggan / Toko</th>
                        <th class="p-6">Alasan</th>
                        <th class="p-6 text-right">Jumlah</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6">Diajukan</th>
                        <th class="p-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($refunds as $refund)
                        @php
                            $badge = $badgeMap[$refund->status];
                            $kode = 'REF-' . str_pad((string) $refund->refund_id, 10, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors"
                            data-table-row data-status="{{ $refund->status }}" data-search="{{ strtolower($kode.' '.$refund->order?->nomor_order.' '.($refund->requester?->nama_lengkap ?? '').' '.($refund->order?->store?->nama_toko ?? '').' '.$refund->alasan) }}"
                            data-id="{{ $refund->refund_id }}" data-kode="{{ $kode }}">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6 font-mono text-on-surface">{{ $kode }}
                                <span class="block mt-1 w-fit px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border {{ $refund->tipe_refund === \App\Models\Refund::TIPE_FULL ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }}">{{ $refund->tipe_refund }}</span>
                            </td>
                            <td class="p-6 font-mono text-on-surface-variant">{{ $refund->order?->nomor_order ?? '-' }}</td>
                            <td class="p-6">
                                <p class="text-on-surface">{{ $refund->requester?->nama_lengkap ?? '-' }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $refund->order?->store?->nama_toko ?? '-' }}</p>
                            </td>
                            <td class="p-6 text-on-surface max-w-[240px]" title="{{ $refund->alasan }}">{{ \Illuminate\Support\Str::limit($refund->alasan, 60) }}</td>
                            <td class="p-6 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format((float) $refund->jumlah, 0, ',', '.') }}</td>
                            <td class="p-6 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase">{{ $badge['label'] }}</span>
                            </td>
                            <td class="p-6 text-on-surface-variant text-xs">{{ $refund->diajukan_pada ? \Carbon\Carbon::parse($refund->diajukan_pada)->locale('id')->diffForHumans() : '-' }}</td>
                            <td class="p-6 text-right whitespace-nowrap">
                                @if ($refund->status === 'requested')
                                    <button type="button" onclick="openRejectRefund(this.closest('tr'))"
                                        class="px-3 py-1.5 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tolak</button>
                                    <form method="POST" action="{{ route('superadmin.pengembalian-dana.setujui', $refund->refund_id) }}" class="inline-block ml-1" onsubmit="return confirm('Setujui refund Rp {{ number_format((float) $refund->jumlah, 0, ',', '.') }} untuk pesanan {{ $refund->order?->nomor_order }}?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Setujui</button>
                                    </form>
                                @elseif ($refund->status === 'disetujui')
                                    <form method="POST" action="{{ route('superadmin.pengembalian-dana.selesaikan', $refund->refund_id) }}" class="inline-block" onsubmit="return confirm('Tandai refund {{ $kode }} sudah dikirim ke Customer?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Selesaikan</button>
                                    </form>
                                @else
                                    <span class="text-on-surface-variant text-xs uppercase">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="py-12 text-center text-on-surface-variant">Tidak ada refund tercatat.</td></tr>
                    @endforelse
                    <tr id="empty-search" class="hidden">
                        <td colspan="9" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada refund yang cocok.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<form method="POST" action="" id="reject-refund-form" onsubmit="closeRejectRefund()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="rejectRefundModal" onclick="if (event.target === this) closeRejectRefund()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">block</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Alasan Penolakan Refund</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Refund <span id="reject-refund-kode" class="font-mono font-bold text-on-surface">-</span> akan ditolak dan Customer dinotifikasi.</p>
                <textarea name="alasan" required minlength="10" maxlength="1000" rows="4"
                    class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-4"
                    placeholder="Tulis alasan penolakan... (minimal 10 karakter)"></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeRejectRefund()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const refundRejectUrl = '{{ route('superadmin.pengembalian-dana.tolak', ':id:') }}';

    function openRejectRefund(row) {
        document.getElementById('reject-refund-kode').textContent = row.dataset.kode;
        document.getElementById('reject-refund-form').action = refundRejectUrl.replace(':id:', row.dataset.id);
        document.querySelector('#reject-refund-form textarea').value = '';
        const modal = document.getElementById('rejectRefundModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectRefund() {
        const modal = document.getElementById('rejectRefundModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeRejectRefund();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('refund-search');
        const clearBtn = document.getElementById('clear-search');
        const countEl = document.getElementById('result-count');
        const emptySearch = document.getElementById('empty-search');

        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeStatus = 'semua';

        function applyFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const matchStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchStatus && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) {
                    visible++;
                    row.querySelector('.row-num').textContent = visible;
                }
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0);
        }

        chipBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                chipBtns.forEach((b) => {
                    b.classList.remove(...activeClasses);
                    b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
                });
                btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
                btn.classList.add(...activeClasses);
                activeStatus = btn.getAttribute('data-chip');
                applyFilter();
            });
        });

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyFilter, 200);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyFilter();
        });

        applyFilter();
    });
</script>
@endpush
