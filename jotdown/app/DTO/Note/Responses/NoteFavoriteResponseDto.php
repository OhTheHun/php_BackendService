<?php

namespace App\DTO\Note\Responses;

class NoteFavoriteResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $id,
        public readonly string $title,
        public readonly bool $isFavorite,
        public readonly ?string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'note' => [
                'id' => $this->id,
                'title' => $this->title,
                'is_favorite' => $this->isFavorite,
                'updated_at' => $this->updatedAt,
            ],
        ];
    }
}
