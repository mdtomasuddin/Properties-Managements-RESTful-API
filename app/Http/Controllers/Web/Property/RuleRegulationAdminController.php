<?php

namespace App\Http\Controllers\Web\Property;

use App\Helpers\Helper;
use App\Models\RuleRegulation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RuleRegulationAdminController
{
    /**
     * Display a listing of the rule regulations.
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $query = RuleRegulation::with(['property', 'user'])->latest();
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
                    ->addColumn('short_title', function ($data) {
                        $title = $data->title ?? 'N/A';
                        $truncated = Str::words($title, 30, '...');
                        return '<span title="' . e($title) . '">' . e($truncated) . '</span>';
                    })
                    ->filterColumn('short_title', function ($query, $keyword) {
                        $query->where('title', 'like', "%{$keyword}%");
                    })
                    ->addColumn('short_description', function ($data) {
                        $description = $data->description ?? 'N/A';
                        $truncated = Str::words($description, 40, '...');
                        return '<span title="' . e($description) . '">' . e($truncated) . '</span>';
                    })
                    ->filterColumn('short_description', function ($query, $keyword) {
                        $query->where('description', 'like', "%{$keyword}%");
                    })
                    ->addColumn('action', function ($data) {
                        return '<a href="' . route('admin-rule-regulation.show', $data->id) . '" class="text-success fs-5 me-2" title="View Rule Regulation Details"><i class="bi bi-eye"></i></a>';
                    })
                    ->rawColumns(['short_title', 'short_description', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.rule_regulation.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified rule regulation.
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $ruleRegulation = RuleRegulation::with(['property', 'user'])->findOrFail($id);
            return view('backend.layouts.rule_regulation.show', compact('ruleRegulation'));
        } catch (Exception $e) {
            return redirect()->route('admin-rule-regulation.index')->with('t-error', 'Rule Regulation not found: ' . $e->getMessage());
        }
    }
}
