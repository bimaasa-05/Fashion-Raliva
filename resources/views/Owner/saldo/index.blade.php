@extends('layouts.owner')

@php
    $fmt = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $isMasuk = fn ($jenis) => in_array($jenis, [
        \App\Models\WalletTransaction::JENIS_PENJUALAN_MASUK,
        \App\Models\WalletTransaction::JENIS_KOMISI_MASUK,
    ]);
@endphp

@section('title', 'Keuangan')
@section('header-title', 'Keuangan')
@section('header-badge', 'Keuangan')
@section('header-subtitle', 'Kelola saldo, pencairan dana, dan pengembalian dana toko Anda dalam satu tempat.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-12 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if (! $wallet)
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-12 text-center card-premium">
            <span class="material-symbols-outlined text-[48px] text-on-surface-variant">account_balance_wallet</span>
            <p class="mt-4 font-title-md text-title-md text-on-surface">Belum ada dompet toko</p>
            <p class="text-on-surface-variant font-body-md text-sm mt-1">Data keuangan akan muncul setelah ada transaksi pada toko ini.</p>
        </div>
    @else
    {{-- Tab Switcher --}}
    <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto max-w-full">
        <button type="button" data-saldo-tab="ringkasan" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Ringkasan</button>
        <button type="button" data-saldo-tab="pencairan" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pencairan Dana</button>
        <button type="button" data-saldo-tab="pengembalian" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pengembalian Dana</button>
    </div>

    {{-- ============ PANEL: RINGKASAN ============ --}}
    <div data-saldo-panel="ringkasan" class="space-y-section-gap">
        {{-- Kartu Saldo --}}
        <section data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
            <div data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 relative overflow-hidden flex flex-col">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
                <p class="raliva-label text-gold-accent relative">Saldo Tersedia</p>
                <p class="raliva-figure text-[34px] md:text-[42px] mt-4 relative">{{ $fmt($wallet->saldo_tersedia) }}</p>
                <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                    <p class="font-body-md text-xs text-inverse-on-surface/60">Siap dicairkan kapan saja</p>
                    <a href="#pencairan" class="py-2.5 px-5 bg-gold-accent text-[#111] text-xs font-semibold rounded btn-premium shrink-0">Cairkan</a>
                </div>
            </div>

            <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <p class="raliva-label relative">Saldo Tertunda</p>
                <p class="raliva-figure text-[26px] mt-4 text-on-surface relative">{{ $fmt($wallet->saldo_tertahan) }}</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-auto pt-6 relative">Dana dilepas otomatis menjadi saldo tersedia H+2 setelah pesanan selesai.</p>
            </div>

            <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">savings</span>
                <p class="raliva-label relative">Total Dicairkan</p>
                <p class="raliva-figure text-[26px] mt-4 text-secondary relative">{{ $fmt($totalDicairkan) }}</p>
                <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                    <p class="font-body-md text-xs text-on-surface-variant">{{ $withdrawals->count() }} pencairan tercatat</p>
                    <a href="#pencairan" class="py-2.5 px-5 border border-muted-border text-xs font-semibold rounded-lg hover:border-gold-accent transition-colors shrink-0">Riwayat</a>
                </div>
            </div>
        </section>

        {{-- Grafik Tren Saldo + Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
            <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Tren Saldo — 6 Bulan Terakhir</h2>
                <div id="chart-wrap" class="relative h-64 md:h-72"><canvas id="saldo-chart"></canvas></div>
            </section>

            <section data-reveal-group class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Bulan Ini</h2>
                <ul class="space-y-4 font-body-md text-sm">
                    <li data-reveal class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                        <span class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] text-secondary fill">add_circle</span>Pemasukan
                        </span>
                        <span class="text-secondary font-bold whitespace-nowrap">{{ $fmt($summary['pemasukan']) }}</span>
                    </li>
                    <li data-reveal class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                        <span class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] text-error fill">remove_circle</span>Pengeluaran
                        </span>
                        <span class="text-error font-bold whitespace-nowrap">− {{ $fmt($summary['pengeluaran']) }}</span>
                    </li>
                </ul>
                <div class="mt-6 pt-5 border-t border-muted-border flex items-center justify-between">
                    <span class="font-title-md text-sm text-on-surface">Perubahan Bersih</span>
                    <span class="font-title-md text-base {{ $summary['bersih'] >= 0 ? 'text-secondary' : 'text-error' }}">
                        {{ $summary['bersih'] >= 0 ? '+' : '−' }} {{ $fmt(abs($summary['bersih'])) }}
                    </span>
                </div>
            </section>
        </div>

        {{-- Riwayat Perubahan Saldo --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Riwayat Perubahan Saldo</h2>
            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[820px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Waktu</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Keterangan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Perubahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mutations as $row)
                            @php $masuk = $isMasuk($row->jenis_transaksi); @endphp
                            <tr class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3.5 px-4 text-on-surface">{{ $row->keterangan }}</td>
                                <td class="py-3.5 px-4 text-right font-bold whitespace-nowrap {{ $masuk ? 'text-secondary' : 'text-error' }}">
                                    {{ $masuk ? '+' : '−' }} {{ $fmt(abs($row->jumlah)) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">{{ $fmt($row->saldo_sesudah) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada mutasi saldo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
                <p class="text-xs text-on-surface-variant">Menampilkan {{ $mutations->count() }} dari {{ $mutations->total() }} mutasi</p>
                {{ $mutations->links() }}
            </div>
        </section>
    </div>

    {{-- ============ PANEL: PENCAIRAN DANA ============ --}}
    <div data-saldo-panel="pencairan" class="hidden space-y-section-gap">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap items-start">
            {{-- Form Pengajuan --}}
            <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Ajukan Pencairan</h2>

                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6 flex items-center justify-between">
                    <div>
                        <p class="raliva-label">Saldo Tersedia</p>
                        <p class="font-title-md text-title-md text-secondary mt-1">{{ $fmt($wallet->saldo_tersedia) }}</p>
                    </div>
                    <span class="material-symbols-outlined fill text-[28px] text-gold-accent">account_balance_wallet</span>
                </div>

                <div class="border border-muted-border rounded-lg p-5 flex items-start gap-3 bg-surface-container-low">
                    <span class="material-symbols-outlined text-[22px] text-gold-accent mt-0.5 shrink-0">lock</span>
                    <div>
                        <p class="font-title-md text-sm text-on-surface">Pengajuan Pencairan Dinonaktifkan</p>
                        <p class="text-xs text-on-surface-variant mt-1">Halaman pencairan dana telah dipindahkan ke Super Admin. Saldo Anda tetap tercatat dan riwayat di bawah ini adalah data nyata.</p>
                    </div>
                </div>
            </section>

            {{-- Riwayat Pencairan --}}
            <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Riwayat Pencairan</h2>
                    <select data-table-filter="status-cair" class="raliva-select">
                        <option value="">Semua Status</option>
                        <option value="pending">Diproses</option>
                        <option value="dibayar">Dibayar</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div data-table-wrap class="overflow-x-auto">
                    <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                        <thead>
                            <tr class="border-b border-muted-border text-left">
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nominal</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Rekening</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($withdrawals as $row)
                                @php
                                    $label = match($row->status) {
                                        'dibayar' => ['Dibayar', 'dibayar'],
                                        'ditolak' => ['Ditolak', 'ditolak'],
                                        default => ['Diproses', 'pending'],
                                    };
                                @endphp
                                <tr data-table-row data-status-cair="{{ $label[1] }}" class="border-b border-muted-border last:border-0">
                                    <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">WD-{{ str_pad($row->withdrawal_id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row->diajukan_pada?->format('d M Y') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ $fmt($row->jumlah) }}</td>
                                    <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row->bankAccount?->bank?->nama_bank ?? 'Bank' }} {{ $row->bankAccount?->nomor_rekening }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if ($label[1] === 'dibayar')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="material-symbols-outlined fill text-[12px]">check_circle</span>{{ $label[0] }}</span>
                                        @elseif ($label[1] === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20" title="{{ $row->alasan_penolakan }}">{{ $label[0] }}</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30"><span class="material-symbols-outlined fill text-[12px]">schedule</span>{{ $label[0] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-8 text-center text-on-surface-variant">Belum ada pencairan dana.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
                    <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pencairan pada status ini.</p>
                </div>
            </section>
        </div>
    </div>

    {{-- ============ PANEL: PENGEMBALIAN DANA ============ --}}
    <div data-saldo-panel="pengembalian" class="hidden space-y-section-gap">
        <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kasus Berjalan</span>
                <span class="raliva-figure text-[26px] text-gold-accent">{{ $refunds->whereIn('status', ['requested', 'disetujui'])->count() }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">assignment_return</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $refunds->where('status', 'selesai')->count() }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nilai Refund</span>
                <span class="raliva-figure text-[26px] text-error">{{ $fmt($refunds->where('status', 'selesai')->sum('jumlah')) }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">money_off</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditolak</span>
                <span class="raliva-figure text-[26px] text-on-surface-variant">{{ $refunds->where('status', 'ditolak')->count() }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">block</span>
            </div>
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Daftar Kasus Pengembalian Dana</h2>
                <select data-table-filter="status-refund" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="requested">Diminta</option>
                    <option value="disetujui">Diproses</option>
                    <option value="selesai">Refund Selesai</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[940px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan / Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Customer</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Alasan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Nominal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($refunds as $row)
                            @php
                                $key = match($row->status) {
                                    'selesai' => 'selesai',
                                    'ditolak' => 'ditolak',
                                    'disetujui' => 'diproses',
                                    default => 'diminta',
                                };
                                $statusLabel = match($row->status) {
                                    'selesai' => 'Refund Selesai',
                                    'ditolak' => 'Ditolak',
                                    'disetujui' => 'Diproses',
                                    default => 'Diminta',
                                };
                            @endphp
                            <tr data-table-row data-status-refund="{{ $key }}" class="border-b border-muted-border last:border-0 align-top">
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-on-surface whitespace-nowrap">{{ $row->order?->nomor_order ?? ('#' . $row->order_id) }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $row->diajukan_pada?->format('d M Y') }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row->requester?->nama_lengkap ?? 'Customer' }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant max-w-[260px]">{{ $row->alasan }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">{{ $fmt($row->jumlah) }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($key === 'selesai')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $statusLabel }}</span>
                                    @elseif ($key === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $statusLabel }}</span>
                                    @elseif ($key === 'diproses')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $statusLabel }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $statusLabel }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-on-surface-variant">Belum ada kasus pengembalian dana.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada kasus refund pada status ini.</p>
            </div>

            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
                Keputusan akhir refund ditentukan Super Admin sesuai kebijakan platform. Nilai refund yang disetujui akan otomatis dipotong dari saldo toko.
            </p>
        </section>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ===== Tab Saldo: Ringkasan | Pencairan | Pengembalian ===== */
    const setSaldoTab = (name) => {
        document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
            const isActive = b.getAttribute('data-saldo-tab') === name;
            b.classList.toggle('bg-deep-onyx', isActive);
            b.classList.toggle('text-on-primary', isActive);
            b.classList.toggle('text-on-surface-variant', !isActive);
        });
        document.querySelectorAll('[data-saldo-panel]').forEach((p) => {
            p.classList.toggle('hidden', p.getAttribute('data-saldo-panel') !== name);
        });
    };

    document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
        b.addEventListener('click', () => {
            history.replaceState(null, '', '#' + b.getAttribute('data-saldo-tab'));
            setSaldoTab(b.getAttribute('data-saldo-tab'));
        });
    });

    const initSaldoFromHash = () => {
        const h = location.hash.replace('#', '');
        if (['pencairan', 'pengembalian'].includes(h)) setSaldoTab(h);
    };
    window.addEventListener('hashchange', initSaldoFromHash);

    window.ralivaOnReady(() => {
        initSaldoFromHash();
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#333333' : '#E9E8E7';
            const tickColor = isDark ? '#BAB8B8' : '#747878';
            const tooltipBg = isDark ? '#F0EEEE' : '#1b1c1c';
            const tooltipText = isDark ? '#111111' : '#ffffff';

            const chartLabels = @json($chart->pluck('label'));
            const chartData = @json($chart->pluck('saldo')->map(fn ($v) => (float) $v));

            const drawAnim = {
                x: { type: 'number', duration: 950, easing: 'easeOutQuart', from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0) },
                y: { type: 'number', duration: 950, easing: 'easeOutQuart' }
            };

            new Chart(document.getElementById('saldo-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [
                        { label: 'Saldo Akhir Bulan', data: chartData, borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.35, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: drawAnim,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, color: tickColor, font: { family: 'Manrope', size: 12 } } },
                        tooltip: {
                            backgroundColor: tooltipBg, titleColor: tooltipText, bodyColor: tooltipText,
                            titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 },
                            padding: 12, cornerRadius: 0,
                            callbacks: { label: (ctx) => ' Saldo: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 }, callback: (v) => (v / 1000000) + ' jt' } },
                        x: { grid: { display: false }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 } } }
                    }
                }
            });
        } catch (e) {}
    });

    document.querySelectorAll('[data-quick]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById('wd-nominal');
            if (input) input.value = btn.getAttribute('data-quick');
        });
    });
</script>
@endpush
