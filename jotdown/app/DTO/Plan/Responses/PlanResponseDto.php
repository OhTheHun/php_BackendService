<?php

namespace App\DTO\Plan\Responses;

class PlanResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float|int $price,
        public readonly ?int $maxNotes,
        public readonly ?int $maxWorkspaces,
        public readonly ?int $maxAttachmentSize,
        public readonly bool $canExport,
        public readonly bool $status,
        public readonly ?string $createdTime,
        public readonly ?string $updatedTime,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'max_notes' => $this->maxNotes,
            'max_workspaces' => $this->maxWorkspaces,
            'max_attachment_size' => $this->maxAttachmentSize,
            'can_export' => $this->canExport,
            'status' => $this->status,
            'created_time' => $this->createdTime,
            'updated_time' => $this->updatedTime,
        ];
    }
}
