<?php

namespace App\Repositories\Interface;

use App\Models\User;

interface IUserRepository
{
    public function findByEmail(string $email): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;
}
