@extends('layouts.superadmin')

@section('title', 'Dashboard Admin Utama')

@section('header-title', 'Selamat datang, Super Admin')
@section('header-badge', 'Kelola & Lihat')

@section('header-subtitle', 'Ini yang terjadi hari ini.')

@section('content')
<!-- Platform Overview -->
<section>
    <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Platform</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-gutter">
        <!-- Stat Card 1 -->
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pengguna</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">24.5K</span>
        </div>
        <!-- Stat Card 2 -->
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Toko</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">storefront</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">1.2K</span>
        </div>
        <!-- Stat Card 3 -->
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pesanan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">8.4K</span>
        </div>
        <!-- Stat Card 4 -->
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">45.2K</span>
        </div>
        <!-- Stat Card 5 -->
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 col-span-2 md:col-span-1 hover:border-gold-accent transition-colors">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Nilai Transaksi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 12.5B</span>
        </div>
        <!-- Stat Card 6 -->
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 col-span-2 md:col-span-1 hover:border-gold-accent transition-colors">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komisi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp 1.2B</span>
        </div>
    </div>
</section>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    <!-- Perlu Perhatian -->
    <section class="lg:col-span-1 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Perlu Perhatian</h2>
        <ul class="flex flex-col gap-4">
            <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined">store_mall_directory</span>
                    </div>
                    <div>
                        <span class="font-title-md text-title-md text-on-surface block">Verifikasi Toko</span>
                        <span class="text-on-surface-variant font-body-md text-sm">12 permintaan menunggu</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline-variant group-hover:text-on-surface transition-colors">chevron_right</span>
            </li>
            <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                    <div>
                        <span class="font-title-md text-title-md text-on-surface block">Moderasi Produk</span>
                        <span class="text-on-surface-variant font-body-md text-sm">45 item ditandai</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline-variant group-hover:text-on-surface transition-colors">chevron_right</span>
            </li>
            <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center text-on-error-container">
                        <span class="material-symbols-outlined">currency_exchange</span>
                    </div>
                    <div>
                        <span class="font-title-md text-title-md text-on-surface block">Permintaan Refund</span>
                        <span class="text-on-surface-variant font-body-md text-sm">8 menunggu tinjauan</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline-variant group-hover:text-on-surface transition-colors">chevron_right</span>
            </li>
        </ul>
    </section>
    <!-- Platform Performance Chart -->
    <section class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Kinerja Platform</h2>
            <div class="flex gap-2">
                <button type="button" data-periode="7d" class="periode-btn px-3 py-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase rounded-full">7D</button>
                <button type="button" data-periode="30d" class="periode-btn px-3 py-1 border border-muted-border text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase rounded-full transition-colors">30D</button>
            </div>
        </div>
        <div class="flex-1 relative min-h-[250px] w-full mt-4">
            <!-- Simple SVG Chart Mockup -->
            <svg class="w-full h-full overflow-visible" preserveaspectratio="none" viewbox="0 0 500 200">
                <!-- Grid lines -->
                <line stroke="#E9E8E7" stroke-width="1" x1="0" x2="500" y1="50" y2="50"></line>
                <line stroke="#E9E8E7" stroke-width="1" x1="0" x2="500" y1="100" y2="100"></line>
                <line stroke="#E9E8E7" stroke-width="1" x1="0" x2="500" y1="150" y2="150"></line>
                <line stroke="#E9E8E7" stroke-width="1" x1="0" x2="500" y1="200" y2="200"></line>
                <!-- Line Chart -->
                <path id="perf-line" class="animate-line" d="M 0,180 C 50,150 100,160 150,100 C 200,40 250,80 300,60 C 350,40 400,90 450,30 C 480,10 500,20 500,20" fill="none" stroke="#C9A24D" stroke-width="3"></path>
                <!-- Gradient Area under line (optional, simplified) -->
                <path id="perf-area" d="M 0,180 C 50,150 100,160 150,100 C 200,40 250,80 300,60 C 350,40 400,90 450,30 C 480,10 500,20 500,20 L 500,200 L 0,200 Z" fill="url(#fade)" opacity="0.2"></path>
                <defs>
                    <lineargradient id="fade" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="#C9A24D" stop-opacity="1"></stop>
                        <stop offset="100%" stop-color="#C9A24D" stop-opacity="0"></stop>
                    </lineargradient>
                </defs>
            </svg>
            <!-- X-axis labels -->
            <div id="perf-labels" class="flex justify-between mt-2 text-on-surface-variant font-label-sm text-[10px] uppercase">
                <span>Senin</span>
                <span>Selasa</span>
                <span>Rabu</span>
                <span>Kamis</span>
                <span>Jumat</span>
                <span>Sabtu</span>
                <span>Minggu</span>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    const periodeBtns = document.querySelectorAll('.periode-btn');
    const perfLine = document.getElementById('perf-line');
    const perfArea = document.getElementById('perf-area');
    const perfLabels = document.getElementById('perf-labels');

    const periodeData = {
        '7d': {
            d: 'M 0,180 C 50,150 100,160 150,100 C 200,40 250,80 300,60 C 350,40 400,90 450,30 C 480,10 500,20 500,20',
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
        },
        '30d': {
            d: 'M 0,160 C 60,170 120,120 180,130 C 240,140 300,80 360,95 C 420,110 470,50 500,45',
            labels: ['Pekan 1', 'Pekan 2', 'Pekan 3', 'Pekan 4']
        }
    };

    periodeBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            periodeBtns.forEach((b) => {
                b.classList.remove('bg-deep-onyx', 'text-on-primary');
                b.classList.add('border', 'border-muted-border', 'text-on-surface-variant', 'hover:text-on-surface');
            });
            btn.classList.remove('border', 'border-muted-border', 'text-on-surface-variant', 'hover:text-on-surface');
            btn.classList.add('bg-deep-onyx', 'text-on-primary');

            const data = periodeData[btn.getAttribute('data-periode')];
            if (!data) return;
            perfLine?.setAttribute('d', data.d);
            perfArea?.setAttribute('d', data.d + ' L 500,200 L 0,200 Z');
            if (perfLabels) {
                perfLabels.innerHTML = data.labels.map((l) => '<span>' + l + '</span>').join('');
            }
        });
    });
</script>
@endpush
<!-- Recent Activity -->
<section>
    <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Aktivitas Terbaru</h2>
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <ul class="flex flex-col">
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-surface">person_add</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface"><span class="font-bold">Sarah Jenkins</span> mendaftar sebagai pengguna baru.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">2 menit lalu</p>
                    </div>
                </div>
            </li>
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-surface">store</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface">Toko <span class="font-bold">LUNARA Fashion</span> terverifikasi.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">15 menit lalu</p>
                    </div>
                </div>
            </li>
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-surface">shopping_cart</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface">Pesanan besar #RLV-2405 telah selesai.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">1 jam lalu</p>
                    </div>
                </div>
            </li>
            <li class="p-4 hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-surface">warning</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface">Produk <span class="font-bold">Oversized Linen Shirt</span> ditandai untuk ditinjau.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">2 jam lalu</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</section>
@endsection