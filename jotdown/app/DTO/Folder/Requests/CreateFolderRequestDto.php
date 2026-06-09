<?php

namespace App\DTO\Folder\Requests;

class CreateFolderRequestDto
{
    public function __construct(
        public readonly string $workspaceId,
        public readonly string $token,
        public readonly string $name,
    ) {}
}
