@extends('layouts.owner')

@section('title', 'Gudang')

@section('header-title', 'Gudang')
@section('header-badge', '2 Gudang Aktif')
@section('header-subtitle', 'Kelola data gudang dan pantau stok di setiap lokasi.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Gudang</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['total'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">warehouse</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Unit Tersimpan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($summary['unit'], 0, ',', '.') }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">inventory_2</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kapasitas Terpakai</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>63</span>%</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="63"></div>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">equalizer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
            <span class="raliva-figure text-[26px] text-error">{{ $summary['menipis'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">warning</span>
        </div>
    </section>

    {{-- Daftar Gudang --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Gudang</h2>
            <p data-reveal class="text-xs text-on-surface-variant flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">lock</span> Halaman ini read-only</p>
        </div>

        <div data-reveal-group class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            @forelse ($warehouses as $g)
                @php
                    $petugas = $g->staff->pluck('nama_lengkap')->filter()->values()->all();
                @endphp
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[24px]">warehouse</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $g->nama_gudang }}</p>
                                <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">{{ $g->alamat }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">{{ ucfirst($g->status) }}</span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Kapasitas Terpakai</span>
                            <span class="font-label-sm text-[11px] font-bold text-on-surface">{{ $g->kapasitas }}%</span>
                        </div>
                        <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $g->kapasitas }}"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-gutter pt-4 border-t border-muted-border text-center">
                        <div>
                            <p class="font-title-md text-base text-on-surface">{{ number_format($summary['unit'], 0, ',', '.') }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Unit Stok</p>
                        </div>
                        <div class="border-x border-muted-border">
                            <p class="font-title-md text-base text-on-surface">{{ $g->produk ?? 0 }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Varian Produk</p>
                        </div>
                        <div>
                            <p class="font-title-md text-base {{ $summary['menipis'] > 0 ? 'text-error' : 'text-secondary' }}">{{ $summary['menipis'] }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Stok Menipis</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @foreach ($petugas as $p)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                                <span class="material-symbols-outlined text-[14px] text-secondary">person</span>{{ $p }}
                            </span>
                        @endforeach
                    </div>

                    <div class="flex gap-gutter mt-auto">
                        <button type="button" data-modal-open="modal-gudang-{{ $g->warehouse_id }}" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Detail</button>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-on-surface-variant text-sm py-8 text-center">Belum ada gudang terdaftar untuk toko ini.</p>
            @endforelse
    </section>

    {{-- Ringkasan Stok Antar Gudang --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Ringkasan Stok Kritis</h2>
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari produk..." data-table-search class="raliva-search" />
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Gudang</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Sisa Stok</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terjual / Minggu</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses->flatMap->variants ?: [] as $v)
                        <tr data-table-row class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $v->product->nama_produk ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $v->sku }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">—</td>
                            <td class="py-3.5 px-4 text-center font-bold text-on-surface-variant">—</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">—</td>
                            <td class="py-3.5 px-4 text-right text-xs font-semibold text-on-surface-variant">Read-only</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-on-surface-variant text-sm">Tidak ada data stok kritis.</td></tr>
                    @endforelse
            </table>
        </div>
    </section>
</div>

{{-- Modal Detail Gudang --}}
@foreach ($warehouses as $g)
<div id="modal-gudang-{{ $g->warehouse_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Gudang</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $g->nama_gudang }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">Status: {{ ucfirst($g->status) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Alamat</p>
                <p class="font-body-md text-sm text-on-surface">{{ $g->alamat }}</p>
            </div>
            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                <p class="text-[10px] uppercase text-on-surface-variant mb-2">Petugas</p>
                @if ($g->staff && $g->staff->count())
                <ul class="space-y-1 text-sm text-on-surface">
                    @foreach ($g->staff as $st)
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>{{ $st->nama_lengkap ?? '-' }}</li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-on-surface-variant">Belum ada petugas.</p>
                @endif
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.body.innerHTML.includes('Belum punya toko');
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
