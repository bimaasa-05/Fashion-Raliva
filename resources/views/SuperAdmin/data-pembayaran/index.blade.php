@extends('layouts.superadmin')

@section('title', 'Data Pembayaran')

@section('header-title', 'Data Pembayaran')
@section('header-badge', 'Kelola & Lihat')
@section('header-subtitle', 'Monitor pembayaran platform dan status verifikasi untuk audit transaksi.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Pembayaran</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Transaksi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['total'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">semua status</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">{{ $stats[App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">perlu ditinjau</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Berhasil</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">check_circle</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats[App\Models\Payment::STATUS_TERVERIFIKASI] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">terverifikasi</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Gagal / Dibatalkan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">cancel</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error">{{ $stats[App\Models\Payment::STATUS_DITOLAK] + $stats[App\Models\Payment::STATUS_KADALUARSA] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">ditolak / kadaluarsa</span>
            </div>
        </div>
    </section>

    <section data-table-scope class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Transaksi Pembayaran</h2>

        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
            </div>
            <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
            <div data-chip-group data-chip-key="status" class="flex flex-wrap gap-2">
                <button type="button" data-chip="semua" class="px-4 py-2 rounded-lg bg-deep-onyx text-on-primary border border-deep-onyx font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</button>
                <button type="button" data-chip="terverifikasi" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Terverifikasi</button>
                <button type="button" data-chip="menunggu" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu</button>
                <button type="button" data-chip="gagal" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Gagal</button>
            </div>
        </div>

        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Pembayaran</th>
                        <th class="p-4 text-left">Metode</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-right">Jumlah</th>
                        <th class="p-4 text-center">Status Verifikasi</th>
                        <th class="p-4 text-left">Batas Waktu</th>
                        <th class="p-4 text-left">Dibayar</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($payments as $pay)
                        @php
                            $statusMap = [
                                'pending' => ['Menunggu', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                                'menunggu_verifikasi' => ['Menunggu', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                                'terverifikasi' => ['Terverifikasi', 'bg-secondary-container/20 text-secondary border-secondary/20'],
                                'ditolak' => ['Gagal', 'bg-error/10 text-error border-error/20'],
                                'kadaluarsa' => ['Kadaluarsa', 'bg-error/10 text-error border-error/20'],
                            ];
                            $st = $statusMap[$pay->status] ?? [ucfirst($pay->status), 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                            $cust = $pay->checkout?->user;
                            $tanggal = $pay->created_at ? Carbon::parse($pay->created_at)->locale('id')->translatedFormat('d M Y • H.i') : '-';
                        @endphp
                        <tr data-table-row data-status="{{ $pay->status }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 font-mono text-on-surface">PAY-{{ $pay->payment_id }}</td>
                            <td class="p-4 text-on-surface">{{ $pay->paymentMethod->nama_metode ?? '-' }}</td>
                            <td class="p-4">
                                <p class="text-on-surface">{{ $cust->nama_lengkap ?? '-' }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $cust->email ?? '' }}</p>
                            </td>
                            <td class="p-4 text-on-surface">{{ $pay->nama_toko }}</td>
                            <td class="p-4 text-right font-bold text-gold-accent">Rp {{ number_format((float) $pay->jumlah, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase">{{ $st[0] }}</span>
                            </td>
                            <td class="p-4 text-on-surface-variant">{{ $pay->batas_waktu ? \Carbon\Carbon::parse($pay->batas_waktu)->locale('id')->translatedFormat('d M Y H:i') : '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $pay->dibayar_pada ? \Carbon\Carbon::parse($pay->dibayar_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $tanggal }}</td>
                            <td class="p-4 text-right"><button type="button" data-detail-open="detail-pembayaran" data-d-nomor="PAY-{{ $pay->payment_id }}" data-d-metode="{{ $pay->paymentMethod->nama_metode ?? '-' }}" data-d-pelanggan="{{ $cust->nama_lengkap ?? '-' }} ({{ $cust->email ?? '' }})" data-d-toko="{{ $pay->nama_toko }}" data-d-jumlah="Rp {{ number_format((float) $pay->jumlah, 0, ',', '.') }}" data-d-status="{{ $st[0] }}" data-d-batas="{{ $pay->batas_waktu ? \Carbon\Carbon::parse($pay->batas_waktu)->locale('id')->translatedFormat('d M Y H:i') : '-' }}" data-d-dibayar="{{ $pay->dibayar_pada ? \Carbon\Carbon::parse($pay->dibayar_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}" data-d-tanggal="{{ $tanggal }}" class="text-gold-accent hover:underline uppercase font-label-sm text-[10px]">Detail</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-on-surface-variant">Belum ada transaksi pembayaran tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<div id="detail-pembayaran" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pembayaran</h3>
                <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Metode</dt><dd class="text-on-surface text-right"><span data-slot="metode"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface text-right"><span data-slot="toko"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Jumlah</dt><dd class="font-bold text-gold-accent text-right"><span data-slot="jumlah"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Status Verifikasi</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Batas Waktu</dt><dd class="text-on-surface text-right"><span data-slot="batas"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Dibayar</dt><dd class="text-on-surface text-right"><span data-slot="dibayar"></span></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Tanggal</dt><dd class="text-on-surface text-right"><span data-slot="tanggal"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>
@endsection
