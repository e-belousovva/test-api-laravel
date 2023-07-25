<?php

declare(strict_types=1);

namespace App\Interfaces\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAllUsers(): Collection;

    public function createUser(array $data): object;

    public function updateUser(User $user, array $data): object;
}
