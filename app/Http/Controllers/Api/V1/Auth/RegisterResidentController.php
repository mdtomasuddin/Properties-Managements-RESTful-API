<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\Helper;
use App\Http\Requests\Api\Auth\RegisterResidentRequest;
use App\Services\Api\V1\Auth\RegisterResidentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterResidentController
{

    private RegisterResidentService $RegisterResidentService;

    /**a
     * call Register Service 3 function ->
     * RegisterResidentService ->call
     */
    public function __construct(RegisterResidentService $RegisterResidentService)
    {
        $this->RegisterResidentService = $RegisterResidentService;
    }

    /**
     * Store RegisterResidentService
     * RegisterResidentRequest .
     */
    public function RegisterResidentInfo(RegisterResidentRequest $request): JsonResponse
    {
        try {
            $data   = $request->validated();
            $result = $this->RegisterResidentService->RegisterResidentInfo($data);

            return response()->json([
                'status'     => true,
                'message'    => 'Resident info & registered saved  successfully.',
                'code'       => 200,
                'role'       => $result['user']->role,
                'token_type' => 'bearer',
                'token'      => $result['token'],
                'data'       => [
                    'id'          => $result['user']->id,
                    'name'        => $result['user']->name,
                    'email'       => $result['user']->email,
                    'phone'       => $result['user']->phone,
                    'address'     => $result['user']->address,
                    'avatar'      => $result['user']->avatar,
                    'cover_photo' => $result['user']->cover_photo,
                    'nid_file'    => $result['user']->nid_file,
                ],
            ], 200);
        } catch (Exception $e) {
            // Log::error('Register personal info error: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to save personal info.', 400, [
                $e->getMessage(),
            ]);
        }
    }
}
