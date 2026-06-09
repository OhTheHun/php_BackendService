<?php

namespace App\Mappings\Note;

use App\DTO\Note\Responses\NoteAttachmentResponseDto;
use App\DTO\Note\Responses\NoteFolderResponseDto;
use App\DTO\Note\Responses\NoteLabelResponseDto;
use App\DTO\Note\Responses\NoteResponseDto;
use App\DTO\Note\Responses\NoteWorkspaceResponseDto;
use App\Models\Note;

class NoteToNoteResponseDto
{
    public function transform(Note $note): NoteResponseDto
    {
        $workspace = $note->relationLoaded('workspace') ? $note->workspace : null;
        $folder = $note->relationLoaded('folder') ? $note->folder : null;
        $labels = $note->relationLoaded('labels') ? $note->labels : collect();
        $attachments = $note->relationLoaded('attachments') ? $note->attachments : collect();

        return new NoteResponseDto(
            id: $note->Id,
            userId: $note->user_id,
            workspaceId: $note->workspace_id,
            folderId: $note->folder_id,
            title: $note->title,
            content: $note->content,
            color: $note->color,
            isPinned: (bool) $note->is_pinned,
            pinnedAt: $note->pinned_at?->toISOString(),
            isFavorite: (bool) $note->is_favorite,
            visibility: $note->visibility ?? 'private',
            isProtected: (bool) $note->is_protected,
            archivedAt: $note->archived_at?->toISOString(),
            createdAt: $note->CreatedTime->toISOString(),
            updatedAt: $note->UpdatedTime?->toISOString(),
            workspace: $workspace ? new NoteWorkspaceResponseDto($workspace->Id, $workspace->name) : null,
            folder: $folder ? new NoteFolderResponseDto($folder->Id, $folder->name) : null,
            labels: $labels->map(fn ($label): NoteLabelResponseDto => new NoteLabelResponseDto(
                id: $label->Id,
                name: $label->name,
                color: $label->color,
            ))->all(),
            attachments: $attachments->map(fn ($attachment): NoteAttachmentResponseDto => new NoteAttachmentResponseDto(
                id: $attachment->Id,
                fileName: $attachment->file_name,
                filePath: $attachment->file_path,
                fileSize: $attachment->file_size,
                fileType: $attachment->file_type,
                attachmentKind: $attachment->attachment_kind,
            ))->all(),
        );
    }
}
