@extends('layouts.owner')

@section('title', 'Pengembalian Dana')
@section('header-title', 'Pengembalian Dana')
@section('header-badge', $eskalasiCount.' Keputusan')
@section('header-subtitle', 'Pengajuan refund yang dieskalasi Admin untuk keputusan Anda, dan refund disetujui yang menunggu penyelesaian dana.')

@section('content')
<div class="space-y-section-gap">
    {{-- Penanda halaman: Komplain / Pengembalian Dana --}}
    <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 max-w-full overflow-x-auto">
        <a href="{{ route('owner.komplain') }}" class="px-4 py-2 rounded-md text-xs font-medium transition-colors whitespace-nowrap {{ request()->routeIs('owner.komplain*') && ! request()->routeIs('owner.pengembalian-dana') ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Komplain</a>
        <a href="{{ route('owner.pengembalian-dana') }}" class="px-4 py-2 rounded-md text-xs font-medium transition-colors whitespace-nowrap {{ request()->routeIs('owner.pengembalian-dana') ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Pengembalian Dana</a>
    </div>
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses pengembalian dana.</p>
            </div>
        </div>
    @endif
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

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium card-static">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Menunggu Keputusan Anda</h2>
                <p class="text-xs text-on-surface-variant mt-1">Refund yang dieskalasi Admin. Setelah diputuskan oleh Anda, tidak tampil lagi di daftar ini.</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $eskalasiCount }}</span>
        </div>

        @if ($refunds->isEmpty())
            <p class="text-on-surface-variant text-sm py-10 text-center">Tidak ada pengajuan refund yang menunggu keputusan Anda.</p>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
                @foreach ($refunds as $r)
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-6 card-premium flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="font-mono text-sm text-on-surface-variant">{{ $r->kode }} • Pesanan #{{ $r->order_id }}</p>
                                <p class="font-title-md text-title-md text-gold-accent mt-1">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Eskalasi</span>
                        </div>
                        <p class="font-body-md text-sm text-on-surface-variant mb-4 flex-1">
                            <span class="text-on-surface font-bold">{{ $r->requester?->nama_lengkap ?? 'Customer' }}:</span> "{{ $r->alasan }}"
                        </p>
                        @if ($r->file_bukti_request)
                            <a href="{{ asset('storage/' . ltrim($r->file_bukti_request, '/')) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-xs font-semibold text-gold-accent hover:underline mb-4">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>Foto bukti barang dari customer
                                @if ($r->deskripsi_bukti_request)
                                    <span class="text-on-surface-variant font-normal">— {{ $r->deskripsi_bukti_request }}</span>
                                @endif
                            </a>
                        @endif
                        <div class="flex gap-3">
                            <button type="button" data-modal-open="modal-setuju-{{ $r->kode }}" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                            <button type="button" data-modal-open="modal-tolak-{{ $r->kode }}" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                        </div>
                    </div>

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
                @endforeach
            </div>
        @endif
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium card-static">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Menunggu Penyelesaian Anda</h2>
                <p class="text-xs text-on-surface-variant mt-1">Refund yang sudah disetujui. Tandai selesai beserta bukti dana dikembalikan ke customer.</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $disetujuiCount }}</span>
        </div>

        @if ($disetujui->isEmpty())
            <p class="text-on-surface-variant text-sm py-10 text-center">Tidak ada refund disetujui yang menunggu penyelesaian.</p>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
                @foreach ($disetujui as $r)
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-6 card-premium flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="font-mono text-sm text-on-surface-variant">{{ $r->kode }} • Pesanan #{{ $r->order_id }}</p>
                                <p class="font-title-md text-title-md text-gold-accent mt-1">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase border border-success/20">Disetujui</span>
                        </div>
                        <p class="font-body-md text-sm text-on-surface-variant mb-4 flex-1">
                            <span class="text-on-surface font-bold">{{ $r->requester?->nama_lengkap ?? 'Customer' }}:</span> "{{ $r->alasan }}"
                        </p>
                        @if ($r->file_bukti_request)
                            <a href="{{ asset('storage/' . ltrim($r->file_bukti_request, '/')) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-xs font-semibold text-gold-accent hover:underline mb-4">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>Foto bukti barang dari customer
                            </a>
                        @endif
                        @if ($r->file_bukti)
                            <a href="{{ asset('storage/' . ltrim($r->file_bukti, '/')) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-xs font-semibold text-gold-accent hover:underline mb-4">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>Bukti dana dikembalikan
                                @if ($r->deskripsi_bukti)
                                    <span class="text-on-surface-variant font-normal">— {{ $r->deskripsi_bukti }}</span>
                                @endif
                            </a>
                        @endif
                        <div class="flex gap-3">
                            <button type="button" data-modal-open="modal-selesaikan-{{ $r->kode }}" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Selesaikan</button>
                        </div>
                    </div>

                    <div id="modal-selesaikan-{{ $r->kode }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                        <form method="POST" action="{{ route('owner.pengembalian-dana.selesaikan', $r) }}" enctype="multipart/form-data" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                            @csrf
                            <p class="raliva-label text-gold-accent">Selesaikan Refund</p>
                            <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $r->kode }}</h3>
                            <p class="text-sm text-on-surface-variant mt-3">Tandai refund sebesar <span class="font-bold text-on-surface">Rp {{ number_format((float) $r->jumlah, 0, ',', '.') }}</span> sebagai selesai. Lampirkan bukti dana dikembalikan ke customer.</p>
                            <label class="block mt-4 text-xs uppercase text-on-surface-variant mb-1">Bukti Refund (JPG/PNG/PDF, maks. 5 MB) — Opsional</label>
                            <input name="file_bukti" type="file" accept="image/jpeg,image/png,image/jpg,application/pdf" class="w-full text-sm" />
                            <label class="block mt-3 text-xs uppercase text-on-surface-variant mb-1">Deskripsi Bukti</label>
                            <input name="deskripsi_bukti" type="text" maxlength="1000" class="raliva-textarea" placeholder="Opsional, cth. transfer ke rekening customer" />
                            <div class="flex gap-3 mt-5">
                                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Ya, Selesaikan</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Check if no store banner exists (means no store)
  const noStore = document.querySelector('[data-no-store-banner]');
  if (!noStore) return;
  // Disable all primary action buttons except Ajukan Toko
  document.querySelectorAll('[data-modal-open], button[type="submit"], a[href*="pengajuan-toko"]:not([href*="ajukan"])').forEach(el=>{
    el.setAttribute('disabled','');
    el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
  });
});
</script>
@endpush