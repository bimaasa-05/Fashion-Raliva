@extends('layouts.superadmin')

@section('title', 'Manajemen Role')
@section('header-title', 'Manajemen Role')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola role dan hak akses pengguna platform')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(201, 162, 77, 0.15); border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">shield</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $stats['total'] }} Role
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            {{ $stats['aktif'] }} Aktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-container/30 text-on-tertiary-container text-[10px] font-bold uppercase tracking-wider border border-tertiary-container/50">
                            {{ $stats['total_permissions'] }} Permission
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Kelola role pengguna dan tentukan hak akses setiap role di platform Raliva.</p>
                </div>
                <button onclick="openCreateModal()" class="bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase px-8 py-4 tracking-widest rounded-lg hover:bg-tertiary-container transition-colors btn-premium inline-flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Role
                </button>
            </div>
        </div>
    </section>

    <!-- Role Grid -->
    <section class="rise rise-d2">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-4">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Role</h2>
            <span class="text-on-surface-variant font-body-md text-sm">{{ $roles->where('status', 'aktif')->count() }} aktif &bull; total {{ $roles->count() }} role</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter" id="role-grid">
            @forelse ($roles as $role)
                <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 card-premium"
                    data-id="{{ $role->role_id }}"
                    data-nama="{{ $role->nama_role }}"
                    data-deskripsi="{{ $role->deskripsi ?? '' }}"
                    data-status="{{ $role->status }}"
                    data-users-count="{{ $role->users_count }}"
                    data-permissions-count="{{ $role->permissions_count }}">
                    <!-- Decorative blob -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/15 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative">
                        <!-- Header: icon + status badge -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-full bg-secondary-container/30 flex items-center justify-center group-hover:scale-105 transition-transform border-2 border-surface-container-lowest shadow-sm">
                                <span class="material-symbols-outlined text-secondary text-[28px]">
                                    @if($role->nama_role === 'Super Admin') shield_person
                                    @elseif($role->nama_role === 'Owner') storefront
                                    @elseif($role->nama_role === 'Admin') admin_panel_settings
                                    @elseif($role->nama_role === 'Produksi') precision_manufacturing
                                    @elseif($role->nama_role === 'Gudang') warehouse
                                    @else person
                                    @endif
                                </span>
                            </div>
                            @if($role->status === 'aktif')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success/10 text-success text-[9px] font-bold uppercase tracking-wider border border-success/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase tracking-wider border border-error/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <!-- Name -->
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors mb-1">{{ $role->nama_role }}</h3>
                        <!-- Description -->
                        <p class="text-on-surface-variant text-sm line-clamp-2 mb-3">{{ $role->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        <!-- Stats -->
                        <div class="flex items-center gap-4 text-xs text-on-surface-variant">
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">group</span>
                                {{ $role->users_count }} user
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">key</span>
                                {{ $role->permissions_count }} permission
                            </span>
                        </div>
                    </div>
                    <!-- Action buttons footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border mt-4">
                        <a href="{{ route('superadmin.manajemen-role.detail', $role) }}" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Detail & Permission">
                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                        </a>
                        <div class="flex items-center gap-1">
                            <button onclick="event.stopPropagation(); openEditModal(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            @if($role->nama_role !== 'Super Admin')
                                <button onclick="event.stopPropagation(); toggleStatus(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-tertiary-container hover:bg-tertiary-container/10 transition-colors" title="{{ $role->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <span class="material-symbols-outlined text-[20px]">{{ $role->status === 'aktif' ? 'block' : 'check_circle' }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">shield</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm">Belum ada role.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<!-- Create/Edit Modal -->
<div id="modal-form-role" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" onclick="closeRoleModal()"></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <form method="POST" action="" id="role-form">
            @csrf
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="role-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Role Baru</h3>
                    <p id="role-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Buat role baru untuk pengguna platform</p>
                </div>
                <button type="button" onclick="closeRoleModal()" class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 space-y-5">
                @method('PUT')
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama_role">Nama Role</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="nama_role" name="nama_role" type="text" maxlength="50" placeholder="Contoh: Marketing" required />
                    @error('nama_role')<p class="font-body-md text-xs text-error mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="deskripsi">Deskripsi</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="deskripsi" name="deskripsi" rows="3" maxlength="255" placeholder="Deskripsi singkat tentang role ini..."></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" onclick="closeRoleModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="role-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Role</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Toggle Status Confirmation -->
<div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="toggleStatusModal" onclick="if (event.target === this) closeToggleModal()">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="w-14 h-14 rounded-full bg-tertiary-container/30 border border-tertiary-container/50 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-tertiary-container text-[28px]">warning</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Ubah Status Role</h3>
            <p class="text-on-surface-variant text-sm text-center mb-4">
                Role <span id="toggle-nama" class="font-bold text-on-surface">-</span> akan di<span id="toggle-action">nonaktifkan</span>.
            </p>
            <form method="POST" action="" id="toggle-status-form">
                @csrf
                @method('PUT')
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeToggleModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-tertiary-container text-on-tertiary-container font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const roleUrls = {
        store: '{{ route("superadmin.manajemen-role.store") }}',
        update: (id) => '{{ route("superadmin.manajemen-role.update", ":id:") }}'.replace(":id:", id),
        toggleStatus: (id) => '{{ route("superadmin.manajemen-role.toggle-status", ":id:") }}'.replace(":id:", id),
    };

    function openCreateModal() {
        document.getElementById('role-modal-title').textContent = 'Tambah Role Baru';
        document.getElementById('role-modal-sub').textContent = 'Buat role baru untuk pengguna platform';
        document.getElementById('nama_role').value = '';
        document.getElementById('deskripsi').value = '';
        document.getElementById('role-form').action = roleUrls.store;
        document.getElementById('role-submit-btn').textContent = 'Tambah Role';
        document.getElementById('modal-form-role').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('nama_role').focus(), 100);
    }

    function openEditModal(card) {
        const d = card.dataset;
        document.getElementById('role-modal-title').textContent = 'Ubah Role';
        document.getElementById('role-modal-sub').textContent = 'Perbarui data role ' + d.nama;
        document.getElementById('nama_role').value = d.nama;
        document.getElementById('deskripsi').value = d.deskripsi;
        document.getElementById('role-form').action = roleUrls.update(d.id);
        document.getElementById('role-submit-btn').textContent = 'Simpan Perubahan';
        document.getElementById('modal-form-role').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('nama_role').focus(), 100);
    }

    function closeRoleModal() {
        document.getElementById('modal-form-role').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function toggleStatus(card) {
        const d = card.dataset;
        const isActive = d.status === 'aktif';
        document.getElementById('toggle-nama').textContent = d.nama;
        document.getElementById('toggle-action').textContent = isActive ? 'nonaktifkan' : 'aktifkan';
        document.getElementById('toggle-status-form').action = roleUrls.toggleStatus(d.id);
        const modal = document.getElementById('toggleStatusModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeToggleModal() {
        const modal = document.getElementById('toggleStatusModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeRoleModal();
            closeToggleModal();
        }
    });
</script>
@endpush
