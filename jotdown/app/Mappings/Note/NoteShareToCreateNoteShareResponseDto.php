<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\CreateNoteShareResponseDto;
use App\Models\NoteShare;

class NoteShareToCreateNoteShareResponseDto
{
    public function transform(NoteShare $noteShare, string $message): CreateNoteShareResponseDto
    {
        return new CreateNoteShareResponseDto(
            message: $message,
            id: $noteShare->Id,
            noteId: $noteShare->note_id,
            email: $noteShare->shared_with_email,
            permission: $noteShare->permission,
        );
    }
}
