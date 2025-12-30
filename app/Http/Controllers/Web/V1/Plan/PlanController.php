<?php

namespace App\Http\Controllers\Web\V1\Plan;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController
{

    /**
     * Display a listing of the resource (Plans).
     * @return \Illuminate\View\View
     * plans index page || grid view of all plans
     */
    public function index()
    {
        $plans = Plan::all();
        return view('backend.layouts.plan.index', [
            'plans' => $plans,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Plan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Plan $plan)
    {
        $tableName = (new Plan())->getTable();
        $rules     = [
            'name'              => ['nullable', 'string', Rule::unique($tableName, 'name')->ignore($plan->id)],
            'price'             => 'nullable',
            'offer_description' => 'nullable|string|max:500',
            'billing_interval'  => ['nullable', Rule::in(['weekly', 'monthly', 'yearly'])],
            'description'       => 'nullable|string|max:500',
            'features'          => 'nullable|string',
            'is_recommended'    => 'nullable|boolean',
            'status'            => 'nullable',
        ];

        $validated = $request->validate($rules);
        $features  = $plan->features;
        if (! empty($validated['features'])) {
            $features = array_map('trim', explode(',', $validated['features']));
            $features = array_filter($features);
        }

        $data = [
            'name'              => $validated['name'] ?? $plan->name,
            'price'             => $validated['price'] ?? $plan->price,
            'billing_interval'  => $validated['billing_interval'] ?? $plan->billing_interval,
            'status'            => $validated['status'] ?? $plan->status,
            'description'       => $validated['description'] ?? $plan->description,
            'offer_description' => $validated['offer_description'] ?? $plan->offer_description,
            'is_recommended'    => $validated['is_recommended'] ?? $plan->is_recommended,
            'features'          => $features,
        ];
        $plan->update($data);
        return redirect()->route('plan.index')->with('t-success', 'Plan Updated successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     * @param Plan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Plan $plan)
    {
        $newStatus = $plan->status === 'active' ? 'inactive' : 'active';
        $plan->update(['status' => $newStatus]);

        return redirect()->route('plan.index')->with('t-success', 'Status Updated successfully.');
    }
}
