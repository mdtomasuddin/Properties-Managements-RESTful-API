<?php

namespace App\Http\Controllers\Web\Maintenance;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Helpers\Helper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class MaintenanceAdminController
{
    /**
     * Display a listing of the maintenances.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = Maintenance::with(['user', 'property', 'category'])->latest();

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('property_name', function ($data) {
                        return $data->property->property_name ?? 'N/A';
                    })
                    ->addColumn('category_name', function ($data) {
                        $icon = $data->category->icon ?? '';
                        $name = $data->category->name ?? 'N/A';
                        if ($icon) {
                            return '<div class="d-flex align-items-center"><img src="' . url($icon) . '" width="25" height="25" class="me-2 rounded-circle" alt="icon"> ' . $name . '</div>';
                        }
                        return $name;
                    })
                    ->addColumn('description', function ($data) {
                        return Str::words($data->description, 40, '...') ?? 'N/A';
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
                    ->filterColumn('description', function ($query, $keyword) {
                        $query->where('description', 'like', "%{$keyword}%");
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('admin-maintenance.show', $data->id) . '" class="text-success fs-5 me-2" title="View Maintenance Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['category_name', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.maintenance.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified maintenance details.
     * @param int $id
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $maintenance = Maintenance::with(['user', 'property.user', 'category'])->findOrFail($id);
            return view('backend.layouts.maintenance.show', compact('maintenance'));
        } catch (Exception $e) {
            return redirect()->route('admin-maintenance.index')->with('t-error', 'Maintenance not found: ' . $e->getMessage());
        }
    }
}
