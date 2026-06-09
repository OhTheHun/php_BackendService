<?php

namespace App\Mappings\Folder;

use App\DTO\Folder\Responses\FolderResponseDto;
use App\Models\Folder;

class FolderToFolderResponseDto
{
    public function transform(Folder $folder): FolderResponseDto
    {
        return new FolderResponseDto(
            id: $folder->Id,
            userId: $folder->user_id,
            name: $folder->name,
            workspaceId: $folder->workspace_id,
            notesCount: (int) ($folder->notes_count ?? $folder->notes()->where('DeleteFlag', false)->count()),
            createdAt: $folder->CreatedTime?->toISOString(),
            updatedAt: $folder->UpdatedTime?->toISOString(),
        );
    }
}
