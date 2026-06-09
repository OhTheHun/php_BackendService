<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteFavoriteResponseDto;
use App\Models\Note;

class NoteToNoteFavoriteResponseDto
{
    public function transform(Note $note): NoteFavoriteResponseDto
    {
        return new NoteFavoriteResponseDto(
            message: 'Note favorite status updated successfully.',
            id: $note->Id,
            title: $note->title,
            isFavorite: $note->is_favorite,
            updatedAt: $note->UpdatedTime?->toISOString(),
        );
    }
}
