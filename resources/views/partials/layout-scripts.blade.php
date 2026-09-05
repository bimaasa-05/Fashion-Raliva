<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    const openSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
        requestAnimationFrame(() => sidebarOverlay.classList.remove('opacity-0'));
    };

    const closeSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('opacity-0');
        setTimeout(() => sidebarOverlay.classList.add('hidden'), 300);
    };

    sidebarToggle?.addEventListener('click', () => {
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    sidebarOverlay?.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeSidebar();
            document.querySelectorAll('[data-profile-menu]').forEach((m) => m.classList.add('hidden'));
            document.querySelectorAll('[data-notification-menu]').forEach((m) => m.classList.add('hidden'));
        }
    });

    const allProfileMenus = document.querySelectorAll('[data-profile-menu]');
    const allNotificationMenus = document.querySelectorAll('[data-notification-menu]');

    /* Chevron indikator menu profil — mengikuti state menu dari jalur mana pun */
    const syncProfileChevrons = () => {
        document.querySelectorAll('[data-profile-container]').forEach((c) => {
            const menu = c.querySelector('[data-profile-menu]');
            c.classList.toggle('menu-open', !!menu && !menu.classList.contains('hidden'));
        });
    };
    if ('MutationObserver' in window) {
        const profileMenuObserver = new MutationObserver(syncProfileChevrons);
        allProfileMenus.forEach((m) => profileMenuObserver.observe(m, { attributes: true, attributeFilter: ['class'] }));
    }

    document.querySelectorAll('[data-profile-toggle]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = btn.parentElement.querySelector('[data-profile-menu]');
            allProfileMenus.forEach((m) => {
                if (m !== menu) m.classList.add('hidden');
            });
            allNotificationMenus.forEach((m) => m.classList.add('hidden'));
            menu?.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll('[data-profile-container]').forEach((container) => {
            if (!container.contains(e.target)) {
                container.querySelector('[data-profile-menu]')?.classList.add('hidden');
            }
        });
    });

    document.querySelectorAll('[data-notification-toggle]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = btn.parentElement.querySelector('[data-notification-menu]');
            allNotificationMenus.forEach((m) => {
                if (m !== menu) m.classList.add('hidden');
            });
            allProfileMenus.forEach((m) => m.classList.add('hidden'));
            menu?.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll('[data-notification-container]').forEach((container) => {
            if (!container.contains(e.target)) {
                container.querySelector('[data-notification-menu]')?.classList.add('hidden');
            }
        });
    });

    const updateThemeIcons = () => {
        const isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('.theme-toggle [data-theme-icon]').forEach((el) => {
            el.textContent = isDark ? 'dark_mode' : 'light_mode';
            el.setAttribute('data-icon', isDark ? 'dark_mode' : 'light_mode');
        });
        document.querySelectorAll('.theme-toggle .icon-moon').forEach((el) => el.classList.toggle('hidden', isDark));
        document.querySelectorAll('.theme-toggle .icon-sun').forEach((el) => el.classList.toggle('hidden', !isDark));
    };

    document.querySelectorAll('.theme-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        });
    });

    updateThemeIcons();

    /* Buka-tutup grup sidebar dengan animasi halus (teknik grid-rows) */
    document.querySelectorAll('[data-sidebar-group-button]').forEach((btn) => {
        const group = btn.parentElement.querySelector('[data-sidebar-group]');
        if (!group) return;
        btn.setAttribute('aria-expanded', group.classList.contains('grid-rows-[1fr]') ? 'true' : 'false');
        btn.addEventListener('click', () => {
            const isOpen = group.classList.contains('grid-rows-[1fr]');
            group.classList.toggle('grid-rows-[1fr]', !isOpen);
            group.classList.toggle('grid-rows-[0fr]', isOpen);
            btn.setAttribute('aria-expanded', String(!isOpen));
            btn.querySelector('.material-symbols-outlined')?.classList.toggle('rotate-180', !isOpen);
            /* Jaga label tetap terpandang setelah tinggi grup berubah (anti tabrakan scroll).
               Scroll dilakukan pada kontainer nav sidebar saja, BUKAN halaman,
               dan selalu menyisakan ruang (offset) agar item tidak menempel ke tepi. */
            setTimeout(() => {
                const nav = group.closest('.sidebar-scroll');
                if (!nav) return;
                const navRect = nav.getBoundingClientRect();
                const btnRect = btn.getBoundingClientRect();
                /* Jika tombol keluar dari area pandang nav, geser nav secukupnya
                   dengan margin 24px supaya tidak dempet dengan tepi bawah */
                if (btnRect.bottom > navRect.bottom - 24) {
                    nav.scrollTo({
                        top: nav.scrollTop + (btnRect.bottom - navRect.bottom) + 24,
                        behavior: 'smooth',
                    });
                }
            }, 330);
        });
    });

    /* ===== Sidebar mini (collapse) — mode desktop saja ===== */
    const collapseBtn = document.getElementById('sidebar-collapse');
    const isDesktop = () => window.matchMedia('(min-width: 768px)').matches;
    const openAllSidebarGroups = () => {
        document.querySelectorAll('[data-sidebar-group]').forEach((group) => {
            group.classList.remove('grid-rows-[0fr]');
            group.classList.add('grid-rows-[1fr]');
        });
    };

    /* Tooltip global untuk sidebar mini — fixed agar tak terpotong overflow nav */
    let menuTipEl = null;
    const hideMenuTip = () => menuTipEl?.classList.remove('visible');
    const showMenuTip = (anchor) => {
        const label = anchor.querySelector('[data-menu-label]');
        const icon = anchor.querySelector('.material-symbols-outlined');
        if (!label || !icon || !sidebar?.classList.contains('sidebar-collapsed') || !isDesktop()) return;
        if (!menuTipEl) {
            menuTipEl = document.createElement('div');
            menuTipEl.id = 'sidebar-tip-global';
            document.body.appendChild(menuTipEl);
        }
        menuTipEl.textContent = label.textContent.trim();
        const r = icon.getBoundingClientRect();
        menuTipEl.style.top = Math.round(r.top + r.height / 2) + 'px';
        menuTipEl.style.left = Math.round(r.right + 12) + 'px';
        requestAnimationFrame(() => menuTipEl.classList.add('visible'));
    };

    const applySidebarCollapse = (collapsed) => {
        sidebar?.classList.toggle('sidebar-collapsed', collapsed);
        collapseBtn?.setAttribute('aria-expanded', String(!collapsed));
        if (collapsed) openAllSidebarGroups();
        hideMenuTip();
    };

    const syncSidebarCollapse = () => {
        if (!isDesktop()) {
            /* Di mobile drawer selalu lebar penuh saat terbuka */
            sidebar?.classList.remove('sidebar-collapsed');
            hideMenuTip();
            return;
        }
        applySidebarCollapse(localStorage.getItem('ralivaSidebarCollapsed') === '1');
    };

    collapseBtn?.addEventListener('click', () => {
        const collapsed = !sidebar?.classList.contains('sidebar-collapsed');
        localStorage.setItem('ralivaSidebarCollapsed', collapsed ? '1' : '0');
        applySidebarCollapse(collapsed);
    });

    window.addEventListener('resize', syncSidebarCollapse);
    syncSidebarCollapse();

    document.querySelectorAll('#sidebar nav a').forEach((a) => {
        a.addEventListener('mouseenter', () => showMenuTip(a));
        a.addEventListener('mouseleave', hideMenuTip);
    });
    // Sidebar profile avatar — tooltip when collapsed (dropleft/dropright)
    document.querySelectorAll('.sidebar-profile').forEach((card) => {
        const nameEl = card.querySelector('[data-sidebar-text] h4');
        if (!nameEl) return;
        card.addEventListener('mouseenter', () => {
            if (!sidebar?.classList.contains('sidebar-collapsed') || !isDesktop()) return;
            if (!menuTipEl) {
                menuTipEl = document.createElement('div');
                menuTipEl.id = 'sidebar-tip-global';
                document.body.appendChild(menuTipEl);
            }
            menuTipEl.textContent = nameEl.textContent.trim();
            const r = card.querySelector('.w-11, .w-10')?.getBoundingClientRect() || card.getBoundingClientRect();
            menuTipEl.style.top = Math.round(r.top + r.height / 2) + 'px';
            menuTipEl.style.left = Math.round(r.right + 12) + 'px';
            requestAnimationFrame(() => menuTipEl.classList.add('visible'));
        });
        card.addEventListener('mouseleave', hideMenuTip);
    });
    sidebar?.querySelector('.sidebar-scroll')?.addEventListener('scroll', hideMenuTip, { passive: true });
</script>
