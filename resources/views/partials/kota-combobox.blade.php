{{-- Combobox kota searchable per pulau (tanpa tambah baru). Params: $prefix, $cities (pulau => [nama]), $selectedName='', $fieldName='kota', $placeholder='Cari kota...' --}}
<div class="relative" id="{{ $prefix }}-kota-box" data-kota-box="{{ $prefix }}">
    <button type="button" id="{{ $prefix }}-kota-btn" aria-haspopup="listbox" aria-expanded="false" class="w-full bg-surface border border-outline-variant rounded-lg pl-3.5 pr-10 py-2.5 font-body-md text-sm text-on-surface text-left transition-colors focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/10">
        <span id="{{ $prefix }}-kota-label" class="truncate {{ $selectedName ? 'text-on-surface' : 'text-on-surface-variant' }}">{{ $selectedName ?: ($placeholder ?? 'Cari kota...') }}</span>
        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
    </button>
    <input type="hidden" name="{{ $fieldName ?? 'kota' }}" id="{{ $prefix }}-kota-hidden" value="{{ $selectedName }}" />
    <div id="{{ $prefix }}-kota-menu" class="hidden absolute z-30 bottom-full mb-2 w-full bg-surface-container-lowest border border-outline-variant rounded-lg shadow-xl overflow-hidden">
        <div class="border-b border-outline-variant p-2">
            <div class="flex items-stretch">
                <span class="inline-flex items-center px-3 text-on-surface-variant bg-surface-container-low border border-outline-variant rounded-l-lg border-r-0 select-none"><span class="material-symbols-outlined text-[18px]">search</span></span>
                <input type="text" id="{{ $prefix }}-kota-search" placeholder="Ketik kota atau pulau..." autocomplete="off" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-3 py-2 text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-secondary" style="border-top-left-radius:0;border-bottom-left-radius:0;" />
            </div>
        </div>
        <ul id="{{ $prefix }}-kota-list" role="listbox" class="max-h-52 overflow-y-auto overscroll-contain py-1" style="overscroll-behavior: contain;">
            @foreach (($cities ?? []) as $pulau => $list)
                <li data-kota-header data-pulau="{{ $pulau }}" aria-hidden="true" class="px-4 pt-2 pb-1 text-[11px] font-bold uppercase tracking-widest text-on-surface bg-surface-container-low sticky top-0">{{ $pulau }}</li>
                @foreach ($list as $c)
                    <li role="option" data-kota data-kota-nama="{{ $c }}" data-pulau="{{ $pulau }}" class="px-4 py-2.5 text-sm cursor-pointer hover:bg-surface-container-low transition-colors text-on-surface">{{ $c }}</li>
                @endforeach
            @endforeach
        </ul>
        <p id="{{ $prefix }}-kota-empty" class="hidden px-4 py-3 text-sm text-on-surface-variant">Kota tidak ditemukan.</p>
    </div>
</div>
<script>
(function () {
    var prefix = '{{ $prefix }}';
    var box = document.getElementById(prefix + '-kota-box');
    var btn = document.getElementById(prefix + '-kota-btn');
    var menu = document.getElementById(prefix + '-kota-menu');
    var label = document.getElementById(prefix + '-kota-label');
    var hidden = document.getElementById(prefix + '-kota-hidden');
    var search = document.getElementById(prefix + '-kota-search');
    var list = document.getElementById(prefix + '-kota-list');
    var empty = document.getElementById(prefix + '-kota-empty');
    if (!box || !btn || !menu || !hidden || !list || box.dataset.kotaInit) return;
    box.dataset.kotaInit = '1';

    function closeMenu() {
        menu.classList.add('hidden');
        btn.setAttribute('aria-expanded', 'false');
    }
    function openMenu() {
        menu.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
        if (search) { search.value = ''; filter(''); requestAnimationFrame(function () { search.focus(); }); }
    }
    function filter(q) {
        q = (q || '').toLowerCase().trim();
        var shown = 0;
        var headers = list.querySelectorAll('[data-kota-header]');
        headers.forEach(function (h) {
            var pulau = (h.getAttribute('data-pulau') || '').toLowerCase();
            var pulauHit = q === '' || pulau.indexOf(q) !== -1;
            var vis = 0;
            var el = h.nextElementSibling;
            while (el && !el.hasAttribute('data-kota-header')) {
                var hit = pulauHit || (el.getAttribute('data-kota-nama') || '').toLowerCase().indexOf(q) !== -1;
                el.classList.toggle('hidden', !hit);
                if (hit) vis++;
                el = el.nextElementSibling;
            }
            h.classList.toggle('hidden', vis === 0);
            shown += vis;
        });
        if (empty) empty.classList.toggle('hidden', shown > 0);
    }
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.contains('hidden') ? openMenu() : closeMenu();
    });
    document.addEventListener('click', function (e) {
        if (!box.contains(e.target)) closeMenu();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
    if (search) search.addEventListener('input', function () { filter(search.value); });
    list.querySelectorAll('[data-kota]').forEach(function (li) {
        li.addEventListener('click', function () {
            hidden.value = li.getAttribute('data-kota-nama');
            label.textContent = li.getAttribute('data-kota-nama');
            label.classList.remove('text-on-surface-variant');
            label.classList.add('text-on-surface');
            closeMenu();
        });
    });
})();
</script>
