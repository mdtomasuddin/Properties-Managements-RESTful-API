<?php

namespace App\Http\Controllers\Api\V1\Property\PaymentReminder;

use App\Helpers\Helper;
use App\Models\PaymentReminder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentReminderController
{

    /**
     * Store a new PaymentReminder with a single file.
     * This method validates the handles Payment Request uploads, and creates a new PaymentReminder.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id'                => 'required|exists:properties,id',
                'resident_id' => 'nullable|exists:residents,id',
                'auto_reminders'             => 'sometimes|boolean',
                'start_reminder_days_before' => 'required_if:auto_reminders,true|integer|min:1|max:31',
                'manual_reminder_date'       => 'nullable|date|after_or_equal:today',
                'reminder_note'              => 'nullable|string|max:500',
                'late_payment_fines'         => 'sometimes|boolean',
                'late_payment_fines_amount'  => 'nullable|min:0',
                'first_reminder_active'      => 'sometimes|boolean',
                'due_date_reminder_active'   => 'sometimes|boolean',
                'overdue_notice_active'      => 'sometimes|boolean',
                'status'                     => 'nullable|in:active,inactive,partially_paid,paid,cancelled',
            ]);
            // return the first error
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $validated            = $validator->validated();
            $validated['user_id'] = Auth::id();
            $PaymentReminder      = PaymentReminder::create($validated);

            return Helper::jsonResponse(true, 'Payment reminder created successfully.', 201, $PaymentReminder);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Payment reminder creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
