<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Tampilkan halaman pusat notifikasi
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        $unreadOnly = $request->query('unread') === '1';

        $query = SystemNotification::forCurrentUser()->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($unreadOnly) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20)->appends($request->all());
        $unreadCount = SystemNotification::forCurrentUser()->where('is_read', false)->count();

        return view('backend.notifications.index', compact('notifications', 'unreadCount', 'type', 'unreadOnly'));
    }

    /**
     * Endpoint API JSON untuk data navbar (jumlah unread & 5 preview notif)
     */
    public function getNavbarData()
    {
        $unreadCount = SystemNotification::forCurrentUser()->where('is_read', false)->count();

        $recentNotifications = SystemNotification::forCurrentUser()
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'is_read' => $notif->is_read,
                    'time_ago' => $notif->created_at ? $notif->created_at->diffForHumans() : '-',
                    'read_url' => route('notifications.read', $notif->id),
                ];
            });

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $recentNotifications,
        ]);
    }

    /**
     * Tandai notifikasi spesifik sebagai dibaca dan redirect ke target URL
     */
    public function markAsRead($id)
    {
        $notification = SystemNotification::forCurrentUser()->findOrFail($id);
        
        $notification->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        if (!empty($notification->url)) {
            return redirect($notification->url);
        }

        return redirect()->route('notifications.index')->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Tandai semua notifikasi yang relevan sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        SystemNotification::forCurrentUser()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca.');
    }
}
