<?php

namespace App\Repositories\Interface;

use App\DTO\Folder\Requests\ListFoldersRequestDto;
use App\Models\Folder;
use Illuminate\Support\Collection;

interface IFolderRepository
{
    public function getByUser(string $userId, ListFoldersRequestDto $request): Collection;

    public function findOwnedById(string $id, string $userId): ?Folder;

    public function create(array $data): Folder;

    public function update(Folder $folder, array $data): Folder;

    public function softDelete(Folder $folder, string $updatedBy): void;
}
