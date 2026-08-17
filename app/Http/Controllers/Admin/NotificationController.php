<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unread()
    {
        $adminId = Auth::guard('admin')->id();

        $items = Notification::query()
            ->forAdmin($adminId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn (Notification $n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at?->format('M j, g:i A'),
                'relative' => $n->created_at?->diffForHumans(),
            ]);

        $unread = Notification::query()->forAdmin($adminId)->unread()->count();

        return response()->json([
            'count' => $unread,
            'items' => $items,
        ]);
    }

    public function markRead(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $notificationId = (int) $request->input('notification_id', 0);

        if ($notificationId > 0) {
            Notification::query()->forAdmin($adminId)->where('id', $notificationId)->where('is_read', false)->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        } else {
            Notification::query()->forAdmin($adminId)->where('is_read', false)->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
