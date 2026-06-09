<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\IUserRepository;
use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    public function findById(string $id): ?User
    {
        return User::query()
            ->where('Id', $id)
            ->where('DeleteFlag', false)
            ->first();
    }

    public function findByIdWithPlan(string $id): ?User
    {
        return User::query()
            ->leftJoin('plans', 'plans.Id', '=', 'users.plan_id')
            ->select('users.*', 'plans.name as plan_name')
            ->where('users.Id', $id)
            ->where('users.DeleteFlag', false)
            ->first();
    }

    public function getProfileStats(string $id): array
    {
        $stats = DB::query()
            ->selectRaw('(select count(*) from notes where user_id = ? and "DeleteFlag" = false) as total_notes', [$id])
            ->selectRaw('(select count(*) from workspaces where user_id = ? and "DeleteFlag" = false) as total_workspaces', [$id])
            ->selectRaw('(select count(*) from labels where user_id = ? and "DeleteFlag" = false) as total_labels', [$id])
            ->selectRaw('(select count(*) from note_shares where shared_by_user_id = ? and "DeleteFlag" = false and revoked_at is null) as shared_notes', [$id])
            ->first();

        return [
            'total_notes' => (int) $stats->total_notes,
            'total_workspaces' => (int) $stats->total_workspaces,
            'total_labels' => (int) $stats->total_labels,
            'shared_notes' => (int) $stats->shared_notes,
        ];
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
