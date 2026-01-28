<?php

namespace App\Http\Controllers\Api\V1\Resident\Rule;

use App\Helpers\Helper;
use App\Models\RuleRegulation;
use Exception;
use Illuminate\Http\Request;

class RulesRegulationController
{
    /**
     * Retrieves a paginated list of Rules & Regulations.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 25);
            $search     = $request->query('search');
            $PropertyId = $request->query('property_id');

            $query = RuleRegulation::where('status', 'active');
            // Apply search filter if provided
            if (! empty($search)) {
                $query->where('description', 'like', '%' . $search . '%');
            }
            if (! empty($PropertyId)) {
                $query->where('property_id', $PropertyId); //filter by property_id
            }
            $data = $query->paginate($perPage);

            return Helper::jsonResponse(true, 'Rules & Regulations retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Rules & Regulations.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
