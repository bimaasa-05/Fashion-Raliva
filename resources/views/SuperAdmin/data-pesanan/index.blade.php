@extends('layouts.superadmin')

@section('title', 'Data Pesanan')

@section('header-title', 'Data Pesanan')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Monitor pesanan dari seluruh toko tanpa mengambil alih operasional')

@section('content')
<!-- Orders Management -->
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Data Pesanan</h2>

    <!-- Filters -->
    <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
        </div>
        <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
        <div id="chip-group" class="flex flex-wrap gap-2">
            <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua Pesanan</button>
            <button type="button" data-chip="pending_payment" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu</button>
            <button type="button" data-chip="dibayar" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibayar</button>
            <button type="button" data-chip="diproses" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diproses</button>
            <button type="button" data-chip="dikirim" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dikirim</button>
            <button type="button" data-chip="selesai" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Selesai</button>
            <button type="button" data-chip="dibatalkan" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibatalkan</button>
            <button type="button" data-chip="refund" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Refund</button>
        </div>
    </div>

    <!-- Search + Result Count -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input id="order-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nomor order, nama pelanggan, atau email..." />
            <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <p class="text-on-surface-variant font-body-md text-xs shrink-0">
            <span id="result-count">{{ $orders->count() }}</span> pesanan
        </p>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-full bg-surface-container-lowest rounded-lg overflow-hidden premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                    <th class="p-6 w-12 text-center">No.</th>
                    <th class="p-6">ID Pesanan</th>
                    <th class="p-6">Toko</th>
                    <th class="p-6">Pelanggan</th>
                    <th class="p-6">Total</th>
                    <th class="p-6">Status</th>
                    <th class="p-6">Waktu</th>
                    <th class="p-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    @php
                        $statusMap = [
                            'pending_payment' => ['Menunggu', 'bg-surface-container-high text-on-surface'],
                            'dibayar' => ['Dibayar', 'bg-info/10 text-info'],
                            'diproses' => ['Diproses', 'bg-surface-container-high text-on-surface'],
                            'dikirim' => ['Dikirim', 'bg-surface-container-high text-on-surface'],
                            'selesai' => ['Selesai', 'bg-success/10 text-success'],
                            'dibatalkan' => ['Dibatalkan', 'bg-error/10 text-error'],
                            'refund' => ['Refund', 'bg-error/10 text-error'],
                        ];
                        $st = $statusMap[$order->status] ?? [ucfirst($order->status), 'bg-surface-container-high text-on-surface'];
                        $pelanggan = $order->checkout?->user;
                    @endphp
                    <tr data-table-row data-status="{{ $order->status }}" data-search="{{ strtolower($order->nomor_order.' '.($pelanggan->nama_lengkap ?? '').' '.($pelanggan->email ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                        <td class="p-6 font-mono">{{ $order->nomor_order }}</td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                                    <span class="material-symbols-outlined text-on-surface">storefront</span>
                                </div>
                                <div>
                                    <p class="font-body-md text-on-surface">{{ $order->store->nama_toko ?? '-' }}</p>
                                    <p class="text-on-surface-variant text-xs">{{ $order->jumlah_produk }} produk</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <p class="font-body-md text-on-surface">{{ $pelanggan->nama_lengkap ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $pelanggan->email ?? '' }}</p>
                        </td>
                        <td class="p-6"><span class="font-bold text-gold-accent">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</span></td>
                        <td class="p-6">
                            <span class="inline-flex items-center px-2 py-1 rounded {{ $st[1] }} text-xs uppercase">{{ $st[0] }}</span>
                        </td>
                        <td class="p-6 text-right"><span class="text-on-surface-variant text-xs">{{ $order->waktu_relatif }}</span></td>
                        <td class="p-6 text-right">
                            <div class="flex gap-2 justify-end">
                                <button type="button" data-detail-open="detail-pesanan" data-d-nomor="{{ $order->nomor_order }}" data-d-toko="{{ $order->store->nama_toko ?? '-' }}" data-d-produk="{{ $order->jumlah_produk }} produk" data-d-pelanggan="{{ $pelanggan->nama_lengkap ?? '-' }} ({{ $pelanggan->email ?? '' }})" data-d-total="Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}" data-d-status="{{ $st[0] }}" data-d-waktu="{{ $order->waktu_relatif }}" data-d-subtotal="Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}" data-d-diskon="Rp {{ number_format((float) $order->total_diskon, 0, ',', '.') }}" data-d-pajak="Rp {{ number_format((float) $order->total_pajak, 0, ',', '.') }}" data-d-layanan="Rp {{ number_format((float) $order->biaya_layanan, 0, ',', '.') }}" data-d-ongkir="Rp {{ number_format((float) $order->total_ongkir, 0, ',', '.') }}" class="px-3 py-1 bg-surface-container-lowest text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">inbox</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Belum ada pesanan tercatat.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="8" class="p-8 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pesanan yang cocok.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<div id="detail-pesanan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pesanan</h3>
                <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface text-right"><span data-slot="toko"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Jumlah Produk</dt><dd class="text-on-surface text-right"><span data-slot="produk"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Subtotal</dt><dd class="text-on-surface text-right"><span data-slot="subtotal"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Diskon</dt><dd class="text-on-surface text-right"><span data-slot="diskon"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pajak</dt><dd class="text-on-surface text-right"><span data-slot="pajak"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Biaya Layanan</dt><dd class="text-on-surface text-right"><span data-slot="layanan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Ongkir</dt><dd class="text-on-surface text-right"><span data-slot="ongkir"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Total</dt><dd class="font-bold text-gold-accent text-right"><span data-slot="total"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Waktu</dt><dd class="text-on-surface text-right"><span data-slot="waktu"></span></dd></div>
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

    const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
    const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
    const searchInput = document.getElementById('order-search');
    const clearBtn = document.getElementById('clear-search');
    const countEl = document.getElementById('result-count');
    const emptySearch = document.getElementById('empty-search');

    const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
    const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

    let activeStatus = 'semua';

    function applyFilter() {
        const term = searchInput.value.trim().toLowerCase();
        let visible = 0;

        rows.forEach((row, i) => {
            const matchStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
            const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
            const show = matchStatus && matchSearch;
            row.classList.toggle('hidden', !show);
            if (show) {
                visible++;
                row.querySelector('.row-num').textContent = visible;
            }
        });

        countEl.textContent = visible;
        emptySearch.classList.toggle('hidden', visible > 0);

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
