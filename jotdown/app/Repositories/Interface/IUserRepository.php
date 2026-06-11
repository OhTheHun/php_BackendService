<?php

namespace App\Repositories\Interface;

use App\Models\User;

interface IUserRepository
{
    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function findByIdWithPlan(string $id): ?User;

    public function findByIdWithPlanForUpdate(string $id): ?User;

    public function getProfileData(string $id): ?array;

    public function getProfileStats(string $id): array;

    public function create(array $data): User;

    public function update(User $user, array $data): User;
}
