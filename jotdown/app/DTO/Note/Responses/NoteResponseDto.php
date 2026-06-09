<?php

namespace App\DTO\Note\Responses;

class NoteResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly ?string $workspaceId,
        public readonly ?string $folderId,
        public readonly string $title,
        public readonly ?string $content,
        public readonly ?string $color,
        public readonly bool $isPinned,
        public readonly ?string $pinnedAt,
        public readonly bool $isFavorite,
        public readonly string $visibility,
        public readonly bool $isProtected,
        public readonly ?string $archivedAt,
        public readonly string $createdAt,
        public readonly ?string $updatedAt,
        public readonly ?NoteWorkspaceResponseDto $workspace,
        public readonly ?NoteFolderResponseDto $folder,
        public readonly array $labels,
        public readonly array $attachments,
    ) {}

    public function toArray(bool $includeRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->userId,
            'workspace_id' => $this->workspaceId,
            'folder_id' => $this->folderId,
            'title' => $this->title,
            'content' => $this->content,
            'color' => $this->color,
            'is_pinned' => $this->isPinned,
            'pinned_at' => $this->pinnedAt,
            'is_favorite' => $this->isFavorite,
            'visibility' => $this->visibility,
            'is_protected' => $this->isProtected,
            'archived_at' => $this->archivedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];

        if ($includeRelations) {
            $data['workspace'] = $this->workspace?->toArray();
            $data['folder'] = $this->folder?->toArray();
        }

        $data['labels'] = array_map(fn (NoteLabelResponseDto $label): array => $label->toArray(), $this->labels);
        $data['attachments'] = array_map(fn (NoteAttachmentResponseDto $attachment): array => $attachment->toArray(), $this->attachments);

        return $data;
    }
}
