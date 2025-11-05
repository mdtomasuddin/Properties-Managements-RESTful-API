<?php

namespace App\Repositories\V1\Auth;

use App\Helpers\Helper;
use App\Interfaces\V1\Auth\UserRepositoryInterface;
use App\Models\Business;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Summary of createUser
     * @param array $credentials
     * @param int $role
     * @return User
     */
    public function createUser(array $credentials, int $role = 2): User
    {
        try {
            // creating user
            $user = User::create([
                'first_name' => $credentials['first_name'],
                'last_name'  => $credentials['last_name'],
                'handle'     => Helper::generateUniqueSlug($credentials['first_name'], 'users', 'handle'),
                'email'      => $credentials['email'],
                'password'   => Hash::make($credentials['password']),
                'role_id'       => $role,
            ]);

            // creating user profile
            $user->profile()->create([]);

            return $user;
        } catch (Exception $e) {
            Log::error('UserRepository::createUser', ['error' => $e->getMessage()]);
            throw $e;
        }
    }



    /**
     * Attempts to retrieve a user by their email address.
     *
     * This method checks the provided credentials and returns the corresponding user.
     *
     * @param array $credentials The user's login credentials (email and password).
     *
     * @return User|null The user object if found, null otherwise.
     *
     * @throws Exception If there is an error during the query.
     */
    public function login(array $credentials): User|null
    {
        try {
            return User::where('email', $credentials['email'])->first();
        } catch (Exception $e) {
            Log::error('UserRepository::login', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
