<?php

namespace App\Interfaces\V1\User;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
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
    public function getUsers(array $conditions=[], array $with=[], bool $build = true): Builder|Collection;
    /**
     * updateUser and profile
     * @param array $data
     * @param \App\Models\User $user
     * @return User
     */
    public function updateUser(array $data, User $user): User;
}
