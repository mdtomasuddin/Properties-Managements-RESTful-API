<?php

namespace App\Http\Controllers\Api\V1\Resident\Payment;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\PaymentPaymentRequest;
use App\Models\PaymentRequest;
use App\Models\Property;
use App\Notifications\PaymentSendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController
{
    /**
     * Store a newly created resource in storage.
     * payment all due  and paymentrequest status update.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id'    => 'required|exists:properties,id',
            'payment_method' => 'required|in:cash,online',
            'account_name'   => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'bank_name'      => 'nullable|string|max:255',
            'branch_name'    => 'nullable|string|max:255',
            'swift_code'     => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, $validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            //Fetch Due Requests
            $paymentRequests = PaymentRequest::where('resident_id', Auth::id())
                ->where('status', 'due')->where('property_id', $request->property_id)->get();

            if ($paymentRequests->isEmpty()) {
                return Helper::jsonResponse(false, 'No pending payment requests found.', 404);
            }

            //Create Master Payment
            $payment = Payment::create([
                'user_id'        => Auth::id(),
                'property_id'    => $request->property_id,
                'total_amount'   => $paymentRequests->sum('amount'),
                'payment_date'   => now(),
                'payment_method' => $request->payment_method,
                'account_name'   => $request->account_name,
                'account_number' => $request->account_number,
                'bank_name'      => $request->bank_name,
                'branch_name'    => $request->branch_name,
                'swift_code'     => $request->swift_code,
            ]);

            //Prepare Pivot Data (Efficient Bulk Insert)
            $pivotData = $paymentRequests->map(fn($req) => [
                'payment_id'         => $payment->id,
                'payment_request_id' => $req->id,
                'created_at'         => now(),
                'updated_at'         => now(),
            ])->toArray();

            PaymentPaymentRequest::insert($pivotData);
            // Update Statuses in one query
            PaymentRequest::whereIn('id', $paymentRequests->pluck('id'))->update(['status' => 'paid']);
            DB::commit();

            // push notification starting------------------>
            $property = Property::with('user')->find($request->property_id);
            if ($property && $property->user) {
                $property->user->notify(new PaymentSendNotification($payment));
            }
            $payment->unsetRelations();
            // push notification ending------------------>

            return Helper::jsonResponse(true, 'Payment processed successfully.', 201, $payment);
        } catch (\Exception $e) {
            DB::rollBack();
            return Helper::jsonResponse(false, 'Payment failed: ' . $e->getMessage(), 500);
        }
    }
}
