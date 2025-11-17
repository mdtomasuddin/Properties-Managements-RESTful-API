<?php

namespace App\Http\Controllers\Api\Property\Announcement;

use App\Helpers\Helper;
use App\Models\Announcement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnnouncementController
{
    /**
     * This method retrieves Announcement.
     * The number of Announcement `per_page` query parameter defult ===100.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 100);
            $search     = $request->query('search');
            $categoryId = $request->query('category_id');
            $propertyId = $request->query('property_id');
            $sortBy     = $request->query('sortby', 'desc');
            $status     = $request->query('status');

            $query = Announcement::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id());

            // search
            if (! empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            // categoryId
            if (! empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }

            //property
            if (! empty($propertyId)) {
                $query->where('property_id', $propertyId);
            }

            //  status filter
            if (! empty($status)) {
                $query->where('status', $status);
            }

            // dynamic sorting
            $query->orderBy('created_at', $sortBy);
            $data = $query->paginate($perPage);

            return Helper::jsonResponse(true, 'Announcement retrieved successfully.', 200, $data);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Announcement.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new Announcement with a single file.
     * This method validates the handles Announcement uploads, and creates a new Announcement.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id'            => 'required|integer|exists:properties,id',
                'category_id'            => 'required|integer|exists:categories,id',
                'title'                  => 'required|string|max:500',
                'message'                => 'required|string|max:1000',
                'send_to'                => 'required|in:individual,all_resident',
                'selected_resident_id'   => 'nullable|array',
                'selected_resident_id.*' => 'integer',
                'is_draft'               => 'boolean',
                'publish_date'           => 'nullable|date',
                'attachments'            => 'nullable|array',
                'attachments.*'          => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'status'                 => 'nullable|in:published,draft,cancelled',
            ]);

            //first error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            //validated data.
            $validated = $validator->validated();

            // Handle file uploads
            $files = [];
            if ($request->has('attachments')) {
                foreach ($request->attachments as $file) {
                    $files[] = Helper::fileUpload($file, 'AnnouncementDocFile', $file->getClientOriginalName());
                }
            }

            $validated['attachments'] = $files;
            $validated['user_id']     = Auth::id();
            $announcement             = Announcement::create($validated);

            return Helper::jsonResponse(true, 'Announcement created successfully.', 201, $announcement);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Announcement creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * This method retrieves Announcement show.
     * @param Request $request $id
     */
    public function show($id)
    {
        try {
            $data = Announcement::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id())->find($id);

            return Helper::jsonResponse(true, 'Announcements retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Announcements.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing Announcement.
     * This method validates the incoming update request,
     * @param Request $request property_id |category_id
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id'            => 'nullable|integer|exists:properties,id',
                'category_id'            => 'nullable|integer|exists:categories,id',
                'title'                  => 'nullable|string|max:500',
                'message'                => 'nullable|string|max:1000',
                'send_to'                => 'nullable|in:individual,all_resident',
                'selected_resident_id'   => 'nullable|array',
                'selected_resident_id.*' => 'integer|exists:residents,id',
                'is_draft'               => 'nullable|boolean',
                'publish_date'           => 'nullable|date',
                'attachments'            => 'nullable|array',
                'attachments.*'          => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
                'status'                 => 'nullable|in:published,draft,cancelled',
            ]);

            //validation errors
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $announcement = Announcement::where('user_id', Auth::id())->find($id);
            if (! $announcement) {
                return Helper::jsonResponse(false, 'Announcement not found', 404);
            }

            $validated = $validator->validated();

            // Handle Announcement attachments uploads
            if ($request->hasFile('attachments')) {
                if ($announcement->attachments && is_array($announcement->attachments)) {
                    foreach ($announcement->attachments as $oldPath) {
                        $oldPath = ltrim(parse_url($oldPath, PHP_URL_PATH), '/');
                        Helper::fileDelete($oldPath); // Delete the old files
                    }
                }
                // Upload new attachments
                $uploadedFiles = [];
                foreach ($request->file('attachments') as $file) {
                    $uploadedFiles[] = Helper::fileUpload($file, 'AnnouncementDocFile', $file->getClientOriginalName());
                }
                $validated['attachments'] = $uploadedFiles;
            }

            $validated['user_id'] = Auth::id();
            $announcement->update($validated);
            return Helper::jsonResponse(true, 'Announcement updated successfully.', 200, $announcement);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Announcement update failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete the specified Announcement.
     * attachments file delete
     * @param int $id
     */

    public function destroy($id)
    {
        try {
            $data = Announcement::where('user_id', Auth::id())->find($id);
            if (! $data) {
                return Helper::jsonResponse(false, 'Document not found.', 404);
            }

            // Handle document uploads if there are attachments
            if (! empty($data->attachments) && is_array($data->attachments)) {
                foreach ($data->attachments as $oldPath) {
                    $oldPath = ltrim(parse_url($oldPath, PHP_URL_PATH), '/');
                    Helper::fileDelete($oldPath); // Delete the old files
                }
            }
            //delete
            $data->delete();
            return Helper::jsonResponse(true, 'Document deleted successfully.', 200);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Document deletion failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
