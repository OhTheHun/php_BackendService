<?php

namespace App\DTO\Note\Requests;

class UpdateNoteShareRequestDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $token,
        public readonly string $visibility,
    ) {}
}
