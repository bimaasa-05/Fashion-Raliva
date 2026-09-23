{{-- Combobox kategori searchable + inline create. Params: $prefix, $categories, $selectedId='', $selectedName='' --}}
<div class="relative" id="{{ $prefix }}-kategori-box" data-ktg-box="{{ $prefix }}">
    <button type="button" id="{{ $prefix }}-kategori-btn" aria-haspopup="listbox" aria-expanded="false" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-3.5 pr-10 py-2.5 font-body-md text-sm text-on-surface text-left transition-colors focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10">
        <span id="{{ $prefix }}-kategori-label" class="truncate {{ $selectedName ? 'text-on-surface' : 'text-on-surface-variant' }}">{{ $selectedName ?: 'Cari atau ketik kategori...' }}</span>
        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
    </button>
    <input type="hidden" name="category_id" id="{{ $prefix }}-kategori-hidden" value="{{ $selectedId }}" />
    <div id="{{ $prefix }}-kategori-menu" class="hidden absolute z-30 mt-1 w-full bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl overflow-hidden">
        <div class="border-b border-muted-border p-2">
            <div class="flex items-stretch">
                <span class="inline-flex items-center px-3 text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none"><span class="material-symbols-outlined text-[18px]">search</span></span>
                <input type="text" id="{{ $prefix }}-kategori-search" placeholder="Cari kategori..." autocomplete="off" class="raliva-input text-sm" style="border-top-left-radius:0;border-bottom-left-radius:0;" />
            </div>
        </div>
        <ul id="{{ $prefix }}-kategori-list" role="listbox" data-ktg-list class="max-h-52 overflow-y-auto overscroll-contain py-1" style="overscroll-behavior: contain;">
            @foreach ($categories as $c)
                <li role="option" data-category-id="{{ $c->category_id }}" data-category-name="{{ $c->nama_kategori }}" class="px-4 py-2.5 text-sm cursor-pointer hover:bg-surface-container-low transition-colors text-on-surface">{{ $c->nama_kategori }}</li>
            @endforeach
        </ul>
        <div id="{{ $prefix }}-kategori-inline" class="hidden border-t border-muted-border p-3 space-y-2">
            <input type="text" id="{{ $prefix }}-kategori-inline-nama" placeholder="Nama kategori baru (cth. Outerwear)" autocomplete="off" maxlength="100" class="raliva-input text-sm" />
            <div class="flex gap-2 justify-end">
                <button type="button" id="{{ $prefix }}-kategori-inline-batal" class="px-3 py-1.5 border border-muted-border rounded-lg text-xs text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="button" id="{{ $prefix }}-kategori-inline-simpan" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-xs rounded btn-premium">Simpan</button>
            </div>
        </div>
        <button type="button" id="{{ $prefix }}-kategori-add" class="w-full flex items-center gap-2 px-4 py-2.5 border-t border-muted-border text-gold-accent text-sm hover:bg-gold-accent/5 transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span> Buat kategori baru
        </button>
    </div>
</div>
