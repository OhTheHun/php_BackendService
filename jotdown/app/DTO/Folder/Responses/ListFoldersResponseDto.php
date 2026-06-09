<?php

namespace App\DTO\Folder\Responses;

class ListFoldersResponseDto
{
    public function __construct(
        public readonly array $folders,
    ) {}

    public function toArray(): array
    {
        return [
            'folders' => array_map(fn (FolderResponseDto $folder) => $folder->toArray(), $this->folders),
        ];
    }
}
