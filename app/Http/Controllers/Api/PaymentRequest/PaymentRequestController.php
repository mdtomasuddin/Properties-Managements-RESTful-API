<?php

namespace App\Http\Controllers\Api\PaymentRequest;

use App\Helpers\Helper;
use App\Models\PaymentRequest;
use App\Models\Property;
use App\Models\User;
use App\Notifications\PaymentRequestNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentRequestController
{

    /**
     * This method retrieves PaymentRequest.
     * The number of PaymentRequest `per_page` query parameter defult ===100.
     * @param Request $request
     */
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
            return Helper::jsonResponse(true, 'Payment Request retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Payment Request.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new PaymentRequest with a single file.
     * This method validates the handles Payment Request uploads, and creates a new PaymentRequest.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'property_id'            => 'required|exists:properties,id',
                'category_id'            => 'required|exists:categories,id',
                'send_to'                => 'required|in:individual,all_resident',
                'selected_resident_id'   => 'required_if:send_to,individual|array',
                'selected_resident_id.*' => 'exists:users,id',
                'amount'                 => 'required|numeric|min:0.01',
                'attachments'            => 'nullable|array',
                'attachments.*'          => 'file|mimes:png,jpg,jpeg,gif,pdf|max:10240',
                'description'            => 'nullable|string|max:5000',
                'due_date'               => 'nullable|date|after_or_equal:today',
                'status'                 => 'nullable|string|in:paid,due,cancelled,draft,sent,partially_paid',
            ]);

            // return the first error
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }
            $validated = $validator->validated();

            // Handle file uploads if
            $files = [];
            if ($request->has('attachments')) {
                foreach ($request->attachments as $file) {
                    $files[] = Helper::fileUpload($file, 'PaymentRequestDocFile', $file->getClientOriginalName());
                }
            }

            //Identify Target Residents
            $targetUserIds = [];
            if ($validated['send_to'] === 'all_resident') {
                $property      = Property::with('user')->findOrFail($validated['property_id']);
                $ownerId       = $property->user->reffer_code;
                $targetUserIds = User::where('invitation_code', $ownerId)->pluck('id')->toArray();
            } else {
                $targetUserIds = $validated['selected_resident_id'];
            }

            //create payment request
            $createdRequests = [];
            foreach ($targetUserIds as $userId) {
                $paymentRequest = PaymentRequest::create([
                    'user_id'     => Auth::id(),
                    'property_id' => $validated['property_id'],
                    'category_id' => $validated['category_id'],
                    'resident_id' => $userId,
                    'amount'      => $validated['amount'],
                    'send_to'     => $validated['send_to'],
                    'description' => $validated['description'] ?? null,
                    'due_date'    => $validated['due_date'] ?? null,
                    'status'      => $validated['status'] ?? 'due',
                    'attachments' => $files,
                ]);
                $this->sendPaymentRequestNotification($paymentRequest);
                $createdRequests[] = $paymentRequest;
                $paymentRequest->unsetRelations();
            }
            return Helper::jsonResponse(true, 'Payment requests created successfully.', 201, $createdRequests);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Creation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Extracted Notification Logic for PaymentRequest.
     * @param mixed $paymentRequest || send notification || send_to -> all_resident , individual selected_resident_id
     */
    private function sendPaymentRequestNotification($paymentRequest)
    {
        $resident = User::find($paymentRequest->resident_id);
        if ($resident) {
            $resident->notify(new PaymentRequestNotification($paymentRequest));
        }
    }
}
