@extends('layouts.admin')

@section('title', 'Permintaan Produksi')

@section('header-title', 'Permintaan Produksi')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Ajukan kebutuhan produksi ketika stok perlu dibuat ulang.')

@section('content')
<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ajukan Produksi</h2>
        <form class="space-y-gutter" id="produksi-form" data-toast-message="Permintaan produksi berhasil dikirim.">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="produk">Produk</label>
                    <select class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="produk">
                        <option>Straight Fit Pants (stok 3)</option>
                        <option>Relaxed Blazer</option>
                        <option>Oversized Linen Shirt</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="jumlah">Jumlah</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="jumlah" type="number" min="1" placeholder="Misal: 50" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="target">Target Selesai</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="target" type="date" />
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="catatan">Catatan untuk Produksi</label>
                <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none" id="catatan" rows="3" placeholder="Contoh: prioritas warna hitam, ukuran 28â€“34..."></textarea>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Kirim Pengajuan</button>
            </div>
        </form>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Riwayat Pengajuan</h2>
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
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">PRD-1042</td>
                        <td class="p-4 text-on-surface">Straight Fit Pants</td>
                        <td class="p-4 text-center text-on-surface">50</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Diproduksi</span></td>
                        <td class="p-4 text-on-surface-variant">19 Agu 2026</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">PRD-1038</td>
                        <td class="p-4 text-on-surface">Oversized Linen Shirt</td>
                        <td class="p-4 text-center text-on-surface">30</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Selesai</span></td>
                        <td class="p-4 text-on-surface-variant">02 Agu 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
