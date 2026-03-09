<?php

namespace App\Http\Controllers\Web\Announcement;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AnnouncementAdminController
{
    /**
     * Display a listing of the announcements.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = Announcement::with(['user', 'property', 'category'])->latest();

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
                    ->editColumn('title', function ($data) {
                        return Str::limit($data->title, 40) ?? 'N/A';
                    })
                    ->editColumn('message', function ($data) {
                        return Str::limit($data->message, 40) ?? 'N/A';
                    })
                    ->editColumn('status', function ($data) {
                        $badgeClass = match ($data->status) {
                            'published', 'active' => 'bg-success',
                            'draft' => 'bg-warning',
                            'inactive', 'cancelled' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                        return '<span class="badge ' . $badgeClass . '">' . ucfirst($data->status) . '</span>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('admin-announcement.show', $data->id) . '" class="text-success fs-5 me-2" title="View Announcement Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['title', 'message', 'status', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.announcement.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified announcement details.
     * @param int $id
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $announcement = Announcement::with(['user', 'property', 'category'])->findOrFail($id);
            return view('backend.layouts.announcement.show', compact('announcement'));
        } catch (Exception $e) {
            return redirect()->route('admin-announcement.index')->with('t-error', 'Announcement not found: ' . $e->getMessage());
        }
    }
}
