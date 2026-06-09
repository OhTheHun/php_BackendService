<?php

namespace App\DTO\Folder\Responses;

class FolderMessageResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly ?FolderResponseDto $folder = null,
        public readonly bool $includeCreatedAt = true,
    ) {}

    public function toArray(): array
    {
        $data = ['message' => $this->message];

        if ($this->folder !== null) {
            $data['folder'] = $this->folder->toArray($this->includeCreatedAt);
        }

        return $data;
    }
}
