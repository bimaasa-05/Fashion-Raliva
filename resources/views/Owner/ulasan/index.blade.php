@extends('layouts.owner')

@section('title', 'Ulasan & Penilaian')

@section('header-title', 'Ulasan & Penilaian')
@section('header-badge', number_format($avg, 1, ',', '.') . ' / 5,0')
@section('header-subtitle', 'Rating dan ulasan customer sebagai bahan evaluasi produk dan layanan.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="space-y-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-40 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
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
    {{-- Ringkasan Rating --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col items-center justify-center text-center card-premium">
            <p class="text-xs font-medium text-on-surface-variant">Rating Toko</p>
            <p class="raliva-figure text-[56px] text-on-surface mt-3"><span>{{ number_format($avg, 1, ',', '.') }}</span></p>
            <div class="flex items-center gap-1 mt-4 text-gold-accent">
                @for ($i = 0; $i < 5; $i++)
                    <span class="material-symbols-outlined {{ $i < round($avg) ? 'fill' : '' }} text-[26px]">star</span>
                @endfor
            </div>
            <p class="text-on-surface-variant font-body-md text-sm mt-2">dari <span class="font-bold text-on-surface">{{ $total }}</span> ulasan terverifikasi</p>
        </section>

        <section data-reveal-group class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Distribusi Penilaian</h2>
            <ul class="space-y-4">
                @foreach ($distribution as $dist)
                    <li class="flex items-center gap-4">
                        <span class="w-20 shrink-0 text-xs font-medium text-on-surface-variant">{{ $dist['label'] }}</span>
                        <div class="flex-1 h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full rounded-full" data-progress="{{ $dist['percent'] }}"></div>
                        </div>
                        <span class="w-24 shrink-0 text-right font-body-md text-xs text-on-surface">{{ number_format($dist['count'], 0, ',', '.') }} ({{ str_replace('.', ',', (string) $dist['percent']) }}%)</span>
                    </li>
                @endforeach
            </ul>
            <div class="grid grid-cols-3 gap-gutter mt-7 pt-6 border-t border-muted-border">
                @php
                    $positif = $total > 0 ? round((($distribution[4]['count'] ?? 0) + ($distribution[5]['count'] ?? 0)) / $total * 100) : 0;
                    $rendah = ($distribution[1]['count'] ?? 0) + ($distribution[2]['count'] ?? 0) + ($distribution[3]['count'] ?? 0);
                @endphp
                <div class="text-center">
                    <p class="font-title-md text-title-md text-secondary">{{ $positif }}%</p>
                    <p class="text-xs text-on-surface-variant mt-1">Ulasan positif</p>
                </div>
                <div class="text-center border-x border-muted-border">
                    <p class="font-title-md text-title-md text-on-surface">{{ $total }}</p>
                    <p class="text-xs text-on-surface-variant mt-1">Total ulasan</p>
                </div>
                <div class="text-center">
                    <p class="font-title-md text-title-md text-gold-accent">{{ number_format($avg, 1, ',', '.') }}</p>
                    <p class="text-xs text-on-surface-variant mt-1">Rata-rata bintang</p>
                </div>
            </div>
        </section>
    </div>

    {{-- Filter --}}
    <div data-reveal class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto max-w-full self-start">
        <button type="button" data-review-tab="semua" class="review-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Semua</button>
        <button type="button" data-review-tab="belum" class="review-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Belum Dibalas</button>
        <button type="button" data-review-tab="rendah" class="review-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">≤ 3 Bintang</button>
    </div>

    {{-- Daftar Ulasan --}}
    <div data-reveal-group class="space-y-gutter -mt-section-gap">
        @forelse ($reviews as $review)
            @php $isLow = $review->rating <= 3; @endphp
            <article data-reveal data-review-card data-belum="no" data-rendah="{{ $isLow ? 'yes' : 'no' }}" class="bg-surface-container-lowest border {{ $isLow ? 'border-error/25' : 'border-muted-border' }} rounded-lg p-5 md:p-6 card-premium">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px] text-on-surface-variant">person</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-title-md text-sm text-on-surface">{{ $review->user?->name ?? 'Customer' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $review->product?->nama_produk ?? '-' }} • {{ $review->created_at?->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-gold-accent shrink-0">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-[17px] {{ $i <= $review->rating ? 'fill' : '' }}">star</span>
                        @endfor
                        @if ($isLow)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Perlu Atensi</span>
                        @endif
                    </div>
                </div>
                <p class="font-body-md text-sm text-on-surface leading-relaxed mt-4">“{{ $review->ulasan }}”</p>
            </article>
        @empty
            <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada ulasan.</p>
        @endforelse
    </div>
</div>

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

@push('scripts')
<script>
    document.querySelectorAll('[data-review-tab]').forEach((tab) => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('[data-review-tab]').forEach((t) => {
                const active = t === tab;
                t.classList.toggle('bg-deep-onyx', active);
                t.classList.toggle('text-on-primary', active);
                t.classList.toggle('text-on-surface-variant', !active);
            });
            const target = tab.getAttribute('data-review-tab');
            document.querySelectorAll('[data-review-card]').forEach((card) => {
                let show = true;
                if (target === 'belum') show = card.getAttribute('data-belum') === 'yes';
                if (target === 'rendah') show = card.getAttribute('data-rendah') === 'yes';
                card.classList.toggle('hidden', !show);
            });
        });
    });
</script>
@endpush
