<?php

namespace App\Http\Controllers\Api\Property\Document;

use App\Helpers\Helper;
use App\Models\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentController
{
    /**
     * This method retrieves Document.
     * The number of Document `per_page` query parameter defult ===100.
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $perPage    = $request->query('per_page', 100);
            $search     = $request->query('search');
            $categoryId = $request->query('category_id');

            $query = Document::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id());

            if (! empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            if (! empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }

            $data = $query->paginate($perPage);

            return Helper::jsonResponse(true, 'Documents retrieved successfully.', 200, $data);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Documents.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a new Document with a single file.
     * This method validates the handles document uploads, and creates a new Document.
     * @param Request $request || store
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id' => 'required|integer',
                'category_id' => 'required|integer',
                'name'        => 'required|string|max:500',
                'file'        => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
            ]);

            //first error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $validated = $validator->validated();
            // Handle file upload
            if ($request->hasFile('file')) {
                $originalName      = $request->file('file')->getClientOriginalName();
                $validated['file'] = Helper::fileUpload($request->file('file'), 'DocumentFile', $originalName);
            }

            $validated['user_id'] = Auth::id();
            $document             = Document::create($validated);

            return Helper::jsonResponse(true, 'Document created successfully.', 201, $document);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Document creation failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * This method retrieves Document show.
     * @param Request $request $id
     */
    public function show($id)
    {
        try {
            $data = Document::with(['property:id,property_name', 'category:id,name'])->where('user_id', Auth::id())->find($id);

            return Helper::jsonResponse(true, 'Documents retrieved successfully.', 200, $data);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve Documents.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing Document.
     * This method validates the incoming update request,
     * @param Request $request property_id |category_id
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'property_id' => 'nullable|integer',
                'category_id' => 'nullable|integer',
                'name'        => 'nullable|string|max:500',
                'file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:30480',
            ]);

            //first error get
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return Helper::jsonResponse(false, $message, 422);
            }

            $document = Document::where('user_id', Auth::id())->find($id);
            if (! $document) {
                return Helper::jsonResponse(false, 'Document not found', 404);
            }

            $validated = $validator->validated();

            // Handle file upload
            if ($request->hasFile('file')) {
                if ($document->file) {
                    $parsedUrl   = parse_url($document->file, PHP_URL_PATH);
                    $oldFilePath = ltrim($parsedUrl, '/');
                    Helper::fileDelete($oldFilePath);
                }
                // Upload new file
                $originalName      = $request->file('file')->getClientOriginalName();
                $validated['file'] = Helper::fileUpload($request->file('file'), 'DocumentFile', $originalName);
            }

            $validated['user_id'] = Auth::id();
            $document->update($validated);
            return Helper::jsonResponse(true, 'Document updated successfully.', 200, $document);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Document update failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete the specified Document.
     * @param int $id
     */
    public function destroy($id)
    {
        try {
            $document = Document::where('user_id', Auth::id())->find($id);
            if (! $document) {
                return Helper::jsonResponse(false, 'Document not found.', 404);
            }

            // Delete existing file if exists
            if ($document->file) {
                $parsedUrl = parse_url($document->file, PHP_URL_PATH);
                $filePath  = ltrim($parsedUrl, '/');
                Helper::fileDelete($filePath);
            }

            // Delete document record
            $document->delete();

            return Helper::jsonResponse(true, 'Document deleted successfully.', 200);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Document deletion failed.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
