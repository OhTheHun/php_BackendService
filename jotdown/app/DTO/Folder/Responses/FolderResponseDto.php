<?php

namespace App\DTO\Folder\Responses;

class FolderResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $name,
        public readonly ?string $workspaceId,
        public readonly int $notesCount,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {}

    public function toArray(bool $includeCreatedAt = true): array
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'workspace_id' => $this->workspaceId,
            'notes_count' => $this->notesCount,
        ];

        if ($includeCreatedAt) {
            $data['created_at'] = $this->createdAt;
        }

        $data['updated_at'] = $this->updatedAt;

        return $data;
    }
}
