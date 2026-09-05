@extends('layouts.gudang')

@section('title', 'Pelanggan Request')

@section('header-title', 'Pelanggan Request')
@section('header-badge', ($counts['menunggu'] ?? 0) . ' Menunggu Cek')
@section('header-subtitle', 'Kelola permintaan pelanggan — custom (Pilih bahan & hitung modal) dan produk tetap.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Alur Info --}}
    <div data-reveal class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
        <div class="border border-gold-accent/25 bg-gold-accent/5 rounded-lg p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">styler</span>
            <div>
                <p class="font-title-md text-sm text-on-surface">Custom: Pelanggan → Admin → Produksi → Gudang</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Pelanggan pilih bahan, Produksi hitung modal (HPP) & total, Gudang cek ketersediaan bahan & konfirmasi.</p>
            </div>
        </div>
        <div class="border border-muted-border bg-surface-container-low rounded-lg p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-on-surface-variant mt-0.5">inventory_2</span>
            <div>
                <p class="font-title-md text-sm text-on-surface">Produk Tetap: Customer → Admin → Gudang</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Gudang cek stok fisik produk jadi dan konfirmasi siap kirim.</p>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Cek</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $counts['menunggu'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu pengecekan stok/bahan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tersedia</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $counts['tersedia'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">siap konfirmasi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Request</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['total'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">pesanan menunggu</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">receipt_long</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diteruskan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['diteruskan'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">diteruskan ke produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tidak Tersedia</span>
            <span class="raliva-figure text-[26px] text-error">{{ $counts['kosong'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">bahan/stok kosong</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">block</span>
        </div>
    </section>

    {{-- Tabel Request --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium" data-table-scope>
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Gudang cek ketersediaan, bukan kelola pelanggan langsung.</span>
                </div>
                <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 self-start sm:self-auto px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors btn-premium">
                    <span class="material-symbols-outlined text-[18px]" data-filter-icon>tune</span>
                    Filter
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
                </button>
            </div>
            <div data-filter-panel class="hidden md:block bg-surface-container-low border border-muted-border rounded-lg p-4">
                <div class="flex items-center gap-2 mb-3 md:hidden">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter &amp; Pencarian</span>
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center gap-gutter">
                    <div class="relative flex-1 min-w-[220px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" placeholder="Cari pelanggan atau produk..." data-table-search class="raliva-search" />
                    </div>
                    <select data-table-filter="jenis" class="raliva-select">
                        <option value="">Semua Jenis</option>
                        <option value="custom">Custom</option>
                        <option value="tetap">Produk Tetap</option>
                    </select>
                    <select data-table-filter="status-request" class="raliva-select">
                        <option value="">Semua Status</option>
                        <option value="cek">Menunggu Cek</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="produksi">Diteruskan Produksi</option>
                        <option value="siap">Siap Ambil/Kirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="kosong">Tidak Tersedia</option>
                    </select>
                </div>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto hidden md:block">
            <table class="premium-table w-full min-w-[1100px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">No.</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Request</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pelanggan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jenis / Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan Pilihan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Modal (HPP)</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Total</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $row)
@php
                        $skey = $row->status_key;
                        $jkey = in_array($skey, ['kosong', 'tidak_tersedia']) ? 'tetap' : 'custom';
                        $jenis = $jkey === 'custom' ? 'Custom' : 'Produk Tetap';
                        $itemsAtr = json_encode($row->list_bahan->map(fn ($b) => [
                            'produk' => $b->nama_produk,
                            'bahan' => $b->bahan,
                            'stok' => $b->stok,
                            'tersedia' => $b->tersedia,
                        ])->values()->all(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
                    @endphp
                        <tr data-table-row data-jenis="{{ $jkey }}" data-status-request="{{ $skey }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $loop->iteration }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $row->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $row->created_at?->format('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row->pelanggan }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $jkey === 'custom' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border mb-1">{{ $jenis }}</span>
                                <p class="font-bold text-on-surface leading-tight">{{ $row->produk }}</p>
                            </td>
                            <td class="py-3.5 px-4 max-w-[260px]">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($row->list_bahan as $b)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-[10px] font-bold {{ $b->tersedia ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-error/10 text-error border-error/20' }}">{{ $b->bahan }} <span>{{ $b->stok }} unit</span></span>
                                    @empty
                                        <span class="text-on-surface-variant">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface-variant whitespace-nowrap">Rp {{ number_format($row->hpp, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($skey === 'tersedia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Tersedia</span>
                                @elseif ($skey === 'diteruskan')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Diteruskan</span>
                                @elseif ($skey === 'tidak_tersedia' || $skey === 'kosong')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Tidak Tersedia</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @php
                                    $dicek = ! is_null($row->status_ketersediaan);
                                    $produkAtr = str_replace(['"', "\n"], ['&quot;', ' '], $row->produk);
                                    $catatanAtr = str_replace(['"', "\n"], ['&quot;', ' '], $row->catatan_gudang ?? '');
                                @endphp
                                <button type="button"
                                    data-detail-open="modal-cek-request"
                                    data-d-nomor="{{ $row->nomor_order }}"
                                    data-d-produk="{{ $produkAtr }}"
                                    data-d-items="{{ $itemsAtr }}"
                                    data-d-hpp="{{ number_format($row->hpp, 0, ',', '.') }}"
                                    data-d-total="{{ number_format($row->total, 0, ',', '.') }}"
                                    data-d-catatan="{{ $catatanAtr }}"
                                    data-order-id="{{ $row->order_id }}"
                                    data-result="{{ $row->status_ketersediaan ?? '' }}"
                                    data-locked="{{ $dicek ? '1' : '0' }}"
                                    class="text-xs font-semibold whitespace-nowrap {{ $dicek ? 'text-on-surface-variant hover:text-gold-accent transition-colors' : 'text-gold-accent hover:underline' }}">
                                    {{ $dicek ? 'Detail' : 'Cek Stok' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="p-10 text-center text-on-surface-variant">Belum ada pesanan yang menunggu pemenuhan gudang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: kartu per request --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter" data-mobile-list>
            @forelse ($requests as $row)
                @php
                    $skeyM = $row->status_key;
                    $jkeyM = in_array($skeyM, ['kosong', 'tidak_tersedia']) ? 'tetap' : 'custom';
                    $jenisM = $jkeyM === 'custom' ? 'Custom' : 'Produk Tetap';
                    $dicekM = ! is_null($row->status_ketersediaan);
                    $itemsAtrM = json_encode($row->list_bahan->map(fn ($b) => [
                        'produk' => $b->nama_produk,
                        'bahan' => $b->bahan,
                        'stok' => $b->stok,
                        'tersedia' => $b->tersedia,
                    ])->values()->all(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
                    $produkAtrM = str_replace(['"', "\n"], ['&quot;', ' '], $row->produk);
                    $catatanAtrM = str_replace(['"', "\n"], ['&quot;', ' '], $row->catatan_gudang ?? '');
                    $statusClassM = [
                        'tersedia' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                        'diteruskan' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                        'tidak_tersedia' => 'bg-error/10 text-error border-error/20',
                        'kosong' => 'bg-error/10 text-error border-error/20',
                        'menunggu' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                        'cek' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                    ][$skeyM] ?? 'bg-surface-container-high text-on-surface-variant border-outline-variant';
                @endphp
                <article data-table-row data-jenis="{{ $jkeyM }}" data-status-request="{{ $skeyM }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface leading-tight">{{ $row->nomor_order }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $row->created_at?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $statusClassM }} text-[10px] font-bold uppercase border shrink-0">{{ $row->status_label }}</span>
                    </div>

                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $jkeyM === 'custom' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border">{{ $jenisM }}</span>
                        <span class="text-on-surface-variant text-xs">•</span>
                        <span class="text-on-surface-variant text-xs truncate">{{ $row->pelanggan }}</span>
                    </div>

                    <p class="font-bold text-on-surface leading-tight mb-2">{{ $row->produk }}</p>

                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex flex-col gap-2 mb-4">
                            <dt class="text-on-surface-variant">Bahan</dt>
                            <dd>
                                <div class="flex flex-wrap gap-1.5 justify-end">
                                    @forelse ($row->list_bahan as $b)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-[10px] font-bold {{ $b->tersedia ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-error/10 text-error border-error/20' }}">{{ $b->bahan }} <span>{{ $b->stok }} unit</span></span>
                                    @empty
                                        <span class="text-on-surface-variant text-sm">—</span>
                                    @endforelse
                                </div>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Modal (HPP)</dt>
                            <dd class="text-on-surface-variant text-right font-bold">Rp {{ number_format($row->hpp, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Total</dt>
                            <dd class="text-gold-accent text-right font-bold">Rp {{ number_format($row->total, 0, ',', '.') }}</dd>
                        </div>
                        @if (! $dicekM)
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Total Stok</dt>
                                <dd class="{{ $row->total_stok > 0 ? 'text-secondary' : 'text-error' }} text-right font-bold">{{ $row->total_stok }} unit</dd>
                            </div>
                        @endif
                    </dl>

                    <button type="button"
                        data-detail-open="modal-cek-request"
                        data-d-nomor="{{ $row->nomor_order }}"
                        data-d-produk="{{ $produkAtrM }}"
                        data-d-items="{{ $itemsAtrM }}"
                        data-d-hpp="{{ number_format($row->hpp, 0, ',', '.') }}"
                        data-d-total="{{ number_format($row->total, 0, ',', '.') }}"
                        data-d-catatan="{{ $catatanAtrM }}"
                        data-order-id="{{ $row->order_id }}"
                        data-result="{{ $row->status_ketersediaan ?? '' }}"
                        data-locked="{{ $dicekM ? '1' : '0' }}"
                        class="w-full min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border font-label-sm text-xs uppercase tracking-wider transition-colors {{ $dicekM ? 'border-muted-border text-on-surface-variant hover:border-gold-accent hover:text-gold-accent' : 'bg-deep-onyx text-on-primary btn-premium' }}">
                        <span class="material-symbols-outlined text-[18px]">{{ $dicekM ? 'visibility' : 'fact_check' }}</span>
                        {{ $dicekM ? 'Detail' : 'Cek Stok' }}
                    </button>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada pesanan yang menunggu pemenuhan gudang.</p>
            @endforelse
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada request yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Alur resmi: Pelanggan → Admin (terima & teruskan) → Gudang cek stok / Produksi hitung modal. Gudang tidak berinteraksi langsung dengan pelanggan.
        </p>
    </section>
</div>

{{-- Modal Cek Stok --}}
<div id="modal-cek-request" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Konfirmasi Ketersediaan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1"><span data-slot="nomor">-</span> — <span data-slot="produk">-</span> <span class="stok-hint">(<span data-slot="jumlah-bahan">0</span> bahan dipilih)</span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('gudang.pelanggan-request.konfirmasi') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="order_id" id="cek-order-id" value="" />
            <div>
                <label class="block raliva-label mb-2">Hasil Pengecekan</label>
                <select name="hasil" id="cek-hasil" class="raliva-select" required>
                    <option value="tersedia">Tersedia — Siap diproses</option>
                    <option value="diteruskan">Diteruskan ke Produksi (bahan perlu produksi)</option>
                    <option value="tidak_tersedia">Tidak Tersedia — Bahan kosong</option>
                </select>
            </div>
            <div>
                <label for="cek-catatan" class="block raliva-label mb-2">Catatan untuk Admin</label>
                <textarea name="catatan" id="cek-catatan" rows="2" placeholder="Stok bahan cukup untuk pemenuhan pesanan, estimasi..." class="raliva-textarea"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-3">
                    <p class="raliva-label mb-2">Bahan Pilihan &amp; Stok</p>
                    <ul class="space-y-2" data-list-bahan></ul>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                    <p class="raliva-label">Modal (HPP)</p>
                    <p class="font-title-md text-sm text-on-surface mt-1">Rp <span data-slot="hpp">0</span></p>
                    <p class="text-xs text-gold-accent font-bold mt-1">Total Rp <span data-slot="total">0</span></p>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
                <button type="submit" id="cek-submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('modal-cek-request');
    if (!modal) return;
    const inputOrder = document.getElementById('cek-order-id');
    const selectHasil = document.getElementById('cek-hasil');
    const textareaCatatan = document.getElementById('cek-catatan');
    const submitBtn = document.getElementById('cek-submit');
    const listBahan = document.querySelector('[data-list-bahan]');
    const slotJumlah = modal.querySelector('[data-slot="jumlah-bahan"]');

    document.querySelectorAll('[data-detail-open="modal-cek-request"]').forEach((btn) => {
        btn.addEventListener('click', () => {
            inputOrder.value = btn.dataset.orderId || '';
            selectHasil.value = btn.dataset.result || 'tersedia';
            textareaCatatan.value = btn.dataset.catatan || '';
            const locked = btn.dataset.locked === '1';
            selectHasil.disabled = locked;
            textareaCatatan.readOnly = locked;
            submitBtn.classList.toggle('hidden', locked);

            let items = [];
            try {
                items = JSON.parse(btn.dataset.dItems || '[]');
            } catch (e) {
                items = [];
            }

            if (listBahan) listBahan.innerHTML = '';
            if (slotJumlah) slotJumlah.textContent = items.length;

            (items.length ? items : [{ produk: btn.dataset.dProduk || '-', bahan: '-', stok: 0, tersedia: false }]).forEach((it) => {
                if (!listBahan) return;
                const li = document.createElement('li');
                li.className = 'flex items-center justify-between gap-2';
                const name = document.createElement('span');
                name.className = 'text-xs text-on-surface leading-tight';
                name.textContent = it.bahan && it.bahan !== '—' ? it.bahan : (it.produk || '-');
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full border text-[10px] font-bold shrink-0 ' + (it.tersedia ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-error/10 text-error border-error/20');
                badge.textContent = it.stok + ' unit';
                li.appendChild(name);
                li.appendChild(badge);
                listBahan.appendChild(li);
            });
        });
    });
})();
</script>
@endpush
