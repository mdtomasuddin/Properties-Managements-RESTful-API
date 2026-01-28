<?php

namespace App\Http\Controllers\Api\V1\Property\Rule;

use App\Helpers\Helper;
use App\Models\RuleRegulation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

            $query = RuleRegulation::where('user_id', Auth::id());
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

    /**
     * Store a new Rules & Regulations
     * @param Request $request || store
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'title'       => 'required|string|max:255',
                'description' => 'required|string|max:2000',
            ]);
            // Validation fails
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $validated            = $validator->validated();
            $validated['user_id'] = Auth::id();
            $data                 = RuleRegulation::create($validated);

            return Helper::jsonResponse(true, 'Rules & Regulations created successfully.', 201, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Rules & Regulations creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified Rules & Regulations.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $data = RuleRegulation::where('user_id', Auth::id())->findOrFail($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Rules & Regulations not found.', 404);
            }

            return Helper::jsonResponse(true, 'Rules & Regulations retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Rules & Regulations.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified Rules & Regulations.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id' => 'nullable|exists:properties,id',
                'title'       => 'nullable|string|max:255',
                'description' => 'nullable|string|max:2000',
            ]);
            // Validation fails
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }
            // Get the existing RuleRegulation
            $data = RuleRegulation::where('user_id', Auth::id())->findOrFail($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Rules & Regulations not found.', 404);
            }
            // Update with validated data
            $validated            = $validator->validated();
            $validated['user_id'] = Auth::id();
            $data->update($validated);
            return Helper::jsonResponse(true, 'Rules & Regulations updated successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Rules & Regulations update failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified Rules & Regulations.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = RuleRegulation::where('user_id', Auth::id())->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Rules & Regulations not found.', 404);
            }
            $data->delete();
            return Helper::jsonResponse(true, 'Rules & Regulations deleted successfully.', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Rules & Regulations deletion failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
