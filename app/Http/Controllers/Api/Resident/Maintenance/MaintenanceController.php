<?php

namespace App\Http\Controllers\Api\Resident\Maintenance;

use App\Helpers\Helper;
use App\Models\Maintenance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MaintenanceController
{
    /**
     * This method retrieves Maintenance.
     * The number of Maintenance `per_page` query parameter defult ===100.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 100);
            $search     = $request->query('search');
            $propertyId = $request->query('property_id');
            $categoryId = $request->query('category_id');

            $query = Maintenance::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id());

            //searching
            if (! empty($search)) {
                $query->where('description', 'like', '%' . $search . '%');
            }
            //category wish
            if (! empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }
            //propertyId wish
            if (! empty($propertyId)) {
                $query->where('property_id', $propertyId);
            }

            $data = $query->paginate($perPage);

            return Helper::jsonResponse(true, 'Maintenance retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Maintenance.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new Maintenance with a single file.
     * This method validates the handles Maintenance uploads, and creates a new Maintenance.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'property_id'   => 'required|integer|exists:properties,id',
                'category_id'   => 'required|integer|exists:categories,id',
                'description'   => 'required|string|max:1500',
                'attachments'   => 'required|array',
                'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'status'        => 'nullable|string|in:active,inactive,inprogress,ongoing,completed,resolved,cancelled',
            ]);

            // Return first validation error message
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $validated = $validator->validated();

            // Handle file uploads
            $files = [];
            if ($request->has('attachments')) {
                foreach ($request->attachments as $file) {
                    $files[] = Helper::fileUpload($file, 'MaintenanceDocFile', $file->getClientOriginalName());
                }
            }
            $validated['attachments'] = $files;
            $validated['user_id']     = Auth::id();

            // Create  record
            $maintenance = Maintenance::create($validated);
            return Helper::jsonResponse(true, 'Maintenance created successfully.', 201, $maintenance);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Maintenance creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
