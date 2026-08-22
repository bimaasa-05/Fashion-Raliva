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

    window.ralivaSetGauge = (circleEl, rate, max = 15, r = 84) => {
        if (!circleEl) return;
        const c = 2 * Math.PI * r;
        circleEl.style.strokeDasharray = c.toFixed(2);
        const clamped = Math.max(0, Math.min(rate, max));
        circleEl.style.strokeDashoffset = (c * (1 - clamped / max)).toFixed(2);
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
                r.style.animation = 'none';
                void r.offsetWidth;
                r.style.animation = '';
            });
        });
    }, 700);
</script>
