<?php

namespace App\Http\Controllers\Web\Property;

use App\Helpers\Helper;
use App\Models\Property;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class PropertyAdminController
{
    /**
     * Display a listing of the properties.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = Property::with('user')->latest();
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->editColumn('property_name', function ($data) {
                        return $data->property_name ?? 'N/A';
                    })
                    ->editColumn('property_type', function ($data) {
                        return $data->property_type ?? 'N/A';
                    })
                    ->editColumn('property_address', function ($data) {
                        return $data->property_address ?? 'N/A';
                    })
                    ->editColumn('number_of_units', function ($data) {
                        return $data->number_of_units ?? '0';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('admin-property.show', $data->id) . '" class="btn btn-sm btn-info text-white" title="View Property Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.layouts.property.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified property.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $property = Property::with('user')->findOrFail($id);
            return view('backend.layouts.property.show', compact('property'));
        } catch (Exception $e) {
            return redirect()->route('admin-property.index')->with('t-error', 'Property not found: ' . $e->getMessage());
        }
    }
}
