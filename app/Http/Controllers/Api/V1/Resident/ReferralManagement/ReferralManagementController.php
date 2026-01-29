<?php
namespace App\Http\Controllers\Api\V1\Resident\ReferralManagement;

use App\Helpers\Helper;
use App\Models\Resident;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReferralManagementController
{

    /**
     * Retrieves the Resident Information based on phone number and invitation code.
     * This method will return an array containing the property_id, unit_flat_number, and invitation_code.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $authUser = Auth::user();
            if (! $authUser) {
                return Helper::jsonResponse(false, 'Unauthorized.', 401);
            }
            $data = Resident::where('phone', $authUser->phone)->where('invitation_code', $authUser->invitation_code)->select('property_id', 'unit_flat_number', 'invitation_code')->get();
            $data->makeVisible(['invitation_code']);
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Data retrieval failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update Resident Profile Information.
     * This method validates the request, updates the user's invitation code, and updates the Resident Information based on phone number.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'property_id'      => 'required|integer|exists:properties,id',
            'invitation_code'  => 'required|string|exists:users,reffer_code',
            'unit_flat_number' => 'required|string',
        ]);
        // Validation fails
        if ($validator->fails()) {
            return Helper::jsonResponse(false, $validator->errors()->first(), 422);
        }

        $validated = $validator->validated(); // Get validated data
        $user      = Auth::user();
        if (! $user) {
            return Helper::jsonResponse(false, 'Unauthorized access.', 401);
        }

        DB::beginTransaction();
        try {
            $user->update([
                'invitation_code' => $validated['invitation_code'],
            ]);

            // Update Resident Information based on phone number
            Resident::where('phone', $user->phone)->update([
                'property_id'      => $validated['property_id'],
                'unit_flat_number' => $validated['unit_flat_number'],
                'invitation_code'  => $validated['invitation_code'],
            ]);
            DB::commit();
            return Helper::jsonResponse(true, 'Data updated successfully.', 201);
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::jsonResponse(false, 'Update failed: ' . $e->getMessage(), 500);
        }
    }
}
