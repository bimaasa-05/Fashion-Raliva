@extends('layouts.superadmin')

@section('title', 'Riwayat Aktivitas')

@section('header-title', 'Activity History')

@section('header-subtitle', 'Audit log of system events and administrative actions.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .icon-fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-scroll::-webkit-scrollbar { height: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { background-color: #e3e2e2; border-radius: 4px; }
    .timeline-line::before { content: ''; position: absolute; left: 20px; top: 48px; bottom: -24px; width: 1px; background-color: #E9E8E7; z-index: 0; }
    .timeline-item:last-child .timeline-line::before { display: none; }
</style>
@endpush

@section('content')
<div class="mb-section-gap">
    <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-2 text-on-surface">Activity History</h1>
    <p class="text-on-surface-variant">Audit log of system events and administrative actions.</p>
</div>

<!-- Filters -->
<div class="mb-8">
    <div class="flex overflow-x-auto filter-scroll pb-2 -mx-gutter px-gutter md:mx-0 md:px-0 space-x-4">
        <button class="whitespace-nowrap px-4 py-2 border-b-2 border-primary text-on-surface font-label-sm text-label-sm uppercase transition-colors">All Activity</button>
        <button class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">User</button>
        <button class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Store</button>
        <button class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Product</button>
        <button class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Finance</button>
        <button class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">System</button>
    </div>
    <div class="h-[1px] w-full bg-muted-border -mt-[1px]"></div>
</div>

<!-- Timeline -->
<div class="space-y-6">
    <div class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center shrink-0 border border-muted-border mt-1"><span class="material-symbols-outlined text-on-surface-variant text-sm">storefront</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Store Approved</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Today, 10:45 AM</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2">
                    <div class="text-sm"><span class="font-bold text-on-surface">Admin (System)</span> approved <span class="font-bold text-on-surface">Lunara Fashion</span>'s application to join the marketplace.</div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Store</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Approval</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center shrink-0 border border-muted-border mt-1"><span class="material-symbols-outlined text-on-surface-variant text-sm">payments</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Payout Requested</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Yesterday, 04:20 PM</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2">
                    <div class="text-sm"><span class="font-bold text-on-surface">Noir Studio</span> requested a payout of <span class="font-bold text-on-surface">Rp 4.500.000</span> to linked bank account.</div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Finance</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Pending</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center shrink-0 border border-muted-border mt-1"><span class="material-symbols-outlined text-on-surface-variant text-sm">block</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Product Rejected</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Oct 24, 02:15 PM</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2">
                    <div class="text-sm"><span class="font-bold text-on-surface">Auto-Moderation</span> rejected product listing <span class="font-bold text-on-surface">"Vintage Leather Jacket"</span> from <span class="font-bold text-on-surface">Kayana Apparel</span>.</div>
                    <div class="mt-2 text-sm text-on-surface-variant bg-surface p-2 border border-muted-border rounded-sm">Reason: Missing required material composition details.</div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Product</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">System</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center shrink-0 border border-muted-border mt-1"><span class="material-symbols-outlined text-on-surface-variant text-sm">percent</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Commission Rate Updated</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Oct 20, 09:00 AM</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2">
                    <div class="text-sm"><span class="font-bold text-on-surface">SuperAdmin</span> changed global marketplace commission rate.</div>
                    <div class="mt-2 text-sm"><span class="line-through text-on-surface-variant">12.5%</span> <span class="material-symbols-outlined text-[12px] align-middle mx-1">arrow_forward</span> <span class="font-bold text-on-surface">15.0%</span></div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">System</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Finance</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-8 text-center">
        <button class="px-6 py-3 border border-on-surface text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-high transition-colors">Load Older Activity</button>
    </div>
</div>
@endsection