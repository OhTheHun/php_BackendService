<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NotePinResponseDto;
use App\Models\Note;

class NoteToNotePinResponseDto
{
    public function transform(Note $note): NotePinResponseDto
    {
        return new NotePinResponseDto(
            message: 'Note pin status updated successfully.',
            id: $note->Id,
            title: $note->title,
            isPinned: $note->is_pinned,
            updatedAt: $note->UpdatedTime?->toISOString(),
        );
    }
}
