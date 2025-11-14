<?php

namespace App\Http\Controllers\Api\PropertyResident;

use App\Helpers\Helper;
use App\Services\Api\PropertyResident\PropertyResidentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyResidentController
{
    protected $service;

    /**
     * PropertyResidentService call =>
     */
    public function __construct(PropertyResidentService $service)
    {
        $this->service = $service;
    }

    /**
     * Property service function call =>
     * Resident service function call =>
     * createFinancialAccount service  function call =>
     * Store Property and Resident
     * Calls PropertyResidentService methods to create property and optionally resident.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Create createProperty if data is provided
            $propertyResult = $this->service->createProperty($request);
            if (! $propertyResult['success']) {
                DB::rollBack();
                return Helper::jsonResponse(false, $propertyResult['message'], $propertyResult['code'] ?? 422); // Check error.
            }
            $property = $propertyResult['data'];

            // Create FinancialAccount if data is provided
            $FinancialAccount        = null;
            $hasFinancialAccountData = $request->filled(['bank_name']);

            if ($hasFinancialAccountData) {
                $FinancialAccountResult = $this->service->createFinancialAccount($request);
                if (! $FinancialAccountResult['success']) {
                    DB::rollBack();
                    return Helper::jsonResponse(false, $FinancialAccountResult['message'], $FinancialAccountResult['code'] ?? 422); // Check error.
                }
                $FinancialAccount = $FinancialAccountResult['data'];
            }

            // Create Resident if data is provided
            $resident        = null;
            $hasResidentData = $request->filled(['unit_flat_number', 'name', 'phone']);

            if ($hasResidentData) {
                $residentResult = $this->service->createResident($request, $property->id);
                if (! $residentResult['success']) {
                    DB::rollBack();
                    return Helper::jsonResponse(false, $residentResult['message'], $residentResult['code'] ?? 422); // Check error.
                }
                $resident = $residentResult['data'];
            }

            DB::commit(); // Final commit
            return Helper::jsonResponse(true, 'Data created successfully', 201, [
                'property'          => $property,
                'resident'          => $resident,
                'financial_account' => $FinancialAccount,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Helper::jsonResponse(false, 'Data created failed: ' . $e->getMessage(), 500);
        }
    }
}
