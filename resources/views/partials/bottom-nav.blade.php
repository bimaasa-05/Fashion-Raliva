<nav class="md:hidden flex justify-around items-center w-full h-[72px] bg-surface border-t border-outline-variant px-xs pb-safe fixed bottom-0 z-50 shadow-sm">
    @php $badgeCounts = $badgeCounts ?? []; @endphp
    @foreach ($items as $item)
        @php
            $isActive = request()->routeIs($item['route']);
        @endphp
        <a class="flex flex-col items-center justify-center {{ $isActive ? 'text-secondary' : 'text-on-surface-variant hover:text-secondary transition-colors' }}" href="{{ route($item['route']) }}">
            <span class="relative inline-flex">
                <span class="material-symbols-outlined {{ $isActive ? 'fill' : '' }}">{{ $item['icon'] }}</span>
                @if (! empty($item['badge']) && (($badgeCounts[$item['badge']] ?? 0) > 0))
                    <span data-sidebar-badge="{{ $item['badge'] }}" class="absolute -top-1 -right-2 inline-flex items-center justify-center min-w-[16px] h-[16px] px-1 rounded-full bg-gold-accent text-white text-[9px] font-bold leading-none">{{ min(99, $badgeCounts[$item['badge']]) }}</span>
                @elseif (! empty($item['badge']))
                    <span data-sidebar-badge="{{ $item['badge'] }}" class="absolute -top-1 -right-2 inline-flex items-center justify-center min-w-[16px] h-[16px] px-1 rounded-full bg-gold-accent text-white text-[9px] font-bold leading-none hidden"></span>
                @endif
            </span>
            <span class="font-label-sm text-label-sm mt-1">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
