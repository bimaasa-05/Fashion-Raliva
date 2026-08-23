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
    <div data-chip-group data-chip-key="status" class="mb-6 flex flex-col md:flex-row gap-3">
        <button type="button" data-chip="semua" class="flex-1 px-4 py-2 border border-muted-border text-on-surface-variant text-sm uppercase rounded hover:bg-surface-container-high transition-colors">Semua Pesanan</button>
        <button type="button" data-chip="menunggu" class="flex-1 px-4 py-2 border border-muted-border text-on-surface-variant text-sm uppercase rounded hover:bg-surface-container-high transition-colors">Menunggu</button>
        <button type="button" data-chip="diproses" class="flex-1 px-4 py-2 bg-deep-onyx border border-deep-onyx text-on-primary text-sm uppercase rounded transition-colors">Diproses</button>
        <button type="button" data-chip="selesai" class="flex-1 px-4 py-2 border border-muted-border text-on-surface-variant text-sm uppercase rounded hover:bg-surface-container-high transition-colors">Selesai</button>
    </div>
    
    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-full bg-surface-container-lowest rounded-lg overflow-hidden premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
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
                <!-- Order Row 1 -->
                <tr data-table-row data-status="diproses" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-6 font-mono">RLV-20240152</td>
                    <td class="p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-surface">storefront</span>
                            </div>
                            <div>
                                <p class="font-body-md text-on-surface">LUNARA Fashion</p>
                                <p class="text-on-surface-variant text-xs">5 produk</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-6">
                        <p class="font-body-md text-on-surface">Sarah Jenkins</p>
                        <p class="text-on-surface-variant text-xs">sarah@email.com</p>
                    </td>
                    <td class="p-6"><span class="font-bold text-gold-accent">Rp 2.550.000</span></td>
                    <td class="p-6">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-surface-container-high text-on-surface text-xs uppercase">Diproses</span>
                    </td>
                    <td class="p-6 text-right"><span class="text-on-surface-variant text-xs">2 jam lalu</span></td>
                    <td class="p-6 text-right">
                        <div class="flex gap-2">
                            <button type="button" data-detail-open="detail-pesanan" data-d-nomor="RLV-20240152" data-d-toko="LUNARA Fashion" data-d-produk="5 produk" data-d-pelanggan="Sarah Jenkins (sarah@email.com)" data-d-total="Rp 2.550.000" data-d-status="Diproses" data-d-waktu="2 jam lalu" class="px-3 py-1 bg-surface-container-lowest text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </div>
                    </td>
                </tr>
                <tr data-table-row data-status="menunggu" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-6 font-mono">RLV-20240151</td>
                    <td class="p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-surface">storefront</span>
                            </div>
                            <div>
                                <p class="font-body-md text-on-surface">NOIRÉ Studio</p>
                                <p class="text-on-surface-variant text-xs">3 produk</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-6">
                        <p class="font-body-md text-on-surface">David Chen</p>
                        <p class="text-on-surface-variant text-xs">david@email.com</p>
                    </td>
                    <td class="p-6"><span class="font-bold text-gold-accent">Rp 1.820.000</span></td>
                    <td class="p-6">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-surface-container-high text-on-surface text-xs uppercase">Menunggu</span>
                    </td>
                    <td class="p-6 text-right"><span class="text-on-surface-variant text-xs">1 hari lalu</span></td>
                    <td class="p-6 text-right">
                        <div class="flex gap-2">
                            <button type="button" data-detail-open="detail-pesanan" data-d-nomor="RLV-20240151" data-d-toko="NOIRÉ Studio" data-d-produk="3 produk" data-d-pelanggan="David Chen (david@email.com)" data-d-total="Rp 1.820.000" data-d-status="Menunggu" data-d-waktu="1 hari lalu" class="px-3 py-1 bg-surface-container-lowest text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </div>
                    </td>
                </tr>
                
                <!-- Order Row 3 -->
                <tr data-table-row data-status="dikirim" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-6 font-mono">RLV-20240150</td>
                    <td class="p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-surface">storefront</span>
                            </div>
                            <div>
                                <p class="font-body-md text-on-surface">Teko & Sons</p>
                                <p class="text-on-surface-variant text-xs">2 produk</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-6">
                        <p class="font-body-md text-on-surface">Mike Wilson</p>
                        <p class="text-on-surface-variant text-xs">mike@email.com</p>
                    </td>
                    <td class="p-6"><span class="font-bold text-gold-accent">Rp 3.200.000</span></td>
                    <td class="p-6">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-surface-container-high text-on-surface text-xs uppercase">Dikirim</span>
                    </td>
                    <td class="p-6 text-right"><span class="text-on-surface-variant text-xs">3 hari lalu</span></td>
                    <td class="p-6 text-right">
                        <div class="flex gap-2">
                            <button type="button" data-detail-open="detail-pesanan" data-d-nomor="RLV-20240150" data-d-toko="Teko &amp; Sons" data-d-produk="2 produk" data-d-pelanggan="Mike Wilson (mike@email.com)" data-d-total="Rp 3.200.000" data-d-status="Dikirim" data-d-waktu="3 hari lalu" class="px-3 py-1 bg-surface-container-lowest text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
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
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Total</dt><dd class="font-bold text-gold-accent text-right"><span data-slot="total"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Waktu</dt><dd class="text-on-surface text-right"><span data-slot="waktu"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>
@endsection