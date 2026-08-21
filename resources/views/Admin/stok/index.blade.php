@extends('layouts.admin')

@section('title', 'Stok')

@section('header-title', 'Stok')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Lihat atau perbarui stok sesuai pembagian tugas dengan Gudang.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Pembaruan stok besar (restock massal) dikoordinasikan dengan Gudang. Kamu dapat menyesuaikan stok satuan untuk mendukung proses order.</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Kelola Stok Produk</h2>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Stok Saat Ini</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Perbarui</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4">
                            <p class="text-on-surface">Oversized Linen Shirt</p>
                            <p class="text-on-surface-variant text-xs">Kemeja • S, M, L, XL</p>
                        </td>
                        <td class="p-4 text-center font-bold text-on-surface">24</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span></td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <input class="w-20 bg-transparent border border-muted-border rounded p-2 text-center font-body-md text-sm focus:outline-none focus:border-gold-accent" type="number" value="24" />
                                <button class="px-3 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Simpan</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4">
                            <p class="text-on-surface">Straight Fit Pants</p>
                            <p class="text-on-surface-variant text-xs">Celana • 28–34</p>
                        </td>
                        <td class="p-4 text-center font-bold text-error">3</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span></td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <input class="w-20 bg-transparent border border-muted-border rounded p-2 text-center font-body-md text-sm focus:outline-none focus:border-gold-accent" type="number" value="3" />
                                <button class="px-3 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Simpan</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4">
                            <p class="text-on-surface">Relaxed Blazer</p>
                            <p class="text-on-surface-variant text-xs">Blazer • M, L</p>
                        </td>
                        <td class="p-4 text-center font-bold text-on-surface">8</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span></td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <input class="w-20 bg-transparent border border-muted-border rounded p-2 text-center font-body-md text-sm focus:outline-none focus:border-gold-accent" type="number" value="8" />
                                <button class="px-3 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Simpan</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
