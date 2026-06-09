<?php

namespace App\DTO\Folder\Requests;

class ListFoldersRequestDto
{
    public function __construct(
        public readonly string $token,
        public readonly ?string $workspaceId,
    ) {}
}
