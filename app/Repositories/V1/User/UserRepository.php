<?php

namespace App\Repositories\V1\User;

use App\Interfaces\V1\User\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;


class UserRepository implements UserRepositoryInterface
{
    /**
     * Retrieve users based on given conditions.
     *
     * @param array $conditions Array of where conditions. Can include:
     *                          - Simple key => value pairs
     *                          - Arrays like ['age', '>=', 18]
     *                          - Or Arrays like [['age', '>=', 18],['created_at', '>=', now()->subMonth()],]
     * @param array $with       eager loading
     * @param bool $build       If true, executes the query and returns Collection.
     *                          If false, returns the query builder.
     * @return Builder<User>|Collection<int, User>
     *
     * @throws Exception
     */
    public function getUsers(array $conditions=[], array $with=[], bool $build = true): Builder|Collection
    {
        try {
            $query = User::query();
            foreach ($conditions as $key => $condition) {
                if (is_array($condition)) {
                    // Advanced condition: ['column', 'operator', 'value']
                    $query->where(...$condition);
                } else {
                    // Simple key => value condition
                    $query->where($key, $condition);
                }
            }
            $query->with($with);
            return $build ? $query->get() : $query;
        } catch (Exception $e) {
            Log::error('UserRepository::getUsers', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * updateUser and profile
     * @param array $data
     * @param \App\Models\User $user
     * @return User
     */
    public function updateUser(array $data, User $user): User
    {
        try {
            $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);
            $user->profile()->update([
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
            ]);
            return $user;
        } catch (Exception $e) {
            Log::error('UserRepository::updateUser', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
