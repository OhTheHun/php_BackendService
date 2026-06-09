<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteProtectionResponseDto;
use App\Models\Note;

class NoteToNoteProtectionResponseDto
{
    public function transform(Note $note, string $message): NoteProtectionResponseDto
    {
        return new NoteProtectionResponseDto(
            message: $message,
            id: $note->Id,
            isProtected: $note->is_protected,
            updatedAt: $note->UpdatedTime?->toISOString(),
        );
    }
}
