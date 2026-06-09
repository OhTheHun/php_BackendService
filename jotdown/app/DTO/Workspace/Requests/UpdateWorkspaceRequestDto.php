<?php

namespace App\DTO\Workspace\Requests;

class UpdateWorkspaceRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly ?string $name,
        public readonly ?string $description,
    ) {}
}
