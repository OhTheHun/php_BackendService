<?php

namespace App\DTO\Note\Responses;

class GetSharedNoteResponseDto
{
    public function __construct(
        public readonly string $shareId,
        public readonly string $noteId,
        public readonly string $title,
        public readonly ?string $content,
        public readonly ?string $color,
        public readonly bool $isPinned,
        public readonly bool $isFavorite,
        public readonly string $visibility,
        public readonly string $sharedByUserId,
        public readonly string $sharedWithEmail,
        public readonly string $permission,
        public readonly ?string $shareToken,
        public readonly ?string $expiresAt,
        public readonly string $createdTime,
    ) {
    }

    public function toArray(): array
    {
        return [
            'share_id' => $this->shareId,
            'note_id' => $this->noteId,
            'title' => $this->title,
            'content' => $this->content,
            'color' => $this->color,
            'is_pinned' => $this->isPinned,
            'is_favorite' => $this->isFavorite,
            'visibility' => $this->visibility,
            'shared_by_user_id' => $this->sharedByUserId,
            'shared_with_email' => $this->sharedWithEmail,
            'permission' => $this->permission,
            'share_token' => $this->shareToken,
            'expires_at' => $this->expiresAt,
            'created_time' => $this->createdTime,
        ];
    }
}
