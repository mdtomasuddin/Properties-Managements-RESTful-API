<?php

namespace App\Services\Web\V1\User;

use App\Interfaces\V1\User\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    /**
     * userRepository
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    /**
     * user
     * @var User
     */
    private User $user;
    /**
     * construct
     * @param \App\Interfaces\V1\User\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->user = Auth::user();
    }

    /**
     * getUsers
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers($request): JsonResponse
    {
        try {
            // getting unbuild data
            $query  = $this->userRepository->getUsers(['role_id' => 2], ['profile'], false);
            // search query
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('handle', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('profile', function ($subQuery) use ($searchTerm) {
                            $subQuery->where('gender', 'like', '%' . $searchTerm . '%');
                        });
                });
            }
            return DataTables::of($query)
                ->addColumn('name', function ($data) {
                    $fullName = ($data->first_name ?? '') . ' ' . ($data->last_name ?? '');
                    $avatar = $data->avatar ? asset($data->avatar) : asset('assets/custom/images/avatar.jpg');
                    $route = route('admin.user.show', $data->handle);
                    return '<td>
                                <div class="d-flex align-items-center">
                                    <img src="' . $avatar . '" alt="Image" class="avatar avatar-sm rounded-circle">
                                    <div class="ms-2">
                                        <h5 class="mb-0">
                                            <a href="' . $route . '" class="text-inherit">' . $fullName . '</a>
                                        </h5>
                                    </div>
                                </div>
                            </td>';
                })
                ->addColumn('handle', fn($data) => $data->handle ?? 'N/A')
                ->addColumn('gender', fn($data) => $data->profile->gender  ?? 'N/A')
                ->addColumn('status', function ($data) {
                    return $data->status ? '<td><span class="badge badge-success-soft rounded-pill">Active</span></td>' : '<td><span class="badge badge-danger-soft rounded-pill">Blocked</span></td>';
                })
                ->addColumn('action', function ($data) {
                    $route = route('admin.user.status', $data->handle);
                    return $data->status ? '<a href="' . $route . '"
                                class="btn btn-ghost btn-icon btn-sm rounded-circle texttooltip"
                                data-template="lockOne">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z" stroke="#1C274C" stroke-width="1.5"></path> <path d="M6 10V8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8V10" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
                                <div id="lockOne" class="d-none">
                                    <span>Block</span>
                                </div>
                            </a>' : '<a href="' . $route . '"
                                class="btn btn-ghost btn-icon btn-sm rounded-circle texttooltip"
                                data-template="lockOne">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z" stroke="#1C274C" stroke-width="1.5"></path> <path d="M6 10V8C6 4.68629 8.68629 2 12 2C14.7958 2 17.1449 3.91216 17.811 6.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
                                <div id="lockOne" class="d-none">
                                    <span>Block</span>
                                </div>
                            </a>';
                })
                ->rawColumns(['name', 'status', 'handle', 'gender', 'action'])
                ->make(true);
        } catch (Exception $e) {
            Log::error('UserService::getUsers', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
