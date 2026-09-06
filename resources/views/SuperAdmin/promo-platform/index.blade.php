@extends('layouts.superadmin')

@section('title', 'Promo Platform')

@section('header-title', 'Promo Platform')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Buat dan kelola promo lintas toko untuk meningkatkan penjualan')

@section('content')
<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">local_fire_department</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Promo Platform</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Buat dan kelola promo lintas toko.</p>
            </div>
        </div>
        <button type="button" data-modal-open="modal-buat-promo" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Buat Promo
        </button>
    </section>

    <!-- Active Promos -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3"><h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Semua Promo</h2></div>
            <span class="text-on-surface-variant font-body-md text-sm">{{ $promos->count() }} promo terdaftar</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @forelse($promos as $promo)
                @php
                    $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($promo->berakhir_pada), false);
                    $sisaHari = max(0, (int) $sisaHari);
                    $tipeLabel = match($promo->tipe_diskon) {
                        'persen' => 'Diskon '.$promo->nilai_diskon.'%',
                        'nominal' => 'Diskon Rp '.number_format((float)$promo->nilai_diskon, 0, ',', '.'),
                        default => ucfirst($promo->tipe_diskon),
                    };
                @endphp
                <div class="group relative overflow-hidden bg-surface-container-lowest border {{ $promo->status === 'aktif' ? 'border-gold-accent/30' : 'border-muted-border' }} rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-12 translate-x-12" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-gold-accent text-[24px]">local_fire_department</span>
                        </div>
                        @if($promo->status === 'aktif')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/20 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">
                                <span class="material-symbols-outlined text-[10px]">local_fire_department</span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    @if($promo->deskripsi)
                        <p class="text-on-surface-variant text-sm mb-2">{{ Str::limit($promo->deskripsi, 80) }}</p>
                    @endif
                    <p class="font-headline-lg text-headline-lg text-gold-accent mb-1">{{ $promo->nama_promo }}</p>
                    <p class="text-on-surface-variant text-xs">{{ $tipeLabel }} &bull; Kode: {{ $promo->kode_promo }}</p>
                    @if($promo->maksimal_diskon)
                        <p class="text-on-surface-variant text-xs mt-1">Maks. Diskon: Rp {{ number_format((float)$promo->maksimal_diskon, 0, ',', '.') }}</p>
                    @endif
                    @if($promo->dapat_digabung)
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20 mt-2">
                            <span class="material-symbols-outlined text-[10px]">merge</span> Dapat Digabung
                        </span>
                    @endif
                    <p class="text-on-surface-variant text-xs mt-1">Tersisa: {{ $sisaHari > 0 ? $sisaHari.' hari' : 'Berakhir' }}</p>
                </div>
            @empty
                <div class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-8">
                    Belum ada promo dibuat.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Modal Buat Promo -->
    <div id="modal-buat-promo" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Buat Promo Baru</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Promo berlaku lintas toko di seluruh platform.</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="{{ route('superadmin.promo-platform.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama_promo">Nama Promo</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="nama_promo" name="nama_promo" type="text" placeholder="Misal: Lebaran Sale" value="{{ old('nama_promo') }}" required />
                    @error('nama_promo')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="kode_promo">Kode Promo</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="kode_promo" name="kode_promo" type="text" placeholder="PROMO-LEBARAN24" value="{{ old('kode_promo') }}" required />
                        @error('kode_promo')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tipe_diskon">Tipe Diskon</label>
                        <select class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="tipe_diskon" name="tipe_diskon" required>
                            <option value="persen" {{ old('tipe_diskon') === 'persen' ? 'selected' : '' }}>Persen (%)</option>
                            <option value="nominal" {{ old('tipe_diskon') === 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                        @error('tipe_diskon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nilai_diskon">Nilai Diskon</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="nilai_diskon" name="nilai_diskon" type="number" min="0" step="0.5" value="{{ old('nilai_diskon', 15) }}" required />
                        @error('nilai_diskon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="minimal_pembelian">Minimal Pembelian</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="minimal_pembelian" name="minimal_pembelian" type="number" min="0" value="{{ old('minimal_pembelian', 0) }}" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="maksimal_diskon">Maksimal Diskon (Rp)</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="maksimal_diskon" name="maksimal_diskon" type="number" min="0" value="{{ old('maksimal_diskon') }}" />
                        @error('maksimal_diskon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Dapat Digabung</label>
                        <select class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="dapat_digabung" name="dapat_digabung">
                            <option value="0" {{ old('dapat_digabung', 0) == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('dapat_digabung') == 1 ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-gutter">
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
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Buat Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
