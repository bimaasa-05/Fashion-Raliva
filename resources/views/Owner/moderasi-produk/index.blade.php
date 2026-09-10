@extends('layouts.owner')

@section('title', 'Moderasi Produk')

@section('header-title', 'Moderasi Produk')
@if ($summary['pending'] > 0)
@section('header-badge', $summary['pending'] . ' Menunggu Review')
@endif
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
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Info Alur Moderasi --}}
    <section data-reveal class="bg-gold-accent/5 border border-gold-accent/30 rounded-lg px-6 py-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-[22px] text-gold-accent mt-0.5">info</span>
        <p class="font-body-md text-sm text-on-surface">Setiap produk baru atau revisi akan direview moderator platform (estimasi <span class="font-bold">1–2 hari kerja</span>) sebelum tampil publik. Produk yang ditolak dapat diperbaiki dan diajukan ulang tanpa memakan slot baru.</p>
    </section>

    {{-- Ringkasan Status --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['total'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">checkroom</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Review</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $summary['pending'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Disetujui &amp; Tayang</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $summary['aktif'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Ditolak</span>
            <span class="raliva-figure text-[26px] text-error">{{ $summary['ditolak'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">cancel</span>
        </div>
    </section>

    {{-- Daftar Moderasi --}}
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div data-reveal class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Katalog Moderasi</h2>
                <p class="text-xs text-on-surface-variant mt-1">Review dan pantau status verifikasi produk sebelum tampil publik.</p>
            </div>
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari produk..." data-table-search class="raliva-search" />
            </div>
        </div>

        {{-- Tab Status --}}
        <div data-reveal class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 mb-6 overflow-x-auto max-w-full">
            <button type="button" data-mod-tab="semua" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Semua ({{ $products->total() }})</button>
            <button type="button" data-mod-tab="pending" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Menunggu ({{ $summary['pending'] }})</button>
            <button type="button" data-mod-tab="disetujui" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Disetujui ({{ $summary['aktif'] }})</button>
            <button type="button" data-mod-tab="ditolak" class="mod-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Ditolak ({{ $summary['ditolak'] }})</button>
        </div>

        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @forelse ($products as $item)
                @php
                    $sku = $item->variants->first()?->sku ?? '-';
                    $status = $item->status;
                    $fotos = $item->images->map(fn ($img) => asset('storage/' . $img->file_gambar))->values()->all();
                @endphp
                <article data-reveal data-mod-row data-status="{{ $status === 'aktif' ? 'disetujui' : $status }}" data-produk-id="{{ $item->product_id }}" data-produk-nama="{{ $item->nama_produk }}" data-produk-sku="{{ $sku }}" data-produk-created="{{ $item->created_at->translatedFormat('d M Y') }}" data-produk-harga="Rp {{ number_format((float) $item->harga_dasar, 0, ',', '.') }}" data-produk-kategori="{{ $item->category?->nama_kategori ?? '-' }}" data-produk-tipe="{{ ucfirst($item->tipe_produk) }}" data-produk-varian="{{ $item->variants->map(fn ($v) => trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')))->filter()->implode(', ') }}" data-produk-deskripsi="{{ $item->deskripsi }}" data-produk-status="{{ $status }}" data-produk-verified="{{ $item->owner_verified_at ? '1' : '0' }}" data-produk-alasan="{{ $status === 'ditolak' ? ($item->alasan_penolakan ?? '') : '' }}" data-produk-images='@json($fotos)' class="group bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium flex flex-col">
                    <div class="relative aspect-[4/3] bg-surface-container-low overflow-hidden" data-produk-gallery>
                        @if (count($fotos))
                            <div class="block w-full h-full" data-produk-main>
                                <img src="{{ $fotos[0] }}" alt="{{ $item->nama_produk }}" data-produk-main-img class="w-full h-full object-cover transition-opacity duration-300" loading="lazy" />
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[42px] text-on-surface-variant/40">checkroom</span>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            @if ($status === 'aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Disetujui</span>
                            @elseif ($status === 'ditolak')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Ditolak</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface/80 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Menunggu</span>
                            @endif
                        </div>
                        @if ($item->owner_verified_at && $status === 'pending')
                            <div class="absolute top-2 left-2 px-2 py-1 bg-gold-accent text-white text-[9px] font-bold uppercase tracking-widest rounded">✓ Owner</div>
                        @endif
                    </div>
                    @if (count($fotos) > 1)
                        <div class="flex gap-2 px-4 pt-3 overflow-x-auto" data-produk-strip>
                            @foreach ($fotos as $i => $f)
                                <button type="button" data-produk-pin="{{ $i }}" aria-label="Tampilkan foto {{ $i + 1 }} dari {{ $item->nama_produk }}" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" class="h-14 w-16 shrink-0 rounded-md overflow-hidden border transition-colors {{ $i === 0 ? 'border-gold-accent ring-2 ring-gold-accent/30' : 'border-outline-variant hover:border-gold-accent' }}">
                                    <img src="{{ $f }}" alt="" class="w-full h-full object-cover" loading="lazy" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                    <div class="px-4 pb-4 pt-2 flex flex-col gap-1 flex-1">
                        <h3 class="font-bold text-on-surface leading-tight truncate">{{ $item->nama_produk }}</h3>
                        <p class="text-xs text-on-surface-variant">{{ $sku }} • Diajukan {{ $item->created_at->translatedFormat('d M Y') }}</p>
                        <p class="font-body-md text-gold-accent font-bold mt-1">Rp {{ number_format((float) $item->harga_dasar, 0, ',', '.') }}</p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border gap-2 flex-wrap">
                            <span class="text-xs text-on-surface-variant truncate">{{ $item->category?->nama_kategori ?? '-' }}</span>
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                <button type="button" data-produk-detail class="inline-flex items-center gap-1 px-3 py-1.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">visibility</span>Detail</button>
                                @if ($status === 'pending' && ! $item->owner_verified_at)
                                    <form method="POST" action="{{ route('owner.moderasi-produk.setujui', $item) }}" onsubmit="return confirm('Setujui {{ $item->nama_produk }} dan teruskan ke SuperAdmin?');" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">check</span>Setujui</button>
                                    </form>
                                    <button type="button" data-produk-tolak="{{ $item->product_id }}" data-produk-nama="{{ $item->nama_produk }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-error/10 border border-error/20 text-error text-xs font-semibold rounded-lg hover:bg-error/20 transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">close</span>Tolak</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-on-surface-variant text-sm py-8 text-center">Tidak ada produk dalam antrean moderasi.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $products->links() }}</div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada produk pada status ini.</p>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.querySelector('[data-no-store-banner]');
  if (!noStore) return;
  // Disable all primary action buttons except Ajukan Toko
  document.querySelectorAll('[data-modal-open], button[type="submit"], a[href*="pengajuan-toko"]:not([href*="ajukan"])').forEach(el=>{
    // Keep Ajukan Toko enabled
    if (el.textContent.includes('Ajukan Toko') || el.getAttribute('data-modal-open')?.includes('modal-tambah')) {
      // For tambah buttons, disable if no store
      el.setAttribute('disabled','');
      el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
      el.title = 'Ajukan toko dulu';
    }
  });
  // More generic: disable all buttons in data-real except those inside pengajuan
  document.querySelectorAll('[data-real] button, [data-real] a.btn-premium').forEach(el=>{
    if (el.closest('[data-modal]')) return;
    if (el.textContent.trim().includes('Ajukan')) return;
    el.setAttribute('disabled','');
    el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
  });
});
</script>
@endpush

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

{{-- Modal Detail Produk --}}
<div id="modal-detail-produk" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div class="min-w-0">
                <h3 id="detail-nama" class="font-title-md text-title-md text-on-surface premium-heading">-</h3>
                <p id="detail-sub" class="text-on-surface-variant font-body-md text-xs mt-1">-</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <div id="detail-main-wrap" class="rounded-lg overflow-hidden border border-outline-variant bg-surface-container-high h-64 flex items-center justify-center">
                <img id="detail-main-img" class="w-full h-full object-cover" src="" alt="Foto produk" />
                <div id="detail-noimg" class="hidden flex-col items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[40px]">checkroom</span>
                    <p class="text-xs">Belum ada foto produk</p>
                </div>
            </div>
            <div id="detail-gallery" class="grid grid-cols-4 gap-2"></div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div><dt class="raliva-label">Harga Dasar</dt><dd id="detail-harga" class="font-bold text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Kategori</dt><dd id="detail-kategori" class="text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Tipe</dt><dd id="detail-tipe" class="text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Varian</dt><dd id="detail-varian" class="text-on-surface mt-1">-</dd></div>
                <div class="sm:col-span-2"><dt class="raliva-label">Deskripsi</dt><dd id="detail-deskripsi" class="text-on-surface mt-1 whitespace-pre-line">-</dd></div>
            </dl>
            <div id="detail-reason-box" class="hidden rounded-lg border border-error/20 bg-error/5 px-4 py-3">
                <p class="font-label-sm text-[10px] uppercase tracking-wider text-error">Alasan Penolakan</p>
                <p id="detail-reason" class="text-sm text-on-surface mt-1">-</p>
            </div>
            <div id="detail-actions" class="flex gap-3">
                <form id="detail-setujui-form" method="POST" action="#" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded-lg btn-premium"><span class="material-symbols-outlined text-[18px]">check</span>Setujui &amp; Teruskan</button>
                </form>
                <button type="button" id="detail-tolak-btn" class="flex-1 inline-flex items-center justify-center gap-1.5 py-3 bg-error/10 border border-error/20 text-error text-sm font-semibold rounded-lg hover:bg-error/20 transition-colors"><span class="material-symbols-outlined text-[18px]">close</span>Tolak</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tolak Produk --}}
<div id="modal-tolak-produk" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tolak Produk</h3>
        <p class="text-on-surface-variant font-body-md text-sm mt-1">Tolak <span id="tolak-nama" class="font-bold text-on-surface">-</span> beserta alasannya.</p>
        <form id="tolak-form" method="POST" action="#" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="tolak-alasan" class="block raliva-label mb-2">Alasan Penolakan</label>
                <textarea id="tolak-alasan" name="alasan" rows="4" required minlength="10" placeholder="Jelaskan alasan penolakan minimal 10 karakter..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" data-modal-close class="flex-1 inline-flex items-center justify-center gap-1.5 py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors"><span class="material-symbols-outlined text-[18px]">close</span>Batal</button>
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 py-3 bg-error/10 border border-error/20 text-error text-sm font-semibold rounded-lg hover:bg-error/20 transition-colors"><span class="material-symbols-outlined text-[18px]">block</span>Tolak Produk</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const detailModal = document.getElementById('modal-detail-produk');
        const tolakModal = document.getElementById('modal-tolak-produk');
        const openModal = (el) => el?.classList.remove('hidden');
        const closeModal = (el) => el?.classList.add('hidden');
        const urlSetujui = (id) => '{{ route('owner.moderasi-produk.setujui', ':id:') }}'.replace(':id:', id);
        const urlTolak = (id) => '{{ route('owner.moderasi-produk.tolak', ':id:') }}'.replace(':id:', id);

        const fillGallery = (urls) => {
            const gallery = document.getElementById('detail-gallery');
            const main = document.getElementById('detail-main-img');
            const noimg = document.getElementById('detail-noimg');
            gallery.innerHTML = '';
            if (!urls.length) {
                main.classList.add('hidden');
                noimg.classList.remove('hidden');
                noimg.classList.add('flex');
                return;
            }
            main.classList.remove('hidden');
            noimg.classList.add('hidden');
            noimg.classList.remove('flex');
            main.src = urls[0];
            urls.forEach((u, i) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'h-20 rounded-lg overflow-hidden border ' + (i === 0 ? 'border-gold-accent ring-2 ring-gold-accent/30' : 'border-outline-variant');
                const im = document.createElement('img');
                im.src = u;
                im.alt = 'Foto produk ' + (i + 1);
                im.className = 'w-full h-full object-cover';
                im.loading = 'lazy';
                b.appendChild(im);
                b.addEventListener('click', () => {
                    main.src = u;
                    gallery.querySelectorAll('button').forEach((x) => {
                        x.classList.remove('border-gold-accent', 'ring-2', 'ring-gold-accent/30');
                        x.classList.add('border-outline-variant');
                    });
                    b.classList.remove('border-outline-variant');
                    b.classList.add('border-gold-accent', 'ring-2', 'ring-gold-accent/30');
                });
                gallery.appendChild(b);
            });
        };

        const openTolak = (id, nama) => {
            document.getElementById('tolak-nama').textContent = nama;
            document.getElementById('tolak-form').action = urlTolak(id);
            openModal(tolakModal);
        };

        document.querySelectorAll('[data-produk-detail]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const card = btn.closest('article');
                if (!card) return;
                const d = card.dataset;
                document.getElementById('detail-nama').textContent = d.produkNama || '-';
                document.getElementById('detail-sub').textContent = (d.produkSku || '-') + ' • Diajukan ' + (d.produkCreated || '-');
                document.getElementById('detail-harga').textContent = d.produkHarga || '-';
                document.getElementById('detail-kategori').textContent = d.produkKategori || '-';
                document.getElementById('detail-tipe').textContent = d.produkTipe || '-';
                document.getElementById('detail-varian').textContent = d.produkVarian || '-';
                document.getElementById('detail-deskripsi').textContent = d.produkDeskripsi || '-';
                const rbox = document.getElementById('detail-reason-box');
                if (d.produkStatus === 'ditolak' && d.produkAlasan) {
                    rbox.classList.remove('hidden');
                    document.getElementById('detail-reason').textContent = d.produkAlasan;
                } else {
                    rbox.classList.add('hidden');
                }
                let urls = [];
                try { urls = JSON.parse(d.produkImages || '[]'); } catch (e) { urls = []; }
                fillGallery(urls);
                const acts = document.getElementById('detail-actions');
                if (d.produkVerified === '1' || d.produkStatus !== 'pending') {
                    acts.classList.add('hidden');
                } else {
                    acts.classList.remove('hidden');
                    const sf = document.getElementById('detail-setujui-form');
                    sf.action = urlSetujui(d.produkId);
                    sf.onsubmit = () => confirm('Setujui ' + (d.produkNama || 'produk') + ' dan teruskan ke SuperAdmin?');
                    document.getElementById('detail-tolak-btn').onclick = () => { closeModal(detailModal); openTolak(d.produkId, d.produkNama || ''); };
                }
                openModal(detailModal);
            });
        });

        document.querySelectorAll('[data-produk-tolak]').forEach((btn) => {
            btn.addEventListener('click', () => openTolak(btn.dataset.produkTolak, btn.dataset.produkNama || ''));
        });

        /* Galeri kartu: hover foto utama → foto ganti-ganti di dalam card;
           klik thumbnail → cycle berhenti & foto utama terkunci ke foto itu.
           Tanpa popup — semua terjadi di dalam card. */
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const cardTimers = new Map();
        const stopCardCycle = (card) => {
            const t = cardTimers.get(card);
            if (t) { clearInterval(t); cardTimers.delete(card); }
        };
        const paintCardPhoto = (card, index) => {
            const mainImg = card.querySelector('[data-produk-main-img]');
            const urls = card._galleryUrls || [];
            if (!mainImg || !urls.length) return;
            const i = Math.min(Math.max(index, 0), urls.length - 1);
            if (card._galleryShown === i && mainImg.getAttribute('src') === urls[i]) return;
            card._galleryShown = i;
            mainImg.onload = () => { mainImg.style.opacity = ''; };
            mainImg.style.opacity = '0';
            mainImg.src = urls[i];
            mainImg.alt = (card.dataset.produkNama || 'Foto produk') + ' — foto ' + (i + 1);
            card.querySelectorAll('[data-produk-pin]').forEach((th) => {
                const active = parseInt(th.dataset.produkPin || '0', 10) === i;
                th.setAttribute('aria-pressed', active ? 'true' : 'false');
                th.classList.toggle('border-gold-accent', active);
                th.classList.toggle('ring-2', active);
                th.classList.toggle('ring-gold-accent/30', active);
                th.classList.toggle('border-outline-variant', !active);
            });
        };
        document.querySelectorAll('article[data-produk-id]').forEach((card) => {
            let urls = [];
            try { urls = JSON.parse(card.dataset.produkImages || '[]'); } catch (e) { urls = []; }
            if (urls.length <= 1) return;
            card._galleryUrls = urls;
            card._galleryPinned = 0;
            card._galleryShown = 0;
            card.querySelectorAll('[data-produk-pin]').forEach((th) => {
                th.addEventListener('click', () => {
                    card._galleryPinned = parseInt(th.dataset.produkPin || '0', 10);
                    stopCardCycle(card);
                    paintCardPhoto(card, card._galleryPinned);
                });
            });
            if (reduceMotion) return;
            const gallery = card.querySelector('[data-produk-gallery]');
            if (!gallery) return;
            gallery.addEventListener('mouseenter', () => {
                stopCardCycle(card);
                let i = card._galleryPinned;
                cardTimers.set(card, setInterval(() => {
                    if (card.classList.contains('hidden')) return;
                    i = (i + 1) % urls.length;
                    paintCardPhoto(card, i);
                }, 1200));
            });
            gallery.addEventListener('mouseleave', () => {
                stopCardCycle(card);
                paintCardPhoto(card, card._galleryPinned);
            });
        });
        document.querySelectorAll('[data-mod-tab]').forEach((tab) => {
            tab.addEventListener('click', () => {
                cardTimers.forEach((t, card) => stopCardCycle(card));
            });
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') { closeModal(detailModal); closeModal(tolakModal); unlockScroll(); }
        });
    })();
</script>
@endpush
