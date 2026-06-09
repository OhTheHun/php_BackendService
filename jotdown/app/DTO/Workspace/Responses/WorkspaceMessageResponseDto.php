<?php

namespace App\DTO\Workspace\Responses;

class WorkspaceMessageResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly ?WorkspaceResponseDto $workspace = null,
        public readonly bool $includeCreatedAt = true,
    ) {}

    public function toArray(): array
    {
        $data = ['message' => $this->message];

        if ($this->workspace !== null) {
            $data['workspace'] = $this->workspace->toArray($this->includeCreatedAt);
        }

        return $data;
    }
}
