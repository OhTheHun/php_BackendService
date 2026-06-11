<?php

namespace App\Repositories\Interface;

use App\Models\Workspace;
use Illuminate\Support\Collection;

interface IWorkspaceRepository
{
    public function getByUser(string $userId): Collection;

    public function findOwnedById(string $id, string $userId): ?Workspace;

    public function countActiveByUser(string $userId): int;

    public function create(array $data): Workspace;

    public function update(Workspace $workspace, array $data): Workspace;

    public function softDelete(Workspace $workspace, string $updatedBy): void;
}
