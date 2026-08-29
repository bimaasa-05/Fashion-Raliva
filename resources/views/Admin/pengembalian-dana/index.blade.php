@extends('layouts.admin')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Periksa dan proses pengajuan refund sesuai kewenangan.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Pengajuan Refund Masuk</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">REF-2601 &#8226; Pesanan #RLV-2069</p>
                        <p class="font-title-md text-title-md text-gold-accent mt-1">Rp 289.000</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant mb-4"><span class="text-on-surface font-bold">Sarah Jenkins:</span> "Ukuran tidak sesuai dengan deskripsi, minta refund ya."</p>
                <div class="flex gap-3">
                    <button type="button" onclick="showRalivaToast('Refund REF-2601 disetujui.', 'task_alt')" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                    <button type="button" onclick="showRalivaToast('Refund REF-2601 ditolak.', 'block')" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                    <button type="button" onclick="showRalivaToast('Refund REF-2601 dieskalasi ke Super Admin.', 'move_up')" class="px-4 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Eskalasi</button>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">REF-2598 &#8226; Pesanan #RLV-2065</p>
                        <p class="font-title-md text-title-md text-gold-accent mt-1">Rp 455.000</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant mb-4"><span class="text-on-surface font-bold">Budi Santoso:</span> "Barang diterima dalam kondisi kusut berat, tidak layak pakai."</p>
                <div class="flex gap-3">
                    <button type="button" onclick="showRalivaToast('Refund REF-2598 disetujui.', 'task_alt')" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                    <button type="button" onclick="showRalivaToast('Refund REF-2598 ditolak.', 'block')" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                    <button type="button" onclick="showRalivaToast('Refund REF-2598 dieskalasi ke Super Admin.', 'move_up')" class="px-4 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Eskalasi</button>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Refund</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Refund</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-right">Jumlah</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-left">Diproses</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">REF-2590</td>
                        <td class="p-4 text-on-surface">Andi Pratama</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 320.000</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span></td>
                        <td class="p-4 text-on-surface-variant">Kemarin, 14.05</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">REF-2585</td>
                        <td class="p-4 text-on-surface">Maya Rossi</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 780.000</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span></td>
                        <td class="p-4 text-on-surface-variant">20 Agu 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
