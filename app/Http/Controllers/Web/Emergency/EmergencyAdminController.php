<?php

namespace App\Http\Controllers\Web\Emergency;

use Illuminate\Http\Request;
use App\Models\Emergency;
use App\Helpers\Helper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class EmergencyAdminController
{
    /**
     * Display a listing of the emergencies.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = Emergency::with(['user', 'property', 'category'])->latest();

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('user_name', function ($data) {
                        return $data->user->name ?? 'N/A';
                    })
                    ->addColumn('property_name', function ($data) {
                        return $data->property->property_name ?? 'N/A';
                    })
                    ->addColumn('category_name', function ($data) {
                        return $data->category->name ?? 'N/A';
                    })
                    ->addColumn('description', function ($data) {
                        return Str::limit($data->description, 40) ?? 'N/A';
                    })
                    ->addColumn('location', function ($data) {
                        return $data->location ?? 'N/A';
                    })
                    ->filterColumn('user_name', function ($query, $keyword) {
                        $query->whereHas('user', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                    })
                    ->filterColumn('property_name', function ($query, $keyword) {
                        $query->whereHas('property', function ($q) use ($keyword) {
                            $q->where('property_name', 'like', "%{$keyword}%");
                        });
                    })
                    ->filterColumn('category_name', function ($query, $keyword) {
                        $query->whereHas('category', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('admin-emergency.show', $data->id) . '" class="text-success fs-5 me-2" title="View Emergency Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['description', 'location', 'user_name', 'property_name', 'category_name', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.emergency.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified emergency details.
     * @param int $id
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $emergency = Emergency::with(['user', 'property', 'category'])->findOrFail($id);
            return view('backend.layouts.emergency.show', compact('emergency'));
        } catch (Exception $e) {
            return redirect()->route('admin-emergency.index')->with('t-error', 'Emergency not found: ' . $e->getMessage());
        }
    }
}
