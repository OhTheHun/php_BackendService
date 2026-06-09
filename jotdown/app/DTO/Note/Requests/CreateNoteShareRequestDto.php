<?php

namespace App\DTO\Note\Requests;

class CreateNoteShareRequestDto
{
    public function __construct(
        public readonly string $noteId,
        public readonly string $token,
        public readonly string $email,
        public readonly string $permission,
    ) {}
}
