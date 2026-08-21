@extends('layouts.admin')

@section('title', 'Data Customer')

@section('header-title', 'Data Customer')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Data customer yang berhubungan dengan toko dan riwayat pesanannya.')

@section('content')
<section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Customer</h2>
        <div class="relative md:w-72">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input class="w-full bg-surface-container-low border border-muted-border rounded pl-12 pr-4 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent transition-colors" placeholder="Cari nama atau email..." type="text" />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[750px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">Customer</th>
                    <th class="p-4 text-left">Bergabung</th>
                    <th class="p-4 text-center">Total Pesanan</th>
                    <th class="p-4 text-right">Total Belanja</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Sarah Jenkins</p>
                        <p class="text-on-surface-variant text-xs">sarah@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface-variant">Mar 2025</td>
                    <td class="p-4 text-center text-on-surface">14</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 12.450.000</td>
                    <td class="p-4 text-right"><button class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Riwayat</button></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Andi Pratama</p>
                        <p class="text-on-surface-variant text-xs">andi.p@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface-variant">Jul 2025</td>
                    <td class="p-4 text-center text-on-surface">6</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 3.890.000</td>
                    <td class="p-4 text-right"><button class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Riwayat</button></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Dewi Lestari</p>
                        <p class="text-on-surface-variant text-xs">dewi.l@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface-variant">Jan 2026</td>
                    <td class="p-4 text-center text-on-surface">9</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 8.720.000</td>
                    <td class="p-4 text-right"><button class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Riwayat</button></td>
                </tr>
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Budi Santoso</p>
                        <p class="text-on-surface-variant text-xs">budi.s@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface-variant">Nov 2025</td>
                    <td class="p-4 text-center text-on-surface">3</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 1.340.000</td>
                    <td class="p-4 text-right"><button class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Riwayat</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
