@extends('layouts.superadmin')

@section('title', 'Menu Admin Utama')

@section('header-title', 'Super Admin Dashboard')

@section('header-subtitle', 'Manage platform operations and configurations.')

@section('content')
<div class="mb-element-gap md:mb-container-margin">
    <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-2">Super Admin Dashboard</h1>
    <p class="font-body-md text-body-md text-on-surface-variant">Manage platform operations and configurations.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-container-margin">
    <!-- Management Group -->
    <section class="bg-surface-container-low rounded-lg border border-muted-border overflow-hidden">
        <div class="px-container-margin py-element-gap border-b border-muted-border bg-surface-container-lowest">
            <h2 class="font-label-sm text-label-sm uppercase text-on-surface-variant">Management</h2>
        </div>
        <ul class="divide-y divide-muted-border">
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.manajemen-pengguna') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">group</span><span class="font-body-md text-body-md">Users</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.manajemen-toko') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">storefront</span><span class="font-body-md text-body-md">Stores</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.moderasi-produk') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">check_box</span><span class="font-body-md text-body-md">Product Moderation</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">category</span><span class="font-body-md text-body-md">Categories</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.laporan') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">receipt_long</span><span class="font-body-md text-body-md">Orders</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
        </ul>
    </section>

    <!-- Finance Group -->
    <section class="bg-surface-container-low rounded-lg border border-muted-border overflow-hidden">
        <div class="px-container-margin py-element-gap border-b border-muted-border bg-surface-container-lowest">
            <h2 class="font-label-sm text-label-sm uppercase text-on-surface-variant">Finance</h2>
        </div>
        <ul class="divide-y divide-muted-border">
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.laporan') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">payments</span><span class="font-body-md text-body-md">Payments</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">currency_exchange</span><span class="font-body-md text-body-md">Refunds</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.permintaan-penarikan') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">account_balance_wallet</span><span class="font-body-md text-body-md">Withdrawals</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.komisi-global') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">percent</span><span class="font-body-md text-body-md">Commission</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">request_quote</span><span class="font-body-md text-body-md">Taxes</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
        </ul>
    </section>

    <!-- Platform Group -->
    <section class="bg-surface-container-low rounded-lg border border-muted-border overflow-hidden">
        <div class="px-container-margin py-element-gap border-b border-muted-border bg-surface-container-lowest">
            <h2 class="font-label-sm text-label-sm uppercase text-on-surface-variant">Platform</h2>
        </div>
        <ul class="divide-y divide-muted-border">
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">view_agenda</span><span class="font-body-md text-body-md">Product Slots</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">local_offer</span><span class="font-body-md text-body-md">Promos</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">account_balance</span><span class="font-body-md text-body-md">Banks</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">local_shipping</span><span class="font-body-md text-body-md">Couriers</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
        </ul>
    </section>

    <!-- Monitoring Group -->
    <section class="bg-surface-container-low rounded-lg border border-muted-border overflow-hidden">
        <div class="px-container-margin py-element-gap border-b border-muted-border bg-surface-container-lowest">
            <h2 class="font-label-sm text-label-sm uppercase text-on-surface-variant">Monitoring & System</h2>
        </div>
        <ul class="divide-y divide-muted-border">
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.laporan') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">bar_chart</span><span class="font-body-md text-body-md">Reports</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="{{ route('superadmin.riwayat-aktivitas') }}"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">history</span><span class="font-body-md text-body-md">Activity</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
            <li><a class="flex items-center justify-between px-container-margin py-element-gap hover:bg-surface-container-high transition-colors group" href="#"><div class="flex items-center gap-element-gap"><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">settings</span><span class="font-body-md text-body-md">Settings</span></div><span class="material-symbols-outlined text-on-surface-variant">chevron_right</span></a></li>
        </ul>
    </section>
</div>
@endsection