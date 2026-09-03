@php
    $user = Auth::user();
    $displayName = $user?->nama_lengkap ?? ($name ?? 'Rizky Pratama');
    $displayRole = $user?->role?->nama_role ?? ($role ?? 'Super Admin');
    $words = preg_split('/\s+/', trim($displayName));
    $init = '';
    if (!empty($words[0])) $init .= mb_substr($words[0], 0, 1);
    if (isset($words[1])) $init .= mb_substr($words[1], 0, 1);
    elseif (mb_strlen($words[0] ?? '') > 1) $init .= mb_substr($words[0], 1, 1);
    $initials = strtoupper(mb_substr($init, 0, 2)) ?: '?';
@endphp
<div class="relative" data-profile-container>
    <button type="button" data-profile-toggle class="flex items-center gap-3 hover:opacity-80 transition-opacity" aria-label="Menu profil">
        @if (empty($compact))
            <div class="text-right hidden lg:block">
                <p class="font-label-sm text-sm text-on-surface leading-tight">{{ $displayName }}</p>
                <p class="text-on-surface-variant text-xs">{{ $displayRole }}</p>
            </div>
        @endif
        <div class="w-10 h-10 rounded-full bg-gold-accent text-white flex items-center justify-center font-bold text-sm shrink-0 border border-gold-accent/30 overflow-hidden">
            @if ($user?->foto_profil_url)
                <img src="{{ $user->foto_profil_url }}" alt="{{ $displayName }}" class="w-full h-full object-cover" />
            @else
                {{ $initials }}
            @endif
        </div>
        <span class="material-symbols-outlined text-[20px] text-on-surface-variant transition-transform duration-300" data-profile-chevron>expand_more</span>
    </button>
    <div data-profile-menu class="hidden absolute right-0 top-full mt-2 w-60 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-[60] overflow-hidden">
        <div class="px-4 py-3 border-b border-muted-border">
            <p class="font-label-sm text-sm text-on-surface">{{ $displayName }}</p>
            <p class="text-on-surface-variant text-xs mt-0.5">{{ $displayRole }}</p>
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
        <form method="POST" action="{{ route('logout') }}" class="border-t border-muted-border">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-error hover:bg-error/10 transition-colors">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span class="font-body-md text-sm">Keluar</span>
            </button>
        </form>
    </div>
</div>
