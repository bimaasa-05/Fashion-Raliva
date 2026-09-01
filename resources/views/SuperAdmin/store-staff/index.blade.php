@extends('layouts.superadmin')

@section('title', 'Staff Toko')
@section('header-title', 'Staff Toko')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola penugasan staff toko di seluruh platform. Tugaskan, ubah status, dan nonaktifkan staff.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(201, 162, 77, 0.15); border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; }
    .staff-row { transition: all 0.2s ease; }
    .staff-row:hover { background-color: rgba(201, 162, 77, 0.04); }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
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
    <section class="rise rise-d1">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Staff Toko</h2>
                    <p class="text-xs text-on-surface-variant mt-1">Semua staff yang ditugaskan di seluruh toko.</p>
                </div>
                <button type="button" onclick="openModal('modal-tambah-staff')" class="py-2.5 px-5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>Tambah Staff
                </button>
            </div>

            <!-- Filters -->
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" id="searchInput" placeholder="Cari nama staff atau toko..." class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" oninput="applyFilter()" />
                </div>
                <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                    <select id="filterStatus" class="raliva-select lg:w-40" onchange="applyFilter()">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                    <select id="filterRole" class="raliva-select lg:w-40" onchange="applyFilter()">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin Toko</option>
                        <option value="produksi">Produksi</option>
                        <option value="gudang">Gudang</option>
                    </select>
                    <select id="filterToko" class="raliva-select lg:w-44" onchange="applyFilter()">
                        <option value="">Semua Toko</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->store_id }}">{{ $store->nama_toko }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="resetFilter()" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">No</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Staff</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Role</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Toko</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Ditugaskan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Aksi</th>
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
                                    <form method="POST" action="{{ route('superadmin.store-staff.update', $s->store_staff_id) }}" class="inline-flex">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer {{ $s->status === 'aktif' ? 'text-secondary border-secondary/30' : 'text-error border-error/30' }}">
                                            <option value="aktif" {{ $s->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ $s->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
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
<div id="modal-detail" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-detail')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Staff</p>
                <h3 id="detail-nama" class="font-title-md text-title-md text-on-surface premium-heading mt-1">-</h3>
            </div>
            <button type="button" onclick="closeModal('modal-detail')" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
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
        </div>
        <div class="px-6 pb-6">
            <button type="button" onclick="closeModal('modal-detail')" class="w-full py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>

<!-- Tambah Staff Modal -->
<div id="modal-tambah-staff" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah-staff')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Staff</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Tugaskan user yang sudah ada ke toko.</p>
            </div>
            <button type="button" onclick="closeModal('modal-tambah-staff')" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('superadmin.store-staff.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Pilih Toko</label>
                <select name="store_id" class="raliva-select" required>
                    <option value="">-- Pilih Toko --</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->store_id }}">{{ $store->nama_toko }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block raliva-label mb-2">Pilih User</label>
                <select name="user_id" class="raliva-select" required>
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->nama_lengkap }} ({{ $roleLabel[$user->role_id] ?? '-' }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-on-surface-variant mt-1">Hanya user dengan role Admin, Produksi, atau Gudang yang muncul.</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeModal('modal-tambah-staff')" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">person_add</span>Tugaskan Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
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
        document.querySelector('[data-table-wrap]')?.closest('div')?.querySelector('table')?.closest('div')?.parentElement?.querySelector('.overflow-x-auto')?.style?.setProperty('display', visible === 0 ? 'none' : '');

        // Update count
        const countEl = document.getElementById('review-count');
        if (countEl) countEl.textContent = visible + ' staff';
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterRole').value = '';
        document.getElementById('filterToko').value = '';
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
