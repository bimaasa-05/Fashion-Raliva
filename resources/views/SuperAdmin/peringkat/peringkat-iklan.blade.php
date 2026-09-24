@extends('layouts.superadmin')

@section('title', 'Peringkat Produk Iklan')

@section('header-title', 'Peringkat Produk Iklan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Slot iklan berbayar — owner membayar agar produknya tampil paling atas di katalog pelanggan.')

@section('content')
<div class="space-y-6">
    <div data-reveal class="flex flex-wrap items-center gap-3 -mt-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </span>
        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
            Data iklan diperbarui real-time
        </span>
    </div>
    <!-- Penjelasan Cara Kerja -->
    <div data-reveal class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gradient-to-r from-gold-accent/10 via-gold-accent/5 to-transparent rounded-lg">
        <span class="material-symbols-outlined text-gold-accent mt-0.5">campaign</span>
        <div>
            <p class="font-body-md text-sm font-bold text-on-surface">Cara kerja slot iklan</p>
            <p class="text-on-surface-variant text-sm mt-0.5">Owner menghubungi admin &amp; membayar agar produknya tampil di posisi teratas katalog pelanggan. <strong class="text-on-surface">Semakin besar pembayaran, semakin tinggi peringkatnya.</strong></p>
        </div>
    </div>

    <!-- Statistik -->
    <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Total Pendapatan Iklan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight break-words">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">payments</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Slot Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight break-words">{{ $slotAktif }} slot</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">ads_click</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Menunggu Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight break-words">{{ $slotTerjadwal }} slot</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Rata-rata Bid</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight break-words">Rp {{ number_format($rataRataBid, 0, ',', '.') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">trending_up</span>
        </div>
    </div>

    <!-- Podium Top 3 -->
    @php // $top3 sudah difilter di controller: hanya aktif + periode berlaku
         $top3 = $top3 ?? collect(); @endphp
    @if($top3->count() >= 1)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Podium Peringkat Saat Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter items-end">
            @if(isset($top3[0]))
            <div class="md:order-2 border-2 border-amber-400 rounded-xl p-6 flex flex-col items-center text-center gap-3 relative overflow-hidden bg-gradient-to-b from-amber-400/20 via-amber-400/5 to-transparent">
                <span class="absolute top-3 right-3 material-symbols-outlined text-amber-400 fill text-[28px]">workspace_premium</span>
                <span class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-white flex items-center justify-center font-title-md text-title-md font-bold shadow-lg">1</span>
                <div>
                    <p class="font-title-md text-title-md text-on-surface leading-snug">{{ $top3[0]->product->nama_produk ?? '-' }}</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $top3[0]->store->nama_toko ?? '-' }}</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-black leading-none">Rp {{ number_format((float)$top3[0]->nominal_bid, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-400/15 text-amber-500 text-[10px] font-bold uppercase border border-amber-400/40"><span class="material-symbols-outlined text-[12px]">check_circle</span>Posisi Teratas</span>
            </div>
            @endif
            @if(isset($top3[1]))
            <div class="md:order-1 bg-surface-container-low bg-gradient-to-b from-slate-400/30 via-slate-400/10 to-transparent border border-slate-400/60 rounded-xl p-6 flex flex-col items-center text-center gap-3">
                <span class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-400 via-slate-500 to-slate-700 text-white flex items-center justify-center font-title-md font-bold shadow-lg">2</span>
                <div>
                    <p class="font-title-md text-sm text-on-surface leading-snug">{{ $top3[1]->product->nama_produk ?? '-' }}</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $top3[1]->store->nama_toko ?? '-' }}</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">Rp {{ number_format((float)$top3[1]->nominal_bid, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-400/25 text-on-surface-variant text-[10px] font-bold uppercase border border-slate-400/60">Posisi 2</span>
            </div>
            @endif
            @if(isset($top3[2]))
            <div class="md:order-3 bg-surface-container-low bg-gradient-to-b from-amber-500/10 to-transparent border border-muted-border rounded-xl p-6 flex flex-col items-center text-center gap-3">
                <span class="w-10 h-10 rounded-full bg-amber-600 text-white flex items-center justify-center font-title-md font-bold">3</span>
                <div>
                    <p class="font-title-md text-sm text-on-surface leading-snug">{{ $top3[2]->product->nama_produk ?? '-' }}</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $top3[2]->store->nama_toko ?? '-' }}</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">Rp {{ number_format((float)$top3[2]->nominal_bid, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Posisi 3</span>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Tabel Peringkat -->
    <section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <h2 id="iklan-panel-title" class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">{{ ($tab ?? 'daftar') === 'pengajuan' ? 'Pengajuan Iklan' : (($tab ?? 'daftar') === 'daftar' ? 'Daftar Peringkat Lengkap' : 'Riwayat Iklan') }}</h2>
                <span id="iklan-panel-chip" class="inline-flex items-center px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border">{{ ($tab ?? 'daftar') === 'pengajuan' ? 'Pengajuan' : (($tab ?? 'daftar') === 'daftar' ? 'Aktif + Terjadwal' : 'Riwayat') }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-2 max-sm:w-full max-sm:justify-start">
                <button type="button" data-tab-btn="pengajuan" class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors {{ ($tab ?? 'daftar') === 'pengajuan' ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">Pengajuan</button>
                <button type="button" data-tab-btn="daftar" class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors {{ ($tab ?? 'daftar') === 'daftar' ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">Terdaftar</button>
                <button type="button" data-tab-btn="riwayat" class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors {{ ($tab ?? 'daftar') === 'riwayat' ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">Riwayat</button>
            </div>
        </div>

        <div id="iklan-panel-pengajuan" class="{{ ($tab ?? 'daftar') === 'pengajuan' ? '' : 'hidden' }}">
            @include('SuperAdmin.peringkat._slot-list', ['slots' => $pengajuanSlots, 'showAksi' => true])
        </div>

        <div id="iklan-panel-daftar" class="{{ ($tab ?? 'daftar') === 'daftar' ? '' : 'hidden' }}">
            @include('SuperAdmin.peringkat._slot-list', ['slots' => $daftarSlots, 'showAksi' => false])
        </div>

        <div id="iklan-panel-riwayat" class="{{ ($tab ?? 'daftar') === 'riwayat' ? '' : 'hidden' }}">
            @include('SuperAdmin.peringkat._slot-list', ['slots' => $riwayatSlots, 'showAksi' => false])
        </div>
    </section>

    <!-- Modal Konfirmasi Hapus Slot Iklan -->
<div id="confirmPeringkatModal" class="fixed inset-0 z-[75] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeConfirmPeringkat()">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div id="confirm-peringkat-icon" class="w-14 h-14 rounded-full bg-error/10 border border-error/20 flex items-center justify-center mx-auto mb-5">
                <span id="confirm-peringkat-icon-sym" class="material-symbols-outlined text-error text-[28px]">delete</span>
            </div>
            <h3 id="confirm-peringkat-title" class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus slot iklan?</h3>
            <p id="confirm-peringkat-desc" class="text-on-surface-variant text-sm text-center mb-4">Hapus slot iklan ini?</p>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeConfirmPeringkat()">Batal</button>
                <button type="button" id="confirm-peringkat-submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-error/90 transition-colors rounded-lg">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak Iklan -->
<div id="tolakIklanModal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeTolakIklan()"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-error">Tolak Pengajuan</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Tolak Iklan</h3>
            </div>
            <button type="button" onclick="closeTolakIklan()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="tolak-iklan-form" method="POST" action="" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Alasan Penolakan</label>
                <textarea name="alasan" rows="3" minlength="10" maxlength="1000" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error transition-colors" placeholder="Minimal 10 karakter"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeTolakIklan()" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-error text-on-error text-sm font-semibold rounded flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">block</span>Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let _pendingPeringkatForm = null;
    function openConfirmPeringkat(e, msg, aksi) {
        e.preventDefault();
        _pendingPeringkatForm = e.target;
        document.getElementById('confirm-peringkat-desc').textContent = msg || 'Hapus slot iklan ini?';
        const titleEl = document.getElementById('confirm-peringkat-title');
        const iconWrap = document.getElementById('confirm-peringkat-icon');
        const iconSym = document.getElementById('confirm-peringkat-icon-sym');
        const btn = document.getElementById('confirm-peringkat-submit');
        if (aksi === 'setujui') {
            if (titleEl) titleEl.textContent = 'Setujui dan aktifkan iklan?';
            if (iconWrap) iconWrap.className = 'w-14 h-14 rounded-full bg-success/10 border border-success/25 flex items-center justify-center mx-auto mb-5';
            if (iconSym) { iconSym.className = 'material-symbols-outlined text-success text-[28px]'; iconSym.textContent = 'check_circle'; }
            if (btn) { btn.textContent = 'Ya, Setujui'; btn.className = 'flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium'; }
        } else {
            if (titleEl) titleEl.textContent = 'Hapus slot iklan?';
            if (iconWrap) iconWrap.className = 'w-14 h-14 rounded-full bg-error/10 border border-error/20 flex items-center justify-center mx-auto mb-5';
            if (iconSym) { iconSym.className = 'material-symbols-outlined text-error text-[28px]'; iconSym.textContent = 'delete'; }
            if (btn) { btn.textContent = 'Ya, Hapus'; btn.className = 'flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-error/90 transition-colors rounded-lg'; }
        }
        const m = document.getElementById('confirmPeringkatModal');
        m.classList.remove('hidden'); m.classList.add('flex');
        document.body.style.overflow = 'hidden';
        return false;
    }
    function closeConfirmPeringkat() {
        const m = document.getElementById('confirmPeringkatModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow = ''; }
        _pendingPeringkatForm = null;
    }
    document.getElementById('confirm-peringkat-submit')?.addEventListener('click', () => {
        if (_pendingPeringkatForm) _pendingPeringkatForm.submit();
        closeConfirmPeringkat();
    });
    function openTolakIklan(id) {
        const form = document.getElementById('tolak-iklan-form');
        form.action = '{{ route('superadmin.peringkat-iklan.tolak', ':id:') }}'.replace(':id:', id);
        document.getElementById('tolakIklanModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeTolakIklan() {
        document.getElementById('tolakIklanModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeConfirmPeringkat(); closeTolakIklan(); } });

    const _iklanTabMeta = {
        pengajuan: { title: 'Pengajuan Iklan', chip: 'Pengajuan' },
        daftar: { title: 'Daftar Peringkat Lengkap', chip: 'Aktif + Terjadwal' },
        riwayat: { title: 'Riwayat Iklan', chip: 'Riwayat' },
    };
    function switchIklanTab(tab) {
        ['pengajuan', 'daftar', 'riwayat'].forEach((t) => {
            document.getElementById('iklan-panel-' + t)?.classList.toggle('hidden', t !== tab);
        });
        document.querySelectorAll('[data-tab-btn]').forEach((b) => {
            const active = b.dataset.tabBtn === tab;
            b.className = 'px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors ' +
                (active ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent');
        });
        const meta = _iklanTabMeta[tab] || _iklanTabMeta.daftar;
        const titleEl = document.getElementById('iklan-panel-title');
        const chipEl = document.getElementById('iklan-panel-chip');
        if (titleEl) titleEl.textContent = meta.title;
        if (chipEl) chipEl.textContent = meta.chip;
        const panel = document.getElementById('iklan-panel-' + tab);
        if (panel) panel.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('revealed'));
    }
    document.querySelectorAll('[data-tab-btn]').forEach((b) => {
        b.addEventListener('click', () => switchIklanTab(b.dataset.tabBtn));
    });
</script>
@endpush