<?php

namespace App\Http\Controllers\Api\V1\Resident\Support;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class SupportController
{
    /**
     * property owner information get <div class=""></div>
     * The method returns the user information in a JSON response with the status code 200.
     * The method throws an exception if the data is not found.
     * @throws Exception
     */
    public function index()
    {
        try {
            $invitationCode = Auth::user()->invitation_code;
            $userId         = User::where('reffer_code', $invitationCode)->select('id', 'email', 'phone')->first();
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $userId);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
