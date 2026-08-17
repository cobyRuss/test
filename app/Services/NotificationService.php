<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\Notification;

class NotificationService
{
    public static function sendToAdmins(string $type, string $title, string $body, string $link): void
    {
        foreach (AdminUser::query()->get() as $admin) {
            Notification::query()->create([
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'link' => $link,
            ]);
        }
    }

    public static function sendToCustomer(int $customerId, string $type, string $title, string $body, string $link): void
    {
        Notification::query()->create([
            'recipient_type' => 'customer',
            'recipient_id' => $customerId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);
    }
}
