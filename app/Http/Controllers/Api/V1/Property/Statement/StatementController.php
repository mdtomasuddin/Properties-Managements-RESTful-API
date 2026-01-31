<?php

namespace App\Http\Controllers\Api\V1\Property\Statement;

use App\Helpers\Helper;
use App\Models\PaymentRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatementController
{

    /**
     * Retrieves a list of payment requests for a given property and status.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @queryParam property_id The property ID to filter by. Defaults to null.
     * @queryParam status The status of the payment requests to filter by. Defaults to null.
     */
    public function index(Request $request)
    {
        try {
            $propertyId = $request->query('property_id');
            $status     = $request->query('status');

            $query = PaymentRequest::with(['resident', 'property', 'category'])
                ->where('user_id', Auth::id());

            if (! empty($propertyId)) {
                $query->where('property_id', $propertyId);
            }
            if (! empty($status)) {
                $query->where('status', $status);
            }

            $payments = $query->get();

            // Group by resident to ensure unique rows
            $residentInformation = $payments->groupBy('resident_id')->map(function ($group) {
                $firstRecord   = $group->first();
                $typeBreakdown = $group->map(function ($payment) {
                    return [
                        'payment_category' => $payment->category->name ?? 'General',
                        'amount'           => $payment->amount,
                    ];
                })->values();

                return [
                    'name'          => $firstRecord->resident->name ?? 'Unknown',
                    'property_name' => $firstRecord->property->property_name ?? 'N/A',
                    'amount'        => $group->sum('amount'),
                    'status'        => $group->contains('status', 'due') ? 'due' : 'paid',
                    'data'          => [
                        'total_requests' => $group->count(),
                        'payment_type'   => $typeBreakdown, // The category list you requested
                        'last_date'      => $group->max('created_at'),
                    ],
                ];
            })->values();

            $response = [
                'Amount'   => [
                    'total_amount' => $payments->sum('amount'),
                    'total_count'  => $residentInformation->count(),
                    'date'         => now()->toDateTimeString(),
                ],
                'resident' => $residentInformation,
            ];

            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $response);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Data', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    // 1. Check if any payment in this group is 'overdue' (due_date < today and not paid)
    // $hasOverdue = $group->filter(function($item) {
    //     return $item->status !== 'paid' && $item->due_date < now()->toDateString();
    // })->isNotEmpty();

    // // 2. Decide overall status text
    // if ($hasOverdue) {
    //     $overallStatus = 'overdue';
    // } elseif ($group->contains('status', 'due')) {
    //     $overallStatus = 'due';
    // } else {
    //     $overallStatus = 'paid';
    // }
}
