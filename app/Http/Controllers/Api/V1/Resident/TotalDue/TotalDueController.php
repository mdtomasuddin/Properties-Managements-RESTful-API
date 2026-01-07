<?php

namespace App\Http\Controllers\Api\V1\Resident\TotalDue;

use App\Helpers\Helper;
use App\Models\PaymentRequest;
use App\Models\Property;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TotalDueController
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     * Property payment requests list only Authenticated Resident.
     */
    public function index(Request $request)
    {
        try {
            $propertyId = $request->query('property_id');
            $authUserId = Auth::id();

            // Check if the property exists
            $property = Property::find($propertyId);
            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found.', 404);
            }

            // Fetch payment requests with necessary breakdown
            $paymentRequests = PaymentRequest::query()
                ->select('id', 'category_id', 'property_id', 'send_to', 'resident_id', 'amount', 'due_date')
                ->with(['category:id,name'])->where('property_id', $propertyId)->where('status', 'due')->where('resident_id', $authUserId)
                ->latest()->get();

            //total due amount calculation
            $totalDue = $paymentRequests->sum('amount');

            //Build response
            $data = [
                'total_due'        => $totalDue,
                'payment_requests' => $paymentRequests,
            ];
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data, true);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
