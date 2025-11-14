<?php

namespace App\Http\Controllers\Api\Category;

use App\Helpers\Helper;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController
{
    /**
     * Display a listing of the Category.
     * Available sections: 'payment', 'emergency', 'maintenance', 'announcement', 'document'
     */
    public function index(Request $request)
    {
        try {
            $section = $request->query('section');
            $data    = Category::select('id', 'name', 'type', 'section')->where('section', $section)->where('status', 'active')->get();

            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
