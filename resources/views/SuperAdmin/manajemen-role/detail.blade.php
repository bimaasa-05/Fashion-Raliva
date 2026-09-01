@extends('layouts.superadmin')

@section('title', 'Detail Role - ' . $role->nama_role)
@section('header-title', $role->nama_role)
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Detail role dan pengelolaan hak akses')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .perm-toggle:checked { background-color: rgba(201, 162, 77, 1); border-color: rgba(201, 162, 77, 1); }
    .perm-toggle { transition: all 0.2s ease; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-on-surface-variant">
        <a href="{{ route('superadmin.manajemen-role') }}" class="hover:text-gold-accent transition-colors">Manajemen Role</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-on-surface font-medium">{{ $role->nama_role }}</span>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">shield</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="material-symbols-outlined text-[14px]">key</span>
                            {{ $role->permissions_count }} Permission
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-container/30 text-on-tertiary-container text-[10px] font-bold uppercase tracking-wider border border-tertiary-container/50">
                            <span class="material-symbols-outlined text-[14px]">group</span>
                            {{ $role->users_count }} User
                        </span>
                        @if($role->status === 'aktif')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    @if($role->deskripsi)
                        <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">{{ $role->deskripsi }}</p>
                    @endif
                </div>
                <a href="{{ route('superadmin.manajemen-role') }}" class="bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase px-8 py-4 tracking-widest rounded-lg hover:bg-tertiary-container transition-colors btn-premium inline-flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>
    </section>

    <!-- Permission Assignment -->
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 card-premium">
        <form method="POST" action="{{ route('superadmin.manajemen-role.permissions.update', $role) }}" id="permissions-form">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gold-accent text-[20px]">key</span>
                    </div>
                    <h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Hak Akses (Permission)</h2>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleAllPermissions(true)" class="px-4 py-2 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors">Pilih Semua</button>
                    <button type="button" onclick="toggleAllPermissions(false)" class="px-4 py-2 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors">Batal Semua</button>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($groupedPermissions as $groupName => $permissions)
                    @if($permissions->isNotEmpty())
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-2 border-b border-muted-border/60">
                                <span class="material-symbols-outlined text-gold-accent text-[18px]">
                                    @if(str_contains($groupName, 'User')) person
                                    @elseif(str_contains($groupName, 'Store')) storefront
                                    @elseif(str_contains($groupName, 'Product')) checkroom
                                    @elseif(str_contains($groupName, 'Order') || str_contains($groupName, 'Payment')) payments
                                    @elseif(str_contains($groupName, 'Shipment') || str_contains($groupName, 'Refund')) local_shipping
                                    @elseif(str_contains($groupName, 'Wallet') || str_contains($groupName, 'Withdrawal')) account_balance_wallet
                                    @elseif(str_contains($groupName, 'Warehouse') || str_contains($groupName, 'Production')) warehouse
                                    @elseif(str_contains($groupName, 'Promotion') || str_contains($groupName, 'Review')) local_offer
                                    @else support_agent
                                    @endif
                                </span>
                                <h3 class="font-label-sm text-label-sm text-on-surface uppercase tracking-widest">{{ $groupName }}</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($permissions as $perm)
                                    <label class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
                                        <span class="flex-1 min-w-0">
                                            <span class="block font-title-md text-title-sm text-on-surface">{{ $perm->nama_permission }}</span>
                                            <span class="block font-body-md text-xs text-on-surface-variant mt-0.5">{{ $perm->deskripsi }}</span>
                                            <span class="block font-body-md text-[10px] text-on-surface-variant/50 mt-0.5 font-mono">{{ $perm->kode_permission }}</span>
                                        </span>
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $perm->permission_id }}"
                                               class="perm-toggle w-5 h-5 accent-gold-accent shrink-0 rounded"
                                               {{ $role->permissions->contains('permission_id', $perm->permission_id) ? 'checked' : '' }} />
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-gutter pt-6 mt-6 border-t border-muted-border">
                <button type="button" onclick="document.getElementById('permissions-form').reset()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Reset</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Permission</button>
            </div>
        </form>
    </section>

    <!-- Users with this Role -->
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">group</span>
            </div>
            <h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">User dengan Role Ini</h2>
            <span class="text-on-surface-variant font-body-md text-sm ml-auto">{{ $role->users_count }} user</span>
        </div>

        @if($role->users->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @foreach($role->users as $user)
                    <div class="flex items-center gap-4 p-4 border border-muted-border rounded-lg hover:border-gold-accent transition-colors">
                        <div class="w-10 h-10 rounded-full bg-secondary-container/30 flex items-center justify-center shrink-0">
                            <span class="font-title-md text-title-sm text-secondary">{{ substr($user->nama_lengkap, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-title-md text-title-sm text-on-surface truncate">{{ $user->nama_lengkap }}</h4>
                            <p class="text-on-surface-variant text-xs truncate">{{ $user->email }}</p>
                        </div>
                        @if($user->status === 'aktif')
                            <span class="inline-flex px-2 py-0.5 rounded-full bg-success/10 text-success text-[9px] font-bold uppercase border border-success/20">Aktif</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Nonaktif</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">person_off</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Belum ada user dengan role ini.</p>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    function toggleAllPermissions(state) {
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = state);
    }
</script>
@endpush
