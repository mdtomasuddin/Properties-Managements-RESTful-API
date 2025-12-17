<?php

namespace App\Helpers;

use App\Models\FirebaseToken;
use App\Models\Notification;
use DevKandil\NotiFire\Facades\Fcm;
use Illuminate\Support\Facades\Log;


class PushNotificationHelper
{
    /**
     * Send push notification
     * payment_reminders||announcements||Emergency || maintenance||Payment
     * @param string $type (Notification type, e.g.)
     * @param string $title (Notification title)
     * @param string $body (Notification body)
     * @param array $extraData (Additional data etc.)
     */
    public static function send($tokens = null, $title = null, $body = null)
    {
        if (! empty($tokens)) {
            try {
                $data = Fcm::withTitle($title)
                    ->withBody($body)
                    ->sendNotification($tokens);
                Log::info('Notification sent:', ['data' => $data]);  // Log for debugging
            } catch (\Exception $e) {
                Log::error('FCM Notification error:', ['error' => $e->getMessage()]);
            }
        }
    }
}
