@extends('layouts.admin')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Periksa dan proses pengajuan refund sesuai kewenangan.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success'))
        <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error/30 text-error rounded-lg px-4 py-3 text-sm font-body-md">{{ session('error') }}</div>
    @endif

    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Pengajuan Refund Masuk</h2>
        @if ($pengajuan->isEmpty())
            <p class="text-on-surface-variant text-sm py-8 text-center bg-surface-container-lowest border border-muted-border rounded-lg">Tidak ada pengajuan refund yang menunggu.</p>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            @foreach ($pengajuan as $r)
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">{{ $r->kode }} &#8226; Pesanan #{{ $r->order_id }}</p>
                        <p class="font-title-md text-title-md text-gold-accent mt-1">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $r->status === 'escalated' ? 'Eskalasi' : 'Menunggu' }}</span>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant mb-4 flex-1"><span class="text-on-surface font-bold">{{ $r->requester?->nama_lengkap ?? 'Customer' }}:</span> "{{ $r->alasan }}"</p>
                <div class="flex gap-3">
                    <button type="button" data-modal-open="modal-setuju-{{ $r->kode }}" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                    <button type="button" data-modal-open="modal-tolak-{{ $r->kode }}" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                    @if ($r->status === 'requested')
                    <button type="button" data-modal-open="modal-eskalasi-{{ $r->kode }}" class="px-4 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Eskalasi</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Refund</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Refund</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-right">Jumlah</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Diproses</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($riwayat as $r)
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">{{ $r->kode }}</td>
                        <td class="p-4 text-on-surface">{{ $r->requester?->nama_lengkap ?? '-' }}</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</td>
                        <td class="p-4 text-center">
                            @php $st = $r->status; @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border
                                @if($st==='disetujui') bg-secondary-container/20 text-secondary border-secondary/20
                                @elseif($st==='ditolak') bg-error/10 text-error border-error/20
                                @else bg-surface-container-high text-on-surface-variant border-outline-variant @endif">
                                {{ $st }}
                            </span>
                        </td>
                        <td class="p-4 text-on-surface-variant">{{ optional($r->selesai_pada)->translatedFormat('d M Y, H.i') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-6 text-center text-on-surface-variant text-sm">Belum ada riwayat refund.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $riwayat->links() }}
            </div>
        </div>
    </section>
</div>

{{-- Modal konfirmasi per refund --}}
@foreach ($pengajuan as $r)
<div id="modal-setuju-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pengembalian-dana.setujui', $r) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        @csrf
        <p class="raliva-label text-gold-accent">Setujui Refund</p>
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
        <p class="text-sm text-on-surface-variant mt-3">Setujui refund sebesar <span class="font-bold text-on-surface">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</span> untuk {{ $r->requester?->nama_lengkap ?? 'customer' }}?</p>
        <div class="flex gap-3 mt-6">
            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Ya, Setujui</button>
        </div>
    </form>
</div>

<div id="modal-tolak-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pengembalian-dana.tolak', $r) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
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

<div id="modal-eskalasi-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pengembalian-dana.eskalasi', $r) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        @csrf
        <p class="raliva-label text-gold-accent">Eskalasi Refund</p>
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
        <p class="text-sm text-on-surface-variant mt-3">Eskalasi refund ini ke Super Admin untuk keputusan akhir?</p>
        <div class="flex gap-3 mt-6">
            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Ya, Eskalasi</button>
        </div>
    </form>
</div>
@endforeach
@endsection
