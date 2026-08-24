@extends('layouts.superadmin')

@section('title', 'Komisi Raliva')

@section('header-title', 'Komisi Raliva')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Atur persentase komisi platform yang berlaku untuk semua transaksi berhasil.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .text-gradient-gold {
        background: linear-gradient(115deg, #a8823a 0%, #C9A24D 35%, #ecd398 55%, #C9A24D 80%, #a8823a 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-glow::before {
        content: '';
        position: absolute;
        inset: -30%;
        background: radial-gradient(circle at 70% 30%, rgba(201, 162, 77, 0.14), transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(201, 162, 77, 0.08), transparent 40%);
        pointer-events: none;
    }

    .gauge-progress {
        transition: stroke-dashoffset 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        filter: drop-shadow(0 0 6px rgba(201, 162, 77, 0.45));
    }

    @keyframes riseIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .rise { opacity: 0; animation: riseIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }

    .sim-chip { transition: all 0.2s ease; }
    .sim-chip:hover { border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="w-full max-w-5xl mx-auto space-y-section-gap">

    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">percent</span>
        <div class="relative z-10 p-8 md:p-12 flex flex-col lg:flex-row lg:items-center justify-between gap-section-gap">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        Aktif • Berlaku Global
                    </span>
                    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">Diperbarui 12 Okt 2023</span>
                </div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Tarif Komisi Saat Ini</p>
                <div class="flex items-end gap-4 mb-4">
                    <span id="current-rate" class="font-display-lg text-gradient-gold text-7xl md:text-8xl leading-none tracking-tight">5%</span>
                    <span class="material-symbols-outlined text-gold-accent text-[28px] mb-2 fill">trending_up</span>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-md">Diterapkan otomatis ke penjualan toko di seluruh platform. Perubahan tarif akan tercatat dalam riwayat audit.</p>
                <div class="flex flex-wrap gap-gutter mt-8">
                    <button id="btn-ubah-komisi" type="button" class="bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase px-8 py-4 tracking-widest rounded-lg hover:bg-tertiary-container transition-colors btn-premium inline-flex items-center gap-2" data-modal-open="modal-edit-komisi">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Ubah Komisi
                    </button>
                    <a href="#riwayat-komisi" class="border border-muted-border text-on-surface font-label-sm text-label-sm uppercase px-8 py-4 tracking-widest rounded-lg hover:border-gold-accent hover:text-gold-accent transition-colors inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">history</span>
                        Lihat Riwayat
                    </a>
                </div>
            </div>

            <div class="shrink-0 mx-auto lg:mx-0">
                <div class="relative w-[210px] h-[210px]">
                    <svg viewBox="0 0 200 200" class="w-full h-full -rotate-90">
                        <circle cx="100" cy="100" r="84" fill="none" stroke="rgba(127,127,127,0.18)" stroke-width="12" />
                        <circle id="gauge-ring" cx="100" cy="100" r="84" fill="none" stroke="#C9A24D" stroke-width="12" stroke-linecap="round" stroke-dasharray="527.79" stroke-dashoffset="527.79" class="gauge-progress" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center rotate-0">
                        <span class="font-title-md text-title-md text-on-surface leading-none" id="gauge-value">5%</span>
                        <span class="font-label-sm text-[9px] text-on-surface-variant uppercase tracking-widest mt-1">dari skala<br />maks. 15%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Dampak Komisi Bulan Ini</h2>
        <div data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Estimasi Komisi</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp 612,8JT</span>
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-secondary">trending_up</span>+12,5% vs bulan lalu</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[64px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Transaksi Berkomisi</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">1.248</span>
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-secondary">trending_up</span>+8,2% vs bulan lalu</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[64px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Toko Terdampak</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">42</span>
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-on-surface-variant">trending_flat</span>stabil</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[64px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">storefront</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">Perubahan Tarif</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">2×</span>
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">tahun 2023</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[64px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">history</span>
            </div>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 md:p-8 card-premium">
        <div class="flex items-start justify-between gap-4 mb-6 flex-wrap">
            <div>
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Simulator Dampak Komisi</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Hitung pembagian pendapatan antara platform dan toko secara real-time.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase tracking-wider border border-gold-accent/30">
                <span class="material-symbols-outlined text-[14px]">bolt</span>
                Real-time
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-3" for="sim-sales">Estimasi Penjualan Toko / Bulan</label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none font-body-md">Rp</div>
                    <input type="number" id="sim-sales" min="0" step="1000000" value="50000000"
                        class="w-full bg-transparent border border-muted-border rounded-lg pl-11 pr-4 py-4 font-headline-lg-mobile text-headline-lg-mobile text-on-surface focus:outline-none focus:border-gold-accent transition-colors" />
                </div>
                <div class="flex flex-wrap gap-2 mt-4">
                    <button type="button" data-sim-preset="25000000" class="sim-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant">25 JT</button>
                    <button type="button" data-sim-preset="50000000" class="sim-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant">50 JT</button>
                    <button type="button" data-sim-preset="100000000" class="sim-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant">100 JT</button>
                    <button type="button" data-sim-preset="250000000" class="sim-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant">250 JT</button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs mt-4 flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                    Simulasi menggunakan tarif aktif dan belum termasuk pajak serta biaya payment gateway.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter content-start">
                <div class="rounded-xl p-6 bg-gradient-to-br from-gold-accent/15 via-gold-accent/5 to-transparent border border-gold-accent/25">
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant block mb-2">Komisi Platform</span>
                    <span id="sim-commission" class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent block leading-tight">Rp 2.500.000</span>
                    <span class="text-xs text-on-surface-variant mt-2 block">5% dari penjualan</span>
                </div>
                <div class="rounded-xl p-6 bg-surface-container-low border border-muted-border">
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant block mb-2">Diterima Toko</span>
                    <span id="sim-store" class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface block leading-tight">Rp 47.500.000</span>
                    <span class="text-xs text-on-surface-variant mt-2 block">95% dari penjualan</span>
                </div>
                <div class="sm:col-span-2 rounded-xl border border-muted-border bg-surface-container-low px-5 py-4 flex items-center justify-between">
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Total Penjualan</span>
                    <span id="sim-total" class="font-title-md text-title-md text-on-surface">Rp 50.000.000</span>
                </div>
            </div>
        </div>
    </section>

    <section id="riwayat-komisi" class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 md:p-8 card-premium scroll-mt-24">
        <h2 class="font-title-md text-title-md mb-8 uppercase tracking-wider text-on-surface premium-heading">Riwayat Perubahan Tarif</h2>
        <ol class="relative border-l border-muted-border ml-3 space-y-10">
            <li class="pl-8 relative">
                <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-gold-accent border-4 border-surface-container-lowest shadow-[0_0_0_3px_rgba(201,162,77,0.25)]"></span>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">12 Oktober 2023</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Naik</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="font-body-md text-on-surface-variant line-through">4%</span>
                    <span class="material-symbols-outlined text-[16px] text-gold-accent">east</span>
                    <span class="font-title-md text-title-md text-gradient-gold font-bold">5%</span>
                    <span class="text-on-surface-variant font-body-md text-sm">• oleh <span class="text-on-surface font-bold">Super Admin</span></span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Penyesuaian mengikuti pertumbuhan volume transaksi kuartal III.</p>
            </li>
            <li class="pl-8 relative">
                <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-secondary border-4 border-surface-container-lowest"></span>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">3 Juni 2023</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Turun</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="font-body-md text-on-surface-variant line-through">5%</span>
                    <span class="material-symbols-outlined text-[16px] text-gold-accent">east</span>
                    <span class="font-title-md text-title-md text-on-surface font-bold">4%</span>
                    <span class="text-on-surface-variant font-body-md text-sm">• oleh <span class="text-on-surface font-bold">Super Admin</span></span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Insentif program "Toko Baru 6 Bulan" untuk menarik seller baru.</p>
            </li>
            <li class="pl-8 relative">
                <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-outline-variant border-4 border-surface-container-lowest"></span>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">15 Januari 2023</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">Inisial</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="font-title-md text-title-md text-on-surface font-bold">5%</span>
                    <span class="text-on-surface-variant font-body-md text-sm">• oleh <span class="text-on-surface font-bold">Sistem</span></span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Tarif awal ditetapkan saat peluncuran marketplace Raliva.</p>
            </li>
        </ol>
    </section>

    <!-- Modal Perbarui Tarif Komisi -->
    <div id="modal-edit-komisi" data-modal class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
        <div class="relative w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Perbarui Tarif Komisi</h2>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Berlaku untuk seluruh transaksi baru di semua toko.</p>
                </div>
                <button type="button" data-modal-close onclick="closeEditForm();" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form class="p-6 space-y-container-margin" onsubmit="event.preventDefault(); document.getElementById('confirm-dialog').classList.remove('hidden');">
                <div class="space-y-container-margin">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="commissionRate">Tarif Baru (%)</label>
                        <div class="relative max-w-xs">
                            <input class="w-full bg-transparent border border-muted-border p-4 font-headline-lg-mobile text-headline-lg-mobile focus:outline-none focus:border-gold-accent transition-colors placeholder-on-surface-variant/50" id="commissionRate" max="15" min="0" name="commissionRate" oninput="updatePreview(this.value)" placeholder="misal 5.5" step="0.1" type="number" value="5" required />
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none font-title-md">%</div>
                        </div>
                        <p class="text-xs text-on-surface-variant mt-2">Batas aman internal: 0–15%.</p>
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="updateNotes">Catatan Perubahan (Internal)</label>
                        <textarea class="w-full bg-transparent border border-muted-border p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="updateNotes" name="updateNotes" placeholder="Alasan perubahan ini..." rows="3"></textarea>
                    </div>
                    <div class="bg-surface-container border border-gold-accent/20 p-container-margin flex flex-col sm:flex-row justify-between items-start sm:items-center gap-gutter rounded-lg">
                        <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Pratinjau</span><span class="font-body-md text-body-md">Dari penjualan Rp 1.000.000</span></div>
                        <div class="text-right"><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Pendapatan Platform</span><span class="font-title-md text-title-md text-gold-accent" id="preview-amount">Rp 50.000</span></div>
                    </div>
                    <div class="flex gap-gutter pt-container-margin">
                        <button class="flex-1 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors rounded-lg" onclick="closeEditForm();" type="button">Batal</button>
                        <button class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors rounded-lg btn-premium" type="submit">Tinjau Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updatePreview(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return;
        const sale = 1000000;
        const rev = sale * (num / 100);
        document.getElementById('preview-amount').innerText = 'Rp ' + rev.toLocaleString('id-ID');
        document.getElementById('confirm-rate').innerText = num + '%';
    }

    function closeEditForm() {
        document.getElementById('modal-edit-komisi').classList.add('hidden');
        document.getElementById('edit-form')?.classList.add('hidden');
        document.getElementById('modal-edit-komisi').querySelector('form').reset();
        document.getElementById('commissionRate').value = parseFloat(document.getElementById('current-rate').innerText);
        updatePreview(document.getElementById('commissionRate').value);
        document.body.style.overflow = '';
    }

    function getActiveRate() {
        return parseFloat(document.getElementById('current-rate').innerText) || 5;
    }

    function updateSimulator() {
        const rate = getActiveRate();
        const sales = parseFloat(document.getElementById('sim-sales').value) || 0;
        const commission = sales * (rate / 100);
        document.getElementById('sim-total').innerText = 'Rp ' + sales.toLocaleString('id-ID');
        document.getElementById('sim-commission').innerText = 'Rp ' + commission.toLocaleString('id-ID');
        document.getElementById('sim-commission').nextElementSibling.innerText =
            rate.toLocaleString('id-ID', { maximumFractionDigits: 2 }) + '% dari penjualan';
        document.getElementById('sim-store').innerText = 'Rp ' + (sales - commission).toLocaleString('id-ID');
    }

    function setGauge(rate) {
        const max = 15;
        const c = 2 * Math.PI * 84;
        const clamped = Math.max(0, Math.min(rate, max));
        document.getElementById('gauge-ring').style.strokeDashoffset = (c * (1 - clamped / max)).toFixed(2);
        document.getElementById('gauge-value').innerText = (rate % 1 === 0 ? rate : rate.toLocaleString('id-ID')) + '%';
    }

    function countUp(el, target, suffix, duration = 900) {
        const start = performance.now();
        const step = (now) => {
            const t = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            const val = target * eased;
            el.innerText = (target % 1 === 0 ? Math.round(val) : val.toFixed(1).replace('.', ',')) + suffix;
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    function applyKomisi() {
        const newRateRaw = document.getElementById('confirm-rate').innerText.replace('%', '');
        const newRate = parseFloat(newRateRaw);
        document.getElementById('confirm-dialog').classList.add('hidden');
        closeEditForm();

        countUp(document.getElementById('current-rate'), newRate, '%');
        setGauge(newRate);
        updateSimulator();
        showRalivaToast('Tarif komisi global diperbarui menjadi ' + newRate.toLocaleString('id-ID') + '%.', 'task_alt');
    }

    document.addEventListener('DOMContentLoaded', () => {
        setGauge(getActiveRate());
        countUp(document.getElementById('current-rate'), getActiveRate(), '%');
        updateSimulator();

        document.querySelectorAll('[data-sim-preset]').forEach((chip) => {
            chip.addEventListener('click', () => {
                document.getElementById('sim-sales').value = chip.getAttribute('data-sim-preset');
                updateSimulator();
            });
        });
        document.getElementById('sim-sales').addEventListener('input', updateSimulator);
    });
</script>
@endpush

@push('modals')
<div class="fixed inset-0 z-[95] hidden bg-surface-container/80 backdrop-blur-sm flex items-center justify-center p-gutter" id="confirm-dialog">
    <div class="bg-surface border border-muted-border p-section-gap max-w-md w-full shadow-2xl relative rounded-xl">
        <button type="button" class="absolute top-4 right-4 text-on-surface-variant hover:text-deep-onyx transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')"><span class="material-symbols-outlined">close</span></button>
        <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center mx-auto mb-gutter">
            <span class="material-symbols-outlined text-gold-accent text-[28px]">published_with_changes</span>
        </div>
        <h3 class="font-display-lg text-headline-lg-mobile md:text-headline-lg mb-gutter text-center">Konfirmasi Perubahan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-container-margin text-center">Anda akan mengubah tarif komisi global platform menjadi <strong class="text-deep-onyx" id="confirm-rate">5%</strong>. Perubahan ini berlaku untuk semua transaksi selanjutnya.</p>
        <div class="flex flex-col gap-gutter">
            <button type="button" class="w-full bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors rounded-lg btn-premium" onclick="applyKomisi();">Konfirmasi & Terapkan</button>
            <button type="button" class="w-full border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors rounded-lg" onclick="document.getElementById('confirm-dialog').classList.add('hidden')">Batal</button>
        </div>
    </div>
</div>
@endpush
