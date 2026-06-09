<?php

namespace App\DTO\Note\Responses;

class NoteArchiveResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $id,
        public readonly ?string $archivedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'note' => [
                'id' => $this->id,
                'archived_at' => $this->archivedAt,
            ],
        ];
    }
}
