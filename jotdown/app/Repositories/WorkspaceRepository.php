<?php

namespace App\Repositories;

use App\Models\Workspace;
use App\Repositories\Interface\IWorkspaceRepository;
use Illuminate\Support\Collection;

class WorkspaceRepository implements IWorkspaceRepository
{
    public function getByUser(string $userId): Collection
    {
        return Workspace::query()
            ->withCount([
                'folders' => fn ($query) => $query->where('DeleteFlag', false),
                'notes' => fn ($query) => $query->where('DeleteFlag', false),
            ])
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->orderBy('name')
            ->get();
    }

    public function findOwnedById(string $id, string $userId): ?Workspace
    {
        return Workspace::query()
            ->withCount([
                'folders' => fn ($query) => $query->where('DeleteFlag', false),
                'notes' => fn ($query) => $query->where('DeleteFlag', false),
            ])
            ->where('Id', $id)
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->first();
    }

    public function countActiveByUser(string $userId): int
    {
        return Workspace::query()
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->count();
    }

    public function create(array $data): Workspace
    {
        $workspace = Workspace::query()->create($data);

        return $workspace->loadCount([
            'folders' => fn ($query) => $query->where('DeleteFlag', false),
            'notes' => fn ($query) => $query->where('DeleteFlag', false),
        ]);
    }

    public function update(Workspace $workspace, array $data): Workspace
    {
        $workspace->fill($data);
        $workspace->save();

        return $workspace->loadCount([
            'folders' => fn ($query) => $query->where('DeleteFlag', false),
            'notes' => fn ($query) => $query->where('DeleteFlag', false),
        ]);
    }

    public function softDelete(Workspace $workspace, string $updatedBy): void
    {
        $workspace->fill([
            'DeleteFlag' => true,
            'UpdatedBy' => $updatedBy,
        ]);
        $workspace->save();
    }
}
