<?php

namespace App\Http\Controllers\Api\Emergency;

use App\Helpers\Helper;
use App\Models\Emergency;
use App\Models\Property;
use App\Models\User;
use App\Notifications\EmergencyNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmergencyController
{

    /**
     * This method retrieves Emergency.
     * The number of Emergency `per_page` query parameter defult ===100.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 100);
            $search     = $request->query('search');
            $propertyId = $request->query('property_id');
            $categoryId = $request->query('category_id');

            $query = Emergency::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id());
            //searching
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('location', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
                });
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

            return Helper::jsonResponse(true, 'Emergency retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Emergency.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new Emergency
     * This method validates the handles Emergency , and creates a new Emergency.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'property_id'    => 'required|integer|exists:properties,id',
                'category_id'    => 'required|integer|exists:categories,id',
                'user_type'      => 'nullable|in:property_owner,resident',
                'emergency_type' => 'nullable|string|max:500',
                'location'       => 'required|string|max:500',
                'description'    => 'required|string|max:1500',
                'status'         => 'nullable|in:active,inactive,published,draft,cancelled',
            ]);

            // Return the first validation
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            // Get validated data
            $validated = $validator->validated();

            $validated['user_id'] = Auth::id();
            $emergency            = Emergency::create($validated);

            //push notification starting here==============================
            $this->sendEmergencyNotification($emergency);
            $emergency->unsetRelations();
            //push notification ending here==============================

            return Helper::jsonResponse(true, 'Emergency created successfully.', 201, $emergency);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Emergency creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extracted Notification Logic for Emergencies.
     * @param mixed $emergency || send notification | alert all property involved users
     */
    private function sendEmergencyNotification($emergency)
    {
        $property = Property::with('user')->find($emergency->property_id);
        if ($property && $property->user) {
            $owner           = $property->user;
            $ownerRefferCode = $owner->reffer_code;
            //get all property involved users
            if ($ownerRefferCode) {
                $recipients = User::where('invitation_code', $ownerRefferCode)->get();
                $recipients->push($owner);
                $finalRecipients = $recipients; //all users
                // $finalRecipients = $recipients->unique('id'); //only unique users
                if ($finalRecipients->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($finalRecipients, new EmergencyNotification($emergency));
                }
            }
        }
    }

    /**
     * This method retrieves Emergency show.
     * @param int $id
     */
    public function show($id)
    {
        try {
            $data = Emergency::with(['property:id,property_name', 'category:id,name'])->find($id);
            return Helper::jsonResponse(true, 'Emergency retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Emergency.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
