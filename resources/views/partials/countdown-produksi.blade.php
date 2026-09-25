@once
{{-- Countdown live produksi: "Mulai dalam" → "Sisa" → "Terlambat".
     Elemen target memakai data-countdown-start / data-countdown-end / data-countdown-progress. --}}
<script>
    function countdownProduksiFmt(seconds) {
        const abs = Math.abs(seconds);
        const hari = Math.floor(abs / 86400);
        const jam = Math.floor((abs % 86400) / 3600);
        const mnt = Math.floor((abs % 3600) / 60);
        const dtk = abs % 60;
        const parts = [];
        if (hari > 0) parts.push(hari + 'h');
        if (jam > 0) parts.push(jam + 'j');
        if (mnt > 0) parts.push(mnt + 'm');
        if (dtk > 0) parts.push(dtk + 'd');
        return parts.length ? parts.join(' ') : '0d';
    }

    function updateCountdownsProduksi() {
        document.querySelectorAll('[data-countdown-end]').forEach(el => {
            const end = parseInt(el.dataset.countdownEnd, 10) * 1000;
            const startRaw = el.dataset.countdownStart;
            const start = startRaw ? parseInt(startRaw, 10) * 1000 : null;
            const progress = el.dataset.countdownProgress || '0';
            const now = Date.now();
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
                el.textContent = 'Sisa ' + countdownProduksiFmt(diff) + ' (' + progress + '%)';
                el.classList.add('text-on-surface-variant');
                el.classList.remove('text-error', 'font-bold', 'text-secondary');
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