@extends('layouts.superadmin')

@section('title', 'Data Pembayaran')

@section('header-title', 'Data Pembayaran')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Monitor pembayaran platform dan status verifikasi untuk audit transaksi.')

@section('content')
<div class="space-y-section-gap">
    <!-- Ringkasan -->
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Pembayaran</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Transaksi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['semua'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">semua status</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">{{ $stats['menunggu_verifikasi'] + $stats['pending'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">perlu ditinjau</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Berhasil</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">check_circle</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $stats['terverifikasi'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">terverifikasi</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Gagal / Dibatalkan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">cancel</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error">{{ $stats['ditolak'] + $stats['kadaluarsa'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">ditolak / kadaluarsa</span>
            </div>
        </div>
    </section>

    <!-- Tabel -->
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Transaksi Pembayaran</h2>
            <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filter
                <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
            </button>
        </div>

        <!-- Filters -->
        <div data-filter-panel class="hidden md:block mb-6">
            <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
                </div>
                <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
                <div id="chip-group" class="flex flex-wrap gap-2">
                    <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $stats['semua'] }})</button>
                    <button type="button" data-chip="terverifikasi" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Terverifikasi ({{ $stats['terverifikasi'] }})</button>
                    <button type="button" data-chip="menunggu_verifikasi" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu ({{ $stats['menunggu_verifikasi'] }})</button>
                    <button type="button" data-chip="pending" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Pending ({{ $stats['pending'] }})</button>
                    <button type="button" data-chip="ditolak" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditolak ({{ $stats['ditolak'] }})</button>
                    <button type="button" data-chip="kadaluarsa" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kadaluarsa ({{ $stats['kadaluarsa'] }})</button>
                </div>
            </div>

            <!-- Search + Result Count -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="pembayaran-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari ID pembayaran, nama pelanggan, toko, atau metode..." />
                    <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="result-count">{{ $payments->count() }}</span> transaksi
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="p-6 w-12 text-center">No.</th>
                        <th class="p-6">ID Pembayaran</th>
                        <th class="p-6">Metode</th>
                        <th class="p-6">Pelanggan</th>
                        <th class="p-6">Toko</th>
                        <th class="p-6 text-right">Jumlah</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6">Tanggal</th>
                        <th class="p-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($payments as $pay)
                        @php
                            $statusMap = [
                                'pending' => ['Pending', 'bg-surface-container-high text-on-surface'],
                                'menunggu_verifikasi' => ['Menunggu Verifikasi', 'bg-surface-container-high text-on-surface'],
                                'terverifikasi' => ['Terverifikasi', 'bg-success/10 text-success'],
                                'ditolak' => ['Ditolak', 'bg-error/10 text-error'],
                                'kadaluarsa' => ['Kadaluarsa', 'bg-error/10 text-error'],
                            ];
                            $st = $statusMap[$pay->status] ?? [ucfirst($pay->status), 'bg-surface-container-high text-on-surface'];
                            $cust = $pay->checkout?->user;
                            $tanggal = $pay->created_at ? Illuminate\Support\Carbon::parse($pay->created_at)->locale('id')->translatedFormat('d M Y • H.i') : '-';
                        @endphp
                        <tr data-table-row data-status="{{ $pay->status }}" data-search="{{ strtolower('PAY-'.$pay->payment_id.' '.($cust->nama_lengkap ?? '').' '.($cust->email ?? '').' '.$pay->nama_toko.' '.($pay->paymentMethod->nama_metode ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6 font-mono">PAY-{{ $pay->payment_id }}</td>
                            <td class="p-6 text-on-surface">{{ $pay->paymentMethod->nama_metode ?? '-' }}</td>
                            <td class="p-6">
                                <p class="text-on-surface">{{ $cust->nama_lengkap ?? '-' }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $cust->email ?? '' }}</p>
                            </td>
                            <td class="p-6 text-on-surface">{{ $pay->nama_toko }}</td>
                            <td class="p-6 text-right font-bold text-gold-accent">Rp {{ number_format((float) $pay->jumlah, 0, ',', '.') }}</td>
                            <td class="p-6 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $st[1] }} text-xs uppercase">{{ $st[0] }}</span>
                            </td>
                            <td class="p-6 text-on-surface-variant text-xs">{{ $tanggal }}</td>
                            <td class="p-6 text-right">
                                <button type="button" data-detail-open="detail-pembayaran" data-d-nomor="PAY-{{ $pay->payment_id }}" data-d-metode="{{ $pay->paymentMethod->nama_metode ?? '-' }}" data-d-pelanggan="{{ $cust->nama_lengkap ?? '-' }} ({{ $cust->email ?? '' }})" data-d-toko="{{ $pay->nama_toko }}" data-d-jumlah="Rp {{ number_format((float) $pay->jumlah, 0, ',', '.') }}" data-d-status="{{ $st[0] }}" data-d-tanggal="{{ $tanggal }}" class="px-3 py-1 bg-surface-container-lowest text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-on-surface-variant">Belum ada transaksi pembayaran tercatat.</td>
                        </tr>
                    @endforelse
                    <tr id="empty-search" class="hidden">
                        <td colspan="9" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada transaksi yang cocok.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile: kartu per transaksi -->
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($payments as $pay)
                @php
                    $statusMap = [
                        'pending' => ['Pending', 'bg-surface-container-high text-on-surface'],
                        'menunggu_verifikasi' => ['Menunggu Verifikasi', 'bg-surface-container-high text-on-surface'],
                        'terverifikasi' => ['Terverifikasi', 'bg-success/10 text-success'],
                        'ditolak' => ['Ditolak', 'bg-error/10 text-error'],
                        'kadaluarsa' => ['Kadaluarsa', 'bg-error/10 text-error'],
                    ];
                    $st = $statusMap[$pay->status] ?? [ucfirst($pay->status), 'bg-surface-container-high text-on-surface'];
                    $cust = $pay->checkout?->user;
                    $tanggal = $pay->created_at ? Illuminate\Support\Carbon::parse($pay->created_at)->locale('id')->translatedFormat('d M Y • H.i') : '-';
                @endphp
                <article data-table-row data-status="{{ $pay->status }}" data-search="{{ strtolower('PAY-'.$pay->payment_id.' '.($cust->nama_lengkap ?? '').' '.($cust->email ?? '').' '.$pay->nama_toko.' '.($pay->paymentMethod->nama_metode ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-mono font-bold text-on-surface leading-tight">PAY-{{ $pay->payment_id }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $pay->paymentMethod->nama_metode ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded {{ $st[1] }} text-[10px] font-bold uppercase shrink-0">{{ $st[0] }}</span>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Pelanggan</dt>
                            <dd class="text-on-surface text-right">{{ $cust->nama_lengkap ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Toko</dt>
                            <dd class="text-on-surface text-right">{{ $pay->nama_toko }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Jumlah</dt>
                            <dd class="font-bold text-gold-accent text-right">Rp {{ number_format((float) $pay->jumlah, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Tanggal</dt>
                            <dd class="text-on-surface text-right">{{ $tanggal }}</dd>
                        </div>
                    </dl>

                    <button type="button" data-detail-open="detail-pembayaran" data-d-nomor="PAY-{{ $pay->payment_id }}" data-d-metode="{{ $pay->paymentMethod->nama_metode ?? '-' }}" data-d-pelanggan="{{ $cust->nama_lengkap ?? '-' }} ({{ $cust->email ?? '' }})" data-d-toko="{{ $pay->nama_toko }}" data-d-jumlah="Rp {{ number_format((float) $pay->jumlah, 0, ',', '.') }}" data-d-status="{{ $st[0] }}" data-d-tanggal="{{ $tanggal }}" class="w-full min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>Detail
                    </button>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada transaksi pembayaran tercatat.</p>
            @endforelse
            <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada transaksi yang cocok.</p>
        </div>
    </section>
</div>

<!-- Modal Detail -->
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
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Tanggal</dt><dd class="text-on-surface text-right"><span data-slot="tanggal"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const scope = document.querySelector('[data-table-scope]');
    if (!scope) return;

    const rows = Array.from(scope.querySelectorAll('tr[data-table-row]'));
    const cards = Array.from(scope.querySelectorAll('article[data-table-row]'));
    const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
    const searchInput = document.getElementById('pembayaran-search');
    const clearBtn = document.getElementById('clear-search');
    const countEl = document.getElementById('result-count');
    const emptySearch = document.getElementById('empty-search');
    const emptySearchMobile = document.getElementById('empty-search-mobile');

    const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
    const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

    let activeStatus = 'semua';

    function applyFilter() {
        const term = searchInput.value.trim().toLowerCase();
        let visible = 0;

        const each = (el) => {
            const matchStatus = activeStatus === 'semua' || el.getAttribute('data-status') === activeStatus;
            const matchSearch = !term || (el.getAttribute('data-search') || '').includes(term);
            const show = matchStatus && matchSearch;
            el.classList.toggle('hidden', !show);
            if (show) visible++;
            return show;
        };

        rows.forEach((row) => {
            if (each(row)) {
                const num = row.querySelector('.row-num');
                if (num) num.textContent = visible;
            }
        });
        cards.forEach(each);

        countEl.textContent = visible;
        emptySearch.classList.toggle('hidden', visible > 0);
        emptySearchMobile?.classList.toggle('hidden', visible > 0);

        if (rows.length === 0) {
            emptySearch.classList.add('hidden');
        }
    }

    chipBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            chipBtns.forEach((b) => {
                b.classList.remove(...activeClasses);
                b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
            });
            btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
            btn.classList.add(...activeClasses);
            activeStatus = btn.getAttribute('data-chip');
            applyFilter();
        });
    });

    let debounce;
    searchInput.addEventListener('input', () => {
        clearBtn.classList.toggle('opacity-0', !searchInput.value);
        clearTimeout(debounce);
        debounce = setTimeout(applyFilter, 200);
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('opacity-0');
        applyFilter();
    });

    applyFilter();
});
</script>
@endpush
