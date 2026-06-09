<?php

namespace App\DTO\Note\Requests;

class UpdateNoteRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly ?string $workspaceId,
        public readonly ?string $folderId,
        public readonly ?string $title,
        public readonly ?string $content,
        public readonly ?string $color,
        public readonly ?string $visibility,
        public readonly ?array $labelIds,
    ) {}
}
