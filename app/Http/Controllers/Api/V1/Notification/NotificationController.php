<?php

namespace App\Http\Controllers\Api\V1\Notification;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class NotificationController
{


    /**
     * Retrieves notifications for the current user.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @queryParam per_page int Number of items to return per page. Defaults to 10.
     *Type = MaintenanceNotification || AnnouncementNotification ||PaymentRequestNotification||PaymentSendNotification||EmergencyNotification
     */
    public function getNotification(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            return Helper::jsonResponse(false, 'Unauthorized action', 401);
        }

        $perPage = $request->query('per_page', 10);
        $type    = $request->query('type'); //type of notification
        $query   = $user->notifications();

        // Filter by type
        if ($type) {
            $fullType = str_contains($type, 'App\\Notifications\\') ? $type : 'App\\Notifications\\' . $type;
            $query->where('type', $fullType);
        }
        $notifications = $query->latest()->paginate($perPage);

        return Helper::jsonResponse(true, 'Notifications fetched successfully', 200, $notifications, true);
    }
}
