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
            <span class="raliva-figure text-[26px] text-on-surface">3</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_offer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Penukaran Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">184</span>
            <span class="font-label-sm text-[11px] text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">trending_up</span>+22% vs Juli</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">redeem</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Estimasi Diskon Diberikan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">Rp 8.420.000</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">savings</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Konversi Promo</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>18</span>%</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">query_stats</span>
        </div>
    </section>

    {{-- Daftar Promo --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Promo</h2>
            <button data-reveal type="button" data-modal-open="modal-buat-promo" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full sm:w-auto shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>Buat Promo Baru
            </button>
        </div>

        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-section-gap">
            @foreach ([
                ['kode' => 'GAJIAN25', 'nama' => 'Promo Gajian Agustus', 'tipe' => 'Diskon 25%', 'maks' => 'Maks. Rp 250rb', 'min' => 'Min. belanja Rp 500rb', 'periode' => '25 — 31 Agu 2026', 'terpakai' => 84, 'kuota' => 150, 'status' => 'aktif', 'sisa' => 'berakhir dalam 9 hari'],
                ['kode' => 'NEWFRIEND', 'nama' => 'Bonus Customer Baru', 'tipe' => 'Diskon Rp 75rb', 'maks' => 'Tanpa batas nominal', 'min' => 'Min. belanja Rp 300rb', 'periode' => '01 Jun — 30 Sep 2026', 'terpakai' => 62, 'kuota' => 100, 'status' => 'aktif', 'sisa' => 'berakhir dalam 39 hari'],
                ['kode' => 'RALIVA10', 'nama' => 'Promo Ulang Tahun Raliva', 'tipe' => 'Diskon 10%', 'maks' => 'Tanpa batas nominal', 'min' => 'Tanpa minimum belanja', 'periode' => '01 Sep — 07 Sep 2026', 'terpakai' => 0, 'kuota' => 300, 'status' => 'terjadwal', 'sisa' => 'mulai dalam 10 hari'],
                ['kode' => 'MERDEKA45', 'nama' => 'Diskon Kemerdekaan', 'tipe' => 'Diskon 45%', 'maks' => 'Maks. Rp 400rb', 'min' => 'Min. belanja Rp 750rb', 'periode' => '10 — 17 Agu 2026', 'terpakai' => 150, 'kuota' => 150, 'status' => 'selesai', 'sisa' => 'kuota habis'],
                ['kode' => 'WEEKEND20', 'nama' => 'Weekend Flash Sale', 'tipe' => 'Diskon 20%', 'maks' => 'Maks. Rp 150rb', 'min' => 'Min. belanja Rp 250rb', 'periode' => '08 — 09 Agu 2026', 'terpakai' => 96, 'kuota' => 120, 'status' => 'selesai', 'sisa' => 'periode berakhir'],
                ['kode' => 'CASHBACK50', 'nama' => 'Cashback Raliva Pay', 'tipe' => 'Cashback Rp 50rb', 'maks' => 'Maks. Rp 50rb', 'min' => 'Via Raliva Pay', 'periode' => '01 — 31 Jul 2026', 'terpakai' => 74, 'kuota' => 80, 'status' => 'nonaktif', 'sisa' => 'nonaktif manual'],
            ] as $promo)
                <article data-reveal class="bg-surface-container-lowest border {{ $promo['status'] === 'aktif' ? 'border-gold-accent/40' : 'border-muted-border' }} rounded-lg p-5 flex flex-col gap-4 card-premium relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1 {{ $promo['status'] === 'aktif' ? 'bg-gradient-to-r from-gold-accent to-secondary' : ($promo['status'] === 'terjadwal' ? 'bg-gold-accent/40' : 'bg-surface-container-high') }}"></div>
                    <div class="flex items-start justify-between gap-3 pt-1">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px] text-gold-accent">sell</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-base text-on-surface tracking-wide truncate">{{ $promo['kode'] }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $promo['nama'] }}</p>
                            </div>
                        </div>
                        @if ($promo['status'] === 'aktif')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Aktif</span>
                        @elseif ($promo['status'] === 'terjadwal')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Terjadwal</span>
                        @elseif ($promo['status'] === 'nonaktif')
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Selesai</span>
                        @endif
                    </div>

                    <dl class="space-y-1.5 font-body-md text-xs text-on-surface-variant">
                        <div class="flex justify-between gap-3"><dt>Tipe</dt><dd class="text-on-surface font-bold">{{ $promo['tipe'] }} <span class="font-normal">• {{ $promo['maks'] }}</span></dd></div>
                        <div class="flex justify-between gap-3"><dt>Syarat</dt><dd class="text-on-surface">{{ $promo['min'] }}</dd></div>
                        <div class="flex justify-between gap-3"><dt>Periode</dt><dd class="text-on-surface">{{ $promo['periode'] }}</dd></div>
                    </dl>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Kuota Terpakai</span>
                            <span class="font-label-sm text-[11px] font-bold text-on-surface">{{ $promo['terpakai'] }} / {{ $promo['kuota'] }}</span>
                        </div>
                        <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $promo['kuota'] > 0 ? round($promo['terpakai'] / $promo['kuota'] * 100) : 0 }}"></div>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-1.5 italic">{{ $promo['sisa'] }}</p>
                    </div>

                    <div class="flex items-center gap-gutter pt-1 mt-auto">
                        <button type="button" onclick="showRalivaToast('Form edit promo dibuka (demo).', 'edit')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Kelola</button>
                        @if (in_array($promo['status'], ['aktif', 'terjadwal']))
                            <button type="button" onclick="showRalivaToast('Promo {{ $promo['kode'] }} dinonaktifkan (demo).', 'pause_circle')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-error hover:bg-error/5 hover:border-error/40 transition-colors">Hentikan</button>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>

{{-- Modal Buat Promo --}}
<div id="modal-buat-promo" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-6 md:mt-10 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Buat Promo Baru</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Promo berlaku untuk seluruh produk toko Anda.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Promo baru berhasil dibuat." class="p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                <div>
                    <label for="pr-nama" class="block raliva-label mb-2">Nama Promo</label>
                    <input id="pr-nama" type="text" placeholder="cth. Flash Sale Akhir Bulan" required class="raliva-input" />
                </div>
                <div>
                    <label for="pr-kode" class="block raliva-label mb-2">Kode Promo</label>
                    <input id="pr-kode" type="text" placeholder="cth. FLASHSALE" required class="raliva-input uppercase" />
                </div>
            </div>

            <div>
                <p class="block raliva-label mb-2">Tipe Promo</p>
                <div class="grid grid-cols-3 gap-gutter">
                    @foreach ([['persen', 'Persen (%)'], ['nominal', 'Nominal (Rp)'], ['cashback', 'Cashback']] as $i => $tipe)
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe-promo" value="{{ $tipe[0] }}" {{ $i === 0 ? 'checked' : '' }} class="sr-only peer" />
                            <span class="block text-center py-2.5 border border-muted-border rounded-lg text-xs font-medium text-on-surface peer-checked:border-gold-accent peer-checked:bg-gold-accent/10 peer-checked:text-gold-accent transition-colors">{{ $tipe[1] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="pr-nilai" class="block raliva-label mb-2">Nilai Diskon</label>
                    <input id="pr-nilai" type="number" placeholder="cth. 25 atau 50000" required class="raliva-input" />
                </div>
                <div>
                    <label for="pr-maks" class="block raliva-label mb-2">Potongan Maksimal (Rp)</label>
                    <input id="pr-maks" type="number" placeholder="cth. 250000" class="raliva-input" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="pr-mulai" class="block raliva-label mb-2">Mulai</label>
                    <input id="pr-mulai" type="date" value="2026-09-01" required class="raliva-input" />
                </div>
                <div>
                    <label for="pr-selesai" class="block raliva-label mb-2">Selesai</label>
                    <input id="pr-selesai" type="date" value="2026-09-07" required class="raliva-input" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="pr-min" class="block raliva-label mb-2">Minimum Belanja (Rp)</label>
                    <input id="pr-min" type="number" placeholder="0 = tanpa minimum" class="raliva-input" />
                </div>
                <div>
                    <label for="pr-kuota" class="block raliva-label mb-2">Kuota Pemakaian</label>
                    <input id="pr-kuota" type="number" placeholder="cth. 200" required class="raliva-input" />
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">local_offer</span>Terbitkan Promo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
