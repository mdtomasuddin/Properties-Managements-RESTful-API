<?php

namespace App\Http\Controllers\Api\PaymentRequest;

use App\Helpers\Helper;
use App\Models\PaymentRequest;
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
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id'          => 'required|exists:properties,id',
                'category_id'          => 'required|exists:categories,id',
                'send_to'              => 'required|in:individual,all_resident',
                'selected_resident_id' => 'nullable|array',
                'amount'               => 'required',
                'attachments'          => 'nullable|array',
                'description'          => 'nullable|string|max:5000',
                'due_date'             => 'nullable|date|after_or_equal:today',
                'status'               => 'nullable|in:draft,sent,partially_paid,paid,cancelled,active,inactive',
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

            $validated['attachments'] = $files;
            $validated['user_id']     = Auth::id();
            $paymentRequest           = PaymentRequest::create($validated);

            // Send Notification START
            $this->sendPaymentRequestNotification($paymentRequest);
            $paymentRequest->unsetRelations();
            // Send Notification END

            return Helper::jsonResponse(true, 'Payment request created successfully.', 201, $paymentRequest);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Payment request creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extracted Notification Logic for PaymentRequest.
     * @param mixed $paymentRequest || send notification || send_to -> all_resident , individual selected_resident_id
     */
    private function sendPaymentRequestNotification($paymentRequest)
    {
        $recipients = collect();
        // Determine recipients based on send_to
        if ($paymentRequest->send_to === 'all_resident') {
            $owner = $paymentRequest->property->user; // Property owner
            if ($owner && $owner->reffer_code) {
                // Get all residents with matching invitation_code
                $residents  = User::where('invitation_code', $owner->reffer_code)->get();
                $recipients = $recipients->merge($residents);
                $recipients->push($owner); // Include the property owner
            }
        } elseif ($paymentRequest->send_to === 'individual' && ! empty($paymentRequest->selected_resident_id)) {
            $recipients = User::whereIn('id', $paymentRequest->selected_resident_id)->get(); //only selected residents
        }
        $finalRecipients = $recipients->unique('id'); // Remove duplicate users
        if ($finalRecipients->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($finalRecipients, new PaymentRequestNotification($paymentRequest));
        }
    }
}
