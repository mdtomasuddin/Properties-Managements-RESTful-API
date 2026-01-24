<?php

namespace App\Http\Controllers\Api\V1\Resident\Neighbor;

use App\Helpers\Helper;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class NeighborController
{
    /**
     * all Neighbor Users get.
     * This method retrieves a paginated list of neighbor users.
     * You can filter users by property_id query parameter.
     * The number of users per page can be controlled with the per_page query parameter (default is 50).
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $propertyId = $request->input('property_id');
            $perPage    = $request->input('per_page', 50);

            $query = User::query()
                ->join('residents', 'users.phone', '=', 'residents.phone')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.role',
                    'users.phone',
                    'users.avatar',
                    'residents.unit_flat_number',
                ]);

            //Filter by Property and Referral Code
            if (! empty($propertyId)) {
                $property     = Property::where('status', 'active')->find($propertyId);
                $referralCode = $property->user->reffer_code ?? null;
                $query->where('users.invitation_code', $referralCode);
            }

            //Paginate and Return
            $data = $query->paginate($perPage);

            return Helper::jsonResponse(true, 'Residents retrieved successfully.', 200, $data, true);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    //garbage code 
    // public function index(Request $request)
    // {
    //     try {
    //         $propertyId = $request->input('property_id');
    //         $perPage    = $request->input('per_page', 50);
    //         //all users query
    //         $query = User::where('status', 'active')->select('id', 'name', 'email', 'role', 'phone','avatar');

    //         //filter by property's reffer_code
    //         if (! empty($propertyId)) {
    //             $property     = Property::where('status', 'active')->find($propertyId);
    //             $referralCode = $property->user->reffer_code ?? null;
    //             $query->where('invitation_code', $referralCode);
    //         }
    //         $data = $query->paginate($perPage);
    //         //response with data
    //         return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data, true);
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'Failed to retrieve Data.', 500, [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }
}
