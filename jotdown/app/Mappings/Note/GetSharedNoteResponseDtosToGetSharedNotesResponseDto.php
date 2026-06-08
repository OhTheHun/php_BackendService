<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\GetSharedNotesResponseDto;

class GetSharedNoteResponseDtosToGetSharedNotesResponseDto
{
    public function transform(array $notes, float $elapsedMs): GetSharedNotesResponseDto
    {
        return new GetSharedNotesResponseDto(
            notes: $notes,
            total: count($notes),
            elapsedMs: $elapsedMs,
        );
    }
}
