<?php

namespace App\Http\Controllers\Api\V1\SSLCommerz;

use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SSLCommerzPaymentController
{

    public function PaymentCreate(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
        ]);
        $planId = $request->query('plan_id');
        $user   = Auth::user();

        $plan = Plan::find($planId); //find the plan
        if (! $plan) {
            return response()->json(['status' => false, 'message' => 'Plan not found'], 404);
        }

        $tran_id      = "TRANS_" . uniqid();
        $total_amount = $plan->price; //price of the plan

        //save the transaction in the database
        Transaction::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'tran_id' => $tran_id,
            'amount'  => $total_amount,
            'status'  => 'pending', //pending by default when the transaction is created
        ]);

        $config    = config('sslcommerz');
        $post_data = [
            'store_id'         => $config['apiCredentials']['store_id'],
            'store_passwd'     => $config['apiCredentials']['store_password'],
            'total_amount'     => $total_amount,
            'currency'         => $plan->currency ?? "BDT",
            'tran_id'          => $tran_id,
            'success_url'      => url('/api/payment/success'),
            'fail_url'         => url('/api/payment/fail'),
            'cancel_url'       => url('/api/payment/cancel'),
            'ipn_url'          => url('/api/payment/ipn'),

            //customer information
            'cus_name'         => $user->name ?? 'Customer Name',
            'cus_email'        => $user->email ?? 'example@gmail.com',
            'cus_add1'         => 'Dhaka',
            'cus_city'         => 'Dhaka',
            'cus_postcode'     => '1000',
            'cus_country'      => 'Bangladesh',
            'cus_phone'        => $user->phone ?? '01700000000',
            //shipping information
            'shipping_method'  => 'NO',
            'product_name'     => $plan->name,
            'product_category' => 'Subscription',
            'product_profile'  => 'subscription',
        ];

        $response = Http::asForm()->post($config['apiDomain'] . $config['apiUrl']['make_payment'], $post_data);
        // $result = $response->json();
        $result = $response;

        if (isset($result['status']) && $result['status'] == 'SUCCESS') {
            return response()->json([
                'status'       => true,
                'payment_url'  => $result['GatewayPageURL'],
                'tran_id'      => $tran_id,
                'total_amount' => $total_amount,
            ]);
        }

        return response()->json(['status' => false, 'message' => 'SSLCommerz Error: ' . ($result['failedreason'] ?? 'Unknown error')]);
    }

    /**
     * success payment
     */
    public function PaymentSuccess(Request $request)
    {
        $tran_id = $request->query('tran_id');
        return "<h1>Payment Successful. Transaction ID: $tran_id</h1>";
    }

    /**
     * fail payment
     */

    public function PaymentFail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        Transaction::where('tran_id', $tran_id)->where('status', 'pending')->update(['status' => 'failed']);

        return "<h1>Payment Failed</h1>";
    }

    /**
     * cancel payment
     */
    public function PaymentCancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        Transaction::where('tran_id', $tran_id)->where('status', 'pending')->update(['status' => 'cancelled']);
        return "<h1>Payment Cancelled</h1>";
    }

    /**
     *PaymentIPN 
     */
}
