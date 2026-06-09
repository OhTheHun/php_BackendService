<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteArchiveResponseDto;
use App\Models\Note;

class NoteToNoteArchiveResponseDto
{
    public function transform(Note $note): NoteArchiveResponseDto
    {
        return new NoteArchiveResponseDto(
            message: $note->archived_at ? 'Note archived successfully.' : 'Note unarchived successfully.',
            id: $note->Id,
            archivedAt: $note->archived_at?->toISOString(),
        );
    }
}
