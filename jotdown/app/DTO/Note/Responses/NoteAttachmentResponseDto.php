<?php

namespace App\DTO\Note\Responses;

class NoteAttachmentResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $fileName,
        public readonly string $filePath,
        public readonly int $fileSize,
        public readonly string $fileType,
        public readonly ?string $attachmentKind,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->fileName,
            'file_path' => $this->filePath,
            'file_size' => $this->fileSize,
            'file_type' => $this->fileType,
            'attachment_kind' => $this->attachmentKind,
        ];
    }
}
