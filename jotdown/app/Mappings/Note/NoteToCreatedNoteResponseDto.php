<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteMessageResponseDto;
use App\Models\Note;

class NoteToCreatedNoteResponseDto
{
    public function __construct(
        private readonly NoteToNoteResponseDto $noteToNoteResponseDto,
    ) {}

    public function transform(Note $note): NoteMessageResponseDto
    {
        return new NoteMessageResponseDto(
            message: 'Note created successfully.',
            note: $this->noteToNoteResponseDto->transform($note),
        );
    }
}
