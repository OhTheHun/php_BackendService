<?php

namespace App\DTO\Workspace\Requests;

class ListWorkspacesRequestDto
{
    public function __construct(
        public readonly string $token,
    ) {}
}
