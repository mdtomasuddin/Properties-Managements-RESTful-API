<?php

namespace App\Http\Controllers\Web\CMS;

use App\Helpers\Helper;
use App\Models\FAQ;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class FaqController
{
    /**
     * Display the listing of FAQs.
     * @param Request $request * @throws Exception
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = FAQ::latest();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('question', function ($data) {
                        $question      = $data->question;
                        $shortQuestion = strlen($question) > 50 ? substr($question, 0, 50) . '...' : $question;
                        return '<span class="question-tooltip" style="cursor: pointer;" title="' . htmlspecialchars($question) . '">' . htmlspecialchars($shortQuestion) . '</span>';
                    })
                    ->addColumn('answer', function ($data) {
                        $answer      = $data->answer;
                        $shortAnswer = strlen($answer) > 50 ? substr($answer, 0, 50) . '...' : $answer;
                        return '<span class="answer-tooltip" style="cursor: pointer;" title="' . htmlspecialchars($answer) . '">' . htmlspecialchars($shortAnswer) . '</span>';
                    })
                    ->addColumn('status', function ($data) {
                        $status = '<div class="form-check form-switch" style="margin-left: 40px; width: 50px; height: 24px;">';
                        $status .= '<input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showStatusChangeAlert(' . $data->id . ')">';
                        $status .= '</div>';
                        return $status;
                    })
                    ->addColumn('action', function ($data) {
                        return ' <a href="' . route('faq.edit', $data->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-sm btn-danger">Delete</button>';
                    })
                    ->rawColumns(['question', 'answer', 'status', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.faq.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource. view
     * create view
     */
    public function create(): View
    {
        return view('backend.layouts.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:1000',
            'answer'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('t-error', 'Validation failed');
        }

        try {
            FAQ::create([
                'question' => $request->question,
                'answer'   => $request->answer,
                'status'   => 'active',
            ]);

            return redirect()->route('faq.index')->with('t-success', 'FAQ created successfully');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('t-error', 'Failed to create FAQ: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * @return JsonResponse @param int $id
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = FAQ::find($id);
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return View|RedirectResponse  @param int $id
     */
    public function edit(int $id): View | RedirectResponse
    {
        try {
            $faq = FAQ::find($id);
            return view('backend.layouts.faq.edit', compact('faq'));
        } catch (Exception $e) {
            return redirect()->route('faq.index')->with('t-error', 'FAQ not found');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request && id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:1000',
            'answer'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('t-error', 'Validation failed');
        }

        try {
            $faq = FAQ::find($id);
            $faq->update([
                'question' => $request->question,
                'answer'   => $request->answer,
            ]);

            return redirect()->route('faq.index')->with('t-success', 'FAQ updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('t-error', 'Failed to update FAQ: ' . $e->getMessage());
        }
    }

    /**
     * Change the status of the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse
    {
        try {
            $data = FAQ::findOrFail($id);
            if ($data->status == 'active') {
                $data->status = 'inactive';
                $data->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Unpublished Successfully.',
                    'data'    => $data,
                ]);
            } else {
                $data->status = 'active';
                $data->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Published Successfully.',
                    'data'    => $data,
                ]);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $faq = FAQ::findOrFail($id);
            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FAQ: ' . $e->getMessage(),
            ], 500);
        }
    }
}
