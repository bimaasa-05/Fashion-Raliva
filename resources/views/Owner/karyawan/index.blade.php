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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full sm:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari nama atau email..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="role" class="raliva-select">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin Toko</option>
                    <option value="produksi">Produksi</option>
                    <option value="gudang">Gudang</option>
                </select>
                <select data-table-filter="status" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
                Satu staf dapat ditugaskan ke beberapa toko milik Anda. Halaman ini read-only — pengelolaan staf dilakukan melalui SuperAdmin.
            </p>
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
                            $rkey = $s->role;
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
                                <span class="text-xs text-on-surface-variant">Read-only</span>
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
            Satu staf dapat ditugaskan ke beberapa toko milik Anda. Akun baru akan menerima undangan aktivasi melalui email.
        </p>
    </section>
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
