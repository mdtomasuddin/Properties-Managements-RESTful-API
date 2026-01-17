<?php

namespace App\Http\Controllers\Api\V1\Resident\MyProperty;

use App\Helpers\Helper;
use App\Models\Resident;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPropertyController
{
    /**
     * Retrieves the property information for a given resident.
     * This method retrieves the property_id and unit_flat_number associated with the resident.
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $phone    = Auth::user()->phone;
            $resident = Resident::with('property:id,property_name,property_address')->where('phone', $phone)->select('property_id', 'unit_flat_number')->first();
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $resident);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
