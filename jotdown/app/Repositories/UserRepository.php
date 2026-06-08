<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\IUserRepository;

class UserRepository implements IUserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}
