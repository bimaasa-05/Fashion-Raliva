@extends('layouts.superadmin')

@section('title', 'Saldo Toko')

@section('header-title', 'Saldo Toko')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau saldo dan mutasi keuangan seluruh toko di platform.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Saldo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Saldo Tersedia</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format($totalTersedia, 0, ',', '.') }}</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Saldo Tertahan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">lock</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp {{ number_format($totalTertahan, 0, ',', '.') }}</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Jumlah Toko</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">storefront</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $jumlahToko }}</span>
            </div>
        </div>
    </section>

    <section data-wallet-scope>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Saldo per Toko</h2>
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="wallet-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama toko..." />
                <button type="button" id="wallet-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                <span id="wallet-result-count">{{ $jumlahToko }}</span> toko
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @forelse($wallets as $wallet)
                <div data-table-row data-search="{{ strtolower($wallet->store->nama_toko ?? '') }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                    <div class="flex items-center justify-between">
                        <span class="font-title-md text-title-md text-on-surface">{{ $wallet->store->nama_toko ?? '-' }}</span>
                        <span class="material-symbols-outlined text-gold-accent">account_balance_wallet</span>
                    </div>
                    <div class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format((float)$wallet->saldo_tersedia, 0, ',', '.') }}</div>
                    <div class="pt-4 border-t border-muted-border flex justify-between font-body-md text-sm">
                        <span class="text-on-surface-variant">Menunggu Cair</span>
                        <span class="text-on-surface font-bold">Rp {{ number_format((float)$wallet->saldo_tertahan, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-8">
                    Belum ada data saldo toko.
                </div>
            @endforelse
        </div>
        @if ($wallets->hasPages())
            <div class="mt-6 flex justify-center">{{ $wallets->links() }}</div>
        @endif
        <p id="wallet-empty-search" class="hidden text-center text-on-surface-variant font-body-md text-sm py-8">Tidak ada toko yang cocok.</p>
    </section>

    <section data-mutasi-scope class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Mutasi Terbaru</h2>

        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 space-y-4">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Mutasi</span>
            </div>
            <div id="mutasi-chip-group" class="flex flex-wrap gap-2">
                <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</button>
                <button type="button" data-chip="penjualan_masuk" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Penjualan</button>
                <button type="button" data-chip="komisi_masuk" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Komisi</button>
                <button type="button" data-chip="penyesuaian" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Penyesuaian</button>
                <button type="button" data-chip="refund_keluar" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Refund</button>
                <button type="button" data-chip="withdrawal" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Withdrawal</button>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="mutasi-search" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari toko atau keterangan..." />
                    <button type="button" id="mutasi-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="mutasi-result-count">{{ $transactions->count() }}</span> mutasi
                </p>
            </div>
        </div>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg hidden md:block card-premium">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No.</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-left">Jenis Transaksi</th>
                        <th class="p-4 text-left">Keterangan</th>
                        <th class="p-4 text-right">Saldo Awal</th>
                        <th class="p-4 text-right">Nominal</th>
                        <th class="p-4 text-right">Saldo Akhir</th>
                        <th class="p-4 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($transactions as $tx)
                        @php $rowNumber = $loop->iteration; @endphp
                        @php
                            $isPositive = in_array($tx->jenis_transaksi, ['penjualan_masuk', 'komisi_masuk', 'penyesuaian']);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-status="{{ $tx->jenis_transaksi }}" data-search="{{ strtolower(($tx->wallet->store->nama_toko ?? '').' '.$tx->jenis_transaksi.' '.($tx->keterangan ?? '')) }}">
                            <td class="p-4 text-center text-on-surface-variant font-mono row-num">{{ $rowNumber }}</td>
                            <td class="p-4 text-on-surface">{{ $tx->wallet->store->nama_toko ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant text-xs uppercase">{{ str_replace('_', ' ', $tx->jenis_transaksi) }}</td>
                            <td class="p-4 text-on-surface">{{ $tx->keterangan ?? '-' }}</td>
                            <td class="p-4 text-right text-on-surface-variant">Rp {{ number_format((float)$tx->saldo_sebelum, 0, ',', '.') }}</td>
                            <td class="p-4 text-right font-bold {{ $isPositive ? 'text-secondary' : 'text-error' }}">
                                {{ $isPositive ? '+' : '-' }} Rp {{ number_format(abs((float)$tx->jumlah), 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right text-on-surface-variant">Rp {{ number_format((float)$tx->saldo_sesudah, 0, ',', '.') }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->locale('id')->diffForHumans() : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-on-surface-variant">Belum ada mutasi tercatat.</td>
                        </tr>
                    @endforelse
                    <tr id="mutasi-empty-search" class="hidden">
                        <td colspan="8" class="p-8 text-center text-on-surface-variant">Tidak ada mutasi yang cocok.</td>
                    </tr>
            </tbody>
        </table>
        </div>

        <!-- Mobile: kartu mutasi terbaru -->
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse($transactions as $tx)
                @php
                    $isPositive = in_array($tx->jenis_transaksi, ['penjualan_masuk', 'komisi_masuk', 'penyesuaian']);
                @endphp
                <article data-table-row data-status="{{ $tx->jenis_transaksi }}" data-search="{{ strtolower(($tx->wallet->store->nama_toko ?? '').' '.$tx->jenis_transaksi.' '.($tx->keterangan ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-title-md text-title-md text-on-surface truncate">{{ $tx->wallet->store->nama_toko ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs uppercase mt-0.5">{{ str_replace('_', ' ', $tx->jenis_transaksi) }}</p>
                        </div>
                        <p class="font-bold whitespace-nowrap {{ $isPositive ? 'text-secondary' : 'text-error' }}">{{ $isPositive ? '+' : '-' }} Rp {{ number_format(abs((float)$tx->jumlah), 0, ',', '.') }}</p>
                    </div>
                    <dl class="space-y-2 font-body-md text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Keterangan</dt>
                            <dd class="text-on-surface text-right">{{ $tx->keterangan ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Saldo Sebelum</dt>
                            <dd class="text-on-surface-variant text-right">Rp {{ number_format((float)$tx->saldo_sebelum, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Saldo Sesudah</dt>
                            <dd class="text-on-surface text-right">Rp {{ number_format((float)$tx->saldo_sesudah, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Waktu</dt>
                            <dd class="text-on-surface text-right">{{ $tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->locale('id')->diffForHumans() : '-' }}</dd>
                        </div>
                    </dl>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada mutasi tercatat.</p>
            @endforelse
            <p id="mutasi-empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada mutasi yang cocok.</p>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
    const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

    /* ---- Scope: Saldo per Toko ---- */
    const walletScope = document.querySelector('[data-wallet-scope]');
    if (walletScope) {
        const rows = Array.from(walletScope.querySelectorAll('[data-table-row]'));
        const searchInput = document.getElementById('wallet-search');
        const clearBtn = document.getElementById('wallet-clear-search');
        const countEl = document.getElementById('wallet-result-count');
        const emptySearch = document.getElementById('wallet-empty-search');

        function applyWallet() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;
            rows.forEach((row) => {
                const match = !term || (row.getAttribute('data-search') || '').includes(term);
                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
        }

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyWallet, 200);
        });
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyWallet();
        });
        applyWallet();
    }

    /* ---- Scope: Mutasi Terbaru ---- */
    const mutasiScope = document.querySelector('[data-mutasi-scope]');
    if (mutasiScope) {
        const rows = Array.from(mutasiScope.querySelectorAll('tr[data-table-row], article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#mutasi-chip-group .chip-btn');
        const searchInput = document.getElementById('mutasi-search');
        const clearBtn = document.getElementById('mutasi-clear-search');
        const countEl = document.getElementById('mutasi-result-count');
        const emptySearch = document.getElementById('mutasi-empty-search');
        const emptySearchMobile = document.getElementById('mutasi-empty-search-mobile');

        let activeChip = 'semua';

        function applyMutasi() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const matchChip = activeChip === 'semua' || row.getAttribute('data-status') === activeChip;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchChip && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) {
                    visible++;
                    const num = row.querySelector('.row-num');
                    if (num) num.textContent = visible;
                }
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
            emptySearchMobile.classList.toggle('hidden', visible > 0 || rows.length === 0);
        }

        chipBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                chipBtns.forEach((b) => {
                    b.classList.remove(...activeClasses);
                    b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
                });
                btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
                btn.classList.add(...activeClasses);
                activeChip = btn.getAttribute('data-chip');
                applyMutasi();
            });
        });

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyMutasi, 200);
        });
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyMutasi();
        });
        applyMutasi();
    }
});
</script>
@endpush
