<?php

namespace App\Http\Controllers\Web\User\Properties;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class PropertyOwnerController
{
    /**
     * Display a listing of the property owners.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = User::where('role', 'property_owner')->latest();
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && ! empty($request->search['value'])) {
                            $searchValue = $request->search['value'];
                            $query->where(function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%")
                                    ->orWhere('email', 'like', "%{$searchValue}%")
                                    ->orWhere('phone', 'like', "%{$searchValue}%");
                            });
                        }
                    })
                    ->addIndexColumn()
                    ->addColumn('avatar', function ($data) {
                        if ($data->avatar) {
                            return '<img src="' . $data->avatar . '" alt="avatar" class="rounded-circle border" width="40" height="40">';
                        }
                        return 'N/A';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->name ?? 'N/A';
                    })
                    ->addColumn('email', function ($data) {
                        return '<a href="mailto:' . $data->email . '">' . $data->email . '</a>';
                    })
                    ->addColumn('phone', function ($data) {
                        return '<a href="tel:' . $data->phone . '">' . $data->phone . '</a>';
                    })
                    ->addColumn('reffer_code', function ($data) {
                        return $data->reffer_code ?? 'N/A';
                    })
                    ->addColumn('subscription', function ($data) {
                        return $data->subscription ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('property-owner.show', $data->id) . '" class="text-success fs-5 me-2" title="View Property Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['avatar', 'subscription', 'name', 'email', 'phone', 'reffer_code', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.property_owner.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified property owner and their properties.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $user = User::with('properties')->findOrFail($id);
            return view('backend.layouts.property_owner.show', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('property-owner.index')->with('t-error', 'User not found: ' . $e->getMessage());
        }
    }
}
