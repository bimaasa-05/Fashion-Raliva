@extends('layouts.superadmin')

@section('title', 'Komplain')

@section('header-title', 'Komplain')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola dan eskalasi komplain dari seluruh toko di platform.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Komplain</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Terbuka</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error">27</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diproses Toko</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">41</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">schedule</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Eskalasi ke Owner</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">6</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">move_up</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">118</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Komplain Lintas Toko</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[950px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Komplain</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-left">Topik</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">KOM-0318</td>
                        <td class="p-4 text-on-surface">Sarah Jenkins</td>
                        <td class="p-4 text-on-surface">LUNARA Fashion</td>
                        <td class="p-4 text-on-surface">Ukuran tidak sesuai</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Terbuka</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="showRalivaToast('Komplain KOM-0318 dieskalasi ke Owner Toko.', 'move_up')" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Eskalasi ke Owner</button>
                            <button type="button" data-detail-open="detail-komplain" data-d-nomor="KOM-0318" data-d-pelanggan="Sarah Jenkins" data-d-toko="LUNARA Fashion" data-d-topik="Ukuran tidak sesuai" data-d-status="Terbuka" class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </td>
                    </tr>
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">KOM-0316</td>
                        <td class="p-4 text-on-surface">Andi Pratama</td>
                        <td class="p-4 text-on-surface">Velvet Closet</td>
                        <td class="p-4 text-on-surface">Barang tidak pernah tiba</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Diproses Toko</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="showRalivaToast('Komplain KOM-0316 dieskalasi ke Owner Toko.', 'move_up')" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Eskalasi ke Owner</button>
                            <button type="button" data-detail-open="detail-komplain" data-d-nomor="KOM-0316" data-d-pelanggan="Andi Pratama" data-d-toko="Velvet Closet" data-d-topik="Barang tidak pernah tiba" data-d-status="Diproses Toko" class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </td>
                    </tr>
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">KOM-0310</td>
                        <td class="p-4 text-on-surface">Maya Rossi</td>
                        <td class="p-4 text-on-surface">KAYANA Apparel</td>
                        <td class="p-4 text-on-surface">Kualitas bahan di bawah ekspektasi</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Eskalasi</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" data-detail-open="detail-komplain" data-d-nomor="KOM-0310" data-d-pelanggan="Maya Rossi" data-d-toko="KAYANA Apparel" data-d-topik="Kualitas bahan di bawah ekspektasi" data-d-status="Eskalasi ke Owner" class="px-3 py-1.5 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">KOM-0305</td>
                        <td class="p-4 text-on-surface">Dewi Lestari</td>
                        <td class="p-4 text-on-surface">Atelier Rina</td>
                        <td class="p-4 text-on-surface">Barang rusak</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Selesai</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" data-detail-open="detail-komplain" data-d-nomor="KOM-0305" data-d-pelanggan="Dewi Lestari" data-d-toko="Atelier Rina" data-d-topik="Barang rusak" data-d-status="Selesai" class="px-3 py-1.5 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div id="detail-komplain" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Komplain</h3>
                <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Customer</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface text-right"><span data-slot="toko"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Topik</dt><dd class="text-on-surface text-right"><span data-slot="topik"></span></dd></div>
            <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>
@endsection
