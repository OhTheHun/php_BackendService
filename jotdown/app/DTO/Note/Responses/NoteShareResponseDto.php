<?php

namespace App\DTO\Note\Responses;

class NoteShareResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $id,
        public readonly string $visibility,
        public readonly ?string $shareUrl,
        public readonly string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'note' => [
                'id' => $this->id,
                'visibility' => $this->visibility,
                'share_url' => $this->shareUrl,
                'updated_at' => $this->updatedAt,
            ],
        ];
    }
}
