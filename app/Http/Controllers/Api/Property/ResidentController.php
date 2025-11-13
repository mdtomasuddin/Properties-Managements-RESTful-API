<?php

namespace App\Http\Controllers\Api\Property;

use App\Helpers\Helper;
use App\Models\Resident;
use Exception;
// use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResidentController
{

    /**
     * Retrieve a paginated list of Resident.
     * This method retrieves Resident.
     * The number of Resident `per_page` query parameter defult ===100.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 100);
            $data    = Resident::where('user_id', Auth::user()->id)->paginate($perPage); //get

            return Helper::jsonResponse(true, 'Resident retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Resident.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new Resident and associated data.
     * This method validates the handles document uploads, and creates a new Resident.
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'property_id'                  => 'required|exists:properties,id',
                'unit_flat_number'             => 'nullable|max:50',
                'name'                         => 'required|string|max:255',
                'phone'                        => 'nullable|max:20',
                'address'                      => 'nullable|string|max:500',
                'payment_frequency'            => 'nullable|in:monthly,quarterly,yearly',
                'auto_rent_reminder'           => 'boolean',
                'monthly_rent'                 => 'nullable|numeric|min:1|max:5000000',
                'fixed_monthly_recurring_cost' => 'nullable|numeric|min:1|max:5000000',
                'due_amount'                   => 'nullable|numeric|min:1|max:5000000',
                'advance_paid_amount'          => 'nullable|numeric|min:1|max:5000000',
                'start_date'                   => 'nullable|date',
                'end_date'                     => 'nullable|date|after_or_equal:start_date',
                'agreement_file.*'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'tenant_file.*'                => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'sms_invitation'               => 'nullable|string|max:5000',
            ]);

            //first error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            // Handle agreement_file
            $agreement_files = [];
            if ($request->hasFile('agreement_file')) {
                foreach ($request->file('agreement_file') as $file) {
                    $fileName          = time() . '_' . $file->getClientOriginalName();
                    $agreement_files[] = Helper::fileUpload($file, 'agreement_file', $fileName);
                }
            }

            // Handle tenant_file
            $tenant_files = [];
            if ($request->hasFile('tenant_file')) {
                foreach ($request->file('tenant_file') as $file) {
                    $fileName       = time() . '_' . $file->getClientOriginalName();
                    $tenant_files[] = Helper::fileUpload($file, 'tenant_file', $fileName);
                }
            }

            //validated data
            $validated                   = $validator->validated();
            $validated['agreement_file'] = $agreement_files;
            $validated['tenant_file']    = $tenant_files;
            $validated['user_id']        = Auth::id();

            // Create Resident
            $resident = Resident::create($validated);

            return Helper::jsonResponse(true, 'Resident created successfully.', 201, $resident);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Resident creation failed', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display details of a specific resident.
     * This method retrieves the details of a resident by its ID and returns the information.
     * @param int $id
     */
    public function show($id)
    {
        try {
            $data = Resident::where('user_id', Auth::user()->id)->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Resident Not Found.', 404);
            }
            return Helper::jsonResponse(true, 'Resident details retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Resident details', 404, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
