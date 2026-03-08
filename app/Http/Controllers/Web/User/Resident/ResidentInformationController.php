<?php

namespace App\Http\Controllers\Web\User\Resident;

use App\Helpers\Helper;
use App\Models\Resident;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ResidentInformationController
{
    /**
     * Display a listing of the resident information.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = User::with(['resident.property.user'])->where('role', 'resident')->latest();

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && ! empty($request->search['value'])) {
                            $searchValue = $request->search['value'];
                            $query->where(function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%")
                                    ->orWhere('email', 'like', "%{$searchValue}%")
                                    ->orWhere('phone', 'like', "%{$searchValue}%")
                                    ->orWhere('invitation_code', 'like', "%{$searchValue}%")
                                    ->orWhereHas('resident.property', function ($propQuery) use ($searchValue) {
                                        $propQuery->where('property_name', 'like', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->addIndexColumn()
                    ->addColumn('avatar', function ($data) {
                        $avatar = $data->avatar ?? null;
                        if ($avatar) {
                            return '<img src="' . $avatar . '" alt="avatar" class="rounded-circle border" width="40" height="40">';
                        }
                        return 'N/A';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->name ?? 'N/A';
                    })
                    ->addColumn('email', function ($data) {
                        if ($data->email) {
                            return '<a href="mailto:' . $data->email . '">' . $data->email . '</a>';
                        }
                        return 'N/A';
                    })
                    ->addColumn('phone', function ($data) {
                        if ($data->phone) {
                            return '<a href="tel:' . $data->phone . '">' . $data->phone . '</a>';
                        }
                        return 'N/A';
                    })
                    ->addColumn('property_name', function ($data) {
                        return $data->resident && $data->resident->property ? $data->resident->property->property_name : 'N/A';
                    })
                    ->addColumn('unit_flat_number', function ($data) {
                        return $data->resident && $data->resident->unit_flat_number ? $data->resident->unit_flat_number : 'N/A';
                    })
                    ->addColumn('property_owner', function ($data) {
                        if ($data->resident && $data->resident->property && $data->resident->property->user) {
                            return $data->resident->property->user->name;
                        } elseif ($data->invitation_code) {
                            $owner = User::where('reffer_code', $data->invitation_code)->first();
                            return $owner ? $owner->name : 'N/A';
                        }
                        return 'N/A';
                    })

                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('resident-information.show', $data->id) . '" class="text-success fs-5 me-2" title="View Resident Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['avatar', 'email', 'phone', 'property_name', 'unit_flat_number', 'property_owner', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.resident_information.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resident details.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $user           = User::with(['resident'])->where('role', 'resident')->findOrFail($id);
            $resident       = $user->resident ?? new Resident(['user_id' => $id]);
            $resident->user = $user;

            $invitationCode = $user->invitation_code;
            $owner = User::where('role', 'property_owner')->where('reffer_code', $invitationCode)->with('properties')->first();

            $resident->assigned_owner    = $owner;
            $resident->assigned_property = $owner ? $owner->properties->first() : null;

            return view('backend.layouts.resident_information.show', compact('resident'));
        } catch (Exception $e) {
            return redirect()->route('resident-information.index')->with('t-error', 'Resident not found: ' . $e->getMessage());
        }
    }
}
