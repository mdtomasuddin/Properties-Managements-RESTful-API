<?php

namespace App\Http\Controllers\Api\V1\Resident\Payment;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\PaymentPaymentRequest;
use App\Models\PaymentRequest;
use App\Models\Property;
use App\Notifications\PaymentSendNotification;
use Exception;
use function Symfony\Component\String\s;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController
{
    /**
     * This method retrieves Payment.
     * The number of Payment `per_page` query parameter defult === 50.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 50);
            $status  = $request->query('status');
            // query to get data
            $query = Payment::with([
                'property:id,property_name,user_id',
                'property.user:id,name,email,phone',
                'user:id,name,email,phone',
                'payment_payment_requests.payment_request' => function ($query) {
                    $query->select('id', 'category_id', 'amount');
                },
                'payment_payment_requests.payment_request.category:id,name',
            ])->where('user_id', Auth::id());

            // Filter by status
            if (! empty($status)) {
                $query->where('status', $status);
            }
            $data = $query->latest()->paginate($perPage);
            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * This method creates a new Payment.
     * The Payment is created with the PaymentRequests that are due for the Resident.
     * The Payment is then linked to the PaymentRequests.
     * A notification is then sent to the Property Owner.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
            'status'         => 'nullable|in:paid,unpaid,pending,due,overdue,cancelled,refunded',
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
                'invoice_number' => 'INV-' . date('Y') . '-' . date('m') . '-' . date('d') . '-' . str_pad(Payment::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT),
                'payment_date'   => now(),
                'payment_method' => $request->payment_method,
                'account_name'   => $request->account_name,
                'account_number' => $request->account_number,
                'bank_name'      => $request->bank_name,
                'branch_name'    => $request->branch_name,
                'swift_code'     => $request->swift_code,
                'status'         => $request->status ?? 'pending',
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

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     * Property payment requests list only Authenticated Resident.
     */
    public function show($id)
    {
        try {
            $payment = Payment::with([
                'property:id,property_name,user_id',
                'property.user:id,name,email,phone',
                'user:id,name,email,phone',
                'payment_payment_requests.payment_request' => function ($query) {
                    $query->select('id', 'category_id', 'amount');
                },
                'payment_payment_requests.payment_request.category:id,name',
            ])->where('user_id', Auth::id())->find($id);

            if (! $payment) {
                return Helper::jsonResponse(false, 'Payment record not found.', 404);
            }

            //Build Response
            $data = [
                'invoice'           => [
                    'invoice_number' => (string) $payment->invoice_number,
                    'issue_date'     => $payment->created_at->format('Y-m-d'),
                    'billing_period' => $payment->created_at->format('F Y'),
                    'status'         => (string) $payment->status ?? 'N/A',
                ],
                'resident'          => [
                    'name'  => (string) $payment->user->name ?? 'N/A',
                    'email' => (string) $payment->user->email ?? 'N/A',
                    'phone' => (string) $payment->user->phone ?? 'N/A',
                ],
                'property'          => [
                    'property_name' => (string) $payment->property->property_name ?? 'N/A',
                    'name'          => (string) $payment->property->user->name ?? 'N/A',
                    'phone'         => (string) $payment->property->user->phone ?? 'N/A',
                ],
                'payment_breakdown' => $payment->payment_payment_requests->map(function ($pivot) {
                    return [
                        'category' => (string) $pivot->payment_request->category->name ?? 'General',
                        'amount'   => (int) $pivot->payment_request->amount,
                    ];
                }),
                'total_amount'      => (int) $payment->total_amount,
                'payment_detail'    => [
                    'payment_method' => (string) $payment->payment_method,
                    'payment_date'   => $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'N/A',
                ],
            ];

            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
