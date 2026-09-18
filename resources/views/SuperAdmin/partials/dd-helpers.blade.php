<script>
    /* ── Custom Dropdown Helpers (shared across SuperAdmin pages) ── */

    function toggleDropdown(id) {
        const menu = document.getElementById(id + '-menu');
        const chevron = document.getElementById(id + '-chevron');
        const trigger = document.getElementById(id + '-trigger');
        if (!menu) return;
        const open = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden', open);
        if (chevron) chevron.classList.toggle('rotate-180', !open);
        if (trigger) trigger.setAttribute('aria-expanded', String(!open));
    }

    function closeDropdown(id) {
        const menu = document.getElementById(id + '-menu');
        const chevron = document.getElementById(id + '-chevron');
        const trigger = document.getElementById(id + '-trigger');
        if (menu) menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }

    function closeAllDropdowns() {
        document.querySelectorAll('[data-dropdown-menu]:not(.hidden)').forEach((menu) => {
            menu.classList.add('hidden');
            const root = menu.parentElement;
            const chevron = root?.querySelector('[data-dd-chevron]');
            if (chevron) chevron.classList.remove('rotate-180');
            const trigger = root?.querySelector('[data-dd-trigger]');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        });
    }

    /* Sync hidden input + label + check marks inside a custom dropdown container. */
    function ddSet(id, value, label) {
        const root = document.getElementById(id);
        const input = root?.querySelector('[data-dd-value]');
        if (input) input.value = value;
        const labelEl = root?.querySelector('[data-dd-label]');
        if (labelEl) labelEl.textContent = label;
        root?.querySelectorAll('[data-dd-option]').forEach((opt) => {
            const check = opt.querySelector('[data-dd-check]');
            const sel = opt.getAttribute('data-dd-option') === String(value);
            if (check) check.classList.toggle('hidden', !sel);
            opt.setAttribute('aria-selected', String(sel));
        });
        closeDropdown(id);
    }

    /* Reset chevron rotation when a dropdown is closed by outside click. */
    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-dd-option], [data-dd-trigger]')) return;
        document.querySelectorAll('[data-dd-trigger]').forEach((t) => {
            const menu = t.parentElement?.querySelector('[data-dropdown-menu]');
            if (menu && menu.classList.contains('hidden')) {
                const ch = t.querySelector('[data-dd-chevron]');
                if (ch) ch.classList.remove('rotate-180');
                t.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>