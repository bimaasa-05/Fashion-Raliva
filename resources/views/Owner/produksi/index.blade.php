@extends('layouts.owner')

@section('title', 'Produksi')

@section('header-title', 'Produksi')
@section('header-badge', '3 Berjalan')
@section('header-subtitle', 'Pantau permintaan produksi barang untuk toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="space-y-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-36 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Permintaan Berjalan</span>
            <span class="raliva-figure text-[26px] text-on-surface">3</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">8</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Unit Diproduksi (Agu)</span>
            <span class="raliva-figure text-[26px] text-on-surface">640</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Persetujuan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">1</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
    </section>

    {{-- Daftar Permintaan Produksi --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Permintaan Produksi</h2>
            <button data-reveal type="button" data-modal-open="modal-buat-produksi" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full sm:w-auto shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>Permintaan Baru
            </button>
        </div>

        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['kode' => 'PRQ-0041', 'produk' => 'Blazer Wool Premium', 'qty' => 40, 'target' => '30 Agu 2026', 'pic' => 'Tim Produksi Internal', 'progress' => 65, 'tahap' => 'Penjahitan — tahap 3 dari 5', 'status' => 'Diproses', 'key' => 'diproses'],
                ['kode' => 'PRQ-0040', 'produk' => 'Kemeja Linen Oversized', 'qty' => 120, 'target' => '28 Agu 2026', 'pic' => 'Konveksi Mitra Sentra Abadi', 'progress' => 90, 'tahap' => 'Quality control & finishing', 'status' => 'Diproses', 'key' => 'diproses'],
                ['kode' => 'PRQ-0042', 'produk' => 'Silk Scarf Monogram', 'qty' => 80, 'target' => '05 Sep 2026', 'pic' => 'Menunggu konfirmasi mitra', 'progress' => 0, 'tahap' => 'Belum dimulai', 'status' => 'Menunggu Persetujuan', 'key' => 'menunggu'],
                ['kode' => 'PRQ-0039', 'produk' => 'Wide Leg Trousers', 'qty' => 60, 'target' => '20 Agu 2026', 'pic' => 'Tim Produksi Internal', 'progress' => 100, 'tahap' => 'Selesai — masuk gudang utama', 'status' => 'Selesai', 'key' => 'selesai'],
                ['kode' => 'PRQ-0038', 'produk' => 'Knit Cardigan Rajut', 'qty' => 45, 'target' => '12 Agu 2026', 'pic' => 'Rajut Mandiri Bandung', 'progress' => 100, 'tahap' => 'Selesai — masuk gudang utama', 'status' => 'Selesai', 'key' => 'selesai'],
            ] as $pr)
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 md:p-6 card-premium">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[22px] text-gold-accent">precision_manufacturing</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface">{{ $pr['kode'] }} — {{ $pr['produk'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ number_format($pr['qty'], 0, ',', '.') }} unit • Target {{ $pr['target'] }} • {{ $pr['pic'] }}</p>
                            </div>
                        </div>
                        @if ($pr['key'] === 'diproses')
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $pr['status'] }}</span>
                        @elseif ($pr['key'] === 'selesai')
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-deep-onyx text-on-primary text-[10px] font-bold uppercase">{{ $pr['status'] }}</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $pr['status'] }}</span>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-1 lg:grid-cols-5 gap-4 items-end">
                        <div class="lg:col-span-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ $pr['tahap'] }}</span>
                                <span class="font-label-sm text-[11px] font-bold text-secondary">{{ $pr['progress'] }}%</span>
                            </div>
                            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                                <div class="progress-fill h-full rounded-full" data-progress="{{ $pr['progress'] }}"></div>
                            </div>
                        </div>
                        <div class="flex gap-gutter lg:justify-end">
                            @if ($pr['key'] === 'menunggu')
                                <button type="button" onclick="showRalivaToast('Permintaan produksi dikirim ke mitra (demo).', 'send')" class="flex-1 lg:flex-none py-2.5 px-4 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium whitespace-nowrap">Kirim</button>
                                <button type="button" onclick="showRalivaToast('Permintaan dibatalkan (demo).', 'cancel')" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-error hover:border-error/40 transition-colors whitespace-nowrap">Batal</button>
                            @else
                                <button type="button" onclick="showRalivaToast('Detail produksi dibuka (demo).', 'visibility')" class="flex-1 lg:flex-none py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Lihat Detail</button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>

{{-- Modal Buat Permintaan Produksi --}}
<div id="modal-buat-produksi" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Permintaan Produksi Baru</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Ajukan produksi internal atau melalui mitra konveksi.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Permintaan produksi berhasil dibuat." class="p-6 space-y-5">
            <div>
                <label for="pd-produk" class="block raliva-label mb-2">Produk</label>
                <select id="pd-produk" required class="raliva-select">
                    <option>Trench Coat Signature</option>
                    <option>Blazer Wool Premium</option>
                    <option selected>Silk Scarf Monogram</option>
                    <option>Dress Midi Satin</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="pd-jumlah" class="block raliva-label mb-2">Jumlah Unit</label>
                    <input id="pd-jumlah" type="number" value="80" min="1" required class="raliva-input" />
                </div>
                <div>
                    <label for="pd-target" class="block raliva-label mb-2">Target Selesai</label>
                    <input id="pd-target" type="date" value="2026-09-05" required class="raliva-input" />
                </div>
            </div>
            <div>
                <label for="pd-mitra" class="block raliva-label mb-2">Pelaksana</label>
                <select id="pd-mitra" class="raliva-select">
                    <option>Tim Produksi Internal</option>
                    <option>Konveksi Mitra Sentra Abadi</option>
                    <option>Rajut Mandiri Bandung</option>
                </select>
            </div>
            <div>
                <label for="pd-catatan" class="block raliva-label mb-2">Catatan Material / Instruksi</label>
                <textarea id="pd-catatan" rows="3" placeholder="cth. Gunakan sutra premium grade A, warna gold sesuai swatch..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span>Buat Permintaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
