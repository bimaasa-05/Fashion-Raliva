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

    document.querySelectorAll('[data-sidebar-group-button]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const group = btn.parentElement.querySelector('[data-sidebar-group]');
            group?.classList.toggle('hidden');
            btn.querySelector('.material-symbols-outlined')?.classList.toggle('rotate-180');
        });
    });
</script>
