<?php

namespace App\Http\Controllers\Api\V1\Property\Statement;

use App\Helpers\Helper;
use App\Models\PaymentRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatementController
{

    //statement 
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 100);
            $categoryId = $request->query('category_id');
            $propertyId = $request->query('property_id');
            $status     = $request->query('status');

            $query = PaymentRequest::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id());

            // categoryId
            if (! empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }

            //property
            if (! empty($propertyId)) {
                $query->where('property_id', $propertyId);
            }

            //  status filter
            if (! empty($status)) {
                $query->where('status', $status);
            }

            $data = $query->paginate($perPage);
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Data ', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
