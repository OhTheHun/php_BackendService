<?php

namespace App\DTO\Workspace\Requests;

class DeleteWorkspaceRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
    ) {}
}
