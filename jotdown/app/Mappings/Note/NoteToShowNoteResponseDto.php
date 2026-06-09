<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\ShowNoteResponseDto;
use App\Models\Note;

class NoteToShowNoteResponseDto
{
    public function __construct(
        private readonly NoteToNoteResponseDto $noteToNoteResponseDto,
    ) {}

    public function transform(Note $note): ShowNoteResponseDto
    {
        return new ShowNoteResponseDto($this->noteToNoteResponseDto->transform($note));
    }
}
