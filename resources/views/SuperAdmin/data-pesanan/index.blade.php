@extends('layouts.superadmin')

@section('title', 'Data Pesanan')

@section('header-title', 'Data Pesanan')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Monitor pesanan dari seluruh toko tanpa mengambil alih operasional')

@section('content')
<!-- Orders Management -->
<section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6">
    <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface">Data Pesanan</h2>
    
    <!-- Filters -->
    <div class="mb-6 flex flex-col md:flex-row gap-3">
        <button class="flex-1 px-4 py-2 border border-muted-border text-on-surface-variant text-sm uppercase rounded hover:bg-surface-container-high transition-colors">Semua Pesanan</button>
        <button class="flex-1 px-4 py-2 border border-muted-border text-on-surface-variant text-sm uppercase rounded hover:bg-surface-container-high transition-colors">Menunggu</button>
        <button class="flex-1 px-4 py-2 border border-primary text-primary text-sm uppercase rounded hover:bg-primary-fixed-dim transition-colors">Diproses</button>
        <button class="flex-1 px-4 py-2 border border-success text-success text-sm uppercase rounded hover:bg-success/20 transition-colors">Selesai</button>
    </div>
    
    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-full bg-surface-container-lowest rounded-lg overflow-hidden">
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
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
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
                            <button class="px-3 py-1 bg-white text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </div>
                    </td>
                </tr>
                
                <!-- Order Row 2 -->
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
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
                            <button class="px-3 py-1 bg-white text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </div>
                    </td>
                </tr>
                
                <!-- Order Row 3 -->
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
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
                            <button class="px-3 py-1 bg-white text-on-surface text-xs uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection