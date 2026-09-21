@extends('layouts.superadmin')

@section('title', 'Staff Toko')
@section('header-title', 'Staff Toko')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola penugasan staff toko di seluruh platform. Tugaskan, ubah status, dan nonaktifkan staff.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(139, 30, 63, 0.5); color: #8B1E3F; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(139, 30, 63, 0.15); border-color: rgba(139, 30, 63, 0.5); color: #8B1E3F; }
    .staff-row { transition: all 0.2s ease; }
    .staff-row:hover { background-color: rgba(139, 30, 63, 0.04); }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <div data-reveal class="flex flex-wrap items-center gap-3 -mt-2 mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </span>
        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
            Data staff diperbarui real-time
        </span>
    </div>
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">groups</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $summary['total'] }} Staff
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            {{ $summary['aktif'] }} Aktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                            {{ $summary['nonaktif'] }} Nonaktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider border border-outline-variant">
                            {{ $summary['total_toko'] }} Toko
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Lihat dan kelola penugasan staff dari seluruh toko di platform Raliva. Tugaskan user ke toko yang membutuhkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Toolbar -->
    <section class="rise rise-d1" data-table-scope>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Staff Toko</h2>
                    <span class="text-xs text-on-surface-variant mt-0.5 w-full">Semua staff yang ditugaskan di seluruh toko.</span>
                </div>
                <button type="button" onclick="openAddStaffModal()" class="py-2.5 px-5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>Tambah Staff
                </button>
            </div>

            <!-- Filters -->
            <div class="md:hidden mb-6">
                <button type="button" data-filter-toggle class="inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors w-full">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    Filter
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
                </button>
            </div>
            <div data-filter-panel class="hidden md:block flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" id="searchInput" placeholder="Cari nama staff atau toko..." class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" oninput="applyFilter()" />
                </div>
                <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                    <div class="relative w-full lg:w-40" id="filterStatus-dd">
                        <button type="button" data-dd-trigger id="filterStatus-trigger" onclick="toggleDropdown('filterStatus')" aria-haspopup="listbox" aria-expanded="false"
                            class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent transition-colors cursor-pointer text-left">
                            <span id="filterStatus-label" class="truncate">Semua Status</span>
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="filterStatus-chevron">expand_more</span>
                        </button>
                        <div id="filterStatus-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                            class="hidden absolute left-0 top-full mt-2 w-full min-w-[160px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                            <button type="button" role="option" aria-selected="true" data-dd-option="" onclick="selectFilterStatus(''); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Semua Status<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent">check</span>
                            </button>
                            <button type="button" role="option" aria-selected="false" data-dd-option="aktif" onclick="selectFilterStatus('aktif'); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Aktif<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                            <button type="button" role="option" aria-selected="false" data-dd-option="nonaktif" onclick="selectFilterStatus('nonaktif'); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Nonaktif<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                        </div>
                        <input type="hidden" id="filterStatus" value="" />
                    </div>
                    <div class="relative w-full lg:w-40" id="filterRole-dd">
                        <button type="button" data-dd-trigger id="filterRole-trigger" onclick="toggleDropdown('filterRole')" aria-haspopup="listbox" aria-expanded="false"
                            class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent transition-colors cursor-pointer text-left">
                            <span id="filterRole-label" class="truncate">Semua Role</span>
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="filterRole-chevron">expand_more</span>
                        </button>
                        <div id="filterRole-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                            class="hidden absolute left-0 top-full mt-2 w-full min-w-[160px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                            <button type="button" role="option" aria-selected="true" data-dd-option="" onclick="selectFilterRole(''); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Semua Role<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent">check</span>
                            </button>
                            <button type="button" role="option" aria-selected="false" data-dd-option="admin" onclick="selectFilterRole('admin'); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Admin Toko<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                            <button type="button" role="option" aria-selected="false" data-dd-option="produksi" onclick="selectFilterRole('produksi'); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Produksi<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                            <button type="button" role="option" aria-selected="false" data-dd-option="gudang" onclick="selectFilterRole('gudang'); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Gudang<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                        </div>
                        <input type="hidden" id="filterRole" value="" />
                    </div>
                    <div class="relative w-full lg:w-44" id="filterToko-dd">
                        <button type="button" data-dd-trigger id="filterToko-trigger" onclick="toggleDropdown('filterToko')" aria-haspopup="listbox" aria-expanded="false"
                            class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent transition-colors cursor-pointer text-left">
                            <span id="filterToko-label" class="truncate">Semua Toko</span>
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="filterToko-chevron">expand_more</span>
                        </button>
                        <div id="filterToko-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                            class="hidden absolute left-0 top-full mt-2 w-full min-w-[180px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-y-auto max-h-64 py-1">
                            <button type="button" role="option" aria-selected="true" data-dd-option="" onclick="selectFilterToko(''); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Semua Toko<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent">check</span>
                            </button>
                            @foreach ($stores as $store)
                                <button type="button" role="option" aria-selected="false" data-dd-option="{{ $store->store_id }}" onclick="selectFilterToko('{{ $store->store_id }}'); applyFilter()" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                    {{ $store->nama_toko }}<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" id="filterToko" value="" />
                    </div>
                    <button type="button" onclick="resetFilter()" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full min-w-[860px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">No</th>
                            <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">Staff</th>
                            <th class="px-4 py-4 text-center w-12 text-center text-[10px] font-semibold tracking-widest">Role</th>
                            <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">Toko</th>
                            <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">Ditugaskan</th>
                            <th class="px-4 py-4 text-center w-12 text-center text-[10px] font-semibold tracking-widest">Status</th>
                            <th class="px-4 py-4 text-center w-12 text-center text-[10px] font-semibold tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @forelse ($staff as $s)
                            @php
                                $u = $s->user;
                                $nm = $u?->nama_lengkap ?? '-';
                                $initial = collect(explode(' ', $nm))->map(fn ($w) => mb_substr($w, 0, 1))->slice(0, 2)->implode('');
                                $rkey = $roleOf($s);
                                $roleClass = match ($s->user?->role_id) {
                                    3 => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                                    4 => 'bg-secondary-container/20 text-secondary border-secondary/20',
                                    5 => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                                    default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                                };
                            @endphp
                            <tr class="staff-row border-b border-muted-border last:border-0"
                                data-id="{{ $s->store_staff_id }}"
                                data-status="{{ $s->status }}"
                                data-role="{{ $s->user?->role_id }}"
                                data-store="{{ $s->store_id }}"
                                data-search="{{ strtolower($nm . ' ' . ($u?->email ?? '') . ' ' . ($s->store->nama_toko ?? '')) }}">
                                <td class="py-3.5 px-4 text-on-surface-variant">{{ $loop->iteration }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $initial }}</div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-on-surface truncate">{{ $nm }}</p>
                                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $u?->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $roleClass }} text-[9px] font-bold uppercase border whitespace-nowrap">{{ $rkey }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant text-[11px] whitespace-nowrap">{{ $s->store->nama_toko ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $s->tanggal_penugasan?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <form method="POST" action="{{ route('superadmin.store-staff.update', $s->store_staff_id) }}" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        @method('PUT')
                                        <div class="relative" id="rowStatus-{{ $s->store_staff_id }}-dd">
                                            <button type="button" data-dd-trigger id="rowStatus-{{ $s->store_staff_id }}-trigger" onclick="toggleDropdown('rowStatus-{{ $s->store_staff_id }}')" aria-haspopup="listbox" aria-expanded="false"
                                                class="flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer text-left min-w-[110px] {{ $s->status === 'aktif' ? 'text-secondary border-secondary/30' : 'text-error border-error/30' }}">
                                                <span id="rowStatus-{{ $s->store_staff_id }}-label" class="truncate">{{ $s->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span>
                                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="rowStatus-{{ $s->store_staff_id }}-chevron">expand_more</span>
                                            </button>
                                            <div id="rowStatus-{{ $s->store_staff_id }}-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                                                class="hidden absolute left-0 top-full mt-1 w-full min-w-[130px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'aktif' ? 'true' : 'false' }}" data-dd-option="aktif" onclick="selectRowStatus({{ $s->store_staff_id }}, 'aktif')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Aktif<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'aktif' ? '' : 'hidden' }}">check</span>
                                                </button>
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'nonaktif' ? 'true' : 'false' }}" data-dd-option="nonaktif" onclick="selectRowStatus({{ $s->store_staff_id }}, 'nonaktif')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Nonaktif<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'nonaktif' ? '' : 'hidden' }}">check</span>
                                                </button>
                                            </div>
                                            <input type="hidden" name="status" value="{{ $s->status }}" />
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-2 py-1 rounded-md bg-deep-onyx text-on-primary text-[10px] font-bold uppercase tracking-wider hover:bg-black transition-colors">Simpan</button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" onclick="openDetail({{ $s->store_staff_id }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-12 text-center text-on-surface-variant">Belum ada staff toko.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile: kartu per staff -->
            <div class="md:hidden grid grid-cols-1 gap-gutter mb-6">
                @forelse ($staff as $s)
                    @php
                        $u = $s->user;
                        $nm = $u?->nama_lengkap ?? '-';
                        $initial = collect(explode(' ', $nm))->map(fn ($w) => mb_substr($w, 0, 1))->slice(0, 2)->implode('');
                        $rkey = $roleOf($s);
                        $roleClass = match ($s->user?->role_id) {
                            3 => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                            4 => 'bg-secondary-container/20 text-secondary border-secondary/20',
                            5 => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                            default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                        };
                    @endphp
                    <article class="staff-row bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden"
                        data-id="{{ $s->store_staff_id }}"
                        data-status="{{ $s->status }}"
                        data-role="{{ $s->user?->role_id }}"
                        data-store="{{ $s->store_id }}"
                        data-search="{{ strtolower($nm . ' ' . ($u?->email ?? '') . ' ' . ($s->store->nama_toko ?? '')) }}">
                        <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">badge</span>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $initial }}</div>
                                <div class="min-w-0">
                                    <p class="font-title-md text-title-md text-on-surface truncate">{{ $nm }}</p>
                                    <p class="text-xs text-on-surface-variant truncate">{{ $u?->email ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $roleClass }} text-[9px] font-bold uppercase border shrink-0 whitespace-nowrap">{{ $rkey }}</span>
                        </div>
                        <dl class="space-y-2 font-body-md text-sm mb-4">
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Toko</dt>
                                <dd class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant text-[11px] text-right">{{ $s->store->nama_toko ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Ditugaskan</dt>
                                <dd class="text-on-surface text-right">{{ $s->tanggal_penugasan?->translatedFormat('d M Y') ?? '-' }}</dd>
                            </div>
                        </dl>
                        <div class="flex items-center gap-gutter">
                            <form method="POST" action="{{ route('superadmin.store-staff.update', $s->store_staff_id) }}" class="flex items-center gap-1.5 shrink-0">
                                @csrf
                                @method('PUT')
                                <div class="relative" id="rowStatus-m-{{ $s->store_staff_id }}-dd">
                                        <button type="button" data-dd-trigger id="rowStatus-m-{{ $s->store_staff_id }}-trigger" onclick="toggleDropdown('rowStatus-m-{{ $s->store_staff_id }}')" aria-haspopup="listbox" aria-expanded="false"
                                            class="flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-2 py-2 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer text-left min-w-[110px] {{ $s->status === 'aktif' ? 'text-secondary border-secondary/30' : 'text-error border-error/30' }}">
                                            <span id="rowStatus-m-{{ $s->store_staff_id }}-label" class="truncate">{{ $s->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span>
                                            <span class="material-symbols-outlined text-[14px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="rowStatus-m-{{ $s->store_staff_id }}-chevron">expand_more</span>
                                        </button>
                                        <div id="rowStatus-m-{{ $s->store_staff_id }}-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                                            class="hidden absolute left-0 top-full mt-1 w-full min-w-[130px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                                            <button type="button" role="option" aria-selected="{{ $s->status === 'aktif' ? 'true' : 'false' }}" data-dd-option="aktif" onclick="selectRowStatus({{ $s->store_staff_id }}, 'aktif')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                Aktif<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'aktif' ? '' : 'hidden' }}">check</span>
                                            </button>
                                            <button type="button" role="option" aria-selected="{{ $s->status === 'nonaktif' ? 'true' : 'false' }}" data-dd-option="nonaktif" onclick="selectRowStatus({{ $s->store_staff_id }}, 'nonaktif')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                Nonaktif<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'nonaktif' ? '' : 'hidden' }}">check</span>
                                            </button>
                                        </div>
                                        <input type="hidden" name="status" value="{{ $s->status }}" />
                                    </div>
                                <button type="submit" class="inline-flex items-center px-2.5 py-2 rounded-lg bg-deep-onyx text-on-primary text-[10px] font-bold uppercase tracking-wider">Simpan</button>
                            </form>
                            <button type="button" onclick="openDetail({{ $s->store_staff_id }})" class="flex-1 min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                            </button>
                        </div>
                    </article>
                @empty
                    <p class="text-center text-on-surface-variant py-10">Belum ada staff toko.</p>
                @endforelse
                <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada staff yang cocok.</p>
            </div>

            <!-- Empty Search State -->
            <div id="empty-search" class="hidden flex-col items-center py-12 text-center gap-3">
                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada staff yang cocok.</p>
                <button type="button" onclick="resetFilter()" class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
            </div>

            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
                Staff dapat ditugaskan ke satu toko. Penugasan tidak menghapus akun user, hanya menonaktifkan akses ke toko.
            </p>
        </div>
    </section>
</div>

<!-- Detail Modal -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-detail',
    'dataModal' => true,
    'icon' => 'badge',
    'title' => 'Detail Staff',
    'subtitle' => '<span id="detail-nama">-</span>',
    'subtitleRaw' => true,
])
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-medium text-on-surface-variant mb-1">Nama</label>
            <p id="detail-nama-lengkap" class="text-sm font-semibold text-on-surface">-</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-on-surface-variant mb-1">Email</label>
            <p id="detail-email" class="text-sm font-semibold text-on-surface">-</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-medium text-on-surface-variant mb-1">Role</label>
            <p id="detail-role" class="text-sm font-semibold text-on-surface">-</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-on-surface-variant mb-1">Status</label>
            <p id="detail-status" class="text-sm font-semibold">-</p>
        </div>
    </div>
    <div>
        <label class="block text-xs font-medium text-on-surface-variant mb-1">Toko</label>
        <p id="detail-toko" class="text-sm font-semibold text-on-surface">-</p>
    </div>
    <div>
        <label class="block text-xs font-medium text-on-surface-variant mb-1">Tanggal Penugasan</label>
        <p id="detail-tanggal" class="text-sm font-semibold text-on-surface">-</p>
    </div>
    @slot('footer')
        <button type="button" data-modal-close class="btn-modal btn-modal-ghost w-full">Tutup</button>
    @endslot
@endcomponent

<!-- Tambah Staff Modal -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-tambah-staff',
    'dataModal' => true,
    'icon' => 'person_add',
    'title' => 'Tambah Staff',
    'subtitle' => 'Tugaskan user yang sudah ada ke toko.',
])
    <form method="POST" action="{{ route('superadmin.store-staff.store') }}" id="tambah-staff-form" class="space-y-5">
        @csrf
        <div>
            <label class="block raliva-label mb-2">Pilih Toko</label>
            <div class="relative" id="formStore-dd">
                <button type="button" data-dd-trigger id="formStore-trigger" onclick="toggleDropdown('formStore')" aria-haspopup="listbox" aria-expanded="false"
                    class="w-full flex items-center justify-between gap-2 bg-surface-container-lowest border border-muted-border rounded-lg px-3.5 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200 cursor-pointer text-left">
                    <span id="formStore-label" class="truncate">-- Pilih Toko --</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="formStore-chevron">expand_more</span>
                </button>
                <div id="formStore-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                    class="hidden absolute left-0 top-full mt-2 w-full min-w-[240px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-y-auto max-h-64 py-1">
                    @foreach ($stores as $store)
                        <button type="button" role="option" aria-selected="false" data-dd-option="{{ $store->store_id }}" onclick="selectFormStore('{{ $store->store_id }}')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                            {{ $store->nama_toko }}<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="store_id" id="formStore" value="" required />
            </div>
        </div>
        <div>
            <label class="block raliva-label mb-2">Pilih User</label>
            <div class="relative" id="formUser-dd">
                <button type="button" data-dd-trigger id="formUser-trigger" onclick="toggleDropdown('formUser')" aria-haspopup="listbox" aria-expanded="false"
                    class="w-full flex items-center justify-between gap-2 bg-surface-container-lowest border border-muted-border rounded-lg px-3.5 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200 cursor-pointer text-left">
                    <span id="formUser-label" class="truncate">-- Pilih User --</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="formUser-chevron">expand_more</span>
                </button>
                <div id="formUser-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                    class="hidden absolute left-0 top-full mt-2 w-full min-w-[260px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-y-auto max-h-64 py-1">
                    @foreach ($users as $user)
                        <button type="button" role="option" aria-selected="false" data-dd-option="{{ $user->user_id }}" onclick="selectFormUser('{{ $user->user_id }}')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                            <span class="truncate">{{ $user->nama_lengkap }} ({{ $roleLabel[$user->role_id] ?? '-' }})</span><span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden shrink-0">check</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="user_id" id="formUser" value="" required />
            </div>
            <p class="text-xs text-on-surface-variant mt-1">Hanya user dengan role Admin, Produksi, atau Gudang yang muncul.</p>
        </div>
    </form>
    @slot('footer')
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter">
            <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
            <button type="submit" form="tambah-staff-form" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[16px]">person_add</span>Tugaskan Staff</button>
        </div>
    @endslot
@endcomponent
@endsection

@push('scripts')
@include('SuperAdmin.partials.dd-helpers')
<script>
    // === MODAL SYSTEM ===
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
        document.body.style.overflow = '';
    }
    function openAddStaffModal() {
        closeDropdown('formStore');
        closeDropdown('formUser');
        ddSet('formStore', '', '-- Pilih Toko --');
        ddSet('formUser', '', '-- Pilih User --');
        openModal('modal-tambah-staff');
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAllDropdowns();
            document.querySelectorAll('[id^="modal-"]').forEach(m => {
                if (!m.classList.contains('hidden')) {
                    m.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
    });

    // === FILTER & SEARCH ===
    const allData = @json($staffJson);
    const storeLabelMap = @json($stores->pluck('nama_toko', 'store_id'));
    const userLabelMap = @json($users->mapWithKeys(fn ($u) => [$u->user_id => $u->nama_lengkap . ' (' . ($roleLabel[$u->role_id] ?? '-') . ')']));

    function selectFilterStatus(v) {
        const labels = { '': 'Semua Status', aktif: 'Aktif', nonaktif: 'Nonaktif' };
        ddSet('filterStatus', v, labels[v] ?? labels['']);
    }
    function selectFilterRole(v) {
        const labels = { '': 'Semua Role', admin: 'Admin Toko', produksi: 'Produksi', gudang: 'Gudang' };
        ddSet('filterRole', v, labels[v] ?? labels['']);
    }
    function selectFilterToko(v) {
        ddSet('filterToko', v, v === '' ? 'Semua Toko' : (storeLabelMap[v] ?? 'Semua Toko'));
    }

    function applyRowStatusClass(id, status) {
        ['rowStatus-' + id, 'rowStatus-m-' + id].forEach((suffix) => {
            const trigger = document.getElementById(suffix + '-trigger');
            if (!trigger) return;
            trigger.classList.remove('text-secondary', 'text-error', 'border-secondary/30', 'border-error/30');
            trigger.classList.add.apply(trigger.classList, status === 'aktif' ? ['text-secondary', 'border-secondary/30'] : ['text-error', 'border-error/30']);
        });
    }
    function selectRowStatus(id, status) {
        ddSet('rowStatus-' + id, status, status === 'aktif' ? 'Aktif' : 'Nonaktif');
        ddSet('rowStatus-m-' + id, status, status === 'aktif' ? 'Aktif' : 'Nonaktif');
        applyRowStatusClass(id, status);
    }

    function selectFormStore(v) {
        ddSet('formStore', v, v === '' ? '-- Pilih Toko --' : (storeLabelMap[v] ?? '-- Pilih Toko --'));
    }
    function selectFormUser(v) {
        ddSet('formUser', v, v === '' ? '-- Pilih User --' : (userLabelMap[v] ?? '-- Pilih User --'));
    }
    document.getElementById('tambah-staff-form')?.addEventListener('submit', (e) => {
        if (!document.getElementById('formStore').value || !document.getElementById('formUser').value) {
            e.preventDefault();
            window.showRalivaToast?.('Pilih toko dan user terlebih dahulu.', 'error');
        }
    });

    function applyFilter() {
        const search = document.getElementById('searchInput').value.toLowerCase().trim();
        const status = document.getElementById('filterStatus').value;
        const role = document.getElementById('filterRole').value;
        const toko = document.getElementById('filterToko').value;

        const rows = document.querySelectorAll('.staff-row');
        let visible = 0;

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowRole = row.dataset.role;
            const rowStore = row.dataset.store;
            const rowSearch = row.dataset.search;

            let show = true;
            if (status && rowStatus !== status) show = false;
            if (role && rowRole !== role) show = false;
            if (toko && rowStore !== toko) show = false;
            if (search && !rowSearch.includes(search)) show = false;

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('empty-search').style.display = visible === 0 ? 'flex' : 'none';
        const em = document.getElementById('empty-search-mobile');
        if (em) em.style.display = visible === 0 ? 'block' : 'none';
        document.querySelector('[data-table-wrap]')?.closest('div')?.querySelector('table')?.closest('div')?.parentElement?.querySelector('.overflow-x-auto')?.style?.setProperty('display', visible === 0 ? 'none' : '');

        // Update count
        const countEl = document.getElementById('review-count');
        if (countEl) countEl.textContent = visible + ' staff';
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        selectFilterStatus('');
        selectFilterRole('');
        selectFilterToko('');
        applyFilter();
    }

    // === DETAIL MODAL ===
    function openDetail(id) {
        const data = allData.find(d => d.id === id);
        if (!data) return;
        document.getElementById('detail-nama').textContent = data.nama;
        document.getElementById('detail-nama-lengkap').textContent = data.nama;
        document.getElementById('detail-email').textContent = data.email;
        document.getElementById('detail-role').textContent = data.role_label;
        document.getElementById('detail-toko').textContent = data.toko;
        document.getElementById('detail-tanggal').textContent = data.tanggal;
        const statusEl = document.getElementById('detail-status');
        statusEl.textContent = data.status === 'aktif' ? 'Aktif' : 'Nonaktif';
        statusEl.className = 'text-sm font-semibold ' + (data.status === 'aktif' ? 'text-success' : 'text-error');
        openModal('modal-detail');
    }
</script>
@endpush
