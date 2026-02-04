<?php
namespace App\Http\Controllers\Api\V1\Notification;

use App\Helpers\Helper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Mark a notification as read.
     * @param Request $request
     * @return JsonResponse
     */
    public function MarkAsRead(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|exists:notifications,id',
            ]);

            //* Get the authenticated user
            $user = Auth::user();

            //! Find the notification based on ID and user
            $notification = $user->notifications()->where('id', $request->notification_id)->first();

            //? If the notification doesn't exist, return a 404 error
            if (! $notification) {
                return Helper::jsonResponse(false, 'Notification not found', 404);
            }

            //* Mark the notification as read
            $notification->markAsRead();

            //! Return success response
            return Helper::jsonResponse(true, 'Notification marked as read', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to mark notification as read', 500, ['error' => $e->getMessage()]);
        }
    }
}
