<?php

namespace App\DTO\Note\Responses;

class CreateNoteShareResponseDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $id,
        public readonly string $noteId,
        public readonly string $email,
        public readonly string $permission,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'share' => [
                'id' => $this->id,
                'note_id' => $this->noteId,
                'email' => $this->email,
                'permission' => $this->permission,
            ],
        ];
    }
}
