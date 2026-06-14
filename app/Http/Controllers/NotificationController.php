<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        // Marquer tout comme lu
        Auth::user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['ok' => true]);
    }
}
