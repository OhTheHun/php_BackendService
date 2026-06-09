<?php

namespace App\DTO\Label\Responses;

class LabelResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $name,
        public readonly string $color,
        public readonly int $notesCount,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'color' => $this->color,
            'notes_count' => $this->notesCount,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
