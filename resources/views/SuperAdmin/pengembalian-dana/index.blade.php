@extends('layouts.superadmin')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Monitor dan tangani kasus refund yang dieskalasikan ke platform.')

@php
    $tabs = [
        \App\Models\Refund::STATUS_REQUESTED => ['label' => 'Menunggu Keputusan'],
        \App\Models\Refund::STATUS_DISETUJUI => ['label' => 'Disetujui'],
        \App\Models\Refund::STATUS_SELESAI => ['label' => 'Selesai'],
        \App\Models\Refund::STATUS_DITOLAK => ['label' => 'Ditolak'],
    ];

    $badgeMap = [
        \App\Models\Refund::STATUS_REQUESTED => ['label' => 'Menunggu Keputusan', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        \App\Models\Refund::STATUS_DISETUJUI => ['label' => 'Disetujui', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Refund::STATUS_SELESAI => ['label' => 'Selesai', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Refund::STATUS_DITOLAK => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
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
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">{{ $stats['menunggu'] }}</span>
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

    <section data-table-scope class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Pengembalian Dana</h2>

        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
            </div>
            <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('superadmin.pengembalian-dana') }}"
                    class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === 'semua'
                        ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                        : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">Semua</a>
                @foreach ($tabs as $key => $tab)
                    <a href="{{ route('superadmin.pengembalian-dana', ['status' => $key]) }}"
                        class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === $key
                            ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                            : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">{{ $tab['label'] }}</a>
                @endforeach
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] premium-table">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="p-4 text-left">ID Refund</th>
                            <th class="p-4 text-left">Pesanan</th>
                            <th class="p-4 text-left">Pelanggan / Toko</th>
                            <th class="p-4 text-left">Alasan</th>
                            <th class="p-4 text-right">Jumlah</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-left">Diajukan</th>
                            <th class="p-4 text-left">Selesai</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-sm">
                        @forelse ($refunds as $refund)
                            @php
                                $badge = $badgeMap[$refund->status];
                                $kode = 'REF-' . str_pad((string) $refund->refund_id, 10, '0', STR_PAD_LEFT);
                            @endphp
                            <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors"
                                data-id="{{ $refund->refund_id }}" data-kode="{{ $kode }}">
                                <td class="p-4 font-mono text-on-surface">{{ $kode }}
                                    <span class="block mt-1 w-fit px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border {{ $refund->tipe_refund === \App\Models\Refund::TIPE_FULL ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }}">{{ $refund->tipe_refund }}</span>
                                </td>
                                <td class="p-4 font-mono text-on-surface-variant">{{ $refund->order?->nomor_order ?? '-' }}</td>
                                <td class="p-4">
                                    <p class="text-on-surface">{{ $refund->requester?->nama_lengkap ?? '-' }}</p>
                                    <p class="text-on-surface-variant text-xs">{{ $refund->order?->store?->nama_toko ?? '-' }}</p>
                                </td>
                                <td class="p-4 text-on-surface max-w-[240px]" title="{{ $refund->alasan }}">{{ \Illuminate\Support\Str::limit($refund->alasan, 60) }}
                                    @if ($refund->status === \App\Models\Refund::STATUS_DITOLAK && $refund->alasan_penolakan)
                                        <span class="block text-xs text-error mt-1" title="{{ $refund->alasan_penolakan }}">Alasan tolak: {{ \Illuminate\Support\Str::limit($refund->alasan_penolakan, 50) }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format((float) $refund->jumlah, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                </td>
                                <td class="p-4 text-on-surface-variant text-xs">{{ $refund->diajukan_pada ? \Carbon\Carbon::parse($refund->diajukan_pada)->locale('id')->diffForHumans() : '-' }}</td>
                                <td class="p-4 text-on-surface-variant text-xs">{{ $refund->selesai_pada ? \Carbon\Carbon::parse($refund->selesai_pada)->locale('id')->diffForHumans() : '-' }}</td>
                                <td class="p-4 text-right whitespace-nowrap">
                                    @if ($refund->status === \App\Models\Refund::STATUS_REQUESTED)
                                        <button type="button" onclick="openRejectRefund(this.closest('tr'))"
                                            class="px-3 py-1.5 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tolak</button>
                                        <form method="POST" action="{{ route('superadmin.pengembalian-dana.setujui', $refund->refund_id) }}" class="inline-block ml-1" onsubmit="return confirm('Setujui refund Rp {{ number_format((float) $refund->jumlah, 0, ',', '.') }} untuk pesanan {{ $refund->order?->nomor_order }}?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Setujui</button>
                                        </form>
                                    @elseif ($refund->status === \App\Models\Refund::STATUS_DISETUJUI)
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
                            <tr><td colspan="9" class="py-12 text-center text-on-surface-variant">Tidak ada refund pada status ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
</script>
@endpush
