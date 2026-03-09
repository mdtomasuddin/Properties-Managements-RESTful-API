<?php

namespace App\Http\Controllers\Web\Property;

use App\Helpers\Helper;
use App\Models\SecurityContact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class SecurityContactAdminController
{
    /**
     * Display a listing of the security contacts.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = SecurityContact::with(['property', 'category', 'user'])->latest();
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('property_name', function ($data) {
                        return $data->property->property_name ?? 'N/A';
                    })
                    ->filterColumn('property_name', function ($query, $keyword) {
                        $query->whereHas('property', function ($q) use ($keyword) {
                            $q->where('property_name', 'like', "%{$keyword}%");
                        });
                    })
                    ->addColumn('category_name', function ($data) {
                        return $data->category->name ?? 'N/A';
                    })
                    ->filterColumn('category_name', function ($query, $keyword) {
                        $query->whereHas('category', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->phone ?? 'N/A';
                    })
                    ->addColumn('short_description', function ($data) {
                        $description = $data->description ?? 'N/A';
                        $truncated = Str::words($description, 30, '...');
                        return '<span title="' . e($description) . '">' . e($truncated) . '</span>';
                    })
                    ->filterColumn('short_description', function ($query, $keyword) {
                        $query->where('description', 'like', "%{$keyword}%");
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('admin-security-contact.show', $data->id) . '" class="text-success fs-5 me-2" title="View Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['short_description', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.security_contact.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified security contact.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $securityContact = SecurityContact::with(['property', 'category', 'user'])->findOrFail($id);
            return view('backend.layouts.security_contact.show', compact('securityContact'));
        } catch (Exception $e) {
            return redirect()->route('admin-security-contact.index')->with('t-error', 'Security Contact not found: ' . $e->getMessage());
        }
    }
}
