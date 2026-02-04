<?php

namespace App\Http\Controllers\Api\V1\Notification;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class NotificationController
{


    public function getNotification(Request $request)
    {
        $perPage = $request->query('per_page', 10); //pagination default 10
        $type = $request->query('type');

        $user = auth()->user();

        if (! $user) {
            return Helper::jsonResponse(false, 'Unauthorized action', 404);
        }
        $data = $user->notifications()->latest()->paginate($perPage);

        //makeHidden
        // $data->makeHidden(['type', 'notifiable_type', 'updated_at', 'notifiable_id']);
        return Helper::jsonResponse(true, 'Notification fetched successfully', 200, $data);
    }
}
