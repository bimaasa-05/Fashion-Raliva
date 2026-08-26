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
    <section>
        <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Verifikasi Pembayaran</h2>
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
                            <p class="font-mono text-sm text-on-surface-variant">#CKT-{{ str_pad((string) $pembayaran->checkout_id, 4, '0', STR_PAD_LEFT) }} • {{ $pembayaran->checkout?->user?->nama_lengkap ?? '-' }}</p>
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
                        <form method="POST" action="{{ route('admin.verifikasi-pembayaran.setujui', $pembayaran->payment_id) }}" onsubmit="return confirm('Setujui pembayaran Rp {{ number_format((float) $pembayaran->jumlah, 0, ',', '.') }} dari {{ $pembayaran->checkout?->user?->nama_lengkap }}?');">
                            @csrf
                            <div class="flex gap-3">
                                <button type="button" onclick="openTolakPembayaran(this.closest('.card-premium').dataset.id)"
                                    class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-black transition-colors btn-premium">Setujui</button>
                            </div>
                        </form>
                    @else
                        <p class="text-center text-on-surface-variant text-xs uppercase tracking-widest py-2 border-t border-muted-border">Diverifikasi oleh {{ $verifTerakhir?->verifier?->nama_lengkap ?? '-' }} • {{ $verifTerakhir?->diverifikasi_pada?->translatedFormat('d M Y H:i') }}</p>
                    @endif
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada pembayaran pada tab ini.</p>
            @endforelse
        </div>
    </section>
</div>

<form method="POST" action="" id="tolak-pembayaran-form" onsubmit="closeTolakPembayaran()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="tolakPembayaranModal" onclick="if (event.target === this) closeTolakPembayaran()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Tolak Pembayaran</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Checkout #<span id="tolak-pembayaran-id" class="font-mono font-bold text-on-surface">-</span> akan ditolak. Customer diminta mengunggah ulang bukti.</p>
                <textarea name="alasan" required minlength="10" maxlength="1000" rows="4"
                    class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-4"
                    placeholder="Contoh: Nominal transfer tidak sesuai dengan total tagihan... (minimal 10 karakter)"></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeTolakPembayaran()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const tolakPembayaranUrl = '{{ route('admin.verifikasi-pembayaran.tolak', ':id:') }}';

    function openTolakPembayaran(paymentId) {
        document.getElementById('tolak-pembayaran-id').textContent = paymentId;
        document.getElementById('tolak-pembayaran-form').action = tolakPembayaranUrl.replace(':id:', paymentId);
        document.querySelector('#tolak-pembayaran-form textarea').value = '';
        const modal = document.getElementById('tolakPembayaranModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeTolakPembayaran() {
        const modal = document.getElementById('tolakPembayaranModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTolakPembayaran();
    });
</script>
@endpush
