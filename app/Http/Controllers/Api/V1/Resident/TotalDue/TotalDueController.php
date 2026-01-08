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

    /**
     * Due Invoice
     * @return \Illuminate\Http\Response
     * Property payment requests list only Authenticated Resident.
     */
    public function DueInvoice($id)
    {
        try {
            $authUserId = Auth::id();
            $property   = Property::with('user:id,name,phone')->find($id);

            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found.', 404);
            }
            $paymentRequests = PaymentRequest::query()
                ->with(['category:id,name'])->where('property_id', $id)->where('status', 'due')->where('resident_id', $authUserId)->get();

            if ($paymentRequests->isEmpty()) {
                return Helper::jsonResponse(false, 'No pending payments found for this property.', 404);
            }

            //Calculate Total Due
            $totalDue = $paymentRequests->sum('amount');

            //Build Response
            $data = [
                'invoice_info'      => [
                    'invoice_number' => (string) "N/A",
                    'issue_date'     => now()->format('Y-m-d'),
                    'billing_period' => now()->format('F Y'),
                    'status'         => 'Due',
                ],
                'resident'          => [
                    'name'  => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? 'N/A',
                ],
                'property'          => [
                    'property_name' => $property->property_name,
                    'name'          => $property->user->name ?? 'N/A',
                    'phone'         => $property->user->phone ?? 'N/A',
                ],
                'payment_breakdown' => $paymentRequests->map(function ($req) {
                    return [
                        'category' => (string) $req->category->name ?? 'General Charge',
                        'amount'   => (int) $req->amount,
                    ];
                }),
                'total_due'         => $totalDue,
            ];

            return Helper::jsonResponse(true, 'Invoice data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
