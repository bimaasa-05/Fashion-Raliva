@extends('layouts.owner')

@section('title', 'Moderasi Produk')

@section('header-title', 'Moderasi Produk')
@section('header-badge', '5 Menunggu Review')
@section('header-subtitle', 'Pantau status verifikasi produk dan alasan penolakannya.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="space-y-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-32 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Info Alur Moderasi --}}
    <section data-reveal class="bg-gold-accent/5 border border-gold-accent/30 rounded-lg px-6 py-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-[22px] text-gold-accent mt-0.5">info</span>
        <p class="font-body-md text-sm text-on-surface">Setiap produk baru atau revisi akan direview moderator platform (estimasi <span class="font-bold">1–2 hari kerja</span>) sebelum tampil publik. Produk yang ditolak dapat diperbaiki dan diajukan ulang tanpa memakan slot baru.</p>
    </section>

    {{-- Ringkasan Status --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="raliva-figure text-[26px] text-on-surface">136</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Review</span>
            <span class="raliva-figure text-[26px] text-gold-accent">5</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Disetujui &amp; Tayang</span>
            <span class="raliva-figure text-[26px] text-secondary">128</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditolak</span>
            <span class="raliva-figure text-[26px] text-error">3</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">cancel</span>
        </div>
    </section>

    {{-- Daftar Moderasi --}}
    <section data-table-scope>
        <div data-reveal class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Status Verifikasi Produk</h2>
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari produk..." data-table-search class="raliva-search" />
            </div>
        </div>

        {{-- Tab Status --}}
        <div data-reveal class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 mb-6 overflow-x-auto max-w-full">
            <button type="button" data-mod-tab="semua" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Semua (8)</button>
            <button type="button" data-mod-tab="pending" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Menunggu (2)</button>
            <button type="button" data-mod-tab="disetujui" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Disetujui (4)</button>
            <button type="button" data-mod-tab="ditolak" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Ditolak (2)</button>
        </div>

        <div data-reveal-group class="space-y-gutter">
            @foreach ([
                ['nama' => 'Blazer Wool Premium', 'sku' => 'RLV-BWP-014', 'versi' => 'Revisi v2 — perubahan harga & foto detail', 'tgl' => '21 Agu 2026', 'status' => 'pending', 'catatan' => 'Menunggu review moderator. Estimasi selesai dalam 1–2 hari kerja.', 'ikon' => 'schedule'],
                ['nama' => 'Dress Midi Satin', 'sku' => 'RLV-DMS-011', 'versi' => 'Unggahan baru', 'tgl' => '19 Agu 2026', 'status' => 'disetujui', 'catatan' => 'Tayang publik sejak 20 Agu 2026, 09:12.', 'ikon' => 'verified'],
                ['nama' => 'Jaket Denim Vintage Wash', 'sku' => 'RLV-JDV-005', 'versi' => 'Unggahan baru', 'tgl' => '18 Agu 2026', 'status' => 'ditolak', 'alasan' => 'Jumlah foto kurang dari 3. Mohon tambahkan foto detail bahan, jahitan, dan label ukuran sesuai panduan katalog.', 'ikon' => 'cancel'],
                ['nama' => 'Kemeja Linen Oversized', 'sku' => 'RLV-KLO-032', 'versi' => 'Perubahan deskripsi bahan', 'tgl' => '17 Agu 2026', 'status' => 'disetujui', 'catatan' => 'Revisi disetujui dan tayang sejak 17 Agu 2026, 15:40.', 'ikon' => 'verified'],
                ['nama' => 'Silk Scarf Monogram', 'sku' => 'RLV-SSM-002', 'versi' => 'Isi ulang stok & foto baru', 'tgl' => '16 Agu 2026', 'status' => 'pending', 'catatan' => 'Antrian review ke-2. Estimasi selesai besok pagi.', 'ikon' => 'schedule'],
                ['nama' => 'Wide Leg Trousers', 'sku' => 'RLV-WLT-008', 'versi' => 'Perubahan harga promo', 'tgl' => '15 Agu 2026', 'status' => 'disetujui', 'catatan' => 'Harga baru berlaku sejak 16 Agu 2026.', 'ikon' => 'verified'],
                ['nama' => 'Trench Coat Signature', 'sku' => 'RLV-TCS-001', 'versi' => 'Perbaikan ukuran chart', 'tgl' => '14 Agu 2026', 'status' => 'ditolak', 'alasan' => 'Tabel ukuran tidak sesuai standar (tidak mencantumkan lingkar pinggang). Gunakan template ukuran resmi Raliva pada pusat panduan.', 'ikon' => 'cancel'],
                ['nama' => 'Knit Cardigan Rajut', 'sku' => 'RLV-KCR-021', 'versi' => 'Unggahan baru', 'tgl' => '12 Agu 2026', 'status' => 'disetujui', 'catatan' => 'Tayang publik sejak 13 Agu 2026, 08:05.', 'ikon' => 'verified'],
            ] as $item)
                <article data-reveal data-mod-row data-status="{{ $item['status'] }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 card-premium">
                    <div class="flex flex-col md:flex-row md:items-start gap-4 justify-between">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-12 aspect-[4/5] rounded-md bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[22px] text-on-surface-variant">checkroom</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface">{{ $item['nama'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $item['sku'] }} • {{ $item['versi'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">Diajukan {{ $item['tgl'] }}</p>
                            </div>
                        </div>
                        @if ($item['status'] === 'disetujui')
                            <span class="inline-flex shrink-0 items-center px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span>
                        @elseif ($item['status'] === 'ditolak')
                            <span class="inline-flex shrink-0 items-center px-2.5 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span>
                        @else
                            <span class="inline-flex shrink-0 items-center px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menunggu Review</span>
                        @endif
                    </div>

                    @if ($item['status'] === 'ditolak')
                        <div class="mt-4 border border-error/25 bg-error/5 rounded-lg px-4 py-3.5 flex items-start gap-3">
                            <span class="material-symbols-outlined text-[20px] text-error mt-0.5 shrink-0">error</span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 flex-1">
                                <p class="font-body-md text-sm text-on-surface"><span class="font-bold text-error">Alasan penolakan:</span> {{ $item['alasan'] }}</p>
                                <button type="button" data-modal-open="modal-perbaiki-produk" class="shrink-0 px-4 py-2 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium whitespace-nowrap">Perbaiki &amp; Ajukan Ulang</button>
                            </div>
                        </div>
                    @elseif ($item['status'] === 'pending')
                        <div class="mt-4 border border-gold-accent/25 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px] text-gold-accent">hourglass_top</span>
                            <p class="font-body-md text-sm text-on-surface-variant">{{ $item['catatan'] }}</p>
                        </div>
                    @else
                        <p class="mt-4 text-xs text-on-surface-variant flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] fill text-secondary">check_circle</span>{{ $item['catatan'] }}
                        </p>
                    @endif
                </article>
            @endforeach
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada produk pada status ini.</p>
        </div>
    </section>
</div>

{{-- Modal Perbaiki Produk --}}
<div id="modal-perbaiki-produk" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Perbaikan Pengajuan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Produk akan kembali masuk antrean moderasi setelah dikirim.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Perbaikan produk berhasil diajukan ulang." class="p-6 space-y-5">
            <div class="border border-muted-border bg-surface-container-low rounded-lg px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">tips_and_updates</span>
                <p class="text-on-surface-variant font-body-md text-sm">Baca alasan penolakan dengan teliti. Revisi yang sama tanpa perbaikan dapat memperpanjang waktu review.</p>
            </div>
            <div>
                <label for="revisi-catatan" class="block raliva-label mb-2">Ringkasan Perbaikan</label>
                <textarea id="revisi-catatan" rows="4" placeholder="cth. Menambahkan 3 foto detail bahan dan mengganti tabel ukuran sesuai template resmi..." required class="raliva-textarea"></textarea>
            </div>
            <div>
                <label class="block raliva-label mb-2">Lampiran Tambahan (opsional)</label>
                <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-outline-variant rounded-lg px-6 py-8 cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group">
                    <span class="material-symbols-outlined text-[30px] text-on-surface-variant group-hover:text-gold-accent transition-colors">upload_file</span>
                    <span class="text-on-surface-variant font-body-md text-sm">Pilih file pendukung</span>
                </label>
                <input type="file" class="hidden" />
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Ajukan Ulang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-mod-tab]').forEach((tab) => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('[data-mod-tab]').forEach((t) => {
                const active = t === tab;
                t.classList.toggle('bg-deep-onyx', active);
                t.classList.toggle('text-on-primary', active);
                t.classList.toggle('text-on-surface-variant', !active);
            });
            const target = tab.getAttribute('data-mod-tab');
            let visible = 0;
            document.querySelectorAll('[data-mod-row]').forEach((row) => {
                const show = target === 'semua' || row.getAttribute('data-status') === target;
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            document.querySelector('[data-empty-state]')?.classList.toggle('hidden', visible > 0);
            document.querySelector('[data-empty-state]')?.classList.toggle('flex', visible === 0);
        });
    });
</script>
@endpush
