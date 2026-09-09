<div id="notif-toast-container" class="fixed top-4 right-4 z-[99] flex flex-col gap-2 w-[calc(100vw-2rem)] max-w-sm pointer-events-none"></div>
@once
<script>
    (function () {
        const ICONS = {
            order: 'shopping_bag',
            pembayaran: 'payments',
            pengiriman: 'local_shipping',
            komplain: 'support_agent',
            wallet: 'account_balance_wallet',
            promo: 'local_offer',
            sistem: 'notifications',
        };
        const TONES = {
            order: '#E6C27A',
            pembayaran: '#4FD1C5',
            pengiriman: '#7FB3E8',
            komplain: '#F18A9B',
            wallet: '#9DB3FF',
            promo: '#F2A541',
            sistem: '#C0C8D8',
        };
        let queue = [];
        let showing = false;

        let soundScheduled = false;
        /* Bunyi notif "ting" ala beautycare — murni Web Audio, tanpa mp3. */
        function sound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                if (ctx.state === 'suspended') {
                    if (soundScheduled) return;
                    soundScheduled = true;
                    const resume = () => {
                        soundScheduled = false;
                        sound();
                    };
                    document.addEventListener('pointerdown', resume, { once: true });
                    document.addEventListener('keydown', resume, { once: true });
                    return;
                }
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = 880;
                osc.type = 'sine';
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.4);
            } catch (e) { /* audio tidak tersedia */ }
        }

        function render(item) {
            const container = document.getElementById('notif-toast-container');
            if (!container) return;
            const el = document.createElement('div');
            el.className = 'pointer-events-auto flex items-start gap-3 bg-white border border-muted-border rounded-xl shadow-2xl px-4 py-3 translate-y-[-16px] opacity-0 transition-all duration-300 cursor-pointer';
            el.style.boxShadow = '0 10px 40px rgba(12,12,20,0.18)';
            const tone = TONES[item.type] || '#E6C27A';
            el.innerHTML =
                '<span class="material-symbols-outlined text-[22px] mt-0.5 shrink-0" style="color:' + tone + '">' + (ICONS[item.type] || 'notifications') + '</span>' +
                '<div class="min-w-0 flex-1">' +
                '<p class="font-label-sm text-[10px] uppercase tracking-wider" style="color:' + tone + '">' + (item.judul || 'Notifikasi') + '</p>' +
                '<p class="font-body-md text-[13px] text-on-surface leading-snug mt-0.5">' + (item.message || item.isi || '') + '</p>' +
                '</div>' +
                '<button class="shrink-0 text-on-surface-variant hover:text-on-surface transition-colors" aria-label="Tutup"><span class="material-symbols-outlined text-[18px]">close</span></button>';
            const closeBtn = el.querySelector('button');
            el.addEventListener('click', (e) => {
                if (e.target.closest('button')) return;
                hide(el);
                if (item.url) window.location.href = item.url;
                else if (item.target) window.location.href = item.target;
            });
            closeBtn.addEventListener('click', () => hide(el));
            container.appendChild(el);
            requestAnimationFrame(() => {
                el.classList.remove('translate-y-[-16px]', 'opacity-0');
            });
            setTimeout(() => sound(), 300);
            setTimeout(() => hide(el), 5000);
        }

        function hide(el) {
            el.classList.add('translate-y-[-16px]', 'opacity-0');
            setTimeout(() => {
                el.remove();
                showing = false;
                next();
            }, 300);
        }

        function next() {
            if (!queue.length) return;
            showing = true;
            render(queue.shift());
        }

        function push(item) {
            queue.push(item);
            if (!showing) next();
        }

        window.showNotifToast = function (item) {
            if (item && typeof item === 'object') push(item);
        };

        window.addEventListener('raliva:notif', (e) => {
            push(e.detail.wrapped);
        });
    })();
</script>
@endonce