<?php
namespace App\Http\Controllers\Api\V1\Property\SecurityContact;

use App\Helpers\Helper;
use App\Models\SecurityContact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SecurityContactController
{

    /**
     * This method retrieves Security Contacts.
     * The number of Security Contacts `per_page` query parameter default === 50.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 50);
            $search     = $request->query('search');
            $propertyId = $request->query('property_id');

            $query = SecurityContact::with(['property:id,property_name', 'category:id,name']);
            //filter by search.
            if (! empty($search)) {
                $query->where('phone', 'like', '%' . $search . '%');
            }
            //filter by property_id.
            if (! empty($propertyId)) {
                $query->where('property_id', $propertyId);
            }
            $data = $query->paginate($perPage);
            return Helper::jsonResponse(true, 'Security Contacts retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Security Contacts.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * This method validates the handles Security Contact , and creates a new Security Contact.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id' => 'required|integer|exists:properties,id',
                'category_id' => 'required|integer|exists:categories,id',
                'phone'       => 'required|string|max:20',
                'description' => 'nullable|string|max:1000',
            ]);

            //first error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $validated            = $validator->validated();
            $validated['user_id'] = Auth::id();
            $SecurityContact      = SecurityContact::create($validated);

            return Helper::jsonResponse(true, 'Security Contact created successfully.', 201, $SecurityContact);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Security Contact creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * This method retrieves Security Contacts show.
     * @param Request $request $id
     */
    public function show($id)
    {
        try {
            $data = SecurityContact::with(['property:id,property_name', 'category:id,name'])->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Security Contact not found.', 404);
            }

            return Helper::jsonResponse(true, 'Security Contacts retrieved successfully.', 200, $data);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Security Contacts.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing Security Contact.
     * * @param Request $request
     * @param int $id  // Assuming the ID is passed in the route
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $securityContact = SecurityContact::find($id);
            if (! $securityContact) {
                return Helper::jsonResponse(false, 'Security Contact not found.', 404);
            }
            $validator = Validator::make($request->all(), [
                'property_id' => 'nullable|integer|exists:properties,id',
                'category_id' => 'nullable|integer|exists:categories,id',
                'phone'       => 'nullable|string|max:20',
                'description' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 422);
            }

            $validated            = $validator->validated();
            $validated['user_id'] = Auth::id();

            $securityContact->update($validated);
            return Helper::jsonResponse(true, 'Security Contact updated successfully.', 200, $securityContact);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Security Contact update failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove an existing Security Contact.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $securityContact = SecurityContact::find($id);
            if (! $securityContact) {
                return Helper::jsonResponse(false, 'Security Contact not found.', 404);
            }
            $securityContact->delete();
            return Helper::jsonResponse(true, 'Security Contact deleted successfully.', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Security Contact deletion failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
