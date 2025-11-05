<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\PasswordChangeRequest;
use App\Services\Api\V1\Auth\PasswordService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    protected PasswordService $passwordService;

    /**
     * Constructor for initializing the class with the PasswordService dependency.
     *
     * @param PasswordService $passwordService The service used for handling password-related operations such as reset and change.
     */
    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }





    /**
     * Changes the user's password based on the provided request data.
     *
     * Validates the incoming request, processes the password change using the PasswordService,
     * and returns a JSON response indicating the success or failure of the operation.
     *
     * @param PasswordChangeRequest $request The request containing the user's email and new password.
     *
     * @return JsonResponse The JSON response indicating the success or failure of the password change.
     */
    public function changePassword(PasswordChangeRequest $request): JsonResponse
    {
        try {
            $this->passwordService->changePassword($request->email, $request->password);
            return $this->success(202, 'Password Changed Successfully', []);
        } catch (Exception $e) {
            Log::error('PasswordController::changePassword', ['error' => $e->getMessage()]);
            return $this->error(500, 'Server Error', $e->getMessage());
        }
    }
}
