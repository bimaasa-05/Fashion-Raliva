@extends('layouts.superadmin')

@section('title', 'Pencairan Dana')

@section('header-title', 'Pencairan Dana')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi dan setujui pengajuan pencairan dana Owner.')

@php
    $tabs = [
        'semua' => 'Semua',
        \App\Models\Withdrawal::STATUS_PENDING => 'Menunggu',
        \App\Models\Withdrawal::STATUS_DISETUJUI => 'Disetujui',
        \App\Models\Withdrawal::STATUS_DIBAYAR => 'Dibayar',
        \App\Models\Withdrawal::STATUS_DITOLAK => 'Ditolak',
    ];

    $badgeMap = [
        \App\Models\Withdrawal::STATUS_PENDING => ['label' => 'Menunggu', 'class' => 'bg-surface-container-high text-on-surface border-outline-variant'],
        \App\Models\Withdrawal::STATUS_DISETUJUI => ['label' => 'Disetujui', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Withdrawal::STATUS_DIBAYAR => ['label' => 'Dibayar', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Withdrawal::STATUS_DITOLAK => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
@endphp

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Pengajuan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-container-margin card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-surface-container-high flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-on-surface">pending_actions</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Pengajuan Menunggu</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-primary">{{ $stats['menunggu'] }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Menunggu verifikasi dan persetujuan</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-container-margin card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-secondary-container flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-secondary">account_balance_wallet</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Total Nominal Menunggu</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format($stats['nominal_menunggu'], 0, ',', '.') }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Total nominal diajukan Owner</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-container-margin card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gold-accent/10 flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-gold-accent">task_alt</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Total Disetujui</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp {{ number_format($stats['total_semua'], 0, ',', '.') }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Akumulasi pencairan diproses / dibayar</p>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Pengajuan Pencairan</h2>
            <div class="flex flex-wrap gap-2">
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('superadmin.permintaan-penarikan', $key === 'semua' ? [] : ['status' => $key]) }}"
                        class="px-3 py-1.5 font-label-sm text-[11px] uppercase tracking-wider rounded-lg transition-colors {{ $activeStatus === $key
                            ? 'bg-secondary-container/10 text-secondary border border-secondary'
                            : 'border border-muted-border bg-surface text-on-surface hover:bg-surface-container-low' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left border-collapse premium-table">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low">
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Toko / Pemilik</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Detail Pengajuan</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Info Bank</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Status</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase text-left">Dibayar</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-muted-border font-body-md text-sm">
                        @forelse ($withdrawals as $w)
                            @php
                                $badge = $badgeMap[$w->status];
                                $initial = strtoupper(substr(collect(preg_split('/\s+/', trim($w->store->nama_toko ?? '')))->map(fn ($k) => mb_substr($k, 0, 1))->implode(''), 0, 2));
                            @endphp
                            <tr class="hover:bg-surface-container-low transition-colors group"
                                data-id="{{ $w->withdrawal_id }}"
                                data-nama="{{ $w->store->nama_toko }}"
                                data-jumlah="{{ number_format((float) $w->jumlah, 0, ',', '.') }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                                        <div>
                                            <p class="font-title-md text-title-md text-primary">{{ $w->store->nama_toko }}</p>
                                            <p class="text-on-surface-variant">{{ $w->store->owner->nama_lengkap ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $w->jumlah, 0, ',', '.') }}</p>
                                    <p class="text-on-surface-variant">{{ $w->diajukan_pada?->translatedFormat('d M Y') }}</p>
                                    @if ($w->status === \App\Models\Withdrawal::STATUS_DITOLAK && $w->alasan_penolakan)
                                        <p class="text-xs text-error mt-1 max-w-[240px]" title="{{ $w->alasan_penolakan }}">Alasan: {{ \Illuminate\Support\Str::limit($w->alasan_penolakan, 60) }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-primary">{{ $w->bankAccount?->bank?->nama_bank ?? '-' }}</p>
                                    <p class="text-on-surface-variant">**** **** {{ substr($w->bankAccount?->nomor_rekening ?? '', -4) }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full font-label-sm text-xs uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    @if ($w->ditinjau_pada)<p class="text-[11px] text-on-surface-variant mt-1">Ditinjau {{ $w->ditinjau_pada->translatedFormat('d M Y') }}</p>@endif
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant text-xs">{{ $w->dibayar_pada ? \Carbon\Carbon::parse($w->dibayar_pada)->locale('id')->diffForHumans() : '-' }}</td>
                                <td class="py-4 px-6 text-right">
                                    @if ($w->status === \App\Models\Withdrawal::STATUS_PENDING)
                                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                            <button type="button" onclick="openRejectDialog(this.closest('tr'))" title="Tolak"
                                                class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors">
                                                <span class="material-symbols-outlined text-sm">close</span>
                                            </button>
                                            <button type="button" onclick="openApproveDialog(this.closest('tr'))" title="Setujui"
                                                class="w-8 h-8 flex items-center justify-center bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity">
                                                <span class="material-symbols-outlined text-sm">check</span>
                                            </button>
                                        </div>
                                    @elseif ($w->status === \App\Models\Withdrawal::STATUS_DISETUJUI)
                                        <button type="button" onclick="openPaidDialog(this.closest('tr'))"
                                            class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">Tandai Dibayar</button>
                                    @else
                                        <span class="text-on-surface-variant text-xs uppercase">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Tidak ada pengajuan pada status ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="paid-dialog">
    <form method="POST" action="" id="paid-form" onsubmit="hideDialog('paid-dialog')">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-section-gap max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-secondary-container/30 border border-secondary/25 flex items-center justify-center mx-auto mb-gutter">
                <span class="material-symbols-outlined text-secondary text-[28px]">local_atm</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Tandai Sudah Dibayar</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-2 text-center">Konfirmasikan bahwa dana sebesar <span id="paid-nominal" class="font-title-md text-gold-accent">-</span> untuk <span id="paid-toko" class="font-bold text-on-surface">-</span> telah dikirim ke rekening tujuan.</p>
            <p class="font-body-md text-sm text-on-surface-variant mb-8 text-center">Saldo tertahan toko akan dilepas dan transaksi tercatat di wallet.</p>
            <div class="flex justify-end gap-4">
                <button type="button" class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                    onclick="hideDialog('paid-dialog')">Batal</button>
                <button type="submit" class="bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity btn-premium">Ya, Sudah Dibayar</button>
            </div>
        </div>
    </form>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="approve-dialog">
    <form method="POST" action="" id="approve-form" onsubmit="hideDialog('approve-dialog')">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-section-gap max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-gutter">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">task_alt</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Konfirmasi Pencairan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-8 text-center">Anda akan menyetujui pencairan sebesar <span id="approve-nominal" class="font-title-md text-gold-accent">-</span> ke <span id="approve-toko" class="font-bold text-on-surface">-</span>. Saldo toko akan dikunci untuk proses pembayaran.</p>
            <div class="flex justify-end gap-4">
                <button type="button" class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                    onclick="hideDialog('approve-dialog')">Batal</button>
                <button type="submit" class="bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Konfirmasi Persetujuan</button>
            </div>
        </div>
    </form>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="reject-dialog">
    <form method="POST" action="" id="reject-form" onsubmit="hideDialog('reject-dialog')" class="w-full max-w-md">
        @csrf
        <div class="bg-surface-container-lowest border border-error/25 p-section-gap max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-gutter">
                <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-error mb-4 text-center">Tolak Pencairan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-4 text-center">Anda yakin ingin menolak pengajuan dari <span id="reject-toko" class="font-bold text-on-surface">-</span>?</p>
            <div class="mb-8">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Alasan Penolakan</label>
                <textarea name="alasan" required minlength="10" maxlength="1000"
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24"
                    placeholder="Tulis alasan... (minimal 10 karakter)"></textarea>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                    onclick="hideDialog('reject-dialog')">Batal</button>
                <button type="submit" class="bg-error text-on-error px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Tolak Pengajuan</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const withdrawalUrls = {
        setujui: (id) => '{{ route('superadmin.permintaan-penarikan.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.permintaan-penarikan.tolak', ':id:') }}'.replace(':id:', id),
        dibayar: (id) => '{{ route('superadmin.permintaan-penarikan.tandai-dibayar', ':id:') }}'.replace(':id:', id)
    };

    function openPaidDialog(row) {
        document.getElementById('paid-toko').textContent = row.dataset.nama;
        document.getElementById('paid-nominal').textContent = 'Rp ' + row.dataset.jumlah;
        document.getElementById('paid-form').action = withdrawalUrls.dibayar(row.dataset.id);
        showDialog('paid-dialog');
    }

    function openApproveDialog(row) {
        document.getElementById('approve-toko').textContent = row.dataset.nama;
        document.getElementById('approve-nominal').textContent = 'Rp ' + row.dataset.jumlah;
        document.getElementById('approve-form').action = withdrawalUrls.setujui(row.dataset.id);
        showDialog('approve-dialog');
    }

    function openRejectDialog(row) {
        document.getElementById('reject-toko').textContent = row.dataset.nama;
        document.getElementById('reject-form').action = withdrawalUrls.tolak(row.dataset.id);
        document.querySelector('#reject-form textarea').value = '';
        showDialog('reject-dialog');
    }

    function showDialog(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function hideDialog(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { hideDialog('approve-dialog'); hideDialog('reject-dialog'); hideDialog('paid-dialog'); }
    });
</script>
@endpush
