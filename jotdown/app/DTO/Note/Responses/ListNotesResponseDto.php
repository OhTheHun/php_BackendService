<?php

namespace App\DTO\Note\Responses;

class ListNotesResponseDto
{
    public function __construct(
        public readonly array $notes,
        public readonly int $currentPage,
        public readonly int $perPage,
        public readonly int $total,
        public readonly int $lastPage,
    ) {}

    public function toArray(): array
    {
        return [
            'notes' => array_map(fn (NoteResponseDto $note): array => $note->toArray(false), $this->notes),
            'meta' => [
                'current_page' => $this->currentPage,
                'per_page' => $this->perPage,
                'total' => $this->total,
                'last_page' => $this->lastPage,
            ],
        ];
    }
}
