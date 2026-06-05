<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\GetSharedNoteResponseDto;
use App\Models\NoteShare;

class NoteShareToGetSharedNoteResponseDto
{
    public function transform(NoteShare $noteShare): GetSharedNoteResponseDto
    {
        $note = $noteShare->note;

        return new GetSharedNoteResponseDto(
            shareId: $noteShare->Id,
            noteId: $note->Id,
            title: $note->title,
            content: $note->content,
            color: $note->color,
            isPinned: $note->is_pinned,
            isFavorite: $note->is_favorite,
            visibility: $note->visibility,
            sharedByUserId: $noteShare->shared_by_user_id,
            sharedWithEmail: $noteShare->shared_with_email,
            permission: $noteShare->permission,
            shareToken: $noteShare->share_token,
            expiresAt: $noteShare->expires_at?->toISOString(),
            createdTime: $noteShare->CreatedTime->toISOString(),
        );
    }
}
