<?php

namespace App\View\Composers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        $notifications = $user
            ? Notification::with('aktor:user_id,nama_lengkap,foto_profil')
                ->forUser($user->user_id)
                ->latest()
                ->limit(8)
                ->get()
            : collect();

        $view->with('sidebarNotifications', $notifications);
        $view->with('sidebarNotificationsUnread', $user ? NotificationService::unreadCount($user->user_id) : 0);
    }
}