@extends('layouts.owner')

@section('title', 'Ulasan & Penilaian')

@section('header-title', 'Ulasan & Penilaian')
@section('header-badge', '4,9 / 5,0')
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
    {{-- Ringkasan Rating --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col items-center justify-center text-center card-premium">
            <p class="text-xs font-medium text-on-surface-variant">Rating Toko</p>
            <p class="raliva-figure text-[56px] text-on-surface mt-3"><span>4,9</span></p>
            <div class="flex items-center gap-1 mt-4 text-gold-accent">
                @for ($i = 0; $i < 5; $i++)
                    <span class="material-symbols-outlined fill text-[26px]">star</span>
                @endfor
            </div>
            <p class="text-on-surface-variant font-body-md text-sm mt-2">dari <span class="font-bold text-on-surface">1.284</span> ulasan terverifikasi</p>
        </section>

        <section data-reveal-group class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Distribusi Penilaian</h2>
            <ul class="space-y-4">
                @foreach ([['Bintang 5', 89, 1141], ['Bintang 4', 8, 103], ['Bintang 3', 2, 26], ['Bintang 2', 0.6, 8], ['Bintang 1', 0.4, 6]] as $dist)
                    <li class="flex items-center gap-4">
                        <span class="w-20 shrink-0 text-xs font-medium text-on-surface-variant">{{ $dist[0] }}</span>
                        <div class="flex-1 h-2.5 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full rounded-full" data-progress="{{ $dist[1] }}"></div>
                        </div>
                        <span class="w-24 shrink-0 text-right font-body-md text-xs text-on-surface">{{ number_format($dist[2], 0, ',', '.') }} ({{ str_replace('.', ',', (string) $dist[1]) }}%)</span>
                    </li>
                @endforeach
            </ul>
            <div class="grid grid-cols-3 gap-gutter mt-7 pt-6 border-t border-muted-border">
                <div class="text-center">
                    <p class="font-title-md text-title-md text-on-surface">97%</p>
                    <p class="text-xs text-on-surface-variant mt-1">Ulasan positif</p>
                </div>
                <div class="text-center border-x border-muted-border">
                    <p class="font-title-md text-title-md text-on-surface">&lt; 2 jam</p>
                    <p class="text-xs text-on-surface-variant mt-1">Rata-rata balasan</p>
                </div>
                <div class="text-center">
                    <p class="font-title-md text-title-md text-on-surface">12</p>
                    <p class="text-xs text-on-surface-variant mt-1">Belum dibalas</p>
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
        @foreach ([
            ['nama' => 'Sarah Jenkins', 'produk' => 'Trench Coat Signature', 'rating' => 5, 'tgl' => '22 Agu 2026', 'isi' => 'Bahan premium, jahitan rapi. Pengiriman cepat dan packaging elegan. Sangat recommended!', 'balasan' => null],
            ['nama' => 'Dimas Anggara', 'produk' => 'Blazer Wool Premium', 'rating' => 4, 'tgl' => '21 Agu 2026', 'isi' => 'Pas di badan, warnanya sesuai foto. Sedikit lama di proses kemasan tapi overall memuaskan.', 'balasan' => ['Terima kasih mas Dimas! Kami terus mempercepat proses pengemasan. Selamat berbelanja kembali.', '21 Agu 2026']],
            ['nama' => 'Rina Wulandari', 'produk' => 'Wide Leg Trousers', 'rating' => 2, 'tgl' => '20 Agu 2026', 'isi' => 'Ukuran 30 ternyata lebih besar dari ekspektasi. Pengembalian prosedurnya agak ribet.', 'balasan' => null],
            ['nama' => 'Aulia Rahma', 'produk' => 'Silk Scarf Monogram', 'rating' => 5, 'tgl' => '19 Agu 2026', 'isi' => 'Hadiah untuk ibu, beliau sangat suka. Terima kasih Raliva!', 'balasan' => ['Terima kasih kak Aulia atas apresiasinya. Senang sekali mendengar ibunya menyukai hadiah ini!', '19 Agu 2026']],
            ['nama' => 'Fajar Nugroho', 'produk' => 'Kemeja Linen Oversized', 'rating' => 3, 'tgl' => '18 Agu 2026', 'isi' => 'Bahannya adem, tapi warna sage yang datang sedikit berbeda dengan foto.', 'balasan' => null],
        ] as $review)
            @php $isLow = $review['rating'] <= 3; @endphp
            <article data-reveal data-review-card data-belum="{{ $review['balasan'] ? 'no' : 'yes' }}" data-rendah="{{ $isLow ? 'yes' : 'no' }}" class="bg-surface-container-lowest border {{ $isLow ? 'border-error/25' : 'border-muted-border' }} rounded-lg p-5 md:p-6 card-premium">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px] text-on-surface-variant">person</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-title-md text-sm text-on-surface">{{ $review['nama'] }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $review['produk'] }} • {{ $review['tgl'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-gold-accent shrink-0">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-[17px] {{ $i <= $review['rating'] ? 'fill' : '' }}">star</span>
                        @endfor
                        @if ($isLow)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Perlu Atensi</span>
                        @endif
                    </div>
                </div>
                <p class="font-body-md text-sm text-on-surface leading-relaxed mt-4">“{{ $review['isi'] }}”</p>

                @if ($review['balasan'])
                    <div class="mt-4 ml-0 sm:ml-[52px] border-l-[3px] border-gold-accent bg-gold-accent/5 rounded-r-lg px-4 py-3.5">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-xs font-semibold text-gold-accent">Balasan Raliva Atelier</p>
                            <span class="text-[11px] text-on-surface-variant">{{ $review['balasan'][1] }}</span>
                        </div>
                        <p class="font-body-md text-sm text-on-surface mt-2">{{ $review['balasan'][0] }}</p>
                    </div>
                @else
                    <div class="mt-4 flex items-center gap-gutter">
                        <button type="button" data-reply-toggle class="flex items-center gap-2 px-4 py-2 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                            <span class="material-symbols-outlined text-[16px]">reply</span>Balas Ulasan
                        </button>
                    </div>
                    <form data-toast-message="Balasan berhasil dikirim." data-reply-form class="hidden mt-3 space-y-3">
                        <textarea rows="2" placeholder="Tulis balasan Anda secara profesional dan empatik..." required class="raliva-textarea"></textarea>
                        <div class="flex justify-end gap-gutter">
                            <button type="button" data-reply-cancel class="py-2 px-4 text-xs font-medium text-on-surface-variant hover:text-on-surface transition-colors">Batal</button>
                            <button type="submit" class="py-2 px-5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium">Kirim Balasan</button>
                        </div>
                    </form>
                @endif
            </article>
        @endforeach
    </div>
</div>
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

    document.querySelectorAll('[data-reply-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const form = btn.closest('article').querySelector('[data-reply-form]');
            form.classList.remove('hidden');
            form.querySelector('textarea').focus();
        });
    });

    document.querySelectorAll('[data-reply-cancel]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const form = btn.closest('[data-reply-form]');
            form.reset();
            form.classList.add('hidden');
        });
    });

    document.querySelectorAll('form[data-reply-form]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.showRalivaToast(form.getAttribute('data-toast-message'));
            form.reset();
            form.classList.add('hidden');
        });
    });
</script>
@endpush
