@extends('layouts.owner')

@section('title', 'Promo Toko')

@section('header-title', 'Promo Toko')
@section('header-badge', '3 Aktif')
@section('header-subtitle', 'Buat dan kelola promo khusus untuk pelanggan toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 6; $i++)
            <div class="h-64 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Promo Berjalan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['aktif'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_offer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Penukaran Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['total'] }}</span>
            <span class="font-label-sm text-[11px] text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">trending_up</span>+22% vs Juli</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">redeem</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Estimasi Diskon Diberikan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">Rp 0</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">savings</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Konversi Promo</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>{{ $counts['aktif'] > 0 ? '18' : '0' }}</span>%</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">query_stats</span>
        </div>
    </section>

    {{-- Daftar Promo --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Promo</h2>
                <p class="text-xs text-on-surface-variant mt-1">Kelola promo aktif, terjadwal, dan riwayat diskon toko Anda.</p>
            </div>
            @if ($store)
            <button type="button" data-modal-open="modal-tambah-promo" class="py-2.5 px-5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>Tambah Promo
            </button>
            @else
            <span class="text-xs text-on-surface-variant bg-surface-container-low border border-muted-border rounded-lg px-4 py-2.5">Ajukan toko untuk membuat promo</span>
            @endif
        </div>

        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-section-gap">
            @forelse ($promos as $promo)
                <article data-reveal class="bg-surface-container-lowest border {{ $promo->status === 'aktif' ? 'border-gold-accent/40' : 'border-muted-border' }} rounded-lg p-5 flex flex-col gap-4 card-premium relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1 {{ $promo->status === 'aktif' ? 'bg-gradient-to-r from-gold-accent to-secondary' : ($promo->mulai_pada && $promo->mulai_pada->isFuture() ? 'bg-gold-accent/40' : 'bg-surface-container-high') }}"></div>
                    <div class="flex items-start justify-between gap-3 pt-1">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px] text-gold-accent">sell</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-base text-on-surface tracking-wide truncate">{{ $promo->kode_promo }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $promo->nama_promo }}</p>
                            </div>
                        </div>
                        @if ($promo->status === 'aktif')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Aktif</span>
                        @elseif ($promo->mulai_pada && $promo->mulai_pada->isFuture())
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Terjadwal</span>
                        @elseif ($promo->status === 'nonaktif')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Selesai</span>
                        @endif
                    </div>

                    <dl class="space-y-1.5 font-body-md text-xs text-on-surface-variant">
                        <div class="flex justify-between gap-3"><dt>Tipe</dt><dd class="text-on-surface font-bold">{{ $promo->tipe_diskon === 'persen' ? 'Diskon '.$promo->nilai_diskon.'%' : 'Diskon Rp '.number_format($promo->nilai_diskon,0,',','.') }} @if($promo->maksimal_diskon)<span class="font-normal">• Maks. Rp {{ number_format($promo->maksimal_diskon,0,',','.') }}</span>@endif</dd></div>
                        <div class="flex justify-between gap-3"><dt>Syarat</dt><dd class="text-on-surface">{{ $promo->minimal_pembelian ? 'Min. belanja Rp '.number_format($promo->minimal_pembelian,0,',','.') : 'Tanpa minimum' }}</dd></div>
                        <div class="flex justify-between gap-3"><dt>Periode</dt><dd class="text-on-surface">{{ $promo->mulai_pada?->translatedFormat('d M Y') }} — {{ $promo->berakhir_pada?->translatedFormat('d M Y') }}</dd></div>
                    </dl>

                    <div class="flex items-center gap-gutter pt-1 mt-auto">
                        <button type="button" data-modal-open="modal-detail-promo-{{ $promo->promotion_id }}" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Detail</button>
                    </div>
                </article>

                {{-- Modal Detail Promo --}}
                <div id="modal-detail-promo-{{ $promo->promotion_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
                        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                            <div>
                                <p class="raliva-label text-gold-accent">Detail Promo</p>
                                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $promo->kode_promo }}</h3>
                            </div>
                            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[24px] text-gold-accent">sell</span>
                                </div>
                                <div>
                                    <p class="font-title-md text-base text-on-surface">{{ $promo->nama_promo }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $promo->status }}</span>
                                </div>
                            </div>
                            <dl class="grid grid-cols-2 gap-4 font-body-md text-sm">
                                <div class="bg-surface-container-low rounded-lg p-3">
                                    <dt class="text-on-surface-variant text-[11px] uppercase">Jenis Diskon</dt>
                                    <dd class="text-on-surface font-bold mt-1">{{ $promo->tipe_diskon === 'persen' ? 'Persen (%)' : 'Nominal (Rp)' }}</dd>
                                </div>
                                <div class="bg-surface-container-low rounded-lg p-3">
                                    <dt class="text-on-surface-variant text-[11px] uppercase">Nilai Diskon</dt>
                                    <dd class="text-on-surface font-bold mt-1">{{ $promo->tipe_diskon === 'persen' ? $promo->nilai_diskon.'%' : 'Rp '.number_format($promo->nilai_diskon,0,',','.') }}</dd>
                                </div>
                                <div class="bg-surface-container-low rounded-lg p-3">
                                    <dt class="text-on-surface-variant text-[11px] uppercase">Min. Pembelian</dt>
                                    <dd class="text-on-surface font-bold mt-1">{{ $promo->minimal_pembelian ? 'Rp '.number_format($promo->minimal_pembelian,0,',','.') : 'Tanpa minimum' }}</dd>
                                </div>
                                <div class="bg-surface-container-low rounded-lg p-3">
                                    <dt class="text-on-surface-variant text-[11px] uppercase">Maks. Diskon</dt>
                                    <dd class="text-on-surface font-bold mt-1">{{ $promo->maksimal_diskon ? 'Rp '.number_format($promo->maksimal_diskon,0,',','.') : 'Tidak dibatasi' }}</dd>
                                </div>
                                <div class="bg-surface-container-low rounded-lg p-3 col-span-2">
                                    <dt class="text-on-surface-variant text-[11px] uppercase">Periode Berlaku</dt>
                                    <dd class="text-on-surface font-bold mt-1">{{ $promo->mulai_pada?->translatedFormat('d M Y') }} — {{ $promo->berakhir_pada?->translatedFormat('d M Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
                            <button type="button" data-modal-close class="py-2.5 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-on-surface-variant text-sm col-span-full py-8 text-center">Belum ada promo.</p>
            @endforelse
        </div>
    </section>
</div>

{{-- Modal Tambah Promo (centered) --}}
<div id="modal-tambah-promo" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Promo Baru</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Promo akan langsung aktif untuk toko Anda.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('owner.promo.store') }}" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Kode Promo</label>
                    <input name="kode_promo" type="text" required placeholder="DISKON10" class="raliva-input uppercase" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nama Promo</label>
                    <input name="nama_promo" type="text" required placeholder="Diskon Lebaran" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Tipe Diskon</label>
                    <select name="tipe_diskon" class="raliva-select">
                        <option value="persen">Persen (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nilai Diskon</label>
                    <input name="nilai_diskon" type="number" min="1" required placeholder="10" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Min. Pembelian (Rp)</label>
                    <input name="minimal_pembelian" type="number" min="0" placeholder="0" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Maks. Diskon (Rp)</label>
                    <input name="maksimal_diskon" type="number" min="0" placeholder="opsional" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Mulai</label>
                    <input name="mulai_pada" type="date" required value="{{ date('Y-m-d') }}" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Berakhir</label>
                    <input name="berakhir_pada" type="date" required class="raliva-input" />
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>Simpan Promo
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
