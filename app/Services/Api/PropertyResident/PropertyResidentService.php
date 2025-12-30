<?php
namespace App\Services\Api\PropertyResident;

use App\Helpers\Helper;
use App\Models\FinancialAccount;
use App\Models\Property;
use App\Models\Resident;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PropertyResidentService
{

    /**
     * Create a new Property.
     * Validates input, uploads optional documents, and saves the property.
     * Returns success status and created property data.
     * createProperty function =>
     */
    public function createProperty($request)
    {
        try {
            // Validation data
            $validator = Validator::make($request->all(), [
                'property_type'      => 'required|in:multi_unit,single_unit',
                'property_name'      => 'required|string|max:500|unique:properties,property_name',
                'property_address'   => 'required|string|max:500',
                'number_of_units'    => 'required|integer|min:1',
                'documents'          => 'nullable|array',
                'documents.*'        => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'security_contact'   => 'nullable|string|max:500',
                'additional_details' => 'nullable|string|max:5000',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            //document uploads
            $uploadedFiles = [];
            if ($request->has('documents')) {
                foreach ($request->documents as $file) {
                    $docName         = time() . '_' . $file->getClientOriginalName();
                    $uploadedFiles[] = Helper::fileUpload($file, 'Propertydocuments', $docName);
                }
            }

            // Prepare data and create property
            $validated    = $validator->validated();
            $propertyData = [
                'user_id'            => Auth::id(),
                'property_type'      => $validated['property_type'] ?? null,
                'property_name'      => $validated['property_name'] ?? null,
                'property_address'   => $validated['property_address'] ?? null,
                'number_of_units'    => $validated['number_of_units'] ?? null,
                'documents'          => $uploadedFiles,
                'security_contact'   => $validated['security_contact'] ?? null,
                'additional_details' => $validated['additional_details'] ?? null,
            ];

            $property = Property::create($propertyData);

            return [
                'success' => true,
                'data'    => $property,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code'    => 422,
            ];
        }
    }

    /**
     * Create a new Resident for a given Property.
     * Validates input, uploads optional files, and saves the resident.
     * Associates the resident with the specified property ID.
     * Returns success status and created resident data.
     * createResident=>
     */
    public function createResident($request, $propertyId)
    {
        try {
            // Validation data
            $validator = Validator::make($request->all(), [
                'unit_flat_number'             => 'required|max:100',
                'name'                         => 'required|string|max:255',
                'phone'                        => 'required|max:20',
                'address'                      => 'nullable|string|max:500',
                'payment_frequency'            => 'required|in:monthly,quarterly,yearly',
                'auto_rent_reminder'           => 'boolean',
                'monthly_rent'                 => 'required|numeric|min:1|max:5000000',
                'fixed_monthly_recurring_cost' => 'nullable|numeric|min:0|max:5000000',
                'due_amount'                   => 'required|numeric|min:0|max:5000000',
                'advance_paid_amount'          => 'required|numeric|min:0|max:5000000',
                'start_date'                   => 'required|date',
                'end_date'                     => 'required|date|after_or_equal:start_date',
                'agreement_file'               => 'nullable|array',
                'agreement_file.*'             => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'tenant_file'                  => 'nullable|array',
                'tenant_file.*'                => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'sms_invitation'               => 'nullable|string|max:5000',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            // Handle Agreement File
            $agreement_files = [];
            if ($request->hasFile('agreement_file')) {
                foreach ($request->file('agreement_file') as $file) {
                    $fileName          = time() . '_' . $file->getClientOriginalName();
                    $agreement_files[] = Helper::fileUpload($file, 'agreement_file', $fileName);
                }
            }

            // Handle Tenant File
            $tenant_files = [];
            if ($request->hasFile('tenant_file')) {
                foreach ($request->file('tenant_file') as $file) {
                    $fileName       = time() . '_' . $file->getClientOriginalName();
                    $tenant_files[] = Helper::fileUpload($file, 'tenant_file', $fileName);
                }
            }

            // Prepare Resident Data
            $validated    = $validator->validated();
            $residentData = [
                'user_id'                      => Auth::id(),
                'property_id'                  => $propertyId, // Use the passed property ID
                'unit_flat_number'             => $validated['unit_flat_number'],
                'name'                         => $validated['name'] ?? null,
                'phone'                        => $validated['phone'] ?? null,
                'address'                      => $validated['address'] ?? null,
                'payment_frequency'            => $validated['payment_frequency'] ?? null,
                'auto_rent_reminder'           => $validated['auto_rent_reminder'] ?? false,
                'monthly_rent'                 => $validated['monthly_rent'] ?? 0,
                'fixed_monthly_recurring_cost' => $validated['fixed_monthly_recurring_cost'] ?? 0,
                'due_amount'                   => $validated['due_amount'],
                'advance_paid_amount'          => $validated['advance_paid_amount'] ?? 0,
                'start_date'                   => $validated['start_date'],
                'end_date'                     => $validated['end_date'],
                'agreement_file'               => $agreement_files,
                'tenant_file'                  => $tenant_files,
                'sms_invitation'               => $validated['sms_invitation'] ?? null,
            ];

            $resident = Resident::create($residentData);

            /**
             *Create corresponding User for Resident
             *Check if user already exists with the same phone number
             */
            $existingUser = User::where('phone', $validated['phone'])->first();
            if ($existingUser) {
                return Helper::jsonResponse(false, 'Phone number already exists.', 422);
            }
            User::create([
                'name'            => $validated['name'] ?? null,
                'phone'           => $validated['phone'] ?? null,
                'address'         => $validated['address'] ?? null,
                'role'            => 'resident',
                'invitation_code' => Auth::user()->reffer_code,
            ]);
            //phone number a invitation SMS logic can be added here if needed .

            return [
                'success' => true,
                'data'    => $resident,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code'    => 422,
            ];
        }
    }

    /**
     * Create a new FinancialAccount.
     * Validates request data, assigns defaults, and stores the account.
     * Returns success status and created FinancialAccount data.
     * createFinancialAccount call =>
     */
    public function createFinancialAccount($request)
    {
        try {
            // Validation data
            $validator = Validator::make($request->all(), [
                'property_id'         => 'nullable|exists:properties,id',
                'bank_name'           => 'required|string|max:200',
                'branch'              => 'required|string|max:255',
                'account_number'      => 'required|string|max:50',
                'account_holder_name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            //FinancialAccount  Data
            $validated            = $validator->validated();
            $FinancialAccountData = [
                'user_id'             => Auth::id(),
                'bank_name'           => $validated['bank_name'] ?? null,
                'branch'              => $validated['branch'] ?? null,
                'account_number'      => $validated['account_number'] ?? null,
                'account_holder_name' => $validated['account_holder_name'] ?? null,
            ];

            $FinancialAccount = FinancialAccount::create($FinancialAccountData);

            return [
                'success' => true,
                'data'    => $FinancialAccount,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code'    => 422,
            ];
        }
    }
}
