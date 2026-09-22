@extends('layouts.superadmin')

@section('title', 'Promo Slot')
@section('header-title', 'Promo Slot')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Buat dan kelola diskon pembelian paket slot untuk toko')

@section('content')
@include('partials.flash-toast')
<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">local_offer</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Promo Slot</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Diskon pembelian paket slot untuk toko.</p>
            </div>
        </div>
        <button type="button" onclick="openPromoForm()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Buat Promo
        </button>
    </section>

    <!-- List -->
    <section data-table-scope class="space-y-gutter">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 space-y-4">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Promo</span>
            </div>
            <div id="promo-chip-group" class="flex flex-wrap gap-2">
                <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $promos->count() }})</button>
                <button type="button" data-chip="aktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aktif ({{ $promos->where('status', 'aktif')->count() }})</button>
                <button type="button" data-chip="nonaktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Nonaktif ({{ $promos->where('status', 'nonaktif')->count() }})</button>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="promo-search" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama promo, kode, paket slot..." />
                    <button type="button" id="promo-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="promo-result-count">{{ $promos->count() }}</span> promo
                </p>
            </div>
        </div>

        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Paket Slot</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Promo</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Diskon</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Berlaku</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($promos as $promo)
                        @php
                            $sisaHari = max(0, (int) now()->diffInDays(\Illuminate\Support\Carbon::parse($promo->berakhir_pada), false));
                            $tipeLabel = $promo->tipe_diskon === 'persen'
                                ? 'Diskon '.($promo->nilai_diskon % 1 == 0 ? number_format((float) $promo->nilai_diskon, 0) : $promo->nilai_diskon).'%'
                                : 'Diskon Rp '.number_format((float) $promo->nilai_diskon, 0, ',', '.');
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-promo-row
                            data-id="{{ $promo->slot_promo_id }}"
                            data-name="{{ $promo->nama_promo }}"
                            data-status="{{ $promo->status }}"
                            data-search="{{ strtolower($promo->nama_promo.' '.$promo->kode_promo.' '.($promo->package?->nama_paket ?? '').' '.($promo->deskripsi ?? '')) }}">
                            <td class="p-6">
                                <p class="text-on-surface font-semibold">{{ $promo->package?->nama_paket ?? '-' }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $promo->package?->jumlah_slot ?? '-' }} slot &bull; Rp {{ number_format((float) ($promo->package?->harga ?? 0), 0, ',', '.') }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-on-surface font-semibold">{{ $promo->nama_promo }}</p>
                                <p class="font-mono text-xs text-gold-accent">{{ $promo->kode_promo }}</p>
                                @if ($promo->deskripsi)
                                    <p class="text-on-surface-variant text-xs mt-1 max-w-[220px] [overflow-wrap:anywhere]">{{ \Illuminate\Support\Str::limit($promo->deskripsi, 60) }}</p>
                                @endif
                            </td>
                            <td class="p-6">
                                <p class="text-on-surface">{{ $tipeLabel }}</p>
                                @if ($promo->maksimal_diskon)
                                    <p class="text-on-surface-variant text-xs">Maks. Rp {{ number_format((float) $promo->maksimal_diskon, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="p-6 text-on-surface-variant text-xs">
                                <p>{{ \Carbon\Carbon::parse($promo->mulai_pada)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($promo->berakhir_pada)->format('d M Y') }}</p>
                                <p class="mt-1">{{ $sisaHari > 0 ? 'Sisa '.$sisaHari.' hari' : 'Berakhir' }}</p>
                            </td>
                            <td class="p-6 text-center">
                                @if ($promo->status === 'aktif' && now()->between($promo->mulai_pada, $promo->berakhir_pada))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/20 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Aktif</span>
                                @elseif ($promo->status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Terjadwal</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-6 text-right whitespace-nowrap">
                                <button type="button" onclick="openPromoForm(this.closest('tr'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button type="button" onclick="openHapusPromo(this.closest('tr'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Belum ada promo slot dibuat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <p id="promo-empty-search" class="hidden text-center text-on-surface-variant font-body-md text-sm py-8">Tidak ada promo yang cocok.</p>
        </div>

        <!-- Mobile cards -->
        <div class="md:hidden grid grid-cols-1 gap-3">
            @forelse ($promos as $promo)
                @php
                    $sisaHari = max(0, (int) now()->diffInDays(\Illuminate\Support\Carbon::parse($promo->berakhir_pada), false));
                    $tipeLabel = $promo->tipe_diskon === 'persen'
                        ? 'Diskon '.($promo->nilai_diskon % 1 == 0 ? number_format((float) $promo->nilai_diskon, 0) : $promo->nilai_diskon).'%'
                        : 'Diskon Rp '.number_format((float) $promo->nilai_diskon, 0, ',', '.');
                @endphp
                <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-4" data-promo-row
                    data-id="{{ $promo->slot_promo_id }}"
                    data-name="{{ $promo->nama_promo }}"
                    data-status="{{ $promo->status }}"
                    data-search="{{ strtolower($promo->nama_promo.' '.$promo->kode_promo.' '.($promo->package?->nama_paket ?? '').' '.($promo->deskripsi ?? '')) }}">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-on-surface font-semibold">{{ $promo->package?->nama_paket ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $promo->package?->jumlah_slot ?? '-' }} slot</p>
                        </div>
                        @if ($promo->status === 'aktif' && now()->between($promo->mulai_pada, $promo->berakhir_pada))
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/20 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Aktif</span>
                        @elseif ($promo->status === 'aktif')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Terjadwal</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-on-surface font-semibold mt-3">{{ $promo->nama_promo }} <span class="font-mono text-xs text-gold-accent">{{ $promo->kode_promo }}</span></p>
                    <p class="text-on-surface-variant text-xs mt-1">{{ $tipeLabel }}@if ($promo->maksimal_diskon) &bull; Maks. Rp {{ number_format((float) $promo->maksimal_diskon, 0, ',', '.') }}@endif</p>
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-muted-border">
                        <p class="text-on-surface-variant text-xs">{{ $sisaHari > 0 ? 'Sisa '.$sisaHari.' hari' : 'Berakhir' }}</p>
                        <div class="flex gap-1">
                            <button type="button" onclick="openPromoForm(this.closest('[data-promo-row]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                            <button type="button" onclick="openHapusPromo(this.closest('[data-promo-row]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-on-surface-variant font-body-md text-sm py-8">Belum ada promo slot dibuat.</div>
            @endforelse
        </div>
    </section>

    <!-- Modal Buat/Ubah Promo -->
    @component('SuperAdmin.partials.premium-modal', [
        'id' => 'modal-buat-promo',
        'dataModal' => true,
        'icon' => 'local_offer',
        'title' => 'Buat Promo Slot',
        'titleId' => 'promo-modal-title',
        'subtitle' => 'Diskon berlaku saat Owner membeli paket slot ini.',
        'subtitleId' => 'promo-modal-sub',
        'size' => 'lg',
    ])
        <form id="promo-form" action="{{ route('superadmin.promo-slot.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="promo-method-input" value="" />
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="slot_package_id">Paket Slot</label>
                <select class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="slot_package_id" name="slot_package_id" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach ($packages as $pkg)
                        <option value="{{ $pkg->slot_package_id }}" data-harga="{{ $pkg->harga }}">{{ $pkg->nama_paket }} ({{ $pkg->jumlah_slot }} slot) &mdash; Rp {{ number_format((float) ($pkg->harga ?? 0), 0, ',', '.') }}</option>
                    @endforeach
                </select>
                @error('slot_package_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama_promo">Nama Promo</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="nama_promo" name="nama_promo" type="text" placeholder="Misal: Promo Ramadhan" value="{{ old('nama_promo') }}" required />
                    @error('nama_promo')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="kode_promo">Kode Promo</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="kode_promo" name="kode_promo" type="text" placeholder="PROMO-RAMADHAN" value="{{ old('kode_promo') }}" required />
                    @error('kode_promo')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tipe_diskon">Tipe Diskon</label>
                    <div class="relative" id="promoTipe-dd">
                        <button type="button" data-dd-trigger id="promoTipe-trigger" onclick="toggleDropdown('promoTipe')" aria-haspopup="listbox" aria-expanded="false"
                            class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors cursor-pointer text-left">
                            <span id="promoTipe-label" class="truncate">-- Pilih Tipe --</span>
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="promoTipe-chevron">expand_more</span>
                        </button>
                        <div id="promoTipe-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                            class="hidden absolute left-0 top-full mt-2 w-full min-w-[180px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                            <button type="button" role="option" aria-selected="false" data-dd-option="persen" onclick="selectPromoTipe('persen')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Persen (%)<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                            <button type="button" role="option" aria-selected="false" data-dd-option="nominal" onclick="selectPromoTipe('nominal')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                Nominal (Rp)<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                            </button>
                        </div>
                        <input type="hidden" name="tipe_diskon" id="tipe_diskon" value="" />
                    </div>
                    @error('tipe_diskon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nilai_diskon">Nilai Diskon</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="nilai_diskon" name="nilai_diskon" type="number" min="1" step="0.5" value="{{ old('nilai_diskon', 10) }}" required />
                    @error('nilai_diskon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="maksimal_diskon">Maksimal Diskon (Rp) <span class="normal-case font-normal">— wajib bila tipe persen</span></label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="maksimal_diskon" name="maksimal_diskon" type="number" min="0" value="{{ old('maksimal_diskon') }}" />
                @error('maksimal_diskon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="mulai_pada">Mulai</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="mulai_pada" name="mulai_pada" type="date" value="{{ old('mulai_pada') }}" required />
                    @error('mulai_pada')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="berakhir_pada">Berakhir</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="berakhir_pada" name="berakhir_pada" type="date" value="{{ old('berakhir_pada') }}" required />
                    @error('berakhir_pada')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="deskripsi">Deskripsi</label>
                <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi singkat promo ini">{{ old('deskripsi') }}</textarea>
            </div>
            <div id="promo-status-wrap" class="hidden">
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Status</label>
                <div class="relative" id="promoStatus-dd">
                    <button type="button" data-dd-trigger id="promoStatus-trigger" onclick="toggleDropdown('promoStatus')" aria-haspopup="listbox" aria-expanded="false"
                        class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors cursor-pointer text-left">
                        <span id="promoStatus-label" class="truncate">Aktif</span>
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="promoStatus-chevron">expand_more</span>
                    </button>
                    <div id="promoStatus-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                        class="hidden absolute left-0 top-full mt-2 w-full min-w-[140px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                        <button type="button" role="option" aria-selected="true" data-dd-option="aktif" onclick="selectPromoStatus('aktif')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                            Aktif<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent">check</span>
                        </button>
                        <button type="button" role="option" aria-selected="false" data-dd-option="nonaktif" onclick="selectPromoStatus('nonaktif')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-body-md text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                            Nonaktif<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                        </button>
                    </div>
                    <input type="hidden" name="status" id="promo_status" value="aktif" />
                </div>
            </div>
            @slot('footer')
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-4">
                    <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                    <button type="submit" form="promo-form" id="promo-submit-btn" class="btn-modal btn-modal-primary">Buat Promo</button>
                </div>
            @endslot
        </form>
    @endcomponent

    <!-- Modal Hapus Promo -->
    @component('SuperAdmin.partials.premium-confirm', [
        'id' => 'hapusPromoModal',
        'icon' => 'delete_forever',
        'zIndex' => 70,
        'close' => 'closeHapusPromo',
    ])
        <form method="POST" action="" id="hapus-promo-form" onsubmit="closeHapusPromo()" class="p-6 space-y-4">
            @csrf
            @method('DELETE')
            <div class="text-center">
                <h3 class="font-title-md text-title-md text-on-surface">Hapus Promo Slot</h3>
                <p class="text-on-surface-variant text-sm mt-2">Promo <span id="hapus-promo-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </form>
        @slot('footer')
            <div class="flex space-x-3">
                <button type="button" class="btn-modal btn-modal-ghost flex-1" onclick="closeHapusPromo()">Batal</button>
                <button type="submit" form="hapus-promo-form" class="btn-modal btn-modal-danger flex-1">Ya, Hapus</button>
            </div>
        @endslot
    @endcomponent
</div>
@endsection

@push('scripts')
@include('SuperAdmin.partials.dd-helpers')
<script>
    const promoUrls = {
        store: '{{ route('superadmin.promo-slot.store') }}',
        detail: (id) => '{{ route('superadmin.promo-slot.detail', ':id:') }}'.replace(':id:', id),
        update: (id) => '{{ route('superadmin.promo-slot.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.promo-slot.destroy', ':id:') }}'.replace(':id:', id)
    };

    const promoFields = ['slot_package_id', 'nama_promo', 'kode_promo', 'nilai_diskon', 'maksimal_diskon', 'deskripsi'];

    function selectPromoTipe(v) {
        document.getElementById('tipe_diskon').value = v;
        syncPromoTipe();
    }
    function syncPromoTipe() {
        const labels = { persen: 'Persen (%)', nominal: 'Nominal (Rp)' };
        const v = document.getElementById('tipe_diskon').value;
        ddSet('promoTipe', v, labels[v] ?? '-- Pilih Tipe --');
    }
    function selectPromoStatus(v) {
        document.getElementById('promo_status').value = v;
        syncPromoStatus();
    }
    function syncPromoStatus() {
        const labels = { aktif: 'Aktif', nonaktif: 'Nonaktif' };
        const v = document.getElementById('promo_status').value;
        ddSet('promoStatus', v, labels[v] ?? 'Aktif');
    }

    function openPromoModal() {
        document.getElementById('modal-buat-promo').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePromoModal() {
        document.getElementById('modal-buat-promo').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openPromoForm(row = null) {
        const form = document.getElementById('promo-form');
        const isEdit = !!row;

        promoFields.forEach((f) => { document.getElementById(f).value = ''; });
        document.getElementById('tipe_diskon').value = '';
        document.getElementById('promo_status').value = 'aktif';
        document.getElementById('promo-method-input').value = '';
        syncPromoTipe();
        syncPromoStatus();
        document.getElementById('promo-status-wrap').classList.toggle('hidden', !isEdit);

        if (isEdit) {
            const id = row.getAttribute('data-id');
            const nama = row.getAttribute('data-name') || '';
            document.getElementById('promo-modal-title').textContent = 'Ubah Promo Slot';
            document.getElementById('promo-modal-sub').textContent = 'Perubahan berlaku saat Owner membeli paket slot terkait.';
            document.getElementById('promo-submit-btn').textContent = 'Simpan Perubahan';

            fetch(promoUrls.detail(id), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then((res) => res.json())
                .then((d) => {
                    promoFields.forEach((f) => {
                        document.getElementById(f).value = d[f] != null ? d[f] : '';
                    });
                    document.getElementById('tipe_diskon').value = d.tipe_diskon || '';
                    document.getElementById('mulai_pada').value = (d.mulai_pada || '').slice(0, 10);
                    document.getElementById('berakhir_pada').value = (d.berakhir_pada || '').slice(0, 10);
                    document.getElementById('promo_status').value = d.status || 'aktif';
                    syncPromoTipe();
                    syncPromoStatus();
                    form.action = promoUrls.update(id);
                    document.getElementById('promo-method-input').value = 'PUT';
                })
                .catch(() => {
                    document.getElementById('promo-modal-title').textContent = 'Ubah Promo Slot (' + nama + ')';
                });
        } else {
            document.getElementById('promo-modal-title').textContent = 'Buat Promo Slot';
            document.getElementById('promo-modal-sub').textContent = 'Diskon berlaku saat Owner membeli paket slot ini.';
            document.getElementById('promo-submit-btn').textContent = 'Buat Promo';
            form.action = promoUrls.store;
        }

        openPromoModal();
    }

    function openHapusPromo(row) {
        document.getElementById('hapus-promo-nama').textContent = row.getAttribute('data-name') || '-';
        document.getElementById('hapus-promo-form').action = promoUrls.hapus(row.getAttribute('data-id'));
        const modal = document.getElementById('hapusPromoModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusPromo() {
        const modal = document.getElementById('hapusPromoModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeAllDropdowns(); closePromoModal(); closeHapusPromo(); }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('[data-promo-row]'));
        const chipBtns = document.querySelectorAll('#promo-chip-group .chip-btn');
        const searchInput = document.getElementById('promo-search');
        const clearBtn = document.getElementById('promo-clear-search');
        const countEl = document.getElementById('promo-result-count');
        const emptySearch = document.getElementById('promo-empty-search');

        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeChip = 'semua';

        function applyFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const matchChip = activeChip === 'semua' || row.getAttribute('data-status') === activeChip;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchChip && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
        }

        chipBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                chipBtns.forEach((b) => {
                    b.classList.remove(...activeClasses);
                    b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
                });
                btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
                btn.classList.add(...activeClasses);
                activeChip = btn.getAttribute('data-chip');
                applyFilter();
            });
        });

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyFilter, 200);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyFilter();
        });

        applyFilter();
    });
</script>
@endpush