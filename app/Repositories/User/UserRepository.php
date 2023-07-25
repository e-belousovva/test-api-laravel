<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function createUser(array $data): object
    {
        return User::query()->create($data);
    }

    public function updateUser(User $user, array $data): object
    {
        $user->update($data);

        return $user;
    }
}
