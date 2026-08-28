@extends('layouts.gudang')

@section('title', 'Barang Keluar')

@section('header-title', 'Barang Keluar')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Catat pengeluaran barang untuk pesanan dan kebutuhan internal.')

@section('content')
@php
    $badgeClass = [
        'Diproses' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'Dikirim' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
    $sumberLabel = [
        'order_item' => 'Pemenuhan Pesanan',
        'stock_transfer' => 'Gudang Lain',
        'production' => 'Produksi',
        'manual' => 'Manual',
    ];
    $sumberIcon = [
        'order_item' => 'shopping_bag',
        'stock_transfer' => 'warehouse',
        'production' => 'precision_manufacturing',
        'manual' => 'edit_note',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter & Pencarian</span>
            </div>
            <form method="GET" class="flex flex-col lg:flex-row lg:items-center gap-gutter">
                <div class="relative flex-1 min-w-0">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
                </div>
                <button type="submit" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Cari</button>
                <button type="button" data-modal-open="modal-barang-keluar" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    Catat Barang Keluar
                </button>
            </form>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor</th>
                        <th class="p-4 text-center">Tujuan</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($items as $m)
                        @php
                            $tujuan = $sumberLabel[$m->sumber_tipe] ?? 'Manual';
                            $status = $m->sumber_tipe === 'stock_transfer' ? 'Dikirim' : 'Selesai';
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row>
                            <td class="p-4"><span class="font-bold text-on-surface">BK-{{ str_pad($m->stock_movement_id, 4, '0', STR_PAD_LEFT) }}</span><span class="block text-xs text-on-surface-variant mt-0.5">{{ $m->created_at?->format('d M Y • H:i') ?? '-' }}</span></td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1.5 text-on-surface whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ $sumberIcon[$m->sumber_tipe] ?? 'edit_note' }}</span>
                                    {{ $tujuan }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface">{{ $m->productVariant->product->nama_produk ?? '-' }}</td>
                            <td class="p-4 text-center font-bold text-error">-{{ $m->jumlah }}</td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $m->creator->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $m->created_at?->format('d M Y') ?? '-' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$status] }} text-[10px] font-bold uppercase border">{{ $status }}</span></td>
                            <td class="p-4 text-center">
                                <button type="button" data-modal-open="bk-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 mx-auto rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-10 text-center text-on-surface-variant">Belum ada catatan barang keluar pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} transaksi • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $items->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>

    @foreach ($items as $m)
        @php $tujuan = $sumberLabel[$m->sumber_tipe] ?? 'Manual'; $status = $m->sumber_tipe === 'stock_transfer' ? 'Dikirim' : 'Selesai'; @endphp
        <div id="bk-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Detail BK-{{ str_pad($m->stock_movement_id, 4, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">{{ $m->created_at?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-start gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gold-accent text-[20px]">{{ $sumberIcon[$m->sumber_tipe] ?? 'edit_note' }}</span>
                    </div>
                    <div>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Tujuan Pengeluaran</p>
                        <p class="font-title-md text-base text-on-surface leading-snug">{{ $tujuan }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">Referensi: {{ $m->sumber_tipe }}{{ $m->sumber_id ? ' #'.$m->sumber_id : '' }}</p>
                    </div>
                </div>
                <div class="border border-muted-border rounded-lg divide-y divide-muted-border mb-6">
                    <div class="flex items-center justify-between gap-4 p-4">
                        <div>
                            <p class="text-on-surface font-bold">{{ $m->productVariant->product->nama_produk ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">Variasi: {{ trim(($m->productVariant->warna ?? '').' '.($m->productVariant->ukuran ?? '')) ?: 'Assorted' }}</p>
                        </div>
                        <span class="font-bold text-error">-{{ $m->jumlah }} unit</span>
                    </div>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas</dt><dd class="text-on-surface">{{ $m->creator->nama_lengkap ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Waktu Pencatatan</dt><dd class="text-on-surface">{{ $m->created_at?->format('d M Y H:i') ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$status] }} text-[10px] font-bold uppercase border">{{ $status }}</span></dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-barang-keluar" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Barang Keluar</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: {{ $warehouse->nama_gudang ?? '-' }}</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Barang keluar berhasil dicatat." class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Catatan tambahan (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
