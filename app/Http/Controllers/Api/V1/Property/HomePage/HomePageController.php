<?php
namespace App\Http\Controllers\Api\V1\Property\HomePage;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\Property;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class HomePageController
{

    /**
     * index the total due and total collected amount for a specific property.
     * * @param Request $request
     * Filter by month or year || default === month
     */
    public function index(Request $request)
    {
        try {
            $filter     = $request->query('filter', 'month');
            $propertyId = $request->query('property_id');
            $now        = Carbon::now();

            //property
            $property = Property::find($propertyId);
            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found.', 404);
            }

            $dueQuery  = PaymentRequest::where('property_id', $propertyId)->where('status', 'due'); //Due amount
            $paidQuery = Payment::where('property_id', $propertyId)->where('status', 'paid');       //Paid amount

            //filter by date month or year
            if ($filter === 'month') {
                $dueQuery->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                $paidQuery->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            } elseif ($filter === 'year') {
                $dueQuery->whereYear('created_at', $now->year);
                $paidQuery->whereYear('created_at', $now->year);
            }

            //sum of due and paid || Calculate percentages
            $totalDue            = $dueQuery->sum('amount');
            $totalCollected      = $paidQuery->sum('total_amount');
            $totalSum            = $totalDue + $totalCollected; // Calculate percentages
            $duePercentage       = $totalSum > 0 ? ($totalDue / $totalSum) * 100 : 0;
            $collectedPercentage = $totalSum > 0 ? ($totalCollected / $totalSum) * 100 : 0;

            //response
            $data = [
                'total_due'                  => (int) $totalDue,
                'total_due_percentage'       => round($duePercentage, 2),
                'total_collected'            => (int) $totalCollected,
                'total_collected_percentage' => round($collectedPercentage, 2),
                'filter'                     => (string) $filter,
            ];
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
