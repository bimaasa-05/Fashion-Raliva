@extends('layouts.gudang')

@section('title', 'Pemindahan Stok')

@section('header-title', 'Pemindahan Stok')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Pindahkan stok antar gudang dalam toko yang sama.')

@section('content')
@php
    $statusLabel = [
        'requested' => 'Draft',
        'approved' => 'Diproses',
        'in_transit' => 'Dikirim',
        'received' => 'Diterima',
        'cancelled' => 'Dibatalkan',
    ];
    $badgeClass = [
        'requested' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
        'approved' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'in_transit' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'received' => 'bg-secondary text-on-secondary border-secondary',
        'cancelled' => 'bg-error/10 text-error border-error/20',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[400px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter & Pencarian</span>
            </div>
            <form method="GET" class="flex flex-col lg:flex-row lg:items-center gap-gutter">
                <select name="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent lg:max-w-[220px]">
                    <option value="">Semua Status</option>
                    @foreach ($statusLabel as $key => $label)
                        <option value="{{ $key }}" {{ ($filters['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Terapkan</button>
                <button type="button" data-modal-open="modal-pemindahan" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0 ml-auto">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    Buat Pemindahan
                </button>
            </form>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor</th>
                        <th class="p-4 text-left">Rute Gudang</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($transfers as $t)
                        @php
                            $firstItem = $t->items->first();
                            $status = $statusLabel[$t->status] ?? $t->status;
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row>
                            <td class="p-4"><span class="font-bold text-on-surface">PM-{{ str_pad($t->stock_transfer_id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td class="p-4">
                                <div class="flex items-center gap-2 text-sm whitespace-nowrap">
                                    <span class="text-on-surface">{{ $t->fromWarehouse->nama_gudang ?? '-' }}</span>
                                    <span class="material-symbols-outlined text-[16px] text-gold-accent">east</span>
                                    <span class="text-on-surface">{{ $t->toWarehouse->nama_gudang ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-on-surface">{{ $firstItem->productVariant->product->nama_produk ?? '-' }}</td>
                            <td class="p-4 text-center font-bold text-gold-accent">{{ $t->items->sum('jumlah') }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $t->diminta_pada?->format('d M Y • H:i') ?? '-' }}</td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $t->requester->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$t->status] ?? 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border">{{ $status }}</span></td>
                            <td class="p-4 text-center">
                                <button type="button" data-modal-open="pm-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-10 text-center text-on-surface-variant">Belum ada pemindahan stok pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transfers->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $transfers->firstItem() }}–{{ $transfers->lastItem() }} dari {{ $transfers->total() }} pemindahan • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $transfers->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>

    @foreach ($transfers as $t)
        @php $firstItem = $t->items->first(); $status = $statusLabel[$t->status] ?? $t->status; @endphp
        <div id="pm-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Detail PM-{{ str_pad($t->stock_transfer_id, 4, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">{{ $t->diminta_pada?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-[18px] text-secondary shrink-0">warehouse</span>
                            <div class="min-w-0">
                                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Asal</p>
                                <p class="text-on-surface truncate">{{ $t->fromWarehouse->nama_gudang ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-gold-accent shrink-0">east</span>
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-[18px] text-gold-accent shrink-0">warehouse</span>
                            <div class="min-w-0">
                                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Tujuan</p>
                                <p class="text-on-surface truncate">{{ $t->toWarehouse->nama_gudang ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Produk</dt><dd class="text-on-surface text-right">{{ $firstItem->productVariant->product->nama_produk ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Jumlah</dt><dd class="text-gold-accent font-bold">{{ $t->items->sum('jumlah') }} unit</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas</dt><dd class="text-on-surface">{{ $t->requester->nama_lengkap ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$t->status] ?? '' }} text-[10px] font-bold uppercase border">{{ $status }}</span></dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-pemindahan" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Buat Pemindahan</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Pindahkan stok ke gudang lain dalam toko yang sama.</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Permintaan pemindahan berhasil dibuat." class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Gudang Asal</label>
                        <input type="text" value="{{ $warehouse->nama_gudang ?? '-' }}" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                        <p class="text-xs text-on-surface-variant mt-1.5">Sesuai penugasan akun Anda.</p>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Gudang Tujuan</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            @foreach ($otherWarehouses as $ow)
                                <option value="{{ $ow->warehouse_id }}">{{ $ow->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Alasan pemindahan, kondisi barang, dll. (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Ajukan Pemindahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
