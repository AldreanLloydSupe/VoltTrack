<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminNotificationController extends Controller
{
    public function read(Request $request, AdminNotification $notification)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);
        abort_unless((int) $notification->user_id === (int) $user->id, 403);

        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function reply(Request $request, AdminNotification $notification)
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403);
        abort_unless((int) $notification->user_id === (int) $user->id, 403);
        abort_unless($notification->resident_id, 404);

        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'max:2000'],
        ]);

        if (
            ! Schema::hasTable('admin_notifications')
            || ! Schema::hasColumn('admin_notifications', 'replied_by')
            || ! Schema::hasColumn('admin_notifications', 'reply_message')
            || ! Schema::hasColumn('admin_notifications', 'replied_at')
        ) {
            return back()->withErrors([
                'reply_message' => 'Reply fields are not available yet. Please run the latest migrations and try again.',
            ]);
        }

        $notification->update([
            'read_at' => $notification->read_at ?? now(),
            'replied_by' => $user->id,
            'reply_message' => $validated['reply_message'],
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Reply sent to resident dashboard.');
    }

    public function destroy(Request $request, AdminNotification $notification)
    {
        $user = $request->user();

        abort_unless($user, 403);
        abort_unless(
            ($user->isAdmin() && (int) $notification->user_id === (int) $user->id)
            || ($user->isResident() && (int) $notification->resident_id === (int) $user->id),
            403
        );

        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Notification deleted.');
    }
}
