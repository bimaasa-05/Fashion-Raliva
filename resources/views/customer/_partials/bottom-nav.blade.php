{{-- SHARED BOTTOM NAVIGATION (mobile only) --}}
<style>
    .material-symbols-outlined[data-weight="fill"] {
        font-variation-settings: 'FILL' 1;
    }
    .bn-circle {
        width: 40px;
        height: 40px;
        color: var(--chrome-text-dim);
        transition: background-color .25s ease, color .25s ease, box-shadow .25s ease;
    }
    .bn-item:hover .bn-circle {
        background-color: var(--chrome-hover);
    }
    .bn-active .bn-label {
        color: var(--chrome-accent);
        font-weight: 700;
    }
</style>
<nav class="md:hidden fixed bottom-0 inset-x-0 z-50 flex justify-around items-center h-[72px] bg-[var(--chrome-bg)] text-[var(--chrome-text)] px-xs pb-safe border-t border-[var(--chrome-border)] shadow-sm">
    @php
        $bnItems = [
            ['label' => __('Home'), 'icon' => 'home', 'href' => route('customer.home'), 'active' => request()->routeIs('customer.home')],
            ['label' => __('Shop'), 'icon' => 'shopping_bag', 'href' => route('customer.shop'), 'active' => request()->routeIs('customer.shop') || request()->routeIs('customer.shop.produk-detail') || request()->routeIs('customer.shop.store-detail')],
            ['label' => __('Pesanan'), 'icon' => 'receipt_long', 'href' => auth()->check() ? route('customer.order-tracking') : route('login', ['redirect' => '/customer/order-tracking']), 'active' => request()->routeIs('customer.order-tracking')],
            ['label' => __('Wishlist'), 'icon' => 'favorite', 'href' => auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => '/customer/wishlist']), 'active' => request()->routeIs('customer.wishlist')],
            ['label' => __('Account'), 'icon' => 'person', 'href' => auth()->check() ? route('customer.account') : route('login', ['redirect' => '/customer/account']), 'active' => request()->routeIs('customer.account') || request()->routeIs('customer.account.edit') || request()->routeIs('customer.account.password') || request()->routeIs('customer.address')],
        ];
    @endphp
    @foreach ($bnItems as $it)
        <a aria-label="{{ $it['label'] }}" href="{{ $it['href'] }}" class="bn-item flex flex-col items-center justify-center gap-1 w-16 h-full{{ $it['active'] ? ' bn-active' : '' }}">
            <span class="bn-circle flex items-center justify-center rounded-full">
                <span class="material-symbols-outlined text-[22px]{{ $it['active'] ? ' text-secondary-fixed-dim' : '' }}"@if($it['active']) data-weight="fill"@endif>{{ $it['icon'] }}</span>
            </span>
            <span class="bn-label font-label-sm text-[10px]">{{ $it['label'] }}</span>
        </a>
    @endforeach
</nav>
