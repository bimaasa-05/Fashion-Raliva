@php
    $welcomeNotification = collect($sidebarNotifications ?? [])
        ->first(fn ($notification) => $notification->dibaca_pada === null
            && \App\Services\NotificationService::isWelcome($notification));
@endphp
<style>
    #notif-toast-container > div {
        background-color: var(--chrome-bg) !important;
        border-color: var(--chrome-border) !important;
        color: var(--chrome-text) !important;
    }
</style>
@if ($welcomeNotification)
<script>
    (function () {
        function showWelcome() {
            if (!window.showNotifToast) return;

            var key = @json('ralivaWelcomeShown_'.$welcomeNotification->notification_id);

            try {
                if (sessionStorage.getItem(key)) return;
                sessionStorage.setItem(key, '1');
            } catch (e) {
                // sessionStorage bisa diblokir di mode privasi; tetap tampilkan sekali.
            }

            window.showNotifToast({
                type: @json($welcomeNotification->tipe),
                judul: @json(__($welcomeNotification->judul)),
                message: @json(__($welcomeNotification->pesan)),
                url: @json($welcomeNotification->url ?: route('customer.notifications')),
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showWelcome);
        } else {
            showWelcome();
        }
    })();
</script>
@endif
