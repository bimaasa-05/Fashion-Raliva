<div class="relative" data-profile-container>
    <button type="button" data-profile-toggle class="flex items-center gap-3 hover:opacity-80 transition-opacity" aria-label="Menu profil">
        @if (empty($compact))
            <div class="text-right hidden lg:block">
                <p class="font-label-sm text-sm text-on-surface leading-tight">{{ $name ?? 'Rizky Pratama' }}</p>
                <p class="text-on-surface-variant text-xs">{{ $role ?? 'Super Admin' }}</p>
            </div>
        @endif
        <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden shrink-0">
            <img alt="Foto Profil" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
        </div>
        <span class="material-symbols-outlined text-[20px] text-on-surface-variant transition-transform duration-300" data-profile-chevron>expand_more</span>
    </button>
    <div data-profile-menu class="hidden absolute right-0 top-full mt-2 w-60 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-[60] overflow-hidden">
        <div class="px-4 py-3 border-b border-muted-border">
            <p class="font-label-sm text-sm text-on-surface">{{ $name ?? 'Rizky Pratama' }}</p>
            <p class="text-on-surface-variant text-xs mt-0.5">{{ $role ?? 'Super Admin' }}</p>
        </div>
        <a href="{{ route($profilRoute ?? 'superadmin.profil') }}" class="flex items-center gap-3 px-4 py-3 text-on-surface hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[20px] text-on-surface-variant">person</span>
            <span class="font-body-md text-sm">Profil</span>
        </a>
        @if (!isset($showPengaturan) || $showPengaturan)
            <a href="{{ route($pengaturanRoute ?? 'superadmin.pengaturan-sistem') }}" class="flex items-center gap-3 px-4 py-3 text-on-surface hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[20px] text-on-surface-variant">settings</span>
                <span class="font-body-md text-sm">Pengaturan</span>
            </a>
        @endif
        <div class="border-t border-muted-border"></div>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-error hover:bg-error/10 transition-colors">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span class="font-body-md text-sm">Keluar</span>
        </a>
    </div>
</div>
