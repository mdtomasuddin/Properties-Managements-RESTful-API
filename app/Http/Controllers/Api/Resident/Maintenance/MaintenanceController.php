<?php

namespace App\Http\Controllers\Api\Resident\Maintenance;

use App\Helpers\Helper;
use App\Models\Maintenance;
use App\Models\Property;
use App\Models\User;
use App\Notifications\MaintenanceNotification;
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
     * Store a new Maintenance with multiple files.
     * This method validates the handles Maintenance uploads, and creates a new Maintenance.
     * @param Request $request || store
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'property_id'   => 'required|integer|exists:properties,id',
                'category_id'   => 'required|integer|exists:categories,id',
                'description'   => 'required|string|max:1500',
                'attachments'   => 'required|array',
                'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 422);
            }

            $validated = $validator->validated();
            $files     = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $file) {
                    $files[] = Helper::fileUpload($file, 'MaintenanceDocFile', $file->getClientOriginalName());
                }
            }
            $validated['attachments'] = $files;
            $validated['user_id']     = Auth::id();
            $maintenance              = Maintenance::create($validated); //created maintenance

            // push notification starting------------------>
            $property = Property::with('user')->find($maintenance->property_id);
            if ($property && $property->user) {
                $property->user->notify(new MaintenanceNotification($maintenance));
            }
            // push notification ended <------------------

            return Helper::jsonResponse(true, 'Maintenance created, saved to DB and push sent.', 201, [
                'id'          => $maintenance->id,
                'category_id' => $maintenance->category_id,
                'property_id' => $maintenance->property_id,
                'user_id'     => $maintenance->user_id,
                'description' => $maintenance->description,
                'attachments' => $maintenance->attachments,
                'status'      => $maintenance->status,
                'created_at'  => $maintenance->created_at,
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Retrieves a Maintenance by its ID.
     * This method retrieves a Maintenance by its ID and returns the information.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show(Request $request, $id)
    {
        try {
            $data = Maintenance::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id())->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Maintenance not found.', 404);
            }
            return Helper::jsonResponse(true, 'Maintenance retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Maintenance.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Deletes a Maintenance by its ID.
     * This method deletes a Maintenance by its ID and returns a JSON response.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $data = Maintenance::where('user_id', Auth::id())->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Maintenance not found.', 404);
            }
            $data->delete();
            return Helper::jsonResponse(true, 'Maintenance deleted successfully.', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to delete Maintenance.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
