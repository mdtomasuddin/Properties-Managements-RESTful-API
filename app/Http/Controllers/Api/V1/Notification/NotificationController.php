<?php
namespace App\Http\Controllers\Api\V1\Notification;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class NotificationController
{

    /**
     * Get Notifications for Authenticated User
     */
    public function getNotification(Request $request)
    {
        $perPage = $request->query('per_page', 10); //pagination default 10

        $user = auth()->user();

        if (! $user) {
            return Helper::jsonResponse(false, 'User not found', 404);
        }

        // $data = $user->notifications()->select('id', 'data', 'read_at', 'created_at')->latest()->paginate($perPage);
        $data = $user->notifications()->latest()->paginate($perPage);
        if ($data->isEmpty()) {
            return Helper::jsonResponse(false, 'No notifications found', 404);
        }

        return Helper::jsonResponse(true, 'Notification fetched successfully', 200, $data);
    }
}
