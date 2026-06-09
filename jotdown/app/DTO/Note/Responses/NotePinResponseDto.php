<?php

namespace App\DTO\Note\Responses;

class NotePinResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $id,
        public readonly string $title,
        public readonly bool $isPinned,
        public readonly ?string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'note' => [
                'id' => $this->id,
                'title' => $this->title,
                'is_pinned' => $this->isPinned,
                'updated_at' => $this->updatedAt,
            ],
        ];
    }
}
