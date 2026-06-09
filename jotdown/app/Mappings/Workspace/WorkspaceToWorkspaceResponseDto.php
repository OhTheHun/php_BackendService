<?php

namespace App\Mappings\Workspace;

use App\DTO\Workspace\Responses\WorkspaceResponseDto;
use App\Models\Workspace;

class WorkspaceToWorkspaceResponseDto
{
    public function transform(Workspace $workspace): WorkspaceResponseDto
    {
        return new WorkspaceResponseDto(
            id: $workspace->Id,
            userId: $workspace->user_id,
            name: $workspace->name,
            description: $workspace->description,
            foldersCount: (int) ($workspace->folders_count ?? 0),
            notesCount: (int) ($workspace->notes_count ?? 0),
            createdAt: $workspace->CreatedTime?->toISOString(),
            updatedAt: $workspace->UpdatedTime?->toISOString(),
        );
    }
}
