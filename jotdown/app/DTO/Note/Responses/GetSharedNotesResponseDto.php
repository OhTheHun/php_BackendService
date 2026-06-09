<?php

namespace App\DTO\Note\Responses;

class GetSharedNotesResponseDto
{
    public function __construct(
        public readonly array $notes,
        public readonly int $total,
        public readonly float $elapsedMs,
    ) {}

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'elapsed_ms' => $this->elapsedMs,
            'notes' => array_map(
                fn (GetSharedNoteResponseDto $note): array => $note->toArray(),
                $this->notes
            ),
        ];
    }
}
