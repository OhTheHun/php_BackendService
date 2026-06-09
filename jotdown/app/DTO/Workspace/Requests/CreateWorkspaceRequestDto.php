<?php

namespace App\DTO\Workspace\Requests;

class CreateWorkspaceRequestDto
{
    public function __construct(
        public readonly string $token,
        public readonly string $name,
        public readonly ?string $description,
    ) {}
}
