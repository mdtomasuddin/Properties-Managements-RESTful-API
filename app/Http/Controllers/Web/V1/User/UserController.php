<?php

namespace App\Http\Controllers\Web\V1\User;

use App\Http\Controllers\Web\V1\Controller;
use App\Models\User;
use App\Services\Web\V1\User\UserService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * userService
     * @var UserService
     */
    private UserService $userService;

    /**
     * construct
     * @param \App\Services\Web\V1\User\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * user index
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request): RedirectResponse|View|JsonResponse
    {
        try {
            if ($request->ajax()) {
                return $this->userService->getUsers($request);
            }
            return view('backend.layouts.user.index');
        } catch (Exception $e) {
            Log::error('UserController::index', ['error' => $e->getMessage()]);
            return redirect()->back()->with('t-error', 'Something went wring..!');
        }
    }

    /**
     * show
     * @param \App\Models\User $user
     * @return RedirectResponse|View
     */
    public function show(User $user): RedirectResponse|View
    {
        try {
            $compact = [
                'user' => $user,
            ];
            return view('backend.layouts.user.show', $compact);
        } catch (Exception $e) {
            Log::error('UserController::index', ['error' => $e->getMessage()]);
            return redirect()->back()->with('t-error', 'Something went wring..!');
        }
    }

    /**
     * status
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status(User $user): RedirectResponse
    {
        try {
            $user->status = !$user->status;
            $user->save();
            return redirect()->back()->with('t-success', 'Status updated');
        } catch (Exception $e) {
            Log::error('UserController::status', ['error' => $e->getMessage()]);
            return redirect()->back()->with('t-error', 'Something went wring..!');
        }
    }
}
