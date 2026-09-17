@extends('layouts.superadmin')

@section('title', 'Manajemen Pengguna')

@section('header-title', 'Manajemen Pengguna')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola seluruh pengguna terdaftar di platform Raliva.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    .text-gradient-gold {
        background: linear-gradient(115deg, #6D1428 0%, #8B1E3F 35%, #c03a5a 55%, #8B1E3F 80%, #6D1428 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-glow::before {
        content: '';
        position: absolute;
        inset: -30%;
        background: radial-gradient(circle at 70% 30%, rgba(139, 30, 63, 0.14), transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(139, 30, 63, 0.08), transparent 40%);
        pointer-events: none;
    }

    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(139, 30, 63, 0.5); color: #8B1E3F; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(139, 30, 63, 0.15); border-color: rgba(139, 30, 63, 0.5); color: #8B1E3F; }

    /* ── Premium user cards ── */
    .user-card { position: relative; }
    .user-card::before {
        content: '';
        position: absolute; inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(140deg, rgba(139, 30, 63,0) 0%, rgba(139, 30, 63,0.45) 50%, rgba(139, 30, 63,0) 100%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor; mask-composite: exclude;
        opacity: 0; transition: opacity .3s ease; pointer-events: none;
    }
    .user-card:hover::before { opacity: 1; }
    .user-avatar-ring {
        background: linear-gradient(135deg, rgba(139, 30, 63,0.25), rgba(139, 30, 63,0.10));
        box-shadow: 0 6px 18px -6px rgba(139, 30, 63,0.5);
    }
    .role-badge {
        background: linear-gradient(135deg, rgba(139, 30, 63,0.16), rgba(139, 30, 63,0.05));
        border: 1px solid rgba(139, 30, 63,0.30);
        color: #8B1E3F;
    }
    @keyframes pulseDot {
        0%   { box-shadow: 0 0 0 0 rgba(20,160,90,0.55); }
        70%  { box-shadow: 0 0 0 7px rgba(20,160,90,0); }
        100% { box-shadow: 0 0 0 0 rgba(20,160,90,0); }
    }
    .status-dot-pulse { animation: pulseDot 2s infinite; }
    .filter-chip { transition: all 0.2s ease; }

    .drawer-overlay { transition: opacity 0.3s ease; }
    .drawer-panel { transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1); }
    .drawer-panel.open { transform: translateX(0); }
    .drawer-panel.closed { transform: translateX(100%); }

    @keyframes riseIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .rise { opacity: 0; animation: riseIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .rise-d1 { animation-delay: 0.1s; }
    .rise-d2 { animation-delay: 0.2s; }
    .rise-d3 { animation-delay: 0.3s; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="w-full max-w-7xl mx-auto space-y-section-gap">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">group</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $stats['total'] }} Pengguna
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            {{ $stats['aktif'] }} Aktif
                        </span>
                        @if ($stats['nonaktif'] > 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                {{ $stats['nonaktif'] }} Non-aktif
                            </span>
                        @endif
                        @if ($stats['suspend'] > 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-container/30 text-on-tertiary-container text-[10px] font-bold uppercase tracking-wider border border-tertiary-container/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary-container"></span>
                                {{ $stats['suspend'] }} Suspended
                            </span>
                        @endif
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Kelola akun pengguna, tetapkan peran, dan pantau status seluruh anggota platform.</p>
                </div>
                <button type="button" onclick="openCreateModal()" class="bg-gold-accent text-white font-label-sm text-[11px] uppercase px-8 py-4 tracking-widest rounded-lg hover:opacity-90 transition-opacity btn-premium inline-flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah Pengguna
                </button>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="rise rise-d1">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium space-y-5">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="user-search" type="text" placeholder="Cari nama, email, atau nomor telepon..." value="{{ request('search') }}" />
                    <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest">Urutkan:</span>
                    <select id="sort-select" class="bg-surface-container-low border border-muted-border rounded-lg px-3 py-3 font-label-sm text-[11px] uppercase tracking-wide text-on-surface focus:outline-none focus:border-gold-accent transition-colors">
                        <option value="nama_asc">Nama A-Z</option>
                        <option value="nama_desc">Nama Z-A</option>
                        <option value="role">Peran</option>
                        <option value="status">Status</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-muted-border/60 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-wrap gap-2 items-center" data-filter-group="role">
                    <span class="text-gold-accent material-symbols-outlined text-[16px]">badge</span>
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest self-center mr-1">Peran:</span>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide active" data-filter="role" data-value="">Semua</button>
                    @foreach ($roles as $role)
                        <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="role" data-value="{{ $role->nama_role }}">{{ $role->nama_role }}</button>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-2 items-center" data-filter-group="status">
                    <span class="text-gold-accent material-symbols-outlined text-[16px]">toggle_on</span>
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest self-center mr-1">Status:</span>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide active" data-filter="status" data-value="">Semua</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="status" data-value="aktif">Aktif</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="status" data-value="nonaktif">Non-aktif</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="status" data-value="suspend">Suspend</button>
                </div>
            </div>
        </div>
    </section>

    <!-- User List (Kartu / Tabel) -->
    <section class="rise rise-d2">
        <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Pengguna</h2>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider text-gold-accent">
                    <span class="material-symbols-outlined text-[14px]">group</span>
                    {{ $users->total() }} pengguna
                </span>
            </div>
            <div class="inline-flex items-center gap-1 p-1 rounded-lg bg-surface-container border border-muted-border" id="view-toggle" role="tablist" aria-label="Mode tampilan">
                <button type="button" data-view="kartu" class="view-toggle-btn inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-md font-label-sm text-[11px] uppercase tracking-widest transition-colors" role="tab">
                    <span class="material-symbols-outlined text-[16px]">grid_view</span> Kartu
                </button>
                <button type="button" data-view="tabel" class="view-toggle-btn inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-md font-label-sm text-[11px] uppercase tracking-widest transition-colors" role="tab">
                    <span class="material-symbols-outlined text-[16px]">table_rows</span> Tabel
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter hidden" id="view-kartu">
            @forelse ($users as $u)
                <div class="user-card group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer card-premium"
                    data-id="{{ $u->user_id }}"
                    data-role="{{ $u->role->nama_role ?? '' }}"
                    data-status="{{ $u->status }}"
                    data-name="{{ $u->nama_lengkap }}"
                    data-email="{{ $u->email }}"
                    data-phone="{{ $u->nomor_telepon ?? '' }}"
                    data-verified="{{ $u->email_verified_at ? 'true' : 'false' }}"
                    data-role-id="{{ $u->role_id }}"
                    data-initial="{{ strtoupper(mb_substr($u->nama_lengkap, 0, 2)) }}"
                    data-role-label="{{ $u->role->nama_role ?? '' }}"
                    onclick="openUserDetail(this)">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/15 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative flex items-start gap-4">
                        <div class="user-avatar-ring w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform border-2 border-surface-container-lowest shadow-sm">
                            @if ($u->foto_profil_url)
                                <img src="{{ $u->foto_profil_url }}" class="w-14 h-14 rounded-full object-cover" alt="{{ $u->nama_lengkap }}" />
                            @else
                                <span class="font-title-md text-title-md text-gold-accent">{{ strtoupper(mb_substr($u->nama_lengkap, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">{{ $u->nama_lengkap }}</h3>
                            <p class="text-on-surface-variant text-sm truncate">{{ $u->email }}</p>
                            <div class="flex items-center gap-2 mt-3 flex-wrap">
                                <span class="role-badge inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">{{ $u->role->nama_role ?? '-' }}</span>
                                @if ($u->status === \App\Models\User::STATUS_AKTIF)
                                    <span class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success status-dot-pulse"></span>Aktif
                                    </span>
                                @elseif ($u->status === \App\Models\User::STATUS_SUSPEND)
                                    <span class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-tertiary-container/30 text-on-tertiary-container border border-tertiary-container/50 text-[9px] font-bold uppercase">Suspend</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Non-aktif</span>
                                @endif
                                @if ($u->email_verified_at)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">
                                        <span class="material-symbols-outlined text-[12px]">verified</span>Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">
                                        <span class="material-symbols-outlined text-[12px]">email</span>Belum Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                        <button type="button" onclick="event.stopPropagation(); openEditModal(this.closest('[data-id]'))" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-gold-accent" title="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                    </div>
                    <div class="relative mt-4 pt-4 border-t border-muted-border/60 flex flex-col gap-2">
                        <span class="inline-flex items-center gap-1.5 text-on-surface-variant text-xs w-full">
                            <span class="material-symbols-outlined text-[14px] shrink-0">key</span>
                            @if ($u->password)
                                <span class="font-mono break-all" title="{{ $u->password }}">{{ $u->password }}</span>
                                <button type="button" onclick="event.stopPropagation(); copyPassword(this)" data-pw="{{ $u->password }}" class="hover:text-gold-accent transition-colors shrink-0 ml-auto" title="Salin password (hash DB)">
                                    <span class="material-symbols-outlined text-[14px]">content_copy</span>
                                </button>
                            @else
                                <span class="text-on-surface-variant/60">—</span>
                            @endif
                        </span>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-on-surface-variant text-xs truncate">
                                <span class="material-symbols-outlined text-[14px]">call</span>
                                {{ $u->nomor_telepon ?? 'No. telepon -' }}
                            </span>
                            <button type="button" onclick="event.stopPropagation(); openHapusModal(this.closest('[data-id]'))" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded-lg hover:bg-error/10 text-on-surface-variant hover:text-error" title="Hapus">
                                <span class="material-symbols-outlined text-[18px]">delete_outline</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16" id="empty-state-static">
                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">group_off</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengguna ditemukan.</p>
                </div>
            @endforelse
            {{-- Empty state saat filter tidak cocok (disembunyikan bila ada hasil) --}}
            <div class="col-span-full text-center py-16 hidden" id="empty-state-filter">
                <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengguna yang cocok dengan filter.</p>
                <button type="button" onclick="resetUserFilters()" class="mt-3 px-5 py-2 rounded-lg border border-gold-accent/40 text-gold-accent font-label-sm text-[11px] uppercase tracking-widest hover:bg-gold-accent/10 transition-colors">Reset Filter</button>
            </div>
        </div>

        <!-- Mode Tabel -->
        <div id="view-tabel">
            <div class="border border-muted-border bg-surface-container-lowest rounded-xl overflow-x-auto card-premium">
                <table class="w-full text-left border-collapse premium-table min-w-[900px]">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low/50">
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap text-center w-14">No.</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Pengguna</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Telepon</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Password</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Peran</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Status</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Verifikasi</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="user-tbody">
                        @forelse ($users as $u)
                            <tr class="user-tr border-b border-muted-border last:border-0 hover:bg-surface-container-low/40 transition-colors cursor-pointer"
                                data-id="{{ $u->user_id }}"
                                data-role="{{ $u->role->nama_role ?? '' }}"
                                data-status="{{ $u->status }}"
                                data-name="{{ $u->nama_lengkap }}"
                                data-email="{{ $u->email }}"
                                data-phone="{{ $u->nomor_telepon ?? '' }}"
                                data-verified="{{ $u->email_verified_at ? 'true' : 'false' }}"
                                data-role-id="{{ $u->role_id }}"
                                data-initial="{{ strtoupper(mb_substr($u->nama_lengkap, 0, 2)) }}"
                                data-role-label="{{ $u->role->nama_role ?? '' }}"
                                onclick="openUserDetail(this)">
                                <td class="p-4 text-center text-on-surface-variant font-mono">{{ ($users->firstItem() ?? 0) + $loop->iteration - 1 }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-accent/20 to-gold-accent/5 border border-gold-accent/20 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            @if ($u->foto_profil_url)
                                                <img src="{{ $u->foto_profil_url }}" class="w-9 h-9 rounded-full object-cover" alt="{{ $u->nama_lengkap }}" />
                                            @else
                                                <span class="font-title-md text-sm text-gold-accent">{{ strtoupper(mb_substr($u->nama_lengkap, 0, 2)) }}</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-title-md text-sm text-on-surface truncate">{{ $u->nama_lengkap }}</p>
                                            <p class="text-xs text-on-surface-variant truncate">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-body-md text-sm text-on-surface-variant whitespace-nowrap">{{ $u->nomor_telepon ?? '-' }}</td>
                                <td class="p-4">
                                    <div class="inline-flex items-center gap-1.5 max-w-[240px]">
                                        @if ($u->password)
                                            <span class="font-mono text-xs text-on-surface whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $u->password }}">{{ $u->password }}</span>
                                            <button type="button" onclick="event.stopPropagation(); copyPassword(this)" data-pw="{{ $u->password }}" class="text-on-surface-variant hover:text-gold-accent transition-colors shrink-0" title="Salin password (hash DB)">
                                                <span class="material-symbols-outlined text-[14px]">content_copy</span>
                                            </button>
                                        @else
                                            <span class="text-on-surface-variant/50 text-xs">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4"><span class="role-badge inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">{{ $u->role->nama_role ?? '-' }}</span></td>
                                <td class="p-4">
                                    @if ($u->status === \App\Models\User::STATUS_AKTIF)
                                        <span class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success status-dot-pulse"></span>Aktif
                                        </span>
                                    @elseif ($u->status === \App\Models\User::STATUS_SUSPEND)
                                        <span class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-tertiary-container/30 text-on-tertiary-container border border-tertiary-container/50 text-[9px] font-bold uppercase">Suspend</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Non-aktif</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if ($u->email_verified_at)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">
                                            <span class="material-symbols-outlined text-[12px]">verified</span>Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">
                                            <span class="material-symbols-outlined text-[12px]">email</span>Belum Verified
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="inline-flex items-center gap-1 justify-end">
                                        <button type="button" onclick="event.stopPropagation(); openEditModal(this.closest('[data-id]'))" class="p-2 rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-gold-accent transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button type="button" onclick="event.stopPropagation(); openHapusModal(this.closest('[data-id]'))" class="p-2 rounded-lg hover:bg-error/10 text-on-surface-variant hover:text-error transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[18px]">delete_outline</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="table-empty-static" class="hidden">
                                <td colspan="8" class="p-8 text-center">
                                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                                        <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">group_off</span>
                                    </div>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengguna ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="table-empty-filter" class="hidden">
                            <td colspan="8" class="p-8 text-center">
                                <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                </div>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengguna yang cocok dengan filter.</p>
                                <button type="button" onclick="resetUserFilters()" class="mt-3 px-5 py-2 rounded-lg border border-gold-accent/40 text-gold-accent font-label-sm text-[11px] uppercase tracking-widest hover:bg-gold-accent/10 transition-colors">Reset Filter</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="mt-6 flex justify-center">{{ $users->links() }}</div>
        @endif
    </section>
</div>

<!-- Drawer Detail Pengguna -->
<div id="user-drawer-overlay" class="drawer-overlay fixed inset-0 z-[60] bg-black/50 backdrop-blur-[2px] hidden opacity-0" onclick="closeUserDetail()"></div>
<div id="user-drawer-panel" class="drawer-panel closed fixed top-0 right-0 z-[65] h-full w-full max-w-md bg-surface-container-lowest border-l border-muted-border shadow-2xl overflow-y-auto">
    <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-center justify-between px-6 py-4 border-b border-muted-border">
        <div class="flex items-center gap-2 min-w-0">
            <button type="button" id="drawer-back" onclick="onDrawerBack()" class="hidden items-center justify-center shrink-0 w-8 h-8 rounded-lg hover:bg-surface-container transition-colors text-on-surface-variant hover:text-gold-accent">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </button>
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pengguna</h3>
        </div>
        <button type="button" onclick="closeUserDetail()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
    </div>
    <div class="p-6 space-y-6">
        <div class="flex items-center gap-4">
            <div id="drawer-avatar" class="w-16 h-16 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 border-2 border-surface-container-lowest shadow-sm overflow-hidden">
                <span id="drawer-initial" class="font-title-lg text-title-lg text-white"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h4 id="drawer-name" class="font-title-md text-title-md text-on-surface truncate"></h4>
                <p id="drawer-email" class="text-on-surface-variant text-sm truncate"></p>
                <p id="drawer-phone" class="text-on-surface-variant text-xs mt-0.5"></p>
                <div class="flex items-center gap-2 mt-1.5">
                    <span id="drawer-role" class="inline-flex px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase"></span>
                    <span id="drawer-status" class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold uppercase"></span>
                    <span id="drawer-verified" class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase"></span>
                </div>
            </div>
        </div>

        <div id="drawer-toko-section" class="space-y-3 hidden">
            <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Toko yang Dimiliki</h5>
            <div id="drawer-toko-list" class="space-y-3"></div>
            <p id="drawer-no-toko" class="text-on-surface-variant/60 text-sm italic hidden">Belum memiliki toko</p>
        </div>

        <div id="drawer-penugasan-section" class="space-y-3 hidden">
            <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Karyawan di Toko Milik Owner</h5>
            <div id="drawer-penugasan-list" class="space-y-3"></div>
            <p id="drawer-no-penugasan" class="text-on-surface-variant/60 text-sm italic hidden">Belum ditugaskan ke toko manapun</p>
            <div id="drawer-warehouses-wrap" class="hidden space-y-2 pt-2 border-t border-muted-border/50">
                <p class="text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Penugasan Gudang</p>
                <div id="drawer-warehouses-list" class="space-y-2"></div>
            </div>
        </div>

        <div class="space-y-3">
            <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Aktivitas Terbaru</h5>
            <div id="drawer-aktivitas-list" class="space-y-3"></div>
            <p id="drawer-no-aktivitas" class="text-on-surface-variant/60 text-sm italic hidden">Belum ada aktivitas</p>
        </div>

        <div class="space-y-3 pt-4 border-t border-muted-border">
            <form method="POST" action="" id="role-form" class="space-y-3">
                @csrf
                @method('PUT')
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Ubah Peran</label>
                <select name="role_id" id="drawer-role-select" class="w-full bg-transparent border border-muted-border rounded-lg p-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent transition-colors">
                    @foreach ($roles as $role)
                        <option value="{{ $role->role_id }}">{{ $role->nama_role }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Perubahan</button>
            </form>

            <form method="POST" action="" id="nonaktifkan-form" onsubmit="return openConfirmNonaktifkan(event)">
                @csrf
                @method('PUT')
                <button type="submit" id="nonaktifkan-btn" class="w-full py-3 border border-error text-error font-label-sm text-[11px] uppercase tracking-widest rounded hover:bg-error/10 transition-colors">Nonaktifkan</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Nonaktifkan/Aktifkan (cascade info) -->
@component('SuperAdmin.partials.premium-confirm', [
    'id' => 'confirmNonaktifkanModal',
    'icon' => 'block',
    'iconWrapId' => 'confirm-nonaktifkan-icon',
    'iconSymId' => 'confirm-nonaktifkan-icon-sym',
    'zIndex' => 75,
    'close' => 'closeConfirmNonaktifkan',
    'dataModal' => true,
])
    <div class="p-6">
        <h3 id="confirm-nonaktifkan-title" class="font-title-md text-title-md text-on-surface mb-2 text-center">Nonaktifkan Pengguna?</h3>
        <p id="confirm-nonaktifkan-desc" class="text-on-surface-variant text-sm text-center mb-6">Status akan diubah dan efek cascade akan dijelaskan di sini.</p>
        <div class="flex space-x-3">
            <button type="button" class="flex-1 btn-modal btn-modal-ghost" onclick="closeConfirmNonaktifkan()">Batal</button>
            <button type="button" id="confirm-nonaktifkan-submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Lanjutkan</button>
        </div>
    </div>
@endcomponent

<!-- Modal Tambah/Edit Pengguna -->
<form method="POST" action="" id="user-form" onsubmit="closeUserModal()">
    @csrf
    @component('SuperAdmin.partials.premium-modal', [
        'id' => 'modal-form-user',
        'dataModal' => true,
        'close' => 'closeUserModal',
        'icon' => 'person_add',
        'title' => 'Tambah Pengguna Baru',
        'titleId' => 'user-modal-title',
        'subtitle' => 'Lengkapi data untuk membuat akun baru.',
        'subtitleId' => 'user-modal-sub',
    ])
        <div class="p-6 space-y-5">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-nama">Nama Lengkap</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="form-nama" name="nama_lengkap" type="text" maxlength="150" placeholder="Masukkan nama lengkap" required />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-email">Email</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="form-email" name="email" type="email" maxlength="150" placeholder="nama@email.com" required />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-phone">Nomor Telepon</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="form-phone" name="nomor_telepon" type="tel" maxlength="30" placeholder="+62 812-3456-7890" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-role">Peran</label>
                    <select class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent transition-colors" id="form-role" name="role_id" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_id }}">{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-status">Status</label>
                    <select class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent transition-colors" id="form-status" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                        <option value="suspend">Suspend</option>
                    </select>
                </div>
            </div>
            <div id="password-fields">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-password">Password</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="form-password" name="password" type="password" minlength="8" placeholder="Minimal 8 karakter" />
                    <p id="form-password-hint" class="text-on-surface-variant/60 text-xs mt-1 hidden">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
                <div class="mt-4">
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="form-password-confirm">Konfirmasi Password</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="form-password-confirm" name="password_confirmation" type="password" placeholder="Ulangi password" />
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeUserModal()" class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" id="user-submit-btn" class="btn-modal btn-modal-primary">Tambah Pengguna</button>
            </div>
        </div>
    @endcomponent
</form>

<!-- Modal Hapus Pengguna -->
<form method="POST" action="" id="hapus-user-form" onsubmit="closeHapusModal()">
    @csrf
    @method('DELETE')
    @component('SuperAdmin.partials.premium-confirm', [
        'id' => 'hapusUserModal',
        'icon' => 'delete_forever',
        'close' => 'closeHapusModal',
        'dataModal' => true,
    ])
        <div class="p-6">
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Pengguna</h3>
            <p class="text-on-surface-variant text-sm text-center mb-6">Pengguna <span id="hapus-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen dari sistem.</p>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 btn-modal btn-modal-ghost" onclick="closeHapusModal()">Batal</button>
                <button type="submit" class="flex-1 btn-modal btn-modal-danger">Ya, Hapus</button>
            </div>
        </div>
    @endcomponent
</form>
@endsection

@push('scripts')
<script>
    const urls = {
        detail: (id) => '{{ url("superadmin/manajemen-pengguna") }}/' + id + '/detail',
        role: (id) => '{{ url("superadmin/manajemen-pengguna") }}/' + id + '/role',
        nonaktifkan: (id) => '{{ url("superadmin/manajemen-pengguna") }}/' + id + '/nonaktifkan',
        store: '{{ route("superadmin.manajemen-pengguna.store") }}',
        update: (id) => '{{ url("superadmin/manajemen-pengguna") }}/' + id,
        destroy: (id) => '{{ url("superadmin/manajemen-pengguna") }}/' + id,
    };

    const rolesJson = @json($roles->pluck('role_id', 'nama_role'));
    let isEditMode = false;

    /* ── Detail Drawer ── */
    const drawerHistory = [];

    function renderDrawerAvatar(data) {
        const avatar = document.getElementById('drawer-avatar');
        avatar.innerHTML = '';
        if (data.foto_profil_url) {
            const img = document.createElement('img');
            img.src = data.foto_profil_url;
            img.alt = data.nama;
            img.className = 'w-16 h-16 rounded-full object-cover';
            avatar.appendChild(img);
        } else {
            const span = document.createElement('span');
            span.id = 'drawer-initial';
            span.className = 'font-title-lg text-title-lg text-white';
            span.textContent = data.initial;
            avatar.appendChild(span);
        }
    }

    function updateDrawerBackButton() {
        const btn = document.getElementById('drawer-back');
        if (drawerHistory.length > 0) btn.classList.remove('hidden');
        else btn.classList.add('hidden');
    }

    function onDrawerBack() {
        const prev = drawerHistory.pop();
        if (prev) fetchAndRender(prev);
        else closeUserDetail();
        updateDrawerBackButton();
    }

    function renderDrawer(data) {
        document.getElementById('drawer-name').textContent = data.nama;
        document.getElementById('drawer-email').textContent = data.email;
        document.getElementById('drawer-phone').textContent = data.nomor_telepon || '';
        document.getElementById('drawer-role').textContent = data.role;
        document.getElementById('drawer-status').textContent = data.status;
        document.getElementById('drawer-status').className = 'inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold uppercase ' + (data.status === 'aktif' ? 'bg-success/10 text-success border border-success/20' : data.status === 'suspend' ? 'bg-tertiary-container/30 text-on-tertiary-container border border-tertiary-container/50' : 'bg-error/10 text-error border border-error/20');
        const verified = data.is_verified ?? (data.email_verified_at != null);
        document.getElementById('drawer-verified').innerHTML = verified
            ? '<span class="material-symbols-outlined text-[10px]">verified</span>Verified'
            : '<span class="material-symbols-outlined text-[10px]">email</span>Belum Verified';
        document.getElementById('drawer-verified').className = 'inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase ' + (verified ? 'bg-secondary-container/20 text-secondary border border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border border-outline-variant');

        renderDrawerAvatar(data);

        const roleId = Object.entries(rolesJson).find(([k, v]) => v && k === data.role);
        if (roleId) document.getElementById('drawer-role-select').value = roleId[1];

        document.getElementById('role-form').action = urls.role(data.user_id);
        document.getElementById('nonaktifkan-form').action = urls.nonaktifkan(data.user_id);

        const nonaktifkanBtn = document.getElementById('nonaktifkan-btn');
        nonaktifkanBtn.textContent = data.status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan';
        nonaktifkanBtn.className = 'w-full py-3 border font-label-sm text-[11px] uppercase tracking-widest rounded transition-colors ' + (data.status === 'aktif' ? 'border-error text-error hover:bg-error/10' : 'border-success text-success hover:bg-success/10');

        document.getElementById('drawer-toko-list').innerHTML = '';
        document.getElementById('drawer-no-toko').classList.add('hidden');
        document.getElementById('drawer-penugasan-list').innerHTML = '';
        document.getElementById('drawer-no-penugasan').classList.add('hidden');
        document.getElementById('drawer-warehouses-wrap').classList.add('hidden');
        document.getElementById('drawer-warehouses-list').innerHTML = '';
        document.getElementById('drawer-aktivitas-list').innerHTML = '';
        document.getElementById('drawer-no-aktivitas').classList.add('hidden');

        const roleIcons = { 'Admin': 'admin_panel_settings', 'Produksi': 'precision_manufacturing', 'Gudang': 'warehouse' };
        const roleBadge = {
            'Admin': 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
            'Produksi': 'bg-secondary-container/20 text-secondary border-secondary/20',
            'Gudang': 'bg-surface-container-high text-on-surface-variant border-outline-variant'
        };

        const tokoSection = document.getElementById('drawer-toko-section');
        const showToko = data.show_toko ?? data.is_owner ?? (data.role === 'Owner');
        if (!showToko) {
            tokoSection.classList.add('hidden');
        } else {
            tokoSection.classList.remove('hidden');
            const tokoList = document.getElementById('drawer-toko-list');
            const noToko = document.getElementById('drawer-no-toko');
            tokoList.innerHTML = '';
            noToko.classList.add('hidden');
            if (data.toko && data.toko.length > 0) {
                data.toko.forEach(t => {
                    const karyawan = (t.karyawan || []).map(k => {
                        const icon = roleIcons[k.role] || 'badge';
                        const badge = roleBadge[k.role] || 'bg-surface-container-high text-on-surface-variant border-outline-variant';
                        const avatarHtml = k.foto
                            ? `<img src="${k.foto}" alt="${k.nama}" class="w-7 h-7 rounded-full object-cover shrink-0">`
                            : `<span class="w-7 h-7 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center text-[10px] font-bold text-on-surface shrink-0">${k.initial || ''}</span>`;
                        const dot = k.status === 'aktif'
                            ? '<span class="w-1.5 h-1.5 rounded-full bg-success status-dot-pulse shrink-0" title="Aktif"></span>'
                            : '<span class="w-1.5 h-1.5 rounded-full bg-error shrink-0" title="Nonaktif"></span>';
                        if (k.user_id) {
                            return `<li>
                                <button type="button" onclick="openKaryawanDetail(${k.user_id})" class="flex w-full items-center gap-2.5 px-2 -mx-2 py-1.5 rounded-lg hover:bg-surface-container-low transition-colors text-left group/row">
                                    ${avatarHtml}
                                    <span class="min-w-0 flex-1 truncate text-sm text-on-surface group-hover/row:underline group-hover/row:text-gold-accent transition-colors">${k.nama || '-'}</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-[9px] font-bold uppercase whitespace-nowrap shrink-0 ${badge}">
                                        <span class="material-symbols-outlined text-[11px]">${icon}</span>${k.role || '-'}
                                    </span>
                                    ${dot}
                                </button>
                            </li>`;
                        }
                        return `<li class="flex items-center gap-2.5 px-2 py-1.5">
                            ${avatarHtml}
                            <span class="min-w-0 flex-1 truncate text-sm text-on-surface">${k.nama || '-'}</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-[9px] font-bold uppercase whitespace-nowrap shrink-0 ${badge}">
                                <span class="material-symbols-outlined text-[11px]">${icon}</span>${k.role || '-'}
                            </span>
                            ${dot}
                        </li>`;
                    }).join('');
                    tokoList.innerHTML += `
                        <div class="rounded-lg border border-muted-border/50 bg-surface-container overflow-hidden">
                            <div class="flex items-center justify-between gap-3 p-3 border-b border-muted-border/50">
                                <div class="min-w-0">
                                    <p class="font-body-md text-sm text-on-surface font-medium truncate flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-gold-accent text-[16px]">storefront</span>${t.nama}
                                    </p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">${t.produk} produk • Rating ${t.rating}</p>
                                </div>
                                ${t.status ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border ${t.status === 'aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-error/10 text-error border-error/20'}">${t.status}</span>` : ''}
                            </div>
                            <div class="p-3">
                                <p class="text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Karyawan</p>
                                ${karyawan ? `<ul class="space-y-1">${karyawan}</ul>` : '<p class="text-xs text-on-surface-variant/60 italic">Belum ada karyawan</p>'}
                            </div>
                        </div>`;
                });
            } else {
                noToko.classList.remove('hidden');
            }
        }

        const penugasanSec = document.getElementById('drawer-penugasan-section');
        const showPenugasan = data.show_penugasan ?? data.is_staff ?? ['Admin', 'Produksi', 'Gudang'].includes(data.role);
        if (!showPenugasan) {
            penugasanSec.classList.add('hidden');
        } else {
            penugasanSec.classList.remove('hidden');
            const penugasanList = document.getElementById('drawer-penugasan-list');
            const noPenugasan = document.getElementById('drawer-no-penugasan');
            penugasanList.innerHTML = '';
            noPenugasan.classList.add('hidden');
            if (data.penugasan && data.penugasan.length > 0) {
                data.penugasan.forEach(t => {
                    const statusBadge = t.status_penugasan === 'aktif'
                        ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border bg-secondary-container/20 text-secondary border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary status-dot-pulse"></span>Aktif</span>'
                        : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border bg-error/10 text-error border-error/20">Nonaktif</span>';
                    const ownerClick = t.owner_user_id
                        ? `<button type="button" onclick="openKaryawanDetail(${t.owner_user_id})" class="font-semibold hover:underline hover:text-gold-accent transition-colors cursor-pointer">${t.owner_nama}</button>`
                        : `<span class="font-semibold">${t.owner_nama}</span>`;
                    penugasanList.innerHTML += `
                        <div class="rounded-lg border border-muted-border/50 bg-surface-container overflow-hidden">
                            <div class="flex items-center justify-between gap-3 p-3 border-b border-muted-border/50">
                                <div class="min-w-0">
                                    <p class="font-body-md text-sm text-on-surface font-medium truncate flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-gold-accent text-[16px]">storefront</span>${t.nama_toko}
                                    </p>
                                    <p class="text-xs text-on-surface-variant mt-0.5 truncate">Status toko: ${t.status_toko}</p>
                                </div>
                                ${statusBadge}
                            </div>
                            <div class="p-3">
                                <p class="text-sm text-on-surface flex items-start gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">badge</span>
                                    <span>Karyawan di Toko Milik Owner: ${ownerClick}</span>
                                </p>
                                <p class="text-xs text-on-surface-variant mt-1 ml-[26px] truncate">${t.owner_email || '-'}</p>
                                <p class="text-[10px] text-on-surface-variant mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">event</span>Ditugaskan sejak ${t.tanggal_penugasan || '-'}
                                </p>
                            </div>
                        </div>`;
                });
            } else {
                noPenugasan.classList.remove('hidden');
            }

            const warehousesWrap = document.getElementById('drawer-warehouses-wrap');
            const warehousesList = document.getElementById('drawer-warehouses-list');
            warehousesList.innerHTML = '';
            if (data.warehouses && data.warehouses.length > 0) {
                warehousesWrap.classList.remove('hidden');
                data.warehouses.forEach(w => {
                    const dot = w.status === 'aktif'
                        ? '<span class="w-1.5 h-1.5 rounded-full bg-secondary status-dot-pulse" title="Aktif"></span>'
                        : '<span class="w-1.5 h-1.5 rounded-full bg-error" title="Nonaktif"></span>';
                    warehousesList.innerHTML += `
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg border border-muted-border/40 bg-surface-container-lowest">
                            <span class="material-symbols-outlined text-gold-accent text-[18px] shrink-0">warehouse</span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-on-surface font-medium truncate">${w.gudang}</p>
                                <p class="text-xs text-on-surface-variant truncate">${w.store} &middot; Owner: ${w.owner} &middot; sejak ${w.tanggal || '-'}</p>
                            </div>
                            ${dot}
                        </div>`;
                });
            }
        }

        if (data.aktivitas && data.aktivitas.length > 0) {
            data.aktivitas.forEach(a => {
                document.getElementById('drawer-aktivitas-list').innerHTML += `
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 fill">schedule</span>
                        <div>
                            <p class="text-sm text-on-surface">${a.deskripsi}</p>
                            <p class="text-xs text-on-surface-variant">${a.tanggal}</p>
                        </div>
                    </div>`;
            });
        } else {
            document.getElementById('drawer-no-aktivitas').classList.remove('hidden');
        }
    }

    function fetchAndRender(userId) {
        return fetch(urls.detail(userId))
            .then(r => { if (!r.ok) throw r; return r.json(); })
            .then(data => renderDrawer(data))
            .catch(() => { window.showRalivaToast && showRalivaToast('Gagal memuat detail pengguna', 'error'); });
    }

    function openKaryawanDetail(userId) {
        const curForm = document.getElementById('role-form').action;
        const curMatch = curForm.match(/(\d+)\/role/);
        if (curMatch) drawerHistory.push(curMatch[1]);
        updateDrawerBackButton();
        fetchAndRender(userId);
    }

    function openUserDetail(card) {
        const d = card.dataset;
        drawerHistory.length = 0;
        updateDrawerBackButton();

        document.getElementById('drawer-name').textContent = d.name;
        document.getElementById('drawer-email').textContent = d.email;
        document.getElementById('drawer-phone').textContent = d.phone || '';
        document.getElementById('drawer-role').textContent = d.roleLabel;
        document.getElementById('drawer-status').textContent = d.status;
        document.getElementById('drawer-status').className = 'inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold uppercase ' + (d.status === 'aktif' ? 'bg-success/10 text-success border border-success/20' : d.status === 'suspend' ? 'bg-tertiary-container/30 text-on-tertiary-container border border-tertiary-container/50' : 'bg-error/10 text-error border border-error/20');
        document.getElementById('drawer-verified').innerHTML = d.verified === 'true'
            ? '<span class="material-symbols-outlined text-[10px]">verified</span>Verified'
            : '<span class="material-symbols-outlined text-[10px]">email</span>Belum Verified';
        document.getElementById('drawer-verified').className = 'inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase ' + (d.verified === 'true' ? 'bg-secondary-container/20 text-secondary border border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border border-outline-variant');

        document.getElementById('role-form').action = urls.role(d.id);
        document.getElementById('nonaktifkan-form').action = urls.nonaktifkan(d.id);

        const nonaktifkanBtn = document.getElementById('nonaktifkan-btn');
        nonaktifkanBtn.textContent = d.status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan';
        nonaktifkanBtn.className = 'w-full py-3 border font-label-sm text-[11px] uppercase tracking-widest rounded transition-colors ' + (d.status === 'aktif' ? 'border-error text-error hover:bg-error/10' : 'border-success text-success hover:bg-success/10');

        document.getElementById('drawer-toko-list').innerHTML = '';
        document.getElementById('drawer-no-toko').classList.add('hidden');
        document.getElementById('drawer-penugasan-list').innerHTML = '';
        document.getElementById('drawer-no-penugasan').classList.add('hidden');
        document.getElementById('drawer-warehouses-wrap').classList.add('hidden');
        document.getElementById('drawer-warehouses-list').innerHTML = '';
        document.getElementById('drawer-aktivitas-list').innerHTML = '';
        document.getElementById('drawer-no-aktivitas').classList.add('hidden');

        fetch(urls.detail(d.id))
            .then(r => r.json())
            .then(data => renderDrawer(data));

        const overlay = document.getElementById('user-drawer-overlay');
        const panel = document.getElementById('user-drawer-panel');
        overlay.classList.remove('hidden');
        setTimeout(() => { overlay.classList.remove('opacity-0'); panel.classList.remove('closed'); panel.classList.add('open'); }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeUserDetail() {
        drawerHistory.length = 0;
        updateDrawerBackButton();
        const overlay = document.getElementById('user-drawer-overlay');
        const panel = document.getElementById('user-drawer-panel');
        overlay.classList.add('opacity-0');
        panel.classList.remove('open');
        panel.classList.add('closed');
        setTimeout(() => { overlay.classList.add('hidden'); }, 300);
        document.body.style.overflow = '';
    }

    /* ── Create / Edit Modal ── */
    function openCreateModal() {
        isEditMode = false;
        document.getElementById('user-modal-title').textContent = 'Tambah Pengguna Baru';
        document.getElementById('user-modal-sub').textContent = 'Lengkapi data untuk membuat akun baru.';
        document.getElementById('user-submit-btn').textContent = 'Tambah Pengguna';
        document.getElementById('user-form').action = urls.store;
        document.getElementById('user-form').querySelector('[name="_method"]')?.remove();

        document.getElementById('form-nama').value = '';
        document.getElementById('form-email').value = '';
        document.getElementById('form-phone').value = '';
        document.getElementById('form-role').value = '{{ $roles->first()?->role_id }}';
        document.getElementById('form-status').value = 'aktif';
        document.getElementById('form-password').value = '';
        document.getElementById('form-password-confirm').value = '';
        document.getElementById('form-password').required = true;
        document.getElementById('form-password-hint').classList.add('hidden');

        document.getElementById('modal-form-user').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(card) {
        isEditMode = true;
        const d = card.dataset;

        document.getElementById('user-modal-title').textContent = 'Edit Pengguna';
        document.getElementById('user-modal-sub').textContent = 'Perbarui data pengguna "' + d.name + '".';
        document.getElementById('user-submit-btn').textContent = 'Simpan Perubahan';

        const form = document.getElementById('user-form');
        form.action = urls.update(d.id);
        if (!form.querySelector('[name="_method"]')) {
            const m = document.createElement('input');
            m.type = 'hidden';
            m.name = '_method';
            m.value = 'PUT';
            form.prepend(m);
        }

        document.getElementById('form-nama').value = d.name;
        document.getElementById('form-email').value = d.email;
        document.getElementById('form-phone').value = d.phone || '';
        document.getElementById('form-role').value = d.roleId;
        document.getElementById('form-status').value = d.status;
        document.getElementById('form-password').value = '';
        document.getElementById('form-password-confirm').value = '';
        document.getElementById('form-password').required = false;
        document.getElementById('form-password-hint').classList.remove('hidden');

        document.getElementById('modal-form-user').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeUserModal() {
        document.getElementById('modal-form-user').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ── Hapus Modal ── */
    function openHapusModal(card) {
        const d = card.dataset;
        document.getElementById('hapus-nama').textContent = d.name;
        document.getElementById('hapus-user-form').action = urls.destroy(d.id);

        const modal = document.getElementById('hapusUserModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        const modal = document.getElementById('hapusUserModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    /* ── Salin password (hash DB) ── */
    function copyPassword(btn) {
        const pw = btn.getAttribute('data-pw');
        if (!pw) return;
        const done = () => {
            const icon = btn.querySelector('.material-symbols-outlined');
            if (!icon) return;
            const old = icon.textContent;
            icon.textContent = 'check';
            btn.classList.add('text-success');
            setTimeout(() => { icon.textContent = old; btn.classList.remove('text-success'); }, 1500);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(pw).then(done).catch(() => fallbackCopy(pw, done));
        } else {
            fallbackCopy(pw, done);
        }
    }

    function fallbackCopy(text, done) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
        done();
    }

    /* ── Mode tampilan: Kartu / Tabel ── */
    const VIEW_KEY = 'raliva_user_view';

    function setViewMode(mode) {
        const kartu = document.getElementById('view-kartu');
        const tabel = document.getElementById('view-tabel');
        if (kartu) kartu.classList.toggle('hidden', mode !== 'kartu');
        if (tabel) tabel.classList.toggle('hidden', mode !== 'tabel');
        document.querySelectorAll('#view-toggle .view-toggle-btn').forEach(btn => {
            const active = btn.getAttribute('data-view') === mode;
            btn.classList.toggle('bg-gold-accent', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('btn-premium', active);
            btn.classList.toggle('shadow-sm', active);
            btn.classList.toggle('text-on-surface-variant', !active);
        });
        try { localStorage.setItem(VIEW_KEY, mode); } catch (e) {}
    }

    function initViewMode() {
        let mode = 'tabel';
        try { mode = localStorage.getItem(VIEW_KEY) || 'tabel'; } catch (e) {}
        setViewMode(mode);
    }

    document.querySelectorAll('#view-toggle .view-toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            setViewMode(btn.getAttribute('data-view'));
            applyUserFilters();
        });
    });
    initViewMode();

    /* ── Live filter & search (no reload) ── */
    const userState = { role: '', status: '', search: '', sort: 'nama_asc' };

    function applyUserFilters() {
        const grid = document.getElementById('view-kartu');
        const tbody = document.getElementById('user-tbody');
        if (!grid && !tbody) return;
        const cards = grid ? Array.from(grid.querySelectorAll('.user-card')) : [];
        const rows = tbody ? Array.from(tbody.querySelectorAll('tr.user-tr')) : [];
        const items = cards.concat(rows);
        const q = userState.search.trim().toLowerCase();
        let visible = 0;

        items.forEach(item => {
            const matchRole = !userState.role || item.dataset.role === userState.role;
            const matchStatus = !userState.status || item.dataset.status === userState.status;
            const hay = (item.dataset.name + ' ' + item.dataset.email + ' ' + item.dataset.phone).toLowerCase();
            const matchSearch = !q || hay.includes(q);
            const show = matchRole && matchStatus && matchSearch;
            item.classList.toggle('hidden', !show);
            if (show) visible++;
        });

        // Sort (kartu & baris tabel serentak)
        const sortFn = (a, b) => {
            switch (userState.sort) {
                case 'nama_desc': return b.dataset.name.localeCompare(a.dataset.name);
                case 'role': return a.dataset.role.localeCompare(b.dataset.role);
                case 'status': return a.dataset.status.localeCompare(b.dataset.status);
                default: return a.dataset.name.localeCompare(b.dataset.name);
            }
        };
        if (grid) cards.slice().sort(sortFn).forEach(c => grid.appendChild(c));
        if (tbody) rows.slice().sort(sortFn).forEach(r => tbody.appendChild(r));

        const filterEmpty = document.getElementById('empty-state-filter');
        if (filterEmpty) filterEmpty.classList.toggle('hidden', visible > 0);
        const staticEmpty = document.getElementById('empty-state-static');
        if (staticEmpty) staticEmpty.classList.toggle('hidden', visible > 0);
        const tFilter = document.getElementById('table-empty-filter');
        if (tFilter) tFilter.classList.toggle('hidden', visible > 0);
        const tStatic = document.getElementById('table-empty-static');
        if (tStatic) tStatic.classList.toggle('hidden', visible > 0);
    }

    function setChips(group, value) {
        document.querySelectorAll('[data-filter-group="' + group + '"] [data-filter]').forEach(btn => {
            const active = btn.getAttribute('data-value') === value;
            btn.classList.toggle('active', active);
            btn.classList.toggle('text-on-surface-variant', !active);
        });
    }

    function resetUserFilters() {
        userState.role = ''; userState.status = ''; userState.search = '';
        document.getElementById('user-search').value = '';
        document.getElementById('clear-search').classList.add('opacity-0');
        setChips('role', '');
        setChips('status', '');
        applyUserFilters();
    }

    // Wire search input (debounced)
    const searchInput = document.getElementById('user-search');
    if (searchInput) {
        let t;
        searchInput.addEventListener('input', () => {
            userState.search = searchInput.value;
            document.getElementById('clear-search').classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(t);
            t = setTimeout(applyUserFilters, 180);
        });
    }
    document.getElementById('clear-search')?.addEventListener('click', () => {
        searchInput.value = '';
        userState.search = '';
        document.getElementById('clear-search').classList.add('opacity-0');
        applyUserFilters();
    });
    document.getElementById('sort-select')?.addEventListener('change', (e) => {
        userState.sort = e.target.value;
        applyUserFilters();
    });
    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.addEventListener('click', () => {
            const f = btn.getAttribute('data-filter');
            const v = btn.getAttribute('data-value');
            userState[f] = v;
            setChips(f, v);
            applyUserFilters();
        });
    });

    /* ── Keyboard ── */
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeUserDetail();
            closeUserModal();
            closeHapusModal();
            closeConfirmNonaktifkan();
        }
    });

    let _pendingNonaktifkanForm = null;
    function openConfirmNonaktifkan(e) {
        e.preventDefault();
        const form = e.target.closest('form') || document.getElementById('nonaktifkan-form');
        const btn = document.getElementById('nonaktifkan-btn');
        const isAktif = (btn.textContent.trim() === 'Nonaktifkan');
        const nama = (document.getElementById('drawer-name')?.textContent || '').trim() || 'pengguna ini';
        const role = (document.getElementById('drawer-role')?.textContent || '').trim();
        const isOwner = role.toLowerCase() === 'owner';
        const tokoVisible = document.getElementById('drawer-toko-section') && !document.getElementById('drawer-toko-section').classList.contains('hidden');
        const tokoCount = document.querySelectorAll('#drawer-toko-list > *').length;

        const titleEl = document.getElementById('confirm-nonaktifkan-title');
        const descEl = document.getElementById('confirm-nonaktifkan-desc');
        const iconWrap = document.getElementById('confirm-nonaktifkan-icon');
        const iconSym = document.getElementById('confirm-nonaktifkan-icon-sym');
        const submitBtn = document.getElementById('confirm-nonaktifkan-submit');
        const modal = document.getElementById('confirmNonaktifkanModal');

        if (isAktif) {
            titleEl.textContent = 'Nonaktifkan Pengguna?';
            if (isOwner) {
                const info = tokoCount > 0 ? tokoCount + ' toko' : 'toko';
                descEl.innerHTML = 'Status <span class="font-bold text-on-surface">"' + nama + '"</span> akan menjadi <span class="font-bold text-error">nonaktif</span>. Jika ini akun <span class="font-bold">Owner</span>, <span class="font-bold">' + info + ' & staff terkait akan ikut dinonaktifkan</span> (bisa diaktifkan lagi).';
            } else {
                descEl.innerHTML = 'Status <span class="font-bold text-on-surface">"' + nama + '"</span> akan menjadi <span class="font-bold text-error">nonaktif</span>.';
            }
            iconWrap.className = 'w-12 h-12 rounded-full bg-error/25 border border-error/30 flex items-center justify-center';
            iconSym.className = 'material-symbols-outlined text-[24px] text-error';
            iconSym.textContent = 'block';
            submitBtn.className = 'flex-1 btn-modal btn-modal-danger';
            submitBtn.textContent = 'Ya, Nonaktifkan';
        } else {
            titleEl.textContent = 'Aktifkan Pengguna?';
            if (isOwner) {
                const info = tokoCount > 0 ? tokoCount + ' toko' : 'toko';
                descEl.innerHTML = 'Status <span class="font-bold text-on-surface">"' + nama + '"</span> akan menjadi <span class="font-bold text-success">aktif</span>. Jika ini akun <span class="font-bold">Owner</span>, <span class="font-bold">' + info + ' & staff terkait akan ikut diaktifkan</span>.';
            } else {
                descEl.innerHTML = 'Status <span class="font-bold text-on-surface">"' + nama + '"</span> akan menjadi <span class="font-bold text-success">aktif</span>.';
            }
            iconWrap.className = 'w-12 h-12 rounded-full bg-success/25 border border-success/30 flex items-center justify-center';
            iconSym.className = 'material-symbols-outlined text-[24px] text-success';
            iconSym.textContent = 'check_circle';
            submitBtn.className = 'flex-1 btn-modal btn-modal-success';
            submitBtn.textContent = 'Ya, Aktifkan';
        }

        _pendingNonaktifkanForm = form;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        return false;
    }
    function closeConfirmNonaktifkan() {
        const modal = document.getElementById('confirmNonaktifkanModal');
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
        _pendingNonaktifkanForm = null;
    }
    document.getElementById('confirm-nonaktifkan-submit')?.addEventListener('click', () => {
        if (_pendingNonaktifkanForm) _pendingNonaktifkanForm.submit();
    });
</script>
@endpush
