<?php

namespace App\DTO\Note\Responses;

class ShowNoteResponseDto
{
    public function __construct(public readonly NoteResponseDto $note) {}

    public function toArray(): array
    {
        return ['note' => $this->note->toArray()];
    }
}
