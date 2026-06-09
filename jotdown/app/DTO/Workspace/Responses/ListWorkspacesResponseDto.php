<?php

namespace App\DTO\Workspace\Responses;

class ListWorkspacesResponseDto
{
    public function __construct(
        public readonly array $workspaces,
    ) {}

    public function toArray(): array
    {
        return [
            'workspaces' => array_map(fn (WorkspaceResponseDto $workspace): array => $workspace->toArray(), $this->workspaces),
        ];
    }
}
