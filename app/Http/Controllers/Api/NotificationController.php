<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\FirebaseToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller {
    /**
     * Get all notifications for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotifications(Request $request): JsonResponse {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        return Helper::jsonResponse(true, 'Notifications retrieved successfully', 200, $notifications);
    }

    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAsRead(Request $request): JsonResponse {
        $request->validate(['notification_id' => 'required|uuid']);
        $user = Auth::user();

        $notification = $user->notifications()
            ->where('id', $request->notification_id)
            ->first();

        if (!$notification) {
            return Helper::jsonResponse(false, 'Notification not found', 404);
        }

        $notification->markAsRead();

        return Helper::jsonResponse(true, 'Notification marked as read', 200);
    }

    /**
     * Send a push notification to a user's mobile device.
     * @param mixed $userId
     * @param mixed $title
     * @param mixed $body
     * @param mixed $data
     * @return void
     */
    public function sendNotifyMobile($userId, $title, $body, $data = []): void {
        try {
            $factory   = (new Factory)->withServiceAccount(storage_path('app/firebase-auth.json'));
            $messaging = $factory->createMessaging();

            $tokens = FirebaseToken::where('user_id', $userId)
                ->pluck('token')
                ->toArray();

            if (empty($tokens)) {
                Log::info("No active Firebase tokens found for user ID: $userId");
                return;
            }

            $notification = Notification::create($title, $body);
            $message      = CloudMessage::new ()
                ->withNotification($notification)
                ->withData($data);

            //! Send notification to multiple tokens at once (batch)
            $messaging->sendMulticast($message, $tokens);

            Log::info("Push notification sent successfully to user ID: $userId");
        } catch (Exception $e) {
            Log::error("Failed to send push notification: {$e->getMessage()}");
        }
    }
}
