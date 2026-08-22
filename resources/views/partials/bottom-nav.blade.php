<nav class="md:hidden flex justify-around items-center w-full h-[72px] bg-surface border-t border-outline-variant px-xs pb-safe fixed bottom-0 z-50 shadow-sm">
    @foreach ($items as $item)
        @php
            $isActive = request()->routeIs($item['route']);
        @endphp
        <a class="flex flex-col items-center justify-center {{ $isActive ? 'text-secondary' : 'text-on-surface-variant hover:text-secondary transition-colors' }}" href="{{ route($item['route']) }}">
            <span class="material-symbols-outlined {{ $isActive ? 'fill' : '' }}">{{ $item['icon'] }}</span>
            <span class="font-label-sm text-label-sm mt-1">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
