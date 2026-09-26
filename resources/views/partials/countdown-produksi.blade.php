@once
{{-- Countdown live produksi: "Mulai dalam" → "Sisa" → "Terlambat".
     Elemen target memakai data-countdown-start / data-countdown-end / data-countdown-progress,
     dan progress bar memakai data-countdown-bar + data-countdown-start / data-countdown-end. --}}
<script>
    function countdownProduksiFmt(seconds) {
        const abs = Math.abs(seconds);
        const hari = Math.floor(abs / 86400);
        const jam = Math.floor((abs % 86400) / 3600);
        const mnt = Math.floor((abs % 3600) / 60);
        const dtk = abs % 60;
        const parts = [];
        if (hari > 0) parts.push(hari + 'h');
        if (hari > 0 || jam > 0) parts.push(jam + 'j');
        if (hari > 0 || jam > 0 || mnt > 0) parts.push(mnt + 'm');
        parts.push(dtk + 'd');
        return parts.join(' ') || '0d';
    }

    function countdownProduksiLive(el) {
        const endTs = parseInt(el.dataset.countdownEnd, 10);
        const startRaw = el.dataset.countdownStart;
        const startTs = startRaw ? parseInt(startRaw, 10) : null;
        if (!startTs || isNaN(endTs)) return null;
        const now = Date.now() / 1000;
        const total = Math.max(1, endTs - startTs);
        if (now < startTs) return { state: 'belum', pct: 0 };
        if (now > endTs) return { state: 'lambat', pct: 100 };
        return { state: 'jalan', pct: Math.min(100, Math.round(((now - startTs) / total) * 100)) };
    }

    function updateCountdownsProduksi() {
        const now = Date.now();
        document.querySelectorAll('[data-countdown-end]').forEach(el => {
            const end = parseInt(el.dataset.countdownEnd, 10) * 1000;
            const startRaw = el.dataset.countdownStart;
            const start = startRaw ? parseInt(startRaw, 10) * 1000 : null;
            const live = countdownProduksiLive(el) || { state: '', pct: 0 };
            if (start && now < start) {
                const wait = Math.floor((start - now) / 1000);
                el.textContent = 'Mulai dalam ' + countdownProduksiFmt(wait);
                el.classList.add('text-secondary');
                el.classList.remove('text-error', 'font-bold', 'text-on-surface-variant');
                return;
            }
            const diff = Math.floor((end - now) / 1000);
            if (diff < 0) {
                el.textContent = 'Terlambat ' + countdownProduksiFmt(diff);
                el.classList.add('text-error', 'font-bold');
                el.classList.remove('text-on-surface-variant', 'text-secondary');
            } else {
                el.textContent = 'Sisa ' + countdownProduksiFmt(diff) + ' (' + live.pct + '%)';
                el.classList.add('text-on-surface-variant');
                el.classList.remove('text-error', 'font-bold', 'text-secondary');
            }
        });
        document.querySelectorAll('[data-countdown-bar]').forEach(el => {
            const live = countdownProduksiLive(el);
            if (!live) return;
            el.style.width = live.pct + '%';
            if (live.state === 'belum') {
                el.classList.add('bg-surface-container-high');
                el.classList.remove('bg-gold-accent', 'bg-error');
            } else if (live.state === 'lambat') {
                el.classList.add('bg-error');
                el.classList.remove('bg-gold-accent', 'bg-surface-container-high');
            } else {
                el.classList.add('bg-gold-accent');
                el.classList.remove('bg-error', 'bg-surface-container-high');
            }
        });
    }

    setInterval(updateCountdownsProduksi, 1000);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateCountdownsProduksi);
    } else {
        updateCountdownsProduksi();
    }
</script>
@endonce