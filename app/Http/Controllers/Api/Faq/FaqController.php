<?php

namespace App\Http\Controllers\Api\Faq;

use App\Helpers\Helper;
use App\Models\FAQ;
use Exception;
use Illuminate\Http\Request;

class FaqController
{
    /**
     * Display a listing of the FAQ.
     * search=> question, answer | per_page Default:50
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 50);
            $search  = trim($request->query('search'));
            //build query
            $query = FAQ::select('id', 'answer', 'question');

            //SEARCH FUNCTIONALITY
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('answer', 'like', '%' . $search . '%')
                        ->orWhere('question', 'like', '%' . $search . '%');
                });
            }
            $data = $query->paginate($perPage);
            //return data
            return Helper::jsonResponse(true, 'FAQ retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve FAQ Data', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
