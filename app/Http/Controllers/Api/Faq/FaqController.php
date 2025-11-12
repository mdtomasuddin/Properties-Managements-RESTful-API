<?php

namespace App\Http\Controllers\Api\Faq;

use App\Helpers\Helper;
use App\Models\FAQ;
use Exception;

class FaqController
{
    /**
     * Display a listing of the FAQ.
     * get => 'id', 'answer', 'question'
     */
    public function index()
    {
        try {
            $data = FAQ::select('id', 'answer', 'question')->get();
            return Helper::jsonResponse(true, 'FAQ retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve FAQ Data', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
