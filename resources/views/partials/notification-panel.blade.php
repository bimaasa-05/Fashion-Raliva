@php
    // Sumber data: composer global (DB) jika ada, fallback ke $items (legacy layout).
    $notificationItems = $sidebarNotifications ?? $items ?? [];
    $unreadCount = (int) ($sidebarNotificationsUnread ?? $unread ?? 0);
    $unreadLabel = $unreadCount > 99 ? '99+' : (string) $unreadCount;
    $lihatSemuaUrl = isset($lihatSemuaRoute) && \Illuminate\Support\Facades\Route::has($lihatSemuaRoute)
        ? route($lihatSemuaRoute)
        : '#';
@endphp
<div class="relative" data-notification-container>
    <button type="button" data-notification-toggle class="relative text-on-surface hover:text-secondary transition-colors" aria-label="Notifikasi">
        <span class="material-symbols-outlined">notifications</span>
        <span data-notif-badge class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 items-center justify-center bg-error text-white text-[10px] font-bold leading-none rounded-full {{ $unreadCount > 0 ? 'flex' : 'hidden' }}">{{ $unreadLabel }}</span>
    </button>
    <div data-notification-menu class="hidden absolute right-0 top-full mt-2 w-80 max-w-[calc(100vw-2rem)] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-[60] overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-muted-border">
            <p class="font-label-sm text-sm text-on-surface uppercase tracking-wider">Notifikasi</p>
            <button type="button" data-mark-all-read class="font-label-sm text-[10px] text-gold-accent uppercase hover:underline">Tandai Dibaca</button>
        </div>
        <ul class="max-h-80 overflow-y-auto" data-notif-list>
            @forelse ($notificationItems as $item)
                @php
                    if (is_object($item) && method_exists($item, 'getAttribute')) {
                        $itemIcon = match ($item->tipe) {
                            'order' => 'shopping_bag',
                            'pembayaran' => 'payments',
                            'pengiriman' => 'local_shipping',
                            'komplain' => 'support_agent',
                            'wallet' => 'account_balance_wallet',
                            'promo' => 'local_offer',
                            default => 'notifications',
                        };
                        $itemHtml = '<span class="font-bold">'.e($item->judul).'</span> — '.e($item->pesan);
                        $itemTime = $item->created_at?->diffForHumans() ?? '-';
                        $itemTarget = $item->url;
                        $itemId = $item->getAttribute('notification_id');
                    } else {
                        $itemIcon = $item['icon'] ?? 'notifications';
                        $itemHtml = $item['html'] ?? ($item['judul'] ?? '');
                        $itemTime = $item['time'] ?? $item['waktu'] ?? '-';
                        $itemTarget = $item['url'] ?? '#';
                        $itemId = $item['id'] ?? $item['id_notif'] ?? null;
                    }
                @endphp
                <li class="flex gap-3 px-4 py-3 border-b border-muted-border last:border-0 hover:bg-surface-container-low transition-colors cursor-pointer"
                    data-notif-item
                    @if ($itemId) data-notif-id="{{ $itemId }}" @endif
                    @if ($itemTarget) data-notif-target="{{ $itemTarget }}" @endif>
                    <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5 shrink-0">{{ $itemIcon }}</span>
                    <div class="min-w-0">
                        <p class="font-body-md text-sm text-on-surface">{!! $itemHtml !!}</p>
                        <p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">{{ $itemTime }}</p>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-sm text-on-surface-variant">Belum ada notifikasi.</li>
            @endforelse
        </ul>
        @if ($lihatSemuaUrl !== '#')
            <a href="{{ $lihatSemuaUrl }}" class="block text-center px-4 py-3 font-label-sm text-label-sm text-gold-accent uppercase tracking-widest hover:bg-surface-container-low transition-colors border-t border-muted-border">Lihat Semua Notifikasi</a>
        @endif
    </div>
</div>
<script>
    (function () {
        // Partial di-include 2x per layout (mobile + desktop) dan tiap script
        // jalan tepat setelah container-nya sendiri ter-parse — ikat per
        // container agar dua-duanya hidup tanpa double-bind.

        var markAllUrl = '{{ route("notifikasi.mark-all-read") }}';
        var getUrl = '{{ route("notifikasi.get") }}';
        var readUrlTemplate = '{{ route("notifikasi.read", ":id") }}';

        var csrf = function () {
            var m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '';
        };

        var esc = function (s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        };

        var bindItem = function (li) {
            li.addEventListener('click', function (e) {
                e.stopPropagation();
                var target = li.getAttribute('data-notif-target') || '#';
                var id = li.getAttribute('data-notif-id');
                if (!id) {
                    if (target && target !== '#') window.location.href = target;
                    return;
                }
                fetch(readUrlTemplate.replace(':id', id), {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', 'Accept': 'application/json' },
                }).then(function (res) {
                    if (!res.ok) throw new Error('fail');
                    return res.json();
                }).then(function (data) {
                    if (window.updateNotifBadge) window.updateNotifBadge();
                    var dest = (data && data.target) || target;
                    if (dest && dest !== '#') window.location.href = dest;
                }).catch(function () {
                    if (target && target !== '#') window.location.href = target;
                });
            });
        };

        var renderList = function (container, notifications) {
            var ul = container.querySelector('[data-notif-list]');
            if (!ul) return;
            ul.innerHTML = '';
            if (!notifications || !notifications.length) {
                var empty = document.createElement('li');
                empty.className = 'px-4 py-8 text-center text-sm text-on-surface-variant';
                empty.textContent = 'Belum ada notifikasi.';
                ul.appendChild(empty);
                return;
            }
            notifications.forEach(function (n) {
                var li = document.createElement('li');
                li.className = 'flex gap-3 px-4 py-3 border-b border-muted-border last:border-0 hover:bg-surface-container-low transition-colors cursor-pointer';
                li.setAttribute('data-notif-item', '');
                if (n.id) li.setAttribute('data-notif-id', n.id);
                if (n.target || n.url) li.setAttribute('data-notif-target', n.target || n.url);
                var unread = n.status === 0;
                var html = '<span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5 shrink-0">' + esc(n.icon || 'notifications') + '</span>' +
                    '<div class="min-w-0 flex-1">' +
                    '<p class="font-body-md text-sm ' + (unread ? 'font-bold ' : '') + 'text-on-surface"><span class="font-bold">' + esc(n.judul || 'Notifikasi') + '</span> — ' + esc(n.isi || '') + '</p>' +
                    '<p class="font-label-sm text-[10px] text-on-surface-variant uppercase mt-1">' + esc(n.waktu || '') + '</p>' +
                    '</div>';
                if (unread) {
                    html += '<span class="w-2 h-2 bg-error rounded-full mt-1.5 shrink-0" aria-hidden="true"></span>';
                }
                li.innerHTML = html;
                bindItem(li);
                ul.appendChild(li);
            });
        };

        var refreshListAndBadge = function (container) {
            fetch(getUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    renderList(container, data.notifications || []);
                    if (window.updateNotifBadge) window.updateNotifBadge();
                })
                .catch(function () {});
        };

        document.querySelectorAll('[data-notification-container]').forEach(function (container) {
            if (container.dataset.notifBound) return;
            container.dataset.notifBound = '1';
            var menu = container.querySelector('[data-notification-menu]');
            var toggle = container.querySelector('[data-notification-toggle]');
            var markAll = container.querySelector('[data-mark-all-read]');
            if (!menu || !toggle) return;

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                var opening = menu.classList.contains('hidden');
                document.querySelectorAll('[data-profile-menu]').forEach(function (m) { m.classList.add('hidden'); });
                menu.classList.toggle('hidden');
                if (opening) refreshListAndBadge(container);
            });

            if (markAll) {
                markAll.addEventListener('click', function (e) {
                    e.stopPropagation();
                    fetch(markAllUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    }).then(function (res) {
                        if (!res.ok) return;
                        if (window.showRalivaToast) window.showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'done_all');
                        refreshListAndBadge(container);
                    });
                });
            }

            container.querySelectorAll('[data-notif-item]').forEach(bindItem);
        });
    })();
</script>