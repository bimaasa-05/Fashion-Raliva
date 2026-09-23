@php
    use App\Support\NotificationMeta;
    /** @var \App\Models\Notification $item */
    $notifMeta = NotificationMeta::for($item->tipe);
    $notifUnread = is_null($item->dibaca_pada);
    $notifTime = isset($item->created_at) ? $item->created_at->diffForHumans() : '-';
    $notifTarget = $item->url;
@endphp
<li class="notif-item {{ $notifUnread ? '' : 'opacity-80' }} flex items-start gap-4 px-4 py-4 hover:bg-surface-container-low transition-colors cursor-pointer rounded-lg"
    data-tipe-item="{{ $item->tipe }}"
    data-notif-id="{{ $item->notification_id }}"
    data-notif-target="{{ $notifTarget ?? '#' }}">
    <div class="relative shrink-0 mt-0.5">
        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ NotificationMeta::toneClass($item->tipe) }}">
            <span class="material-symbols-outlined text-[20px]">{{ $notifMeta['icon'] }}</span>
        </div>
        @if ($notifUnread)
            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface-container-lowest notif-dot"></span>
        @endif
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-body-md text-sm text-on-surface {{ $notifUnread ? 'font-semibold' : '' }} notif-text truncate">
            <span class="font-bold">{{ $item->judul }}</span>
        </p>
        <p class="text-on-surface-variant font-body-md text-[13px] mt-0.5 line-clamp-2">{{ $item->pesan }}</p>
        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ $notifTime }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">{{ $notifMeta['label'] }}</span>
            @if (! empty($showActor) && isset($item->aktor))
                <span class="inline-flex items-center gap-1 font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant max-w-[160px] truncate">
                    <span class="material-symbols-outlined text-[14px]">person</span>{{ $item->aktor->nama_lengkap }}
                </span>
            @endif
            @if (! empty($showTargetUser) && isset($item->user))
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold border border-gold-accent/20">{{ $item->user->nama_lengkap ?? '-' }}</span>
            @endif
        </div>
    </div>
    <span class="material-symbols-outlined text-outline-variant text-[20px] self-center shrink-0">chevron_right</span>
</li>