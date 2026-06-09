<?php

namespace App\DTO\Note\Requests;

class ListNotesRequestDto
{
    public function __construct(
        public readonly string $token,
        public readonly ?string $workspaceId,
        public readonly ?string $folderId,
        public readonly ?string $labelId,
        public readonly ?string $visibility,
        public readonly ?bool $isPinned,
        public readonly ?bool $isFavorite,
        public readonly ?bool $isProtected,
        public readonly ?bool $archived,
        public readonly ?string $search,
        public readonly string $sort,
        public readonly string $direction,
        public readonly int $page,
        public readonly int $perPage,
    ) {}
}
