<?php

namespace App\Repositories;

use App\DTO\Folder\Requests\ListFoldersRequestDto;
use App\Models\Folder;
use App\Repositories\Interface\IFolderRepository;
use Illuminate\Support\Collection;

class FolderRepository implements IFolderRepository
{
    public function getByUser(string $userId, ListFoldersRequestDto $request): Collection
    {
        return Folder::query()
            ->withCount(['notes' => fn ($query) => $query->where('DeleteFlag', false)])
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->when($request->workspaceId !== null, fn ($query) => $query->where('workspace_id', $request->workspaceId))
            ->orderBy('name')
            ->get();
    }

    public function findOwnedById(string $id, string $userId): ?Folder
    {
        return Folder::query()
            ->withCount(['notes' => fn ($query) => $query->where('DeleteFlag', false)])
            ->where('Id', $id)
            ->where('user_id', $userId)
            ->where('DeleteFlag', false)
            ->first();
    }

    public function create(array $data): Folder
    {
        $folder = Folder::query()->create($data);

        return $folder->loadCount(['notes' => fn ($query) => $query->where('DeleteFlag', false)]);
    }

    public function update(Folder $folder, array $data): Folder
    {
        $folder->fill($data);
        $folder->save();

        return $folder->loadCount(['notes' => fn ($query) => $query->where('DeleteFlag', false)]);
    }

    public function softDelete(Folder $folder, string $updatedBy): void
    {
        $folder->fill([
            'DeleteFlag' => true,
            'UpdatedBy' => $updatedBy,
        ]);
        $folder->save();
    }
}
