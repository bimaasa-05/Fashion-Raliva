@php
    // Fallback aman kalau view lupa mengirim variabel (composer layout menjamin-nya).
    $warehouses = $warehouses ?? collect();
    $warehouse = $warehouse ?? null;
@endphp

<div class="relative self-start sm:self-auto">
    <button type="button" data-dropdown-toggle class="w-full sm:w-auto flex items-center justify-between gap-2 border border-muted-border rounded-lg px-4 py-2.5 font-body-md text-sm text-on-surface hover:border-gold-accent transition-colors bg-surface-container-lowest min-w-[180px]">
        <span class="truncate">{{ $warehouse->nama_gudang ?? 'Pilih Gudang' }}</span>
        <span class="material-symbols-outlined text-[18px] shrink-0">expand_more</span>
    </button>
    <div data-dropdown-menu class="hidden absolute right-0 top-full mt-2 w-full sm:w-72 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden">
        @forelse ($warehouses as $wh)
            @if (($warehouse->warehouse_id ?? null) === $wh->warehouse_id)
                <div class="px-4 py-3 border-b border-muted-border flex items-center gap-3 bg-surface-container-low">
                    <span class="material-symbols-outlined text-[20px] text-secondary fill">check_circle</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-body-md text-sm text-on-surface truncate">{{ $wh->nama_gudang }}</p>
                        <p class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant mt-0.5 truncate">{{ $wh->store->nama_toko ?? '' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20 shrink-0">Aktif</span>
                </div>
            @else
                <form action="{{ route('gudang.ganti') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="warehouse_id" value="{{ $wh->warehouse_id }}" />
                    <button type="submit" class="w-full px-4 py-3 flex items-center gap-3 text-left hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant shrink-0">warehouse</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-body-md text-sm text-on-surface truncate">{{ $wh->nama_gudang }}</p>
                            <p class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant mt-0.5 truncate">{{ $wh->store->nama_toko ?? '' }}</p>
                        </div>
                    </button>
                </form>
            @endif
        @empty
            <div class="px-4 py-3 text-sm text-on-surface-variant">Tidak ada gudang ditugaskan.</div>
        @endforelse
    </div>
</div>
