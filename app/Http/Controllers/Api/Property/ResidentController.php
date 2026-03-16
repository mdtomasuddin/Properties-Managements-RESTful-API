<?php

namespace App\Http\Controllers\Api\Property;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResidentController
{
    /**
     * Display a listing of the resource.
     * The number of Resident `per_page` query parameter defult ===50.
     */
    public function index(Request $request)
    {
        try {
            $propertyId = $request->query('property_id');
            $userId = Auth::id();
            $perPage = $request->query('per_page', 50);
            $search = $request->query('search');

            $query = Resident::with('resientUser:id,name,phone,email,avatar,address')
                ->select(['id', 'property_id', 'phone', 'unit_flat_number', 'monthly_rent', 'payment_frequency'])
                ->where('property_id', $propertyId)->where('user_id', $userId);

            // search  name or phone
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            $data = $query->paginate($perPage);

            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data, true);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new Resident and associated data.
     * This method validates the handles document uploads, and creates a new Resident.
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'unit_flat_number' => 'required|max:100',
                'name' => 'required|string|max:255',
                'phone' => 'required|max:20',
                'address' => 'nullable|string|max:500',
                'payment_frequency' => 'required|in:monthly,quarterly,yearly',
                'payment_deadline' => 'nullable|date|after_or_equal:start_date',
                'auto_rent_reminder' => 'boolean',
                'monthly_rent' => 'required|numeric|max:5000000',
                'fixed_monthly_recurring_cost' => 'nullable|numeric|max:5000000',
                'due_amount' => 'required|numeric|max:5000000',
                'advance_paid_amount' => 'required|numeric|max:5000000',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'agreement_file.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'tenant_file.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'sms_invitation' => 'nullable|string|max:5000',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 422);
            }

            $validated = $validator->validated();

            // Check if user already exists with the same phone number
            if (User::where('phone', $validated['phone'])->exists()) {
                return Helper::jsonResponse(false, 'Phone number already exists in user records.', 422);
            }

            return DB::transaction(function () use ($request, $validated) {
                // Handle agreement_file
                $agreement_files = [];
                if ($request->hasFile('agreement_file')) {
                    foreach ($request->file('agreement_file') as $file) {
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $agreement_files[] = Helper::fileUpload($file, 'agreement_file', $fileName);
                    }
                }

                // Handle tenant_file
                $tenant_files = [];
                if ($request->hasFile('tenant_file')) {
                    foreach ($request->file('tenant_file') as $file) {
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $tenant_files[] = Helper::fileUpload($file, 'tenant_file', $fileName);
                    }
                }

                $validated['agreement_file'] = $agreement_files;
                $validated['tenant_file'] = $tenant_files;
                $validated['user_id'] = Auth::id();

                // Create Resident
                $resident = Resident::create($validated);

                // Create User automatically for resident role
                User::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                    'role' => 'resident',
                    'invitation_code' => Auth::user()->reffer_code,
                ]);

                return Helper::jsonResponse(true, 'Resident created successfully.', 201, $resident);
            });
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Resident creation failed', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display details of a specific resident.
     * This method retrieves the details of a resident by its ID and returns the information.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        try {
            // Fatch resident user data
            $data = Resident::with('resientUser:id,name,phone,email,avatar,address')->where('status', 'active')->find($id);

            if (! $data) {
                return Helper::jsonResponse(false, 'Resident Not Found', 404);
            }

            return Helper::jsonResponse(true, 'Data retrieved successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing Resident
     * This method validates the incoming update request,
     * handles document uploads (if Resident are provided), and updates
     *
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate
            $validator = Validator::make($request->all(), [
                'property_id' => 'nullable|exists:properties,id',
                'unit_flat_number' => 'nullable|max:100',
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|max:20',
                'address' => 'nullable|string|max:500',
                'payment_frequency' => 'nullable|in:monthly,quarterly,yearly',
                'payment_deadline' => 'nullable|date|after_or_equal:start_date',
                'auto_rent_reminder' => 'nullable|boolean',
                'monthly_rent' => 'nullable|numeric|max:5000000',
                'fixed_monthly_recurring_cost' => 'nullable|numeric|max:5000000',
                'due_amount' => 'nullable|numeric|max:5000000',
                'advance_paid_amount' => 'nullable|numeric|max:5000000',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'agreement_file.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'tenant_file.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'sms_invitation' => 'nullable|string|max:5000',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 422);
            }

            $resident = Resident::where('user_id', Auth::id())->find($id);
            if (! $resident) {
                return Helper::jsonResponse(false, 'Resident not found', 404);
            }

            $validated = $validator->validated();

            // Check if new phone number is already taken by another user
            if ($request->has('phone') && $request->phone != $resident->phone) {
                if (User::where('phone', $request->phone)->exists()) {
                    return Helper::jsonResponse(false, 'The new phone number is already taken by another account.', 422);
                }
            }

            return DB::transaction(function () use ($request, $resident, $validated) {
                $oldPhone = $resident->phone;

                // Handle agreement_file upload
                if ($request->hasFile('agreement_file')) {
                    if ($resident->agreement_file && is_array($resident->agreement_file)) {
                        foreach ($resident->agreement_file as $agreement_Path) {
                            $Path = ltrim(parse_url($agreement_Path, PHP_URL_PATH), '/');
                            Helper::fileDelete($Path); // Delete old files
                        }
                    }
                    // Upload new files
                    $agreement_files = [];
                    foreach ($request->file('agreement_file') as $file) {
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $agreement_files[] = Helper::fileUpload($file, 'agreement_file', $fileName);
                    }
                    $validated['agreement_file'] = $agreement_files;
                }

                // Handle tenant_file upload
                if ($request->hasFile('tenant_file')) {
                    if ($resident->tenant_file && is_array($resident->tenant_file)) {
                        foreach ($resident->tenant_file as $tenant_Path) {
                            $Path = ltrim(parse_url($tenant_Path, PHP_URL_PATH), '/');
                            Helper::fileDelete($Path); // Delete old files
                        }
                    }
                    // Upload new files
                    $tenant_files = [];
                    foreach ($request->file('tenant_file') as $file) {
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $tenant_files[] = Helper::fileUpload($file, 'tenant_file', $fileName);
                    }
                    $validated['tenant_file'] = $tenant_files;
                }

                $resident->update($validated);

                // Update User table information for the resident
                $user = User::where('phone', $oldPhone)->first();
                if ($user) {
                    $user->name = $request->name;
                    $user->phone = $request->phone;
                    $user->address = $request->address;
                    $user->save();
                }

                return Helper::jsonResponse(true, 'Resident updated successfully.', 200, $resident);
            });
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Resident update failed', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /***
     * Delete a specific Resident and its associated documents.
     * This method deletes the specified Resident ,
     * @param int $id
     */
    public function destroy($id)
    {
        try {

            $resident = Resident::where('user_id', Auth::user()->id)->find($id);
            if (! $resident) {
                return Helper::jsonResponse(false, 'Rsident not found.', 404);
            }

            // Delete exiting agreement_file
            if (! empty($resident->agreement_file) && is_array($resident->agreement_file)) {
                foreach ($resident->agreement_file as $agreement_Path) {
                    $path = ltrim(parse_url($agreement_Path, PHP_URL_PATH), '/');
                    Helper::fileDelete($path);
                }
            }
            // Delete exiting tenant_file
            if (! empty($resident->tenant_file) && is_array($resident->tenant_file)) {
                foreach ($resident->tenant_file as $tenant_Path) {
                    $path = ltrim(parse_url($tenant_Path, PHP_URL_PATH), '/');
                    Helper::fileDelete($path);
                }
            }
            // delete
            $resident->delete();

            return Helper::jsonResponse(true, 'Resident deleted successfully.', 200);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Resident deletion failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Retrieves the payment details for a specific Resident.
     * This method retrieves the details of a Resident by its ID, including the payment history.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function resientPaymentDetails($id)
    {
        try {
            // Fatch resident user data
            $data = Resident::with('resientUser:id,name,phone,email,avatar,address')
                ->select(['id', 'property_id', 'phone', 'unit_flat_number', 'monthly_rent', 'payment_frequency', 'tenant_file', 'agreement_file'])->find($id);

            if (! $data) {
                return Helper::jsonResponse(false, 'Resident Not Found', 404);
            }
            // get payment history
            $paymentHistory = Payment::select('id', 'user_id', 'invoice_number', 'total_amount', 'payment_date', 'status')->where('user_id', $data->resientUser->id ?? null)->latest()->get();

            // build response
            $data = [
                'resident' => $data,
                'payment_history' => $paymentHistory,
            ];

            return Helper::jsonResponse(true, 'Data retrieved successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve data', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
