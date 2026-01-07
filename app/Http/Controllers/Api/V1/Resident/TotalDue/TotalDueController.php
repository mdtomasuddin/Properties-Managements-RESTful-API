<?php

namespace App\Http\Controllers\Api\V1\Resident\TotalDue;

use App\Helpers\Helper;
use App\Models\PaymentRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TotalDueController
{
    // public function index(Request $request)
    // {
    //     try {
    //         $propertyId = $request->query('property_id');
    //         $authUserId = Auth::id();

    //         //Validate property existence and get owner's referral code
    //         $property = Property::with('user:id,reffer_code')->find($propertyId);
    //         if (! $property) {
    //             return Helper::jsonResponse(false, 'Property not found', 404);
    //         }

    //         //Fetch Payment Requests for this property
    //         // We filter: (send_to ALL) OR (send_to INDIVIDUAL and the user is in the selected_resident_id JSON)
    //         $paymentRequests = PaymentRequest::select('id', 'category_id', 'property_id', 'send_to', 'selected_resident_id', 'amount','due_date')->with(['category:id,name,type', 'property:id,property_name'])->where('property_id', $propertyId)
    //             ->where(function ($query) use ($authUserId) {
    //                 $query->where('send_to', 'all_resident')
    //                     ->orWhere(function ($subQuery) use ($authUserId) {
    //                         $subQuery->where('send_to', 'individual')
    //                             ->whereJsonContains('selected_resident_id', (string) $authUserId);
    //                     });
    //             })->latest()->get();

    //         return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $paymentRequests);
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'Failed to retrieve Data.', 500, [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function index(Request $request)
    {
        try {
            $propertyId = $request->query('property_id');
            $authUserId = Auth::id();
            //Validate property existence and get owner's referral code
            $property = Property::select('id', 'user_id', 'property_name')->find($propertyId);
            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found.', 404);
            }

            //Fetch Payment Requests with breakdown requirements.
            $paymentRequests = PaymentRequest::query()
                ->select('id', 'category_id', 'property_id', 'send_to', 'selected_resident_id', 'amount', 'due_date')
                ->with(['category:id,name'])->where('property_id', $propertyId)->where('status', 'due')
                ->where(function ($query) use ($authUserId) {
                    $query->where('send_to', 'all_resident')
                        ->orWhere(function ($subQuery) use ($authUserId) {
                            $subQuery->where('send_to', 'individual')->whereJsonContains('selected_resident_id', (string) $authUserId);
                        });
                })->latest()->get();
            $totalDue = $paymentRequests->sum('amount');

            //Build response
            $data = [
                'total_due'        => $totalDue,
                'payment_requests' => $paymentRequests,
            ];
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data, true);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
