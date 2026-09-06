@extends('layouts.admin')

@section('title', 'Permintaan Produksi')

@section('header-title', 'Permintaan Produksi')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Ajukan kebutuhan produksi ketika stok perlu dibuat ulang.')

@section('content')
<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Produksi</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-2">
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">assignment</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Total Ajuan</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $stats['total_ajuan'] }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Diproduksi</p>
                <p class="font-title-md text-title-md text-secondary mt-1">{{ $stats['diproses'] }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">task_alt</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Selesai</p>
                <p class="font-title-md text-title-md text-gold-accent mt-1">{{ $stats['selesai'] }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden"><span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">inventory_2</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Unit Diminta</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ number_format((float) $stats['unit_diminta'], 0, ',', '.') }}</p>
            </div>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">precision_manufacturing</span></div>
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Permintaan Produksi</h2>
                    <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Ajukan kebutuhan produksi baru.</p>
                </div>
            </div>
            <button type="button" data-modal-open="modal-produksi" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span> Ajukan Produksi
            </button>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Pengajuan</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Diajukan</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($history as $item)
                        @php
                            $namaProduk = collect($item->items)->map(fn ($i) => $i->productVariant?->product?->nama_produk ?? '-')->first() ?? '-';
                            $jumlah = collect($item->items)->sum('jumlah_diminta');
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 font-mono text-on-surface">{{ $item->nomor_produksi }}</td>
                            <td class="p-4 text-on-surface">{{ $namaProduk }}</td>
                            <td class="p-4 text-center text-on-surface">{{ $jumlah }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ ucfirst($item->status) }}</span></td>
                            <td class="p-4 text-on-surface-variant">{{ optional($item->dimulai_pada)->translatedFormat('d M Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-6 text-center text-on-surface-variant text-sm">Belum ada riwayat produksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal Ajukan Produksi -->
<div id="modal-produksi" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Produksi</h3>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Permintaan diteruskan ke tim produksi.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form class="p-6 space-y-5" id="produksi-form" data-toast-message="Permintaan produksi berhasil dikirim.">
            <div>
                <label class="raliva-label" for="produk">Produk</label>
                <select class="raliva-select" id="produk">
                    <option>Straight Fit Pants (stok 3)</option>
                    <option>Relaxed Blazer</option>
                    <option>Oversized Linen Shirt</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="raliva-label" for="jumlah">Jumlah</label>
                    <input class="raliva-input" id="jumlah" type="number" min="1" placeholder="Misal: 50" />
                </div>
                <div>
                    <label class="raliva-label" for="target">Target Selesai</label>
                    <input class="raliva-input" id="target" type="date" />
                </div>
            </div>
            <div>
                <label class="raliva-label" for="catatan">Catatan untuk Produksi</label>
                <textarea class="raliva-textarea" id="catatan" rows="3" placeholder="Contoh: prioritas warna hitam, ukuran 28-34..."></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
