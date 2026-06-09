<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteMessageResponseDto;

class DeletedNoteToNoteMessageResponseDto
{
    public function transform(): NoteMessageResponseDto
    {
        return new NoteMessageResponseDto('Note deleted successfully.');
    }
}
