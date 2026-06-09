<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteShareResponseDto;
use App\Models\Note;

class NoteToNoteShareResponseDto
{
    public function transform(Note $note, string $message): NoteShareResponseDto
    {
        $frontendUrl = (string) config('app.frontend_share_url', 'https://fe-react-liard.vercel.app');
        $shareUrl = $note->visibility === 'public' ? rtrim($frontendUrl, '/').'/shared/'.$note->Id : null;

        return new NoteShareResponseDto(
            message: $message,
            id: $note->Id,
            visibility: $note->visibility,
            shareUrl: $shareUrl,
            updatedAt: $note->UpdatedTime?->toISOString(),
        );
    }
}
