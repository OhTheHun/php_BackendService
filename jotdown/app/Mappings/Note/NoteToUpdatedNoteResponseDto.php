<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteMessageResponseDto;
use App\Models\Note;

class NoteToUpdatedNoteResponseDto
{
    public function __construct(
        private readonly NoteToNoteResponseDto $noteToNoteResponseDto,
    ) {}

    public function transform(Note $note): NoteMessageResponseDto
    {
        return new NoteMessageResponseDto(
            message: 'Note updated successfully.',
            note: $this->noteToNoteResponseDto->transform($note),
        );
    }
}
