<div id="raliva-toast" class="hidden fixed bottom-24 md:bottom-8 right-4 md:right-8 z-[90] items-center gap-3 bg-inverse-surface text-inverse-on-surface pl-4 pr-5 py-3 rounded-lg shadow-xl max-w-[calc(100vw-2rem)]">
    <span class="material-symbols-outlined text-[20px]" data-toast-icon>check_circle</span>
    <p class="font-body-md text-sm" data-toast-message></p>
</div>

<script>
    const ralivaToast = document.getElementById('raliva-toast');
    let ralivaToastTimer;

    window.showRalivaToast = (message, icon = 'check_circle') => {
        if (!ralivaToast) return;
        ralivaToast.querySelector('[data-toast-message]').textContent = message;
        ralivaToast.querySelector('[data-toast-icon]').textContent = icon;
        ralivaToast.classList.remove('hidden');
        ralivaToast.classList.add('flex');
        clearTimeout(ralivaToastTimer);
        ralivaToastTimer = setTimeout(() => {
            ralivaToast.classList.add('hidden');
            ralivaToast.classList.remove('flex');
        }, 2800);
    };

    const closeAllOverlays = () => {
        document.querySelectorAll('[data-modal]').forEach((m) => m.classList.add('hidden'));
        document.querySelectorAll('[data-drawer-panel]').forEach((d) => d.classList.add('translate-x-full'));
        const overlay = document.getElementById('drawer-overlay');
        if (overlay && !overlay.classList.contains('hidden')) {
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
        document.body.style.overflow = '';
    };

    document.querySelectorAll('[data-modal-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modal = document.getElementById(btn.getAttribute('data-modal-open'));
            modal?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach((el) => {
        el.addEventListener('click', () => {
            el.closest('[data-modal]')?.classList.add('hidden');
            document.body.style.overflow = '';
        });
    });

    document.querySelectorAll('[data-detail-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modal = document.getElementById(btn.getAttribute('data-detail-open'));
            if (!modal) return;
            Array.from(btn.attributes).forEach((attr) => {
                if (!attr.name.startsWith('data-d-')) return;
                const key = attr.name.slice(7);
                modal.querySelectorAll('[data-slot="' + key + '"]').forEach((slot) => {
                    slot.textContent = attr.value;
                });
            });
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    document.querySelectorAll('[data-drawer-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const drawer = document.getElementById(btn.getAttribute('data-drawer-open'));
            const overlay = document.getElementById('drawer-overlay');
            drawer?.classList.remove('translate-x-full');
            if (overlay) {
                overlay.classList.remove('hidden');
                requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
            }
            document.body.style.overflow = 'hidden';
        });
    });

    document.querySelectorAll('[data-drawer-close]').forEach((el) => {
        el.addEventListener('click', () => {
            document.querySelectorAll('[data-drawer-panel]').forEach((d) => d.classList.add('translate-x-full'));
            const overlay = document.getElementById('drawer-overlay');
            if (overlay) {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
            document.body.style.overflow = '';
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAllOverlays();
    });

    document.querySelectorAll('[data-dropdown-toggle]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            btn.parentElement.querySelector('[data-dropdown-menu]')?.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => {
            if (!menu.parentElement.contains(e.target)) menu.classList.add('hidden');
        });
    });

    document.querySelectorAll('form[data-toast-message]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.showRalivaToast(form.getAttribute('data-toast-message'));
            form.closest('[data-modal]')?.classList.add('hidden');
            document.body.style.overflow = '';
            form.reset();
        });
    });

    const applyTableFilter = () => {
        document.querySelectorAll('[data-table-scope]').forEach((scope) => {
            const search = scope.querySelector('[data-table-search]');
            const filters = scope.querySelectorAll('[data-table-filter]');
            let visible = 0;
            scope.querySelectorAll('[data-table-row]').forEach((row) => {
                let show = true;
                if (search && search.value.trim() !== '') {
                    show = row.textContent.toLowerCase().includes(search.value.trim().toLowerCase());
                }
                if (show) {
                    filters.forEach((filter) => {
                        const key = filter.getAttribute('data-table-filter');
                        const value = filter.value;
                        if (value !== '' && value !== 'semua' && row.getAttribute('data-' + key) !== value) {
                            show = false;
                        }
                    });
                }
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            const empty = scope.querySelector('[data-empty-state]');
            empty?.classList.toggle('hidden', visible > 0);
            empty?.classList.toggle('flex', visible === 0);
        });
    };

    document.querySelectorAll('[data-table-search]').forEach((input) => input.addEventListener('input', applyTableFilter));
    document.querySelectorAll('[data-table-filter]').forEach((select) => select.addEventListener('change', applyTableFilter));

    document.querySelectorAll('[data-chip-group]').forEach((group) => {
        group.querySelectorAll('[data-chip]').forEach((chip) => {
            chip.addEventListener('click', () => {
                const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
                const idleClasses = ['border-muted-border', 'text-on-surface-variant'];
                group.querySelectorAll('[data-chip]').forEach((c) => {
                    c.classList.remove(...activeClasses);
                    c.classList.add(...idleClasses, 'hover:bg-surface-container-high');
                });
                chip.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
                chip.classList.add(...activeClasses);

                const key = group.getAttribute('data-chip-key') || 'status';
                const value = chip.getAttribute('data-chip');
                const scope = document.querySelector('[data-table-scope]');
                if (!scope) return;
                scope.querySelectorAll('[data-table-row]').forEach((row) => {
                    row.classList.toggle('hidden', !(value === 'semua' || row.getAttribute('data-' + key) === value));
                });
            });
        });
    });

    window.ralivaCountUp = (el, target, suffix = '', duration = 900) => {
        if (!el) return;
        const start = performance.now();
        const hasDecimal = !Number.isInteger(target);
        const step = (now) => {
            const t = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            const val = target * eased;
            el.innerText = (hasDecimal
                ? (Math.round(val * 10) / 10).toFixed(1)
                : Math.round(val).toLocaleString('id-ID')) + suffix;
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    window.ralivaRenderDonut = (el) => {
        if (!el || el.dataset.donutDone) return;
        let segs = [];
        try { segs = JSON.parse(el.getAttribute('data-donut') || '[]'); } catch (e) { return; }
        if (!segs.length) return;
        el.dataset.donutDone = '1';
        const size = parseInt(el.getAttribute('data-donut-size') || '190', 10);
        const strokeW = parseInt(el.getAttribute('data-donut-stroke') || '18', 10);
        const r = (size - strokeW) / 2;
        const c = 2 * Math.PI * r;
        const total = segs.reduce((s, x) => s + (x.value || 0), 0) || 1;
        const NS = 'http://www.w3.org/2000/svg';

        el.classList.add('flex', 'flex-col', 'items-center');
        const wrap = document.createElement('div');
        wrap.className = 'relative w-full';
        wrap.style.maxWidth = (el.getAttribute('data-donut-max') || '210') + 'px';

        const svg = document.createElementNS(NS, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + size + ' ' + size);
        svg.setAttribute('class', 'w-full h-auto -rotate-90');

        const track = document.createElementNS(NS, 'circle');
        track.setAttribute('cx', size / 2); track.setAttribute('cy', size / 2); track.setAttribute('r', r);
        track.setAttribute('fill', 'none'); track.setAttribute('stroke', 'rgba(127,127,127,0.16)');
        track.setAttribute('stroke-width', strokeW);
        svg.appendChild(track);

        let acc = 0;
        segs.forEach((s) => {
            const frac = (s.value || 0) / total;
            const len = c * frac;
            const cir = document.createElementNS(NS, 'circle');
            cir.setAttribute('cx', size / 2); cir.setAttribute('cy', size / 2); cir.setAttribute('r', r);
            cir.setAttribute('fill', 'none'); cir.setAttribute('stroke', s.color || '#C9A24D');
            cir.setAttribute('stroke-width', strokeW);
            cir.style.strokeDasharray = '0 ' + c.toFixed(2);
            cir.style.strokeDashoffset = (-acc).toFixed(2);
            cir.classList.add('raliva-donut-seg');
            const t = document.createElementNS(NS, 'title');
            t.textContent = (s.label || '') + ': ' + (s.value || 0).toLocaleString('id-ID');
            cir.appendChild(t);
            svg.appendChild(cir);
            setTimeout(() => {
                cir.style.strokeDasharray = len.toFixed(2) + ' ' + (c - len).toFixed(2);
            }, 120);
            acc += len;
        });

        wrap.appendChild(svg);
        const center = document.createElement('div');
        center.className = 'absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none';
        const sfx = el.getAttribute('data-donut-suffix') || '';
        center.innerHTML =
            '<span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">' + total.toLocaleString('id-ID') + sfx + '</span>' +
            '<span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mt-1.5">' + (el.getAttribute('data-donut-label') || 'Total') + '</span>';
        wrap.appendChild(center);
        el.appendChild(wrap);

        if (el.getAttribute('data-donut-nolegend') === '1') return;

        const leg = document.createElement('ul');
        leg.className = 'mt-6 w-full space-y-2';
        segs.forEach((s) => {
            const pct = Math.round(((s.value || 0) / total) * 100);
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between gap-3 font-body-md text-sm';
            li.innerHTML =
                '<span class="flex items-center gap-2 text-on-surface min-w-0"><i class="w-2.5 h-2.5 rounded-full shrink-0" style="background:' + (s.color || '#C9A24D') + '"></i><span class="truncate">' + (s.label || '-') + '</span></span>' +
                '<span class="shrink-0 text-on-surface"><b>' + (s.value || 0).toLocaleString('id-ID') + '</b> <span class="text-on-surface-variant text-xs">• ' + pct + '%</span></span>';
            leg.appendChild(li);
        });
        el.appendChild(leg);
    };

    window.ralivaBars = (el) => {
        if (!el || el.dataset.barsDone) return;
        let segs = [];
        try { segs = JSON.parse(el.getAttribute('data-bars') || '[]'); } catch (e) { return; }
        if (!segs.length) {
            el.classList.add('flex', 'items-center', 'justify-center');
            el.innerHTML = '<div class="w-full flex flex-col items-center justify-center py-6 text-center gap-2 text-on-surface-variant">'
                + '<span class="material-symbols-outlined text-[28px] opacity-50">bar_chart</span>'
                + '<p class="font-body-md text-sm">Belum ada data pesanan.</p></div>';
            return;
        }
        el.dataset.barsDone = '1';
        const suffix = el.getAttribute('data-bars-suffix') || '';
        const max = Math.max.apply(null, segs.map((s) => s.value || 0)) || 1;

        el.classList.add('flex', 'items-end', 'gap-2', 'md:gap-3');
        segs.forEach((s, i) => {
            const pct = Math.round(((s.value || 0) / max) * 100);
            const col = document.createElement('div');
            col.className = 'flex-1 min-w-0 flex flex-col items-center justify-end gap-2 h-full';

            const val = document.createElement('span');
            val.className = 'text-[10px] font-bold text-on-surface leading-none';
            val.textContent = (s.value || 0).toLocaleString('id-ID') + suffix;

            const barZone = document.createElement('div');
            barZone.className = 'w-full h-full flex items-end justify-center';
            const bar = document.createElement('div');
            bar.className = 'w-full max-w-[36px] rounded-t-md raliva-bar bg-gradient-to-t from-gold-accent/45 to-gold-accent hover:from-gold-accent/70 hover:shadow-[0_0_12px_rgba(201,162,77,0.35)] transition-shadow';
            bar.style.height = '0%';
            bar.title = (s.label || '') + ': ' + (s.value || 0).toLocaleString('id-ID') + suffix;
            barZone.appendChild(bar);

            const lab = document.createElement('span');
            lab.className = 'font-label-sm text-[10px] uppercase tracking-wide text-on-surface-variant truncate max-w-full';
            lab.textContent = s.label || '';

            col.appendChild(val); col.appendChild(barZone); col.appendChild(lab);
            el.appendChild(col);
            setTimeout(() => { bar.style.height = Math.max(pct, 4) + '%'; }, 120 + i * 70);
        });
    };

    window.ralivaLeaderboard = (el) => {
        if (!el || el.dataset.lbDone) return;
        let rows = [];
        try { rows = JSON.parse(el.getAttribute('data-leaderboard') || '[]'); } catch (e) { return; }
        if (!rows.length) {
            el.classList.add('flex', 'items-center', 'justify-center');
            el.innerHTML = '<div class="w-full flex flex-col items-center justify-center py-6 text-center gap-2 text-on-surface-variant">'
                + '<span class="material-symbols-outlined text-[28px] opacity-50">emoji_events</span>'
                + '<p class="font-body-md text-sm">Belum ada toko dengan transaksi.</p></div>';
            return;
        }
        el.dataset.lbDone = '1';

        const list = document.createElement('ul');
        list.className = 'flex flex-col';
        rows.forEach((r, i) => {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-3 py-3.5' + (i < rows.length - 1 ? ' border-b border-muted-border' : '');
            const rankCls = i === 0
                ? 'bg-gold-accent/15 text-gold-accent border border-gold-accent/40'
                : 'bg-surface-container-high text-on-surface-variant border border-transparent';
            li.innerHTML =
                '<span class="w-8 h-8 rounded-full flex items-center justify-center font-label-sm text-xs font-bold shrink-0 ' + rankCls + '">' + (i + 1) + '</span>' +
                '<div class="flex-1 min-w-0">' +
                    '<div class="flex items-center justify-between gap-3"><p class="font-title-md text-sm text-on-surface truncate">' + (r.name || '-') + '</p>' +
                    '<span class="shrink-0 font-title-md text-sm text-gold-accent">' + (r.display || '') + '</span></div>' +
                    (r.meta ? '<p class="text-xs text-on-surface-variant mb-1.5">' + r.meta + '</p>' : '') +
                    '<div class="h-1 w-full bg-surface-container-high rounded-full overflow-hidden"><div class="h-full rounded-full raliva-lb-fill bg-gradient-to-r from-gold-accent/60 to-gold-accent" style="width:0%" data-w="' + (r.pct || 0) + '%"></div></div>' +
                '</div>';
            list.appendChild(li);
        });
        el.appendChild(list);
        setTimeout(() => {
            el.querySelectorAll('.raliva-lb-fill').forEach((f) => { f.style.width = f.getAttribute('data-w'); });
        }, 150);
    };

    window.ralivaSetGauge = (circleEl, rate, max = 15, r = 84) => {
        if (!circleEl) return;
        const c = 2 * Math.PI * r;
        circleEl.style.strokeDasharray = c.toFixed(2);
        const clamped = Math.max(0, Math.min(rate, max));
        circleEl.style.strokeDashoffset = (c * (1 - clamped / max)).toFixed(2);
    };

    const releaseRise = (root) => {
        root.querySelectorAll('.rise').forEach((el) => {
            const done = () => el.classList.remove('rise');
            if (el.getAnimations && el.getAnimations().length) {
                Promise.all(el.getAnimations().map((a) => a.finished.catch(() => {}))).then(done);
            } else {
                done();
            }
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.page-enter').forEach((el) => {
            const release = () => el.classList.remove('page-enter');
            if (el.getAnimations) {
                const anims = el.getAnimations();
                if (anims.length === 0) {
                    release();
                } else {
                    Promise.all(anims.map((a) => a.finished.catch(() => {}))).then(release);
                }
            } else {
                el.addEventListener('animationend', release, { once: true });
            }
        });

        document.querySelectorAll('[data-donut]').forEach((el) => window.ralivaRenderDonut(el));
        document.querySelectorAll('[data-bars]').forEach((el) => window.ralivaBars(el));
        document.querySelectorAll('[data-leaderboard]').forEach((el) => window.ralivaLeaderboard(el));

        document.querySelectorAll('[data-count]').forEach((el) => {
            const target = parseFloat(el.getAttribute('data-count'));
            if (isNaN(target)) return;
            ralivaCountUp(el, target, el.getAttribute('data-count-suffix') || '');
        });

        document.querySelectorAll('.rise').forEach((el, i) => {
            if (!el.style.animationDelay && i > 0) {
                el.style.animationDelay = Math.min(i * 0.06, 0.42).toFixed(2) + 's';
            }
        });
    });

    setTimeout(() => {
        document.querySelectorAll('[data-skeleton]').forEach((el) => el.classList.add('hidden'));
        document.querySelectorAll('[data-real]').forEach((el) => {
            el.classList.remove('hidden');
            el.querySelectorAll('.rise').forEach((r) => {
                r.classList.remove('rise');
                void r.offsetWidth;
                r.classList.add('rise');
            });
            const chartMap = [
                ['donut', window.ralivaRenderDonut, 'donutDone'],
                ['bars', window.ralivaBars, 'barsDone'],
                ['leaderboard', window.ralivaLeaderboard, 'lbDone']
            ];
            chartMap.forEach(([key, fn, flag]) => {
                el.querySelectorAll('[data-' + key + ']').forEach((m) => {
                    m.innerHTML = '';
                    delete m.dataset[flag];
                    fn(m);
                });
            });
            releaseRise(el);
        });
    }, 700);

    releaseRise(document);
</script>
