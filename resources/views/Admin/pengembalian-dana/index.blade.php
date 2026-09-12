@extends('layouts.admin')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Periksa dan proses pengajuan refund sesuai kewenangan.')

@section('content')
<div class="space-y-section-gap">
    <section data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">assignment_return</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Pengajuan Masuk</span>
            <span class="raliva-figure text-[26px] text-gold-accent relative">{{ $pengajuan->count() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Menunggu</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $pengajuan->where('status','requested')->count() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">outbox</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Eskalasi</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $pengajuan->where('status','escalated')->count() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">history</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Riwayat</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $riwayat->total() ?? $riwayat->count() }}</span>
        </div>
    </section>
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
                    <button type="button" data-modal-open="modal-detail-{{ $r->kode }}" class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:border-gold-accent transition-colors">Detail</button>
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
                        <th class="p-4 text-center">Aksi</th>
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
                        <td class="p-4 text-center"><button type="button" data-modal-open="modal-detail-{{ $r->kode }}" class="inline-flex items-center gap-1 px-3 py-1.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Detail</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-6 text-center text-on-surface-variant text-sm">Belum ada riwayat refund.</td></tr>
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
<div id="modal-detail-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div class="min-w-0">
                <p class="raliva-label text-gold-accent">Detail Refund</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">{{ $r->requester?->nama_lengkap ?? 'Customer' }} &#8226; Pesanan #{{ $r->order?->nomor_order ?? $r->order_id }} &#8226; {{ $r->order?->store?->nama_toko ?? '-' }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Tipe</p>
                    <p class="font-bold text-on-surface mt-1 capitalize">{{ $r->tipe_refund ?? '-' }}</p>
                </div>
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Jumlah</p>
                    <p class="font-bold text-gold-accent mt-1">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</p>
                </div>
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Status</p>
                    <p class="font-bold text-on-surface mt-1 capitalize">{{ $r->status === 'escalated' ? 'Eskalasi' : $r->status }}</p>
                </div>
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Diajukan</p>
                    <p class="font-bold text-on-surface mt-1">{{ $r->diajukan_pada?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </div>
            </div>
            <div class="border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                <p class="text-xs text-on-surface-variant mb-1">Alasan Customer</p>
                <p class="text-sm text-on-surface">"{{ $r->alasan }}"</p>
            </div>
            <div>
                <p class="raliva-label mb-2">Item Refund ({{ $r->items->count() }})</p>
                <div class="space-y-2">
                    @forelse ($r->items as $it)
                        <div class="flex items-start justify-between gap-3 border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface text-sm truncate">{{ $it->orderItem?->nama_produk_snapshot ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $it->orderItem?->productVariant?->sku ?? '-' }} &#8226; Qty {{ $it->quantity }}</p>
                                @if ($it->alasan)<p class="text-xs text-on-surface-variant mt-0.5 italic">{{ $it->alasan }}</p>@endif
                            </div>
                            <p class="text-xs text-on-surface whitespace-nowrap shrink-0">Rp {{ number_format((float) $it->nominal, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-on-surface-variant border border-dashed border-outline-variant rounded-lg px-4 py-3 text-center">Refund diajukan untuk seluruh pesanan (tanpa rincian item).</p>
                    @endforelse
                </div>
            </div>
            @if ($r->reviewer || $r->alasan_penolakan)
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant mb-1">Ditangani oleh {{ $r->reviewer?->nama_lengkap ?? '-' }}</p>
                    @if ($r->alasan_penolakan)<p class="text-sm text-error">{{ $r->alasan_penolakan }}</p>@endif
                </div>
            @endif
            @if ($r->file_bukti)
                <a href="{{ asset('storage/' . ltrim($r->file_bukti, '/')) }}" target="_blank" rel="noopener" class="flex items-center justify-between gap-3 border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                    <span class="font-body-md text-sm text-on-surface truncate">Bukti transfer penyelesaian</span>
                    <span class="material-symbols-outlined text-gold-accent">visibility</span>
                </a>
                @if ($r->deskripsi_bukti)<p class="text-xs text-on-surface-variant">{{ $r->deskripsi_bukti }}</p>@endif
            @endif
        </div>
    </div>
</div>
@endforeach
@foreach ($riwayat as $r)
<div id="modal-detail-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div class="min-w-0">
                <p class="raliva-label text-gold-accent">Detail Refund</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">{{ $r->requester?->nama_lengkap ?? 'Customer' }} &#8226; Pesanan #{{ $r->order?->nomor_order ?? $r->order_id }} &#8226; {{ $r->order?->store?->nama_toko ?? '-' }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Tipe</p>
                    <p class="font-bold text-on-surface mt-1 capitalize">{{ $r->tipe_refund ?? '-' }}</p>
                </div>
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Jumlah</p>
                    <p class="font-bold text-gold-accent mt-1">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</p>
                </div>
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Status</p>
                    <p class="font-bold text-on-surface mt-1 capitalize">{{ $r->status === 'escalated' ? 'Eskalasi' : $r->status }}</p>
                </div>
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Selesai</p>
                    <p class="font-bold text-on-surface mt-1">{{ $r->selesai_pada?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </div>
            </div>
            <div class="border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                <p class="text-xs text-on-surface-variant mb-1">Alasan Customer</p>
                <p class="text-sm text-on-surface">"{{ $r->alasan }}"</p>
            </div>
            <div>
                <p class="raliva-label mb-2">Item Refund ({{ $r->items->count() }})</p>
                <div class="space-y-2">
                    @forelse ($r->items as $it)
                        <div class="flex items-start justify-between gap-3 border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface text-sm truncate">{{ $it->orderItem?->nama_produk_snapshot ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $it->orderItem?->productVariant?->sku ?? '-' }} &#8226; Qty {{ $it->quantity }}</p>
                                @if ($it->alasan)<p class="text-xs text-on-surface-variant mt-0.5 italic">{{ $it->alasan }}</p>@endif
                            </div>
                            <p class="text-xs text-on-surface whitespace-nowrap shrink-0">Rp {{ number_format((float) $it->nominal, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-on-surface-variant border border-dashed border-outline-variant rounded-lg px-4 py-3 text-center">Refund diajukan untuk seluruh pesanan (tanpa rincian item).</p>
                    @endforelse
                </div>
            </div>
            @if ($r->reviewer || $r->alasan_penolakan)
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant mb-1">Ditangani oleh {{ $r->reviewer?->nama_lengkap ?? '-' }}</p>
                    @if ($r->alasan_penolakan)<p class="text-sm text-error">{{ $r->alasan_penolakan }}</p>@endif
                </div>
            @endif
            @if ($r->file_bukti)
                <a href="{{ asset('storage/' . ltrim($r->file_bukti, '/')) }}" target="_blank" rel="noopener" class="flex items-center justify-between gap-3 border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low">
                    <span class="font-body-md text-sm text-on-surface truncate">Bukti transfer penyelesaian</span>
                    <span class="material-symbols-outlined text-gold-accent">visibility</span>
                </a>
                @if ($r->deskripsi_bukti)<p class="text-xs text-on-surface-variant">{{ $r->deskripsi_bukti }}</p>@endif
            @endif
        </div>
    </div>
</div>
@endforeach
@endsection
