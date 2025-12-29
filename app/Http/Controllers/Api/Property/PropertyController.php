<?php
namespace App\Http\Controllers\Api\Property;

use App\Helpers\Helper;
use App\Models\Property;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PropertyController
{

    /**
     * Retrieve a paginated list of properties.
     * This method retrieves properties.
     * The number of properties `per_page` query parameter defult ===100.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 100);
            $data    = Property::where('user_id', Auth::user()->id)->paginate($perPage); //get

            return Helper::jsonResponse(true, 'Property retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Property.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new property and associated data.
     * This method validates the handles document uploads, and creates a new property.
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request
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
            //first error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            //document uploads
            $uploadedFiles = [];
            if ($request->has('documents')) {
                foreach ($request->documents as $file) {
                    $docName         = time() . '_' . $file->getClientOriginalName();
                    $uploadedFiles[] = Helper::fileUpload($file, 'Propertydocuments', $docName);
                }
            }

            //validated data
            $validated              = $validator->validated();
            $validated['documents'] = $uploadedFiles;
            $validated['user_id']   = Auth::user()->id;

            // Create property
            $data = Property::create($validated);

            return Helper::jsonResponse(true, 'Property created successfully.', 201, $data);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Property creation failed', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display details of a specific property.
     * This method retrieves the details of a property by its ID and returns the information.
     * @param int $id
     */
    public function show($id)
    {
        try {
            $data = Property::where('user_id', Auth::user()->id)->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Property Not Found.', 404);
            }
            return Helper::jsonResponse(true, 'Property details retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Property details', 404, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing property.
     * This method validates the incoming update request,
     * handles document uploads (if new documents are provided), and updates.
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate
            $validator = Validator::make($request->all(), [
                'property_type'      => 'nullable|in:multi_unit,single_unit',
                'property_name'      => 'nullable|string|max:500|unique:properties,property_name,' . $id,
                'property_address'   => 'nullable|string|max:500',
                'number_of_units'    => 'nullable|integer|min:1',
                'documents'          => 'nullable|array',
                'documents.*'        => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'security_contact'   => 'nullable|string|max:500',
                'additional_details' => 'nullable|string|max:5000',
            ]);

            // First error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $property = Property::where('user_id', Auth::user()->id)->find($id);

            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found', 404);
            }

            $validated = $validator->validated();

            // Handle Document upload
            if ($request->hasFile('documents')) {
                if ($property->documents && is_array($property->documents)) {
                    foreach ($property->documents as $oldDocumentPath) {
                        $Path = ltrim(parse_url($oldDocumentPath, PHP_URL_PATH), '/');
                        Helper::fileDelete($Path);
                    }
                }
                //New Documents  Upload
                $uploadedFiles = [];
                foreach ($request->file('documents') as $file) {
                    $docName         = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $uploadedFiles[] = Helper::fileUpload($file, 'Propertydocuments', $docName);
                }
                $validated['documents'] = $uploadedFiles;
            }

            $validated['user_id'] = Auth::user()->id;
            $property->update($validated);

            return Helper::jsonResponse(true, 'Property updated successfully.', 200, $property);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Property update failed', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /***
     * Delete a specific property and its associated documents.
     * This method deletes the specified property ,
     * @param int $id
     */
    public function destroy($id)
    {
        try {

            $property = Property::where('user_id', Auth::user()->id)->find($id);

            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found.', 404);
            }

            // Delete exiting documents
            if (! empty($property->documents) && is_array($property->documents)) {
                foreach ($property->documents as $documentPath) {
                    $path = ltrim(parse_url($documentPath, PHP_URL_PATH), '/');
                    Helper::fileDelete($path);
                }
            }
            //delete
            $property->delete();

            return Helper::jsonResponse(true, 'property deleted successfully.', 200);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'property deletion failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Retrieve residents associated with a specific property.
     * This method fetches residents linked to the property using the property's referral code.
     * @param int $id
     */
    public function getResidentsByProperty($id)
    {
        try {
            $property = Property::where('id', $id)->where('user_id', Auth::id())->first();

            if (! $property) {
                return Helper::jsonResponse(false, 'Property not found.', 404);
            }
            // Get property owner's referral code.
            $referralCode = Auth::user()->reffer_code;
            $residents    = User::where('invitation_code', $referralCode)->select('id', 'name', 'email', 'role', 'phone')->get();

            return Helper::jsonResponse(true, 'Residents retrieved successfully.', 200, $residents);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve residents.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
