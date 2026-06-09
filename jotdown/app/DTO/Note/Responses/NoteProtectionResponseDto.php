<?php

namespace App\DTO\Note\Responses;

class NoteProtectionResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $id,
        public readonly bool $isProtected,
        public readonly string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'note' => [
                'id' => $this->id,
                'is_protected' => $this->isProtected,
                'updated_at' => $this->updatedAt,
            ],
        ];
    }
}
