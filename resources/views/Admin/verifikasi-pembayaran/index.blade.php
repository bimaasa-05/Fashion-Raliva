@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('header-title', 'Verifikasi Pembayaran')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Periksa bukti pembayaran dan setujui atau tolak dengan alasan.')

@php
    $tabs = [
        'menunggu' => 'Menunggu Verifikasi',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
    ];
@endphp

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Menunggu</span>
            <span class="raliva-figure text-[26px] text-gold-accent relative">{{ $stats['menunggu'] ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">request_quote</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Nominal Menunggu</span>
            <span class="raliva-figure text-[26px] text-secondary relative">Rp {{ number_format($stats['nominal_menunggu'] ?? 0, 0, ',', '.') }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">check_circle</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Diterima</span>
            <span class="raliva-figure text-[26px] text-secondary relative">{{ $stats['diterima'] ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">cancel</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Ditolak</span>
            <span class="raliva-figure text-[26px] text-error relative">{{ $stats['ditolak'] ?? 0 }}</span>
        </div>
    </section>
    <section>
        <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Verifikasi Pembayaran</h2>
            <div class="flex flex-wrap gap-2">
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('admin.verifikasi-pembayaran', $key === 'menunggu' ? [] : ['tab' => $key]) }}"
                        class="px-3 py-1.5 font-label-sm text-[11px] uppercase tracking-wider rounded-lg transition-colors {{ $activeTab === $key
                            ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                            : 'border border-muted-border bg-surface text-on-surface hover:bg-surface-container-low' }}">
                        {{ $label }} ({{ $stats[$key] }})
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            @forelse ($payments as $pembayaran)
                @php
                    $orderUtama = $pembayaran->checkout->orders->first();
                    $bukti = $pembayaran->proofs->first();
                    $verifTerakhir = $pembayaran->verifications->sortByDesc('payment_verification_id')->first();
                @endphp
                <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-id="{{ $pembayaran->payment_id }}">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="font-mono text-sm text-on-surface-variant">#CKT-{{ str_pad((string) $pembayaran->checkout_id, 4, '0', STR_PAD_LEFT) }} &#8226; {{ $pembayaran->checkout?->user?->nama_lengkap ?? '-' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $orderUtama?->store?->nama_toko ?? '-' }}</p>
                            <p class="font-title-md text-title-md text-gold-accent mt-1">Rp {{ number_format((float) $pembayaran->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $pembayaran->paymentMethod?->nama_metode ?? '-' }}</span>
                    </div>

                    @if ($bukti)
                        <div class="border border-muted-border rounded-lg bg-surface-container-low p-4 flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="material-symbols-outlined text-on-surface-variant">receipt_long</span>
                                <span class="font-body-md text-sm text-on-surface truncate">{{ \Illuminate\Support\Str::afterLast($bukti->file_bukti, '/') }}</span>
                            </div>
                            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase shrink-0 ml-2">{{ $bukti->uploaded_at?->translatedFormat('d M H:i') }}</span>
                        </div>
                    @endif

                    @if ($activeTab !== 'menunggu' && $verifTerakhir)
                        @if ($verifTerakhir->alasan)
                            <p class="text-xs {{ $activeTab === 'ditolak' ? 'text-error' : 'text-secondary' }} mb-4"><span class="uppercase font-bold">{{ $activeTab === 'ditolak' ? 'Alasan tolak' : 'Catatan' }}:</span> {{ $verifTerakhir->alasan }}</p>
                        @endif
                    @endif

                    @if ($activeTab === 'menunggu')
                        <div class="flex gap-3">
                            <button type="button" data-modal-open="modal-tolak-{{ $pembayaran->payment_id }}"
                                class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                            <button type="button" data-modal-open="modal-setujui-{{ $pembayaran->payment_id }}"
                                class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-black transition-colors btn-premium">Setujui</button>
                        </div>
                    @else
                        <p class="text-center text-on-surface-variant text-xs uppercase tracking-widest py-2 border-t border-muted-border">Diverifikasi oleh {{ $verifTerakhir?->verifier?->nama_lengkap ?? '-' }} &#8226; {{ $verifTerakhir?->diverifikasi_pada?->translatedFormat('d M Y H:i') }}</p>
                    @endif
                </div>
                @if ($activeTab === 'menunggu')
                <div id="modal-setujui-{{ $pembayaran->payment_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8 text-center">
                        <div class="w-14 h-14 rounded-full bg-secondary-container/20 border border-secondary/25 flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-secondary text-[28px]">task_alt</span>
                        </div>
                        <h3 class="font-title-md text-title-md text-on-surface mb-2">Setujui Pembayaran</h3>
                        <p class="text-on-surface-variant text-sm mb-6">Setujui pembayaran <span class="font-bold text-on-surface">Rp {{ number_format((float) $pembayaran->jumlah, 0, ',', '.') }}</span> dari <span class="font-bold text-on-surface">{{ $pembayaran->checkout?->user?->nama_lengkap }}</span>?</p>
                        <div class="flex space-x-3">
                            <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                            <form method="POST" action="{{ route('admin.verifikasi-pembayaran.setujui', $pembayaran->payment_id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Konfirmasi</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="modal-tolak-{{ $pembayaran->payment_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                    <form method="POST" action="{{ route('admin.verifikasi-pembayaran.tolak', $pembayaran->payment_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8">
                        @csrf
                        <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
                        </div>
                        <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Tolak Pembayaran</h3>
                        <p class="text-on-surface-variant text-sm text-center mb-4">Checkout <span class="font-mono font-bold text-on-surface">#CKT-{{ str_pad((string) $pembayaran->checkout_id, 4, '0', STR_PAD_LEFT) }}</span> akan ditolak. Customer diminta mengunggah ulang bukti.</p>
                        <textarea name="alasan" required minlength="10" maxlength="1000" rows="4" class="raliva-textarea" placeholder="Contoh: Nominal transfer tidak sesuai dengan total tagihan... (minimal 10 karakter)"></textarea>
                        <div class="flex space-x-3 mt-4">
                            <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                            <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                        </div>
                    </form>
                </div>
                @endif
            @empty
                <p class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada pembayaran pada tab ini.</p>
            @endforelse
        </div>
    </section>
</div>

@endsection
