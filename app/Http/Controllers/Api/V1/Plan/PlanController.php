<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Helpers\Helper;
use App\Models\Plan;
use Exception;

class PlanController
{
    /**
     * All plan
     * @return \Illuminate\Http\Response
     * plan list || all subscription plan
     */
    public function index()
    {
        try {
            $data = Plan::select('id', 'name', 'price', 'offer_description', 'currency', 'description', 'features', 'billing_interval', 'is_recommended')->get();
            if (! $data) {
                return Helper::jsonResponse(false, 'Plan Not Found!', 404);
            }
            return Helper::jsonResponse(true, 'Data retrieved successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * Single plan
     * @param  int  $id
     */
    public function Show($id)
    {
        try {
            $data = Plan::select('id', 'name', 'price', 'offer_description', 'currency', 'description', 'features', 'billing_interval', 'is_recommended')->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Plan Not Found!', 404);
            }
            return Helper::jsonResponse(true, 'Data retrieved successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error: ' . $e->getMessage(), 500);
        }
    }
}
