<?php

namespace App\Http\Controllers\Web\Category;

use App\Helpers\Helper;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CategoryController
{
    /**
     * Display a listing of the categories.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = Category::latest();

                if ($request->has('section') && !empty($request->section)) {
                    $query->where('section', $request->section);
                }

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && ! empty($request->search['value'])) {
                            $searchValue = $request->search['value'];
                            $query->where('name', 'like', "%{$searchValue}%");
                        }
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        return '<button type="button" class="btn btn-sm btn-info edit-category" data-id="' . $data->id . '" data-name="' . $data->name . '" title="Edit Category"><i class="bi bi-pencil"></i></button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.layouts.category.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified category.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name'   => 'required|string|max:300',
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update([
                'name'   => $request->name,
            ]);
            //response
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage(),
            ], 500);
        }
    }
}
