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
}
