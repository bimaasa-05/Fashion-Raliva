@extends('layouts.gudang')

@section('title', 'Permintaan Operasional')

@section('header-title', 'Permintaan')
@section('header-badge', 'Ajukan')
@section('header-subtitle', 'Ajukan permintaan operasional ke Admin toko.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Skeleton --}}
    <div data-skeleton class="space-y-section-gap">
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
            @for ($i = 0; $i < 4; $i++)
                <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
            @endfor
        </div>
        <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>

    <div data-real class="hidden space-y-section-gap">
        {{-- Stats --}}
        <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['pending'] + $stats['disetujui'] + $stats['ditolak'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">permintaan terkirim</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">forum</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pending</span>
                <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['pending'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu Admin</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Disetujui</span>
                <span class="raliva-figure text-[26px] text-secondary">{{ $stats['disetujui'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">diterima Admin</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditolak</span>
                <span class="raliva-figure text-[26px] text-error">{{ $stats['ditolak'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">ditolak Admin</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/15 fill pointer-events-none select-none" aria-hidden="true">cancel</span>
            </div>
        </section>

        {{-- Form Ajukan Permintaan --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[26px] text-gold-accent">edit_note</span>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Permintaan</h3>
                        <p class="font-label-sm text-[11px] text-on-surface-variant mt-0.5">Kirim permintaan operasional ke Admin toko untuk diproses.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('form-ajukan').classList.toggle('hidden')" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded btn-premium">+ Ajukan</button>
            </div>
            <form id="form-ajukan" method="POST" action="{{ route('gudang.permintaan.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_permintaan" class="raliva-label block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1.5">Jenis Permintaan *</label>
                        @php
                            $oldJp = old('jenis_permintaan');
                            $jpHasValue = !empty($oldJp) && isset($jenisOptions[$oldJp]);
                            $jpLabel = $jpHasValue ? $jenisOptions[$oldJp] : 'Pilih jenis permintaan';
                        @endphp
                        <div class="relative w-full" data-cs>
                            <button type="button" data-cs-trigger id="jenis_permintaan-trigger" aria-haspopup="listbox" aria-expanded="false" aria-required="true"
                                class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-3 py-2.5 min-h-11 font-body-md text-sm focus:outline-none focus:border-gold-accent transition-colors cursor-pointer text-left {{ $jpHasValue ? 'text-on-surface' : 'text-on-surface-variant' }}">
                                <span data-cs-label class="truncate">{{ $jpLabel }}</span>
                                <span data-cs-chevron class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform duration-200">expand_more</span>
                            </button>
                            <div data-cs-menu role="listbox" style="transform-origin: top left"
                                class="hidden absolute left-0 right-0 top-full mt-2 z-50 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl overflow-y-auto max-h-64 py-1">
                                <button type="button" role="option" data-cs-option="" data-cs-option-label="Pilih jenis permintaan" data-cs-placeholder
                                    class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface-variant hover:bg-surface-container-high transition-colors cursor-pointer">
                                    Pilih jenis permintaan<span data-cs-check class="material-symbols-outlined text-[18px] text-gold-accent {{ $jpHasValue ? 'hidden' : '' }}">check</span>
                                </button>
                                @foreach ($jenisOptions as $value => $label)
                                    <button type="button" role="option" data-cs-option="{{ $value }}" data-cs-option-label="{{ $label }}"
                                        class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                        {{ $label }}<span data-cs-check class="material-symbols-outlined text-[18px] text-gold-accent {{ $oldJp === $value ? '' : 'hidden' }}">check</span>
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="jenis_permintaan" value="{{ $oldJp }}" data-cs-input />
                        </div>
                        <p id="jp-error" class="hidden mt-1 text-[11px] text-error">Pilih jenis permintaan terlebih dahulu.</p>
                        @error('jenis_permintaan')<p class="mt-1 text-[11px] text-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="judul" class="raliva-label block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1.5">Judul *</label>
                        <input id="judul" type="text" name="judul" maxlength="150" value="{{ old('judul') }}" placeholder="Ringkasan permintaan..." class="raliva-input w-full" />
                        @error('judul')<p class="mt-1 text-[11px] text-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="deskripsi" class="raliva-label block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1.5">Deskripsi *</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="2000" placeholder="Jelaskan kebutuhan operasional secara rinci..." class="raliva-input w-full resize-y">{{ old('deskripsi') }}</textarea>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-[11px] text-on-surface-variant">Maks. 2000 karakter</span>
                        <span class="text-[11px] text-on-surface-variant/70" data-char-count>0 / 2000</span>
                    </div>
                    @error('deskripsi')<p class="mt-1 text-[11px] text-error">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-muted-border">
                    <button type="reset" class="px-4 py-2.5 border border-muted-border rounded-lg text-[10px] font-semibold uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Bersihkan</button>
                    <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">send</span>Ajukan Permintaan
                    </button>
                </div>
            </form>
        </section>

        {{-- Riwayat Permintaan --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex flex-col gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[26px] text-gold-accent">history</span>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Permintaan</h3>
                        <p class="font-label-sm text-[11px] text-on-surface-variant mt-0.5">Daftar permintaan yang pernah diajukan beserta statusnya.</p>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="inline-flex bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1 flex-wrap">
                        @foreach ([
                            'semua' => 'Semua',
                            'pending' => 'Pending',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                        ] as $key => $label)
                            <a href="{{ route('gudang.permintaan', array_merge(request()->except(['status', 'page']), ['status' => $key])) }}"
                               class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $activeStatus === $key ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                    <div class="relative w-full lg:w-72">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" placeholder="Cari judul permintaan..." data-table-search class="raliva-search" />
                    </div>
                </div>
            </div>

            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center w-12">No</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jenis</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Judul</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Admin</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permintaan as $index => $p)
                            @php
                                $no = $permintaan->firstItem() + $index;
                                $statusBadge = match ($p->status) {
                                    'pending' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/20',
                                    'disetujui' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                                    'ditolak' => 'bg-error/10 text-error border-error/20',
                                    default => 'bg-surface-container-high text-on-surface-variant border-muted-border',
                                };
                                $statusLabel = match ($p->status) {
                                    'pending' => 'Pending',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    default => ucfirst($p->status),
                                };
                                $jenisLabel = $jenisOptions[$p->jenis_permintaan] ?? ucfirst($p->jenis_permintaan);
                                $admin = $p->admin ? ($p->admin->nama_lengkap ?? $p->admin->nama ?? $p->admin->email) : null;
                                $tanggal = $p->diproses_pada ?? $p->created_at;
                            @endphp
                            <tr data-table-row class="border-b border-muted-border last:border-0 hover:bg-surface-container-low transition-colors">
                                <td class="py-3.5 px-4 text-center text-on-surface-variant">{{ $no }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 text-on-surface font-semibold">
                                        <span class="material-symbols-outlined text-[16px] text-gold-accent">label</span>{{ $jenisLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-on-surface">{{ $p->judul }}</p>
                                    <p class="text-[11px] text-on-surface-variant line-clamp-1 mt-0.5">{{ $p->deskripsi }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border whitespace-nowrap {{ $statusBadge }}">
                                        @if ($p->status === 'pending')<span class="material-symbols-outlined text-[12px]">schedule</span>
                                        @elseif($p->status === 'disetujui')<span class="material-symbols-outlined text-[12px]">check</span>
                                        @elseif($p->status === 'ditolak')<span class="material-symbols-outlined text-[12px]">close</span>@endif
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap text-xs">
                                    <p>{{ $tanggal?->format('d M Y') ?? '-' }}</p>
                                    <p class="text-[10px] text-on-surface-variant/70">{{ $tanggal?->format('H:i') }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-on-surface-variant">
                                    @if ($admin)
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[14px] text-on-surface-variant">person</span>{{ $admin }}
                                        </span>
                                    @else
                                        <span class="text-on-surface-variant/50">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-[260px]">
                                    @if ($p->catatan_admin)
                                        <p class="text-xs text-on-surface-variant line-clamp-2" title="{{ $p->catatan_admin }}">{{ $p->catatan_admin }}</p>
                                    @else
                                        <span class="text-on-surface-variant/50 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[28px] text-on-surface-variant">inbox</span>
                                        </div>
                                        <p class="text-on-surface-variant font-body-md text-sm">
                                            @if ($activeStatus === 'semua')Belum ada permintaan operasional.@else Tidak ada permintaan dengan status "{{ $activeStatus }}".@endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($permintaan->hasPages())
                <div class="mt-6 flex justify-center">{{ $permintaan->withQueryString()->links() }}</div>
            @endif
        </section>
    </div>
</div>

@push('scripts')
<script>
    // Table search
    const searchInput = document.querySelector('[data-table-search]');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('[data-table-row]').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // Character counter for deskripsi
    const deskripsi = document.getElementById('deskripsi');
    const counter = document.querySelector('[data-char-count]');
    if (deskripsi && counter) {
        const updateCount = () => {
            const len = deskripsi.value.length;
            counter.textContent = len + ' / 2000';
            counter.classList.toggle('text-error', len > 1900);
            counter.classList.toggle('text-on-surface-variant/70', len <= 1900);
        };
        deskripsi.addEventListener('input', updateCount);
        updateCount();
    }

    // Show form when validation errors exist
    @if (count($errors->all()))
        document.getElementById('form-ajukan')?.classList.remove('hidden');
    @endif

    // Jenis Permintaan custom dropdown: validasi required + reset
    const jpForm = document.getElementById('form-ajukan');
    const jpCs = jpForm?.querySelector('[data-cs]');
    const jpInput = jpCs?.querySelector('[data-cs-input]');
    const jpTrigger = jpCs?.querySelector('[data-cs-trigger]');
    const jpLabel = jpCs?.querySelector('[data-cs-label]');
    const jpError = document.getElementById('jp-error');

    if (jpForm && jpCs && jpInput) {
        const setJpError = (on) => {
            jpError?.classList.toggle('hidden', !on);
            jpTrigger?.classList.toggle('border-error', on);
        };
        const syncJpState = () => {
            const has = !!jpInput.value;
            jpTrigger.classList.toggle('text-on-surface', has);
            jpTrigger.classList.toggle('text-on-surface-variant', !has);
        };
        jpCs.querySelectorAll('[data-cs-option]').forEach((opt) => {
            opt.addEventListener('click', () => {
                setJpError(false);
                syncJpState();
            });
        });
        jpForm.addEventListener('submit', (e) => {
            if (!jpInput.value) {
                e.preventDefault();
                setJpError(true);
                jpTrigger?.focus();
            }
        });
        jpForm.addEventListener('reset', () => {
            jpInput.value = '';
            jpCs.querySelectorAll('[data-cs-option]').forEach((o) =>
                o.querySelector('[data-cs-check]')?.classList.add('hidden'));
            const ph = jpCs.querySelector('[data-cs-placeholder]');
            ph?.querySelector('[data-cs-check]')?.classList.remove('hidden');
            if (jpLabel) jpLabel.textContent = 'Pilih jenis permintaan';
            syncJpState();
            setJpError(false);
        });
    }
</script>
@endpush
@endsection
