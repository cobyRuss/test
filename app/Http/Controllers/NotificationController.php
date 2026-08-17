<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unread()
    {
        $customerId = Auth::guard('web')->id();

        $items = Notification::query()
            ->forCustomer($customerId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn (Notification $n) => [
                'id' => $n->id,
                'title' => $n->title,
                'body' => $n->body,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at?->format('M j, g:i A'),
                'relative' => $n->created_at?->diffForHumans(),
            ]);

        $unread = Notification::query()->forCustomer($customerId)->unread()->count();

        return response()->json([
            'count' => $unread,
            'items' => $items,
        ]);
    }

    public function markRead(Request $request)
    {
        $customerId = Auth::guard('web')->id();
        $notificationId = (int) $request->input('notification_id', 0);

        if ($notificationId > 0) {
            Notification::query()->forCustomer($customerId)->where('id', $notificationId)->where('is_read', false)->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        } else {
            Notification::query()->forCustomer($customerId)->where('is_read', false)->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
