@extends('layouts.owner')

@section('title', 'Karyawan')

@section('header-title', 'Karyawan')
@section('header-badge', '6 Aktif')
@section('header-subtitle', 'Tambah, tugaskan, dan kelola tim Admin, Produksi, dan Gudang toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @foreach ([['Total Karyawan', $summary['total'], 'on-surface', 'groups'], ['Admin', $summary['admin'], 'secondary', 'admin_panel_settings'], ['Produksi & Gudang', $summary['produksi_gudang'], 'on-surface', 'precision_manufacturing'], ['Nonaktif', $summary['nonaktif'], 'error', 'person_off']] as $stat)
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ $stat[0] }}</span>
                <span class="raliva-figure text-[26px] text-{{ $stat[2] }}">{{ $stat[1] }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">{{ $stat[3] }}</span>
            </div>
        @endforeach
    </section>

    {{-- Tabel Karyawan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Karyawan</h2>
                <p class="text-xs text-on-surface-variant mt-1">Kelola tim Admin, Produksi, dan Gudang toko Anda.</p>
            </div>
            <button type="button" data-modal-open="modal-tambah-karyawan" class="py-2.5 px-5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px]">person_add</span>Tambah Karyawan
            </button>
        </div>

        {{-- Toolbar: 1 baris rapi — search kiri, filter kanan --}}
        <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari nama atau email..." data-table-search class="raliva-search" />
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                <select data-table-filter="role" class="raliva-select lg:w-44">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin Toko</option>
                    <option value="produksi">Produksi</option>
                    <option value="gudang">Gudang</option>
                </select>
                <select data-table-filter="status" class="raliva-select lg:w-44">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
                <button type="button" data-filter-reset class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[960px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Karyawan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Role</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Ditugaskan di</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bergabung</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $s)
                        @php
                            $u = $s->user;
                            $nm = $u?->nama_lengkap ?? '-';
                            $initial = collect(explode(' ', $nm))->map(fn($w)=>mb_substr($w,0,1))->slice(0,2)->implode('');
                            $rkey = \App\Http\Controllers\Owner\KaryawanController::ROLE_MAP[$u?->role_id] ?? 'lainnya';
                            $rlabel = $roleLabel[$rkey] ?? ucfirst($rkey);
                        @endphp
                        <tr data-table-row data-role="{{ $rkey }}" data-status="{{ $s->status }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $initial }}</div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ $nm }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $u?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $rkey === 'admin' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : ($rkey === 'produksi' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant') }} text-[9px] font-bold uppercase border whitespace-nowrap">{{ $rlabel }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1.5 max-w-[240px]">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant text-[11px] whitespace-nowrap">{{ $storeName }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ optional(\Carbon\Carbon::parse($s->tanggal_penugasan))->translatedFormat('M Y') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($s->status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" data-modal-open="modal-edit-karyawan-{{ $s->store_staff_id }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>Edit
                                    </button>
                                    @if ($s->status === 'aktif')
                                    <button type="button" data-modal-open="modal-nonaktifkan-{{ $s->store_staff_id }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-error/30 text-xs font-semibold text-error hover:bg-error/10 transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">person_off</span>Nonaktifkan
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-on-surface-variant">Belum ada karyawan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada karyawan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Satu staf dapat ditugaskan ke toko Anda. Akun baru akan menerima undangan aktivasi melalui email.
        </p>
    </section>

{{-- Modal Edit & Nonaktifkan per karyawan --}}
@foreach ($staff as $s)
    <div id="modal-edit-karyawan-{{ $s->store_staff_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <p class="raliva-label text-gold-accent">Edit Karyawan</p>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $s->user?->nama_lengkap ?? '-' }}</h3>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('owner.karyawan.update', $s->store_staff_id) }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block raliva-label mb-2">Nama</label>
                    <input type="text" value="{{ $s->user?->nama_lengkap ?? '-' }}" disabled class="raliva-input opacity-70" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Email</label>
                    <input type="email" value="{{ $s->user?->email ?? '-' }}" disabled class="raliva-input opacity-70" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Role</label>
                    <select name="role" class="raliva-select">
                        @php $curRole = \App\Http\Controllers\Owner\KaryawanController::ROLE_MAP[$s->user?->role_id] ?? 'lainnya'; @endphp
                        <option value="admin" {{ $curRole === 'admin' ? 'selected' : '' }}>Admin Toko</option>
                        <option value="produksi" {{ $curRole === 'produksi' ? 'selected' : '' }}>Staf Produksi</option>
                        <option value="gudang" {{ $curRole === 'gudang' ? 'selected' : '' }}>Staf Gudang</option>
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Status</label>
                    <select name="status" class="raliva-select">
                        <option value="aktif" {{ $s->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $s->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">save</span>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-nonaktifkan-{{ $s->store_staff_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-error">person_off</span>
                <h3 class="font-title-md text-title-md text-on-surface">Nonaktifkan Karyawan?</h3>
            </div>
            <p class="text-on-surface-variant text-sm mb-6">{{ $s->user?->nama_lengkap ?? 'Karyawan' }} akan dinonaktifkan dari toko ini. Akses akunnya ke toko ditutup, namun datanya tetap tersimpan.</p>
            <form method="POST" action="{{ route('owner.karyawan.destroy', $s->store_staff_id) }}" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-error text-on-primary text-sm font-semibold rounded btn-premium">Nonaktifkan</button>
            </form>
        </div>
    </div>
@endforeach

{{-- Modal Tambah Karyawan --}}
<div id="modal-tambah-karyawan" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Karyawan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Buat akun staf untuk toko Anda.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('owner.karyawan.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Nama Lengkap</label>
                <input name="nama_lengkap" type="text" required placeholder="Budi Santoso" class="raliva-input" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Email</label>
                <input name="email" type="email" required placeholder="budi@raliva.com" class="raliva-input" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Password Sementara</label>
                <input name="password" type="password" required minlength="6" placeholder="Min. 6 karakter" class="raliva-input" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Role</label>
                <select name="role" class="raliva-select">
                    <option value="admin">Admin Toko</option>
                    <option value="produksi">Staf Produksi</option>
                    <option value="gudang">Staf Gudang</option>
                </select>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>Simpan Karyawan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-modal-open="modal-nonaktifkan"]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const el = document.getElementById('target-karyawan-name');
            if (el && window.targetKaryawan) el.textContent = window.targetKaryawan;
        });
    });
</script>
@endpush
