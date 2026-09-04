@php
    $tab = $activeTab ?? 'pengajuan';
    $tabs = [
        'pengajuan' => 'Pengajuan',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'selesai' => 'Selesai',
    ];
@endphp

@extends('layouts.owner')

@section('title', 'Pengembalian Dana')
@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Proses pengajuan refund toko Anda.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success') || session('error'))
        <div class="space-y-gutter">
            @if (session('success'))
                <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-error/10 border border-error/30 text-error rounded-lg px-4 py-3 text-sm font-body-md">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('owner.pengembalian-dana', ['tab' => $key]) }}"
                   class="px-3 py-1.5 font-label-sm text-[11px] uppercase tracking-wider rounded-lg transition-colors {{ $activeTab === $key
                       ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                       : 'border border-muted-border bg-surface text-on-surface hover:bg-surface-container-low' }}">
                    {{ $label }} ({{ $stats[$key] ?? 0 }})
                </a>
            @endforeach
        </div>

        @if ($refunds->isEmpty())
            <p class="text-on-surface-variant text-sm py-8 text-center">Tidak ada refund pada tab ini.</p>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            @foreach ($refunds as $r)
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-6 card-premium flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="font-mono text-sm text-on-surface-variant">{{ $r->kode }} • Pesanan #{{ $r->order_id }}</p>
                            <p class="font-title-md text-title-md text-gold-accent mt-1">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">
                            {{ $r->status === 'escalated' ? 'Eskalasi' : ucfirst($r->status) }}
                        </span>
                    </div>
                    <p class="font-body-md text-sm text-on-surface-variant mb-4 flex-1">
                        <span class="text-on-surface font-bold">{{ $r->requester?->nama_lengkap ?? 'Customer' }}:</span> "{{ $r->alasan }}"
                    </p>
                    <div class="flex gap-3">
                        @if (in_array($r->status, [\App\Models\Refund::STATUS_REQUESTED, \App\Models\Refund::STATUS_ESKALASI], true))
                            <button type="button" data-modal-open="modal-setuju-{{ $r->kode }}" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                            <button type="button" data-modal-open="modal-tolak-{{ $r->kode }}" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                        @endif
                        @if ($r->status === \App\Models\Refund::STATUS_DISETUJUI)
                            <form method="POST" action="{{ route('owner.pengembalian-dana.selesaikan', $r) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Selesaikan</button>
                            </form>
                        @endif
                    </div>
                </div>

                @if (in_array($r->status, [\App\Models\Refund::STATUS_REQUESTED, \App\Models\Refund::STATUS_ESKALASI], true))
                <div id="modal-setuju-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                    <form method="POST" action="{{ route('owner.pengembalian-dana.setujui', $r) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                        @csrf
                        <p class="raliva-label text-gold-accent">Setujui Refund</p>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
                        <p class="text-sm text-on-surface-variant mt-3">Setujui refund sebesar <span class="font-bold text-on-surface">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</span>?</p>
                        <div class="flex gap-3 mt-6">
                            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Ya, Setujui</button>
                        </div>
                    </form>
                </div>

                <div id="modal-tolak-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                    <form method="POST" action="{{ route('owner.pengembalian-dana.tolak', $r) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                        @csrf
                        <p class="raliva-label text-gold-accent">Tolak Refund</p>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
                        <label class="block mt-4 text-xs uppercase text-on-surface-variant mb-1">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" rows="3" class="raliva-textarea" placeholder="Opsional"></textarea>
                        <div class="flex gap-3 mt-5">
                            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Ya, Tolak</button>
                        </div>
                    </form>
                </div>
                @endif
                @endforeach
        </div>
        @endif
    </section>
</div>
@endsection
