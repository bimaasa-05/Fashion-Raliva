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
        @foreach ([['Total Karyawan', 8, 'on-surface', 'groups'], ['Admin', 2, 'secondary', 'admin_panel_settings'], ['Produksi & Gudang', 5, 'on-surface', 'precision_manufacturing'], ['Nonaktif', 2, 'error', 'person_off']] as $stat)
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
            <button type="button" data-modal-open="modal-tambah-karyawan" class="shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full sm:w-auto">
                <span class="material-symbols-outlined text-[18px]">person_add</span>Tambah Karyawan
            </button>
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
                    @foreach ([
                        ['nama' => 'Sinta Dewi', 'email' => 'sinta.dewi@raliva.id', 'initial' => 'SD', 'role' => 'Admin Toko', 'rkey' => 'admin', 'toko' => ['Raliva Atelier Jakarta'], 'join' => 'Mar 2026', 'status' => 'aktif'],
                        ['nama' => 'Yusuf Maulana', 'email' => 'yusuf.m@raliva.id', 'initial' => 'YM', 'role' => 'Admin Toko', 'rkey' => 'admin', 'toko' => ['Raliva Atelier Jakarta'], 'join' => 'Jun 2026', 'status' => 'aktif'],
                        ['nama' => 'Andi Pratama', 'email' => 'andi.pratama@raliva.id', 'initial' => 'AP', 'role' => 'Gudang', 'rkey' => 'gudang', 'toko' => ['Atelier Jakarta', 'Store Bandung'], 'join' => 'Mar 2026', 'status' => 'aktif'],
                        ['nama' => 'Rudi Hartono', 'email' => 'rudi.hartono@raliva.id', 'initial' => 'RH', 'role' => 'Produksi', 'rkey' => 'produksi', 'toko' => ['Atelier Jakarta', 'Store Bandung'], 'join' => 'Apr 2026', 'status' => 'aktif'],
                        ['nama' => 'Sinta Maharani', 'email' => 'sinta.maharani@raliva.id', 'initial' => 'SM', 'role' => 'Produksi', 'rkey' => 'produksi', 'toko' => ['Store Bandung'], 'join' => 'Jul 2026', 'status' => 'nonaktif'],
                        ['nama' => 'Bagas Saputra', 'email' => 'bagas.s@raliva.id', 'initial' => 'BS', 'role' => 'Gudang', 'rkey' => 'gudang', 'toko' => ['Store Bandung'], 'join' => 'Mei 2026', 'status' => 'nonaktif'],
                    ] as $k)
                        <tr data-table-row data-role="{{ $k['rkey'] }}" data-status="{{ $k['status'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $k['initial'] }}</div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ $k['nama'] }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $k['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $k['rkey'] === 'admin' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : ($k['rkey'] === 'produksi' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant') }} text-[9px] font-bold uppercase border whitespace-nowrap">{{ $k['role'] }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1.5 max-w-[240px]">
                                    @foreach ($k['toko'] as $t)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant text-[11px] whitespace-nowrap">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $k['join'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($k['status'] === 'aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-tugaskan" onclick="showRalivaToast('Panel penugasan dibuka (demo).', 'assignment')" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Tugaskan</button>
                                <span class="text-muted-border mx-1.5">|</span>
                                @if ($k['status'] === 'aktif')
                                    <button type="button" data-modal-open="modal-nonaktifkan" onclick="window.targetKaryawan = '{{ $k['nama'] }}'" class="text-xs font-semibold text-error hover:underline whitespace-nowrap">Nonaktifkan</button>
                                @else
                                    <button type="button" onclick="showRalivaToast('{{ $k['nama'] }} diaktifkan kembali (demo).')" class="text-xs font-semibold text-secondary hover:underline whitespace-nowrap">Aktifkan</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
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
            Satu staf dapat ditugaskan ke beberapa toko milik Anda. Penambahan akun baru dikirim melalui undangan email.
        </p>
    </section>
</div>

{{-- Modal Tambah Karyawan --}}
<div id="modal-tambah-karyawan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Karyawan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Undangan akses akan dikirim via email.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Undangan karyawan berhasil dikirim." class="p-6 space-y-5">
            <div>
                <label for="kr-nama" class="block raliva-label mb-2">Nama Lengkap</label>
                <input id="kr-nama" type="text" placeholder="cth. Dewi Lestari" required class="raliva-input" />
            </div>
            <div>
                <label for="kr-email" class="block raliva-label mb-2">Email Aktif</label>
                <input id="kr-email" type="email" placeholder="cth. dewi.lestari@gmail.com" required class="raliva-input" />
            </div>
            <div>
                <p class="block raliva-label mb-2">Role</p>
                <div class="grid grid-cols-3 gap-gutter">
                    @foreach ([['admin', 'Admin', 'shield_person'], ['produksi', 'Produksi', 'precision_manufacturing'], ['gudang', 'Gudang', 'warehouse']] as $i => $role)
                        <label class="cursor-pointer">
                            <input type="radio" name="role-baru" value="{{ $role[0] }}" {{ $i === 0 ? 'checked' : '' }} class="sr-only peer" />
                            <span class="flex flex-col items-center gap-2 py-4 border border-muted-border rounded-lg text-xs font-medium text-on-surface peer-checked:border-gold-accent peer-checked:bg-gold-accent/10 peer-checked:text-gold-accent transition-colors">
                                <span class="material-symbols-outlined text-[22px]">{{ $role[2] }}</span>{{ $role[1] }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="block raliva-label mb-2">Tugaskan ke Toko</p>
                <div class="space-y-2.5 border border-muted-border rounded-lg p-4 bg-surface-container-low">
                    @foreach ([['Raliva Atelier Jakarta', true], ['Raliva Store Bandung', false], ['Raliva Outlet Surabaya', false]] as $toko)
                        <label class="flex items-center justify-between gap-3 cursor-pointer group">
                            <span class="font-body-md text-sm text-on-surface">{{ $toko[0] }}</span>
                            <label class="raliva-toggle">
                                <input type="checkbox" class="sr-only peer" {{ $toko[1] ? 'checked' : '' }} />
                                <span class="raliva-toggle-track"></span>
                                <span class="raliva-toggle-knob"></span>
                            </label>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send_invitation</span>Kirim Undangan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tugaskan --}}
<div id="modal-tugaskan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Ubah Penugasan Toko</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Penugasan toko berhasil diperbarui." class="p-6 space-y-5">
            <div class="space-y-2.5">
                @foreach ([['Raliva Atelier Jakarta', true], ['Raliva Store Bandung', true], ['Raliva Outlet Surabaya', false]] as $toko)
                    <label class="flex items-center justify-between gap-3 cursor-pointer border border-muted-border rounded-lg px-4 py-3 hover:border-gold-accent/40 transition-colors">
                        <span class="font-body-md text-sm text-on-surface">{{ $toko[0] }}</span>
                        <label class="raliva-toggle">
                            <input type="checkbox" class="sr-only peer" {{ $toko[1] ? 'checked' : '' }} />
                            <span class="raliva-toggle-track"></span>
                            <span class="raliva-toggle-knob"></span>
                        </label>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                Staf hanya dapat mengakses data pada toko yang ditugaskan.
            </p>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Penugasan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Nonaktifkan --}}
<div id="modal-nonaktifkan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-24 md:mt-40 w-[calc(100%-2rem)] max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined fill text-[28px] text-error">person_off</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface">Nonaktifkan Akun?</h3>
            <p class="text-on-surface-variant font-body-md text-sm leading-relaxed"><span class="font-bold text-on-surface" id="target-karyawan-name">Staf ini</span> akan langsung kehilangan akses ke semua toko yang ditugaskan. Riwayat aktivitasnya tetap tersimpan.</p>
            <form data-toast-message="Akun karyawan dinonaktifkan." class="flex flex-col-reverse gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-error text-on-error text-sm font-semibold rounded btn-premium">Ya, Nonaktifkan</button>
            </form>
        </div>
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
